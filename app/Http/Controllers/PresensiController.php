<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\FaceToken;
use App\Models\JadwalKuliah;
use App\Models\Krs;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function viewMahasiswa()
    {
        date_default_timezone_set('Asia/Jakarta');
        $user = Auth::user();
        $currentTime = now()->format('H:i:s');
        $tanggalHariIni = now()->format('Y-m-d'); 

        $dayMapping = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        
        $englishDay = now()->format('l'); 
        $today = $dayMapping[$englishDay] ?? 'Senin'; 

        $isFaceRegistered = \App\Models\FaceToken::where('id_user', $user->id_user)->exists();

        // Ditentukan berdasarkan periode aktif saat ini agar data lama tidak ikut bocor
        $tahunAkademikAktif = '2025/2026'; 
        $semesterAktif = 'Genap';          

        // Menggunakan whereHas untuk menghindari duplikasi baris akibat SQL Join
        $jadwalHariIni = Krs::with(['jadwalKuliah.mataKuliah', 'jadwalKuliah.user'])
            ->where('id_user', $user->id_user)
            ->whereHas('jadwalKuliah', function ($query) use ($today, $tahunAkademikAktif, $semesterAktif) {
                $query->whereRaw('LOWER(hari) = ?', [strtolower($today)])
                    ->where('tahun_akademik', $tahunAkademikAktif)
                    ->where('tipe_semester', $semesterAktif);
            })
            ->get()
            // Mengurutkan koleksi data berdasarkan jam mulai kuliah
            ->sortBy(function ($krs) {
                return $krs->jadwalKuliah->jam_mulai ?? '00:00';
            })
            ->values(); // Mereset indeks array agar rapi kembali

        // 1. Ambil status kehadiran presensi wajah sebagai key-value pair [id_jadwal => status]
        $sudahAbsenHariIni = \App\Models\Absensi::where('id_user', $user->id_user)
            ->whereDate('created_at', $tanggalHariIni) 
            ->pluck('status', 'id_jadwal') 
            ->toArray();

        // 2. Tambahan: Ambil status pengajuan surat (Izin/Sakit) hari ini sebagai [id_jadwal => status_acc]
        $statusSuratHariIni = \App\Models\PengajuanSurat::where('id_user', $user->id_user)
            ->whereDate('tanggal_absen', $tanggalHariIni)
            ->pluck('status_acc', 'id_jadwal') // Menghasilkan array seperti: [4 => 'Pending']
            ->toArray();

        // Kirim variabel $statusSuratHariIni ke dalam compact() agar bisa dibaca oleh Blade
        return view('mahasiswa.presensi', compact(
            'jadwalHariIni', 
            'isFaceRegistered', 
            'today', 
            'currentTime', 
            'sudahAbsenHariIni',
            'statusSuratHariIni' // <-- VARIABEL BARU
        ));
    }    
public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        
        // 1. Validasi dinamis tergantung metode (Menyelaraskan nama input file ke 'surat_dokumen')
        if ($request->has('face_vector')) {
            $request->validate([
                'id_jadwal' => 'required',
                'face_vector' => 'required|array'
            ]);
        } else {
            $request->validate([
                'id_jadwal' => 'required',
                'status' => 'required|in:Izin,Sakit',
                'surat_dokumen' => 'required|image|mimes:jpeg,png,jpg|max:2048' // <-- DISESUAIKAN
            ]);
        }

        $user = Auth::user();
        $jadwalId = $request->id_jadwal;
        $tanggalHariIni = now()->format('Y-m-d');

        // ===================================================================
        // PROSES A: JALUR VERIFIKASI SCAN WAJAH (HADIR)
        // ===================================================================
        if ($request->has('face_vector')) {
            $sudahAbsen = Absensi::where('id_user', $user->id_user)
                ->where('id_jadwal', $jadwalId)
                ->whereDate('created_at', $tanggalHariIni)
                ->exists();

            if ($sudahAbsen) {
                return response()->json(['success' => false, 'message' => 'Anda sudah mencatat kehadiran hari ini.']);
            }

            $incomingVector = $request->face_vector;
            $faceToken = FaceToken::where('id_user', $user->id_user)->first();
            
            if (!$faceToken) {
                return response()->json(['success' => false, 'message' => 'Wajah belum terdaftar di sistem.']);
            }

            $storedVector = json_decode($faceToken->descriptor); 
            $distance = $this->calculateEuclideanDistance($incomingVector, $storedVector);
            $threshold = 0.45; 

            if ($distance > $threshold) {
                return response()->json(['success' => false, 'message' => 'Wajah tidak cocok! Silakan coba lagi.']);
            }

            Absensi::create([
                'id_user'   => $user->id_user,
                'id_jadwal' => $jadwalId,
                'status'    => 'Hadir'
            ]);

            return response()->json(['success' => true, 'message' => 'Presensi wajah berhasil dicatat!']);
        }

        // ===================================================================
        // PROSES B: JALUR UPLOAD SURAT (MASUK KE TABEL PENGHAJUAN SURAT)
        // ===================================================================
        try {
            $status = $request->status; // Mengambil status (Izin/Sakit)

            // Proteksi: Cek apakah sudah pernah mengajukan surat di tanggal & jadwal ini hari ini
            $sudahMengajukan = PengajuanSurat::where('id_user', $user->id_user)
                ->where('id_jadwal', $jadwalId)
                ->whereDate('tanggal_absen', $tanggalHariIni)
                ->exists();

            if ($sudahMengajukan) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Surat keterangan untuk jadwal ini sudah dikirim sebelumnya pada hari ini.'
                ]);
            }

            // Membaca file menggunakan kata kunci 'surat_dokumen' sesuai tag name HTML
            if ($request->hasFile('surat_dokumen')) {
                $file = $request->file('surat_dokumen');
                $filename = strtolower($status) . '-' . $user->id_user . '-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
                
                // Gunakan storeAs dengan disk 'public' agar masuk ke storage/app/public/uploads/bukti_izin
                $file->storeAs('uploads/bukti_izin', $filename, 'public');
                
                // Path ini yang akan disimpan rapi ke dalam database
                $pathFoto = 'uploads/bukti_izin/' . $filename;
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Berkas fisik dokumen wajib diunggah.'
                ]);
            }

            // SIMPAN DATA KE TABEL PENAMPUNGAN (PENGHAJUAN SURAT)
            PengajuanSurat::create([
                'id_user'       => $user->id_user,
                'id_jadwal'     => $jadwalId,
                'tanggal_absen' => $tanggalHariIni,
                'jenis_izin'    => $status,        
                'surat_dokumen' => $pathFoto,      // Path string sekarang sukses masuk ke sini
                'keterangan'    => $request->keterangan ?? null,
                'status_acc'    => 'Pending'
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Surat ' . strtolower($status) . ' berhasil diajukan! Menunggu persetujuan Dosen.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }    
    private function calculateEuclideanDistance($v1, $v2)
    {
        if (count($v1) !== count($v2)) return 1.0; // Maksimal error jika format rusak

        $sum = 0.0;
        for ($i = 0; $i < count($v1); $i++) {
            $sum += pow($v1[$i] - $v2[$i], 2);
        }
        return sqrt($sum);
    }

public function rekap()
{
    date_default_timezone_set('Asia/Jakarta');
    $user = Auth::user();
    $currentTime = now()->format('H:i:s');
    $tanggalHariIni = now()->format('Y-m-d');
    
    $dayMapping = [
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
        'Sunday'    => 'Minggu',
    ];
    $englishDay = now()->format('l'); 
    $today = $dayMapping[$englishDay] ?? 'Senin'; 

    // Periode aktif semester berjalan
    $tahunAkademikAktif = '2025/2026'; 
    $semesterAktif = 'Genap';          

    // Ambil SEMUA matkul KRS mahasiswa di periode aktif
    $krsPeriodeAktif = \App\Models\Krs::with(['jadwalKuliah.mataKuliah', 'jadwalKuliah.user'])
        ->join('jadwal_kuliahs', 'krs.id_jadwal', '=', 'jadwal_kuliahs.id_jadwal')
        ->where('krs.id_user', $user->id_user)
        ->where('jadwal_kuliahs.tahun_akademik', $tahunAkademikAktif)
        ->where('jadwal_kuliahs.tipe_semester', $semesterAktif)
        ->select('krs.*')
        ->get();

    $rekapPerMatkul = [];
    $totalHadirGlobal = 0;
    $totalIzinGlobal = 0;
    $totalAlfaGlobal = 0;

    foreach ($krsPeriodeAktif as $krs) {
        $infoJadwal = $krs->jadwalKuliah;
        if (!$infoJadwal) continue;

        // 1. Ambil data absensi wajah / manual (Hadir / Alfa)
        $listAbsensi = \App\Models\Absensi::where('id_user', $user->id_user)
            ->where('id_jadwal', $krs->id_jadwal)
            ->get();

        // 2. Ambil data dari tabel pengajuan_surat (Izin / Sakit)
        $listSurat = \App\Models\PengajuanSurat::where('id_user', $user->id_user)
            ->where('id_jadwal', $krs->id_jadwal)
            ->get();

        // Mengambil status riil dari absensi utama
        $hadirMurni = $listAbsensi->where('status', 'Hadir')->count();
        $alfaMurni  = $listAbsensi->where('status', 'Alfa')->count();
        
        $isHariIni = (strtolower($infoJadwal->hari) === strtolower($today));
        $isJamKuliahSelesai = ($currentTime > $infoJadwal->jam_selesai);

        // --- PEMBARUAN LOGIKA SELEKSI SURAT ---
        // Filter surat berdasarkan status persetujuan dosen
        $suratDisetujui = $listSurat->where('status_acc', 'Disetujui')->count();
        $suratDitolak   = $listSurat->where('status_acc', 'Ditolak')->count();
        
        // Surat Pending dianggap berjalan HANYA jika harinya hari ini dan jam kuliahnya sudah beres melewati jadwal
        $suratPendingBerjalan = $listSurat->where('status_acc', 'Pending')->filter(function ($surat) use ($isHariIni, $isJamKuliahSelesai) {
            return ($isHariIni && $isJamKuliahSelesai);
        })->count();

        // --- ATURAN RETRIBUSI STATUS BARU ---
        $fixHadir = $hadirMurni + $suratDisetujui; // Kehadiran murni + surat yang sah di-ACC
        $fixIzin  = $suratDisetujui + $suratPendingBerjalan; // Yang masuk statistik izin (Disetujui & Pending kadaluwarsa)
        $fixAlfa  = $alfaMurni + $suratDitolak;  // JIKA DITOLAK MATA KULIAH LANGSUNG MASUK AKUMULASI ALFA!

        // Total pertemuan berjalan dihitung dari seluruh status absensi riil
        $totalBerjalan = $hadirMurni + $alfaMurni + $suratDisetujui + $suratDitolak + $suratPendingBerjalan;

        // Rumus Persentase Kehadiran yang Logis dan Tidak langsung Terjun ke 0%
        if ($totalBerjalan == 0) {
            $persentase = 100; // Jika kelas belum dimulai, persentase default aman di 100%
        } else {
            // Persentase dihitung berdasarkan: (Total Hadir Sah / Total Pertemuan yang Sudah Lewat) * 100
            $persentase = round(($fixHadir / $totalBerjalan) * 100);
        }

        // Progress bar akumulatif menuju pemenuhan target 16 kali tatap muka kuliah
        $barProgress = round(($fixHadir / 16) * 100);
        if ($barProgress > 100) $barProgress = 100;

        // Akumulasi statistik global untuk 4 box widget komponen atas halaman
        $totalHadirGlobal += $hadirMurni; 
        $totalIzinGlobal  += $suratDisetujui; // Box atas hanya memuat total perizinan sah yang disetujui
        $totalAlfaGlobal  += $fixAlfa;        // Box atas ikut bertambah bila ada penolakan berkas surat

        $rekapPerMatkul[] = (object) [
            'nama_mk'        => $infoJadwal->mataKuliah->nama_mk ?? '—',
            'dosen'          => $infoJadwal->user->nama ?? '—',
            'ruangan'        => $infoJadwal->ruangan ?? '—',
            'hari_kuliah'    => $infoJadwal->hari ?? '—',
            'hadir'          => $hadirMurni,
            'izin'           => $fixIzin,
            'alfa'           => $fixAlfa, // Menampilkan data yang sudah ditambah kasus penolakan surat
            'total_berjalan' => $totalBerjalan,
            'persentase'     => $persentase,
            'bar_progress'   => $barProgress
        ];
    }

    $totalMatkul = count($rekapPerMatkul);

    return view('mahasiswa.rekap', [
        'rekapPerMatkul'   => $rekapPerMatkul,
        'totalMatkul'      => $totalMatkul,
        'totalHadir'       => $totalHadirGlobal,
        'totalIzin'        => $totalIzinGlobal,
        'totalAlfa'        => $totalAlfaGlobal,
        'today'            => $today,
        'tahunAkademik'    => $tahunAkademikAktif,
        'semester'         => $semesterAktif
    ]);
}
}

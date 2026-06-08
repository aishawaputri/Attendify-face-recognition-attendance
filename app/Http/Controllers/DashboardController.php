<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\JadwalKuliah;
use App\Models\Absensi;
use App\Models\Krs;
use App\Models\PengajuanSurat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
public function viewAdmin()
{
    try {
        // 1. Hitung statistik dasar langsung dari database
        $total_mahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
        $total_dosen     = \App\Models\User::where('role', 'dosen')->count();
        
        // Asumsi nama model Anda adalah JadwalKuliah dan Absensi
        // Mengambil jumlah jadwal perkuliahan khusus hari ini
        $hariIni = \Carbon\Carbon::now()->locale('id')->dayName; // Contoh: "Senin"
        $jadwal_aktif = \App\Models\JadwalKuliah::where('hari', $hariIni)->count();

        // 2. Ambil 5 riwayat absensi mahasiswa terbaru hari ini
        $recent_absensis = \App\Models\Absensi::with(['user', 'jadwalKuliah.mataKuliah'])
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 3. Ambil seluruh daftar jadwal kuliah khusus untuk hari ini
        $jadwal_hari_ini = \App\Models\JadwalKuliah::with(['mataKuliah', 'user'])
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Lempar seluruh variabel ke view admin
        return view('admin.dashboard', [
            'stats' => [
                'total_mahasiswa' => $total_mahasiswa,
                'total_dosen'     => $total_dosen,
                'jadwal_aktif'    => $jadwal_aktif,
            ],
            'recent_absensis' => $recent_absensis,
            'jadwal_hari_ini' => $jadwal_hari_ini,
        ]);

    } catch (\Exception $e) {
        // Penanganan jika ada kegagalan relasi atau tabel database belum siap
        return "Terjadi kegagalan pemuatan data admin: " . $e->getMessage();
    }
}
public function viewDosen()
{
    $tahunAkademikAktif = '2025/2026';
    $semesterAktif = 'Genap';

    date_default_timezone_set('Asia/Jakarta');
    $user = Auth::user();

    // Mapping nama hari lokal
    $dayMapping = [
        'Monday'    => 'Senin', 
        'Tuesday'   => 'Selasa', 
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis', 
        'Friday'    => 'Jumat', 
        'Saturday'  => 'Sabtu', 
        'Sunday'    => 'Minggu'
    ];
    $today = $dayMapping[now()->format('l')] ?? 'Senin';

    // ================= ALUR DOSEN =================
    if ($user->role === 'dosen' || isset($user->nidn)) {
        // PERBAIKAN: Ditambahkan filter hari ini agar hanya memunculkan jadwal hari berjalan
        $kelasAktif = JadwalKuliah::with('mataKuliah')
            ->where('id_user', $user->id_user)
            ->whereRaw('LOWER(hari) = ?', [strtolower($today)])
            ->where('tahun_akademik', $tahunAkademikAktif)
            ->where('tipe_semester', $semesterAktif)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $totalKelas = $kelasAktif->count();
        $suratPending = PengajuanSurat::whereHas('jadwalKuliah', function($q) use ($user) {
            $q->where('id_user', $user->id_user);
        })->where('status_acc', 'Pending')->count();

        return view('dosen.dashboard', compact('user', 'kelasAktif', 'totalKelas', 'suratPending'));
    }

    // ================= ALUR MAHASISWA =================
    $jadwalHariIni = Krs::with(['jadwalKuliah.mataKuliah', 'jadwalKuliah.user'])
        ->join('jadwal_kuliahs', 'krs.id_jadwal', '=', 'jadwal_kuliahs.id_jadwal')
        ->where('krs.id_user', $user->id_user)
        ->whereRaw('LOWER(jadwal_kuliahs.hari) = ?', [strtolower($today)])
        ->where('jadwal_kuliahs.tahun_akademik', $tahunAkademikAktif)
        ->where('jadwal_kuliahs.tipe_semester', $semesterAktif)
        ->select('krs.*')
        ->get();

    $totalJadwalHariIni = $jadwalHariIni->count();

    $pengajuanSuratTerakhir = PengajuanSurat::with('jadwalKuliah.mataKuliah')
        ->where('id_user', $user->id_user)
        ->orderBy('created_at', 'DESC')
        ->take(5)
        ->get();

    // Hitung persentase kehadiran global mahasiswa
    $krsSemua = Krs::where('id_user', $user->id_user)->get();
    $totalHadirGlobal = 0; $totalBerjalanGlobal = 0;

    foreach ($krsSemua as $krs) {
        $hadirMurni = Absensi::where('id_user', $user->id_user)->where('id_jadwal', $krs->id_jadwal)->where('status', 'Hadir')->count();
        $alfaMurni  = Absensi::where('id_user', $user->id_user)->where('id_jadwal', $krs->id_jadwal)->where('status', 'Alfa')->count();
        $izinDiAcc  = PengajuanSurat::where('id_user', $user->id_user)->where('id_jadwal', $krs->id_jadwal)->where('status_acc', 'Disetujui')->count();
        $izinPending = PengajuanSurat::where('id_user', $user->id_user)->where('id_jadwal', $krs->id_jadwal)->where('status_acc', 'Pending')->count();

        $totalHadirGlobal += ($hadirMurni + $izinDiAcc);
        $totalBerjalanGlobal += ($hadirMurni + $alfaMurni + $izinDiAcc + $izinPending);
    }
    $totalKehadiranGlobal = $totalBerjalanGlobal > 0 ? round(($totalHadirGlobal / $totalBerjalanGlobal) * 100) : 100;

    return view('mahasiswa.dashboard', compact(
        'user', 'jadwalHariIni', 'totalJadwalHariIni', 'pengajuanSuratTerakhir', 'totalKehadiranGlobal'
    ));
}
public function viewMahasiswa()
{
    date_default_timezone_set('Asia/Jakarta');
    $user = Auth::user();
    
    // 1. Dapatkan nama hari ini dalam bahasa Indonesia
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

    // Periode aktif semester berjalan (Sesuaikan dengan sistem Anda)
    $tahunAkademikAktif = '2025/2026'; //
    $semesterAktif = 'Genap';          //

    // 2. Filter Ketat: Hari ini + Periode Semester Aktif yang sedang berlangsung
    $jadwalHariIni = \App\Models\Krs::with(['jadwalKuliah.mataKuliah', 'jadwalKuliah.user'])
        ->join('jadwal_kuliahs', 'krs.id_jadwal', '=', 'jadwal_kuliahs.id_jadwal')
        ->where('krs.id_user', $user->id_user)
        ->whereRaw('LOWER(jadwal_kuliahs.hari) = ?', [strtolower($today)])
        ->where('jadwal_kuliahs.tahun_akademik', $tahunAkademikAktif) //
        ->where('jadwal_kuliahs.tipe_semester', $semesterAktif)       //
        ->select('krs.*')
        ->get();

    $totalJadwalHariIni = $jadwalHariIni->count();

    // 3. Ambil riwayat status pengajuan surat terbaru
    $pengajuanSuratTerakhir = \App\Models\PengajuanSurat::with('jadwalKuliah.mataKuliah')
        ->where('id_user', $user->id_user)
        ->orderBy('created_at', 'DESC')
        ->take(5)
        ->get();

    // 4. Kalkulasi kehadiran global dinamis
    $krsSemua = \App\Models\Krs::where('id_user', $user->id_user)->get();
    $totalHadirGlobal = 0;
    $totalBerjalanGlobal = 0;

    foreach ($krsSemua as $krs) {
        $hadirMurni = \App\Models\Absensi::where('id_user', $user->id_user)->where('id_jadwal', $krs->id_jadwal)->where('status', 'Hadir')->count();
        $alfaMurni  = \App\Models\Absensi::where('id_user', $user->id_user)->where('id_jadwal', $krs->id_jadwal)->where('status', 'Alfa')->count();
        $izinDiAcc  = \App\Models\PengajuanSurat::where('id_user', $user->id_user)->where('id_jadwal', $krs->id_jadwal)->where('status_acc', 'Disetujui')->count();
        $izinPending = \App\Models\PengajuanSurat::where('id_user', $user->id_user)->where('id_jadwal', $krs->id_jadwal)->where('status_acc', 'Pending')->count();

        $totalHadirGlobal += ($hadirMurni + $izinDiAcc);
        $totalBerjalanGlobal += ($hadirMurni + $alfaMurni + $izinDiAcc + $izinPending);
    }

    $totalKehadiranGlobal = $totalBerjalanGlobal > 0 ? round(($totalHadirGlobal / $totalBerjalanGlobal) * 100) : 100;

    return view('mahasiswa.dashboard', compact(
        'user', 
        'jadwalHariIni', 
        'totalJadwalHariIni', 
        'pengajuanSuratTerakhir',
        'totalKehadiranGlobal'
    ));
}    // Fungsi pembantu untuk konversi nama hari ke Bahasa Indonesia (sesuai Enum database Anda)
    private function getNamaHari($day)
    {
        $daftar_hari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        return $daftar_hari[$day];
    }
}
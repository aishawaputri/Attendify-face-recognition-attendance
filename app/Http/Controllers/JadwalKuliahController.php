<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\MataKuliah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKuliahController extends Controller
{
    public function index()
    {
        // Mengambil jadwal beserta relasi matakuliah dan dosen (user)
        $jadwals = JadwalKuliah::with(['matakuliah', 'user'])->orderBy('hari')->get();
        $matakuliahs = MataKuliah::all();
        $dosens = User::where('role', 'dosen')->get(); // Sesuaikan dengan sistem role Anda

        return view('admin.jadwalkuliah', compact('jadwals', 'matakuliahs', 'dosens'));
    }

public function viewDosen()
{
    // 1. Ambil data user dosen yang sedang login
    $user = Auth::user();

    // 2. Ambil bulan dan tahun saat ini secara otomatis
    $sekarang = Carbon::now();
    $bulan = $sekarang->month;
    $tahun = $sekarang->year;

    // 3. Logika menentukan Tipe Semester & Tahun Akademik berdasarkan bulan saat ini
    // Semester Ganjil (September [9] s/d Februari [2])
    if ($bulan >= 9 || $bulan <= 2) {
        $tipeSemester = 'Ganjil';
        // Jika bulan Jan/Feb, tahun akademik dimulai dari tahun lalu. Contoh: Feb 2026 -> Tahun Akademik 2025/2026
        $tahunMulai = ($bulan <= 2) ? $tahun - 1 : $tahun;
        $tahunSelesai = $tahunMulai + 1;
    } 
    // Semester Genap (Maret [3] s/d Agustus [8])
    else {
        $tipeSemester = 'Genap';
        // Contoh: Juni 2026 -> Tahun Akademik 2025/2026
        $tahunMulai = $tahun - 1;
        $tahunSelesai = $tahun;
    }

    $tahunAkademikAktif = $tahunMulai . '/' . $tahunSelesai; // Hasil akhir format: "2025/2026"

    // 4. Kueri data jadwal yang HANYA sesuai dengan Dosen ini DAN Periode Waktu Aktif saat ini
    $semuaJadwal = JadwalKuliah::with('mataKuliah')
        ->where('id_user', $user->id_user)
        ->where('tahun_akademik', $tahunAkademikAktif) // COCOKKAN TAHUN AKADEMIK (Contoh: "2025/2026")
        ->where('tipe_semester', $tipeSemester)        // COCOKKAN TIPE SEMESTER (Ganjil / Genap)
        ->orderBy('jam_mulai', 'asc')
        ->get();

    // 5. Kelompokkan jadwal berdasarkan hari
    $jadwalPerHari = $semuaJadwal->groupBy('hari');

    return view('dosen.jadwal', compact('user', 'jadwalPerHari'));
}

    public function store(Request $request)
    {
        $request->validate([
            'id_matkul' => 'required|exists:mata_kuliahs,id_matkul',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tahun_akademik' => 'required',
            'tipe_semester' => 'required',
        ]);

        JadwalKuliah::create([
            'id_matkul' => $request->id_matkul,
            'id_user' => auth()->user()->id_user, // Mengambil ID admin/user yang input, atau sesuaikan jika pilih dosen
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
            'tahun_akademik' => $request->tahun_akademik,
            'tipe_semester' => $request->tipe_semester,
            'is_buka' => $request->has('is_buka'),
        ]);

        return redirect()->back()->with('success', 'Jadwal kuliah berhasil ditambahkan!');
    }

   // Update Data
    public function update(Request $request, $id_jadwal)
    {
        $jadwal = JadwalKuliah::findOrFail($id_jadwal);
        
        $jadwal->update([
            'id_matkul'      => $request->id_matkul,
            'id_user'        => $request->id_user,
            'hari'           => $request->hari,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'    => $request->jam_selesai,
            'tahun_akademik' => $request->tahun_akademik,
            'tipe_semester'  => $request->tipe_semester,
            'is_buka'        => $request->has('is_buka'),
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    // Hapus Data
    public function destroy($id_jadwal)
    {
        $jadwal = JadwalKuliah::findOrFail($id_jadwal);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
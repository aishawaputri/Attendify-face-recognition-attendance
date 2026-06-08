<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\User;
use App\Models\JadwalKuliah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
    public function index()
    {
        // Ambil data KRS beserta relasi mahasiswa dan jadwal (matakuliah)
        $krsData = Krs::with(['user', 'jadwalKuliah.mataKuliah'])->latest()->get();
        
        // Data pendukung untuk Modal Tambah/Edit
        $mahasiswas = User::where('role', 'mahasiswa')->get();
        $jadwals = JadwalKuliah::with('mataKuliah')->get();

        return view('admin.krs', compact('krsData', 'mahasiswas', 'jadwals'));
    }

    public function viewMahasiswa(Request $request) 
    {
        $now = now();
        
        // --- 1. Tentukan Periode Aktif SEBENARNYA (Berdasarkan Waktu Sekarang) ---
        // Logika: Juli-Desember = Ganjil, Januari-Juni = Genap
        $realTaAktif = ($now->month >= 7) ? $now->year . "/" . ($now->year + 1) : ($now->year - 1) . "/" . $now->year;
        $realSmtAktif = ($now->month >= 7) ? 'Ganjil' : 'Genap';

        // --- 2. Tentukan Periode yang DILIHAT (Berdasarkan Filter) ---
        $filterTa = $request->get('tahun_akademik', $realTaAktif);
        $filterSmt = $request->get('tipe_semester', $realSmtAktif);

        // --- 3. Query Data (Berdasarkan Filter) ---
        $krsRaw = Krs::with(['jadwalKuliah.mataKuliah', 'jadwalKuliah.user'])
            ->join('jadwal_kuliahs', 'krs.id_jadwal', '=', 'jadwal_kuliahs.id_jadwal')
            ->where('krs.id_user', auth()->user()->id_user)
            ->where('jadwal_kuliahs.tahun_akademik', $filterTa)
            ->where('jadwal_kuliahs.tipe_semester', $filterSmt)
            ->select('krs.*')
            ->get();

        $totalSks = $krsRaw->sum(fn($item) => $item->jadwalKuliah->mataKuliah->sks ?? 0);
        $krsData = $krsRaw->groupBy(fn($item) => $item->jadwalKuliah->hari);

        // --- 4. Ambil Daftar Tahun untuk Dropdown ---
        $listTa = \App\Models\JadwalKuliah::select('tahun_akademik')->distinct()->orderBy('tahun_akademik', 'desc')->pluck('tahun_akademik');
        if (!$listTa->contains($realTaAktif)) { $listTa->push($realTaAktif); $listTa = $listTa->sortDesc(); }

        return view('mahasiswa.krs', compact(
            'krsData', 
            'filterTa', 
            'filterSmt', 
            'totalSks', 
            'listTa', 
            'realTaAktif', // Kita kirim ini ke view
            'realSmtAktif'  // Kita kirim ini ke view
        ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'id_jadwal' => 'required|exists:jadwal_kuliahs,id_jadwal',
        ]);

        // Cek agar tidak ada duplikasi KRS (Mahasiswa ambil jadwal yang sama 2x)
        $exists = Krs::where('id_user', $request->id_user)
                     ->where('id_jadwal', $request->id_jadwal)
                     ->exists();

        if ($exists) {
            return back()->with('error', 'Mahasiswa sudah terdaftar di jadwal ini!');
        }

        Krs::create($request->all());
        return back()->with('success', 'Berhasil menambahkan mahasiswa ke jadwal.');
    }

    public function update(Request $request, $id)
    {
        $krs = Krs::findOrFail($id);
        $krs->update($request->all());
        return back()->with('success', 'Data KRS berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Krs::findOrFail($id)->delete();
        return back()->with('success', 'Mahasiswa berhasil dikeluarkan dari jadwal.');
    }
}
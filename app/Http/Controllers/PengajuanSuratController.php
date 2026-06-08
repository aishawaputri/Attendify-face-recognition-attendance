<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    /**
     * Menampilkan daftar pengajuan surat berstatus Pending yang masuk ke Dosen
     */
    public function indexDosen()
    {
        $user = Auth::user();

        // Mengambil surat milik mahasiswa yang mengambil kelas/jadwal dosen ini
        $daftarSurat = PengajuanSurat::with(['mahasiswa', 'jadwalKuliah.mataKuliah'])
            ->whereHas('jadwalKuliah', function($query) use ($user) {
                // Menyaring berdasarkan id_user dosen yang mengajar kelas tersebut
                $query->where('id_user', $user->id_user); 
            })
            ->where('status_acc', 'Pending') // Sesuai nama kolom & enum di migration
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dosen.pengajuan', compact('daftarSurat'));
    }

    /**
     * Memproses aksi ACC atau Tolak dari Dosen
     */
    public function updateStatus(Request $request, $id_pengajuan)
    {
        // Validasi input harus sesuai dengan nilai Enum di database (Kapital)
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        // Cari berdasarkan Primary Key 'id_pengajuan'
        $surat = PengajuanSurat::findOrFail($id_pengajuan);
        
        // Update kolom status_acc
        $surat->status_acc = $request->status;
        $surat->save();

        return redirect()->back()->with('success', 'Status perizinan mahasiswa berhasil diperbarui!');
    }
}
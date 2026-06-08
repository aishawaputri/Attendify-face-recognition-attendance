<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * Menampilkan daftar matakuliah (Read)
     */
    public function index()
    {
        $matakuliahs = MataKuliah::latest()->get();
        return view('admin.matakuliah', compact('matakuliahs'));
    }
    /**
     * Menyimpan data matakuliah baru ke database (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk'  => 'required|unique:mata_kuliahs,kode_mk|max:50',
            'nama_mk'  => 'required|string|max:255',
            'sks'      => 'required|integer|min:1',
            'semester' => 'required|integer|min:1',
        ], [
            'kode_mk.unique' => 'Kode Matakuliah ini sudah digunakan!',
        ]);

        MataKuliah::create($request->all());

        return redirect()->route('admin.matakuliah')
                         ->with('success', 'Data matakuliah berhasil ditambahkan!');
    }


    /**
     * Mengupdate data matakuliah di database (Update)
     */
    public function update(Request $request, $id_matkul)
    {
        $matakuliah = MataKuliah::findOrFail($id_matkul);

        // 1. Validasi inputan form (pengecualian unique untuk ID yang sedang diedit)
        $request->validate([
            'kode_mk'  => 'required|max:50|unique:mata_kuliahs,kode_mk,' . $id_matkul . ',id_matkul',            
            'nama_mk'  => 'required|string|max:255',
            'sks'      => 'required|integer|min:1',
            'semester' => 'required|integer|min:1',
        ]);

        // 2. Update data
        $matakuliah->update($request->all());

        // 3. Redirect dengan pesan sukses
        return redirect()->route('admin.matakuliah')
                         ->with('success', 'Data matakuliah berhasil diperbarui!');
    }

    /**
     * Menghapus data matakuliah dari database (Delete)
     */
    public function destroy($id_matkul)
    {
        $matakuliah = MataKuliah::findOrFail($id_matkul);
        $matakuliah->delete();

        return redirect()->route('admin.matakuliah')
                         ->with('success', 'Data matakuliah berhasil dihapus!');
    }
}
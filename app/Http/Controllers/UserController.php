<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function mahasiswa() {
        return $this->renderUserPage('mahasiswa', 'Data Mahasiswa');
    }

    public function dosen() {
        return $this->renderUserPage('dosen', 'Data Dosen');
    }

    public function admin() {
        return $this->renderUserPage('admin', 'Data Admin / Staf');
    }

    // Fungsi internal agar tidak nulis kode berulang
    private function renderUserPage($role, $title) {
        $users = User::where('role', $role)->get();
        return view('admin.user', compact('users', 'role', 'title'));
    }

    // ==========================================
    // FUNGSI TAMBAH DATA (STORE)
    // ==========================================
    public function store(Request $request, $role)
    {
        // Antisipasi perbedaan atribut name di form HTML
        $namaLengkap = $request->name ?? $request->nama;
        $nomorId = $request->nim ?? $request->nidn ?? $request->nomerIdentitas;

        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama' => $namaLengkap, 
            'email' => $request->email,
            'nomerIdentitas' => $nomorId,
            'password' => bcrypt($request->password),
            'role' => $role,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    // ==========================================
    // FUNGSI UPDATE DATA
    // ==========================================
    public function update(Request $request, $id_user)
    {
        $user = User::where('id_user', $id_user)->firstOrFail();

        // Antisipasi perbedaan atribut name di form HTML
        $namaLengkap = $request->name ?? $request->nama;
        $nomorId = $request->nim ?? $request->nidn ?? $request->nomerIdentitas;

        // Set data ke database sesuai kolom
        $user->nama = $namaLengkap;
        $user->email = $request->email;
        
        if ($nomorId) {
            $user->nomerIdentitas = $nomorId;
        }

        // Cek apakah password diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    // ==========================================
    // FUNGSI HAPUS DATA
    // ==========================================
    public function destroy($id_user)
    {
        $user = User::where('id_user', $id_user)->firstOrFail();
        $user->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
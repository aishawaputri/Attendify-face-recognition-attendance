<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login (Desain modern yang kamu buat).
     */
    public function showLogin()
    {
        return view('auth.login');
    }

   public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            return redirect()->intended($this->getRedirectPath($role));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email'); // Mempertahankan input email agar tidak hilang saat reload
    }
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Helper untuk menentukan path dashboard berdasarkan role.
     */
    private function getRedirectPath($role)
    {
        return match ($role) {
            'admin'     => '/admin/dashboard',
            'dosen'     => '/dosen/dashboard',
            'mahasiswa' => '/dashboard',
            default     => '/login',
        };
    }
}
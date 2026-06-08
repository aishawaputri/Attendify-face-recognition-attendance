<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. LOGIN TRADISIONAL (EMAIL & PASSWORD)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // HELPER: Logika Pengalihan Role
    private function redirectBasedOnRole($user)
    {
        return redirect($this->getRedirectPath($user));
    }

    private function getRedirectPath($user)
    {
        return match($user->role) {
            'admin' => '/admin/dashboard',
            'dosen' => '/dosen/dashboard',
            default => '/mahasiswa/dashboard',
        };
    }
}

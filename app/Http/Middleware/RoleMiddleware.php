<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek apakah belum login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah role user SESUAI dengan role yang diminta di route
        if (Auth::user()->role !== $role) {
            abort(403, 'MAAF, ANDA BUKAN ' . strtoupper($role) . '!');
        }

        return $next($request);
    }
}
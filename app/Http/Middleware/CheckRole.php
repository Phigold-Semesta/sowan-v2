<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan pengguna sudah login
        if (!Auth::check()) {
            // Jika user belum login, lempar ke halaman login
            return redirect()->route('login');
        }

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // 3. Cek apakah role user ada di dalam daftar $roles
        if (in_array($user->role, $roles)) {
            return $next($request); 
        }

        // 4. Jika user sudah login tapi tidak punya akses (bukan admin/petugas)
        // Hindari infinite loop dengan mengecek apakah user sudah berada di dashboard
        if ($request->routeIs('dashboard')) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki hak akses.');
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }
}
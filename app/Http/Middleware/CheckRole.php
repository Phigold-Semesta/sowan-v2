<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// Import Facade Auth agar tidak ada garis merah dan dikenali editor
use Illuminate\Support\Facades\Auth; 

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Menggunakan splat operator untuk menerima banyak role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan pengguna sudah login menggunakan Facade Auth
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        // 3. Cek apakah role user ada di dalam daftar $roles yang dikirim dari route
        // Sesuai tabel user di database Anda: 'administrator', 'petugas', 'pimpinan'
        if (in_array($user->role, $roles)) {
            return $next($request); // Berikan izin akses ✅
        }

        // 4. Jika tidak memiliki akses, arahkan kembali ke dashboard dengan pesan error
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
    }
}
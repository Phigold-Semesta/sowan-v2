<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class TamuMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Menggunakan Auth Guard 'tamu' untuk verifikasi autentikasi yang aman.
        if (!Auth::guard('tamu')->check()) {
            
            // Perbaikan: Cek apakah rute 'tamu.login' ada untuk menghindari RouteNotFoundException
            // Jika tidak ada, fallback ke root ('/') atau rute login yang valid
            $loginRoute = Route::has('tamu.login') ? 'tamu.login' : 'tamu.login.view';
            
            return redirect()->route($loginRoute)->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
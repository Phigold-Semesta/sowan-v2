<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class TamuMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mengecek apakah session 'tamu_id' ada
        if (!Session::has('tamu_id')) {
            // Jika tidak ada, arahkan kembali ke portal publik
            return redirect()->route('tamu.publik')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
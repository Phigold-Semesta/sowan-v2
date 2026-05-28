<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Mendaftarkan alias middleware agar bisa digunakan dengan penamaan 'role'
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // 2. PENTING: Jangan masukkan 'role' ke dalam 'web' group secara default.
        // Biarkan 'web' group tetap standar Laravel agar session dan cookies 
        // untuk tamu tetap berfungsi normal tanpa dipaksa login.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
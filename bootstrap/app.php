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
        // 1. Mendaftarkan alias middleware agar bisa digunakan dengan penamaan yang tepat
        $middleware->alias([
            'role'      => \App\Http\Middleware\CheckRole::class,
            'tamu.auth' => \App\Http\Middleware\TamuMiddleware::class,
        ]);

        // 2. Memastikan Session terkelola dengan baik untuk semua grup route
        // Menambahkan middleware session agar Auth Guard dapat mengenali sesi login tamu
        $middleware->web(append: [
            \Illuminate\Session\Middleware\StartSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
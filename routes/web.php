<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SOWAN v2 (Digital Guest Book)
|--------------------------------------------------------------------------
*/

// 1. Halaman Utama / Welcome
Route::get('/', function () {
    return view('auth.login'); // Redirect ke halaman login sebagai halaman utama
});

// 2. Route Khusus Guest (Belum Login)
// Menggunakan middleware 'guest' agar user yang sudah login tidak bisa kembali ke form login
Route::middleware('guest')->group(function () {
    
    // Menampilkan Form Login (Username & Password)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    // Proses Autentikasi User
    Route::post('/login', [AuthController::class, 'authenticate']);
    
});

// 3. Route Khusus User yang Sudah Login (Terproteksi)
// Menggunakan middleware 'auth' untuk melindungi halaman internal
Route::middleware('auth')->group(function () {
    
    // Halaman Dashboard Utama
    Route::get('/dashboard', function () {
        return view('dashboard'); // Pastikan file resources/views/dashboard.blade.php ada
    })->name('dashboard');

    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
});
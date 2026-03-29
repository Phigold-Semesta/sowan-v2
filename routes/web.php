<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SOWAN v2 (Digital Guest Book)
|--------------------------------------------------------------------------
*/

// 1. Jalur Utama & Autentikasi 🚪
// Mengarahkan '/' langsung ke login jika belum masuk
Route::get('/', function () {
    return redirect()->route('login');
});

// Jalur khusus tamu (Link ini yang dimasukkan ke QR Code) 📱
Route::get('/hadir', [TamuController::class, 'create'])->name('tamu.form');
Route::post('/tamu/simpan', [TamuController::class, 'store'])->name('tamu.store');

// Login & Auth (Hanya untuk tamu/guest yang BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.proses');
});

// 2. Route Terproteksi (Wajib Login) 🔐
Route::middleware('auth')->group(function () {

    // Dashboard Utama
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // --- Grup Akses: Petugas & Administrator ---
    Route::middleware('role:petugas,administrator')->group(function () {
        Route::get('/status-tamu', [TamuController::class, 'indexStatus'])->name('tamu.status');
        Route::patch('/status-tamu/{id}', [TamuController::class, 'updateStatus'])->name('tamu.updateStatus');
    });

    // --- Grup Akses: Khusus Administrator ---
    Route::middleware('role:administrator')->group(function () {
        Route::resource('admin/users', UserController::class)->names('admin.users');
        Route::get('/audit-log', [UserController::class, 'logs'])->name('admin.logs');
    });

    // --- Grup Akses: Pimpinan & Administrator ---
    Route::middleware('role:pimpinan,administrator')->group(function () {
        Route::get('/laporan', [TamuController::class, 'report'])->name('laporan.index');
        Route::get('/statistik', [TamuController::class, 'stats'])->name('statistik.index');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
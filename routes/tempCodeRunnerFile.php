<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetugasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SOWAN v2 (Digital Guest Book)
|--------------------------------------------------------------------------
*/

// 1. Jalur Publik & Autentikasi Dasar 🚪
Route::get('/', function () {
    return redirect()->route('login');
});

// Jalur khusus tamu (Input Form QR Code) - Akses Publik
Route::controller(TamuController::class)->group(function () {
    Route::get('/hadir', 'create')->name('tamu.form');
    Route::post('/tamu/simpan', 'store')->name('tamu.store');
});

// Login & Logout logic
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.proses');
});

// 2. Area Terproteksi (Wajib Login) 🔐
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Utama - Bisa diakses semua role yang login
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // --- Grup Akses: KHUSUS PETUGAS ---
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        
        // Resource Manajemen Tamu
        Route::resource('manajemen_tamu', PetugasController::class)
             ->parameters(['manajemen_tamu' => 'tamu'])
             ->where(['tamu' => '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}']);
        
        // Update status pelayanan
        Route::patch('manajemen_tamu/{tamu}/status', [PetugasController::class, 'updateStatus'])
             ->name('manajemen_tamu.updateStatus')
             ->where('tamu', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}');
    });

    // --- Grup Akses: KHUSUS ADMINISTRATOR ---
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        /**
         * Resource Users mencakup:
         * index   -> GET /admin/users
         * create  -> GET /admin/users/create
         * store   -> POST /admin/users
         * show    -> GET /admin/users/{user}  <-- Ini yang menghubungkan ke show.blade.php
         * edit    -> GET /admin/users/{user}/edit
         * update  -> PUT/PATCH /admin/users/{user}
         * destroy -> DELETE /admin/users/{user}
         */
        Route::resource('users', UserController::class)
             ->parameters(['users' => 'user']); // Memastikan parameter di controller adalah $user

        Route::get('/audit-log', [UserController::class, 'logs'])->name('logs');
    });

    // --- Grup Akses: PIMPINAN & ADMINISTRATOR (Laporan) ---
    Route::middleware('role:pimpinan,administrator')->group(function () {
        Route::get('/laporan', [TamuController::class, 'report'])->name('laporan.index');
        Route::get('/statistik', [TamuController::class, 'stats'])->name('statistik.index');
    });
});
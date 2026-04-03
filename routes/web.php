<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk menghindari error Intelephense

/*
|--------------------------------------------------------------------------
| Web Routes - SOWAN v2 (Digital Guest Book)
|--------------------------------------------------------------------------
*/

// --- 1. JALUR PUBLIK & AUTENTIKASI 🚪 ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Jalur khusus tamu (Input Form QR Code)
Route::controller(TamuController::class)->group(function () {
    Route::get('/hadir', 'create')->name('tamu.form');
    Route::post('/tamu/simpan', 'store')->name('tamu.store');
});

// Logic Login & Logout Dasar
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.proses');
});

// --- 2. AREA TERPROTEKSI (WAJIB LOGIN) 🔐 ---
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /**
     * Dashboard Redirector
     * Menggunakan Facade Auth::user() untuk kompatibilitas editor yang lebih baik
     */
    Route::get('/dashboard', function () {
        $user = Auth::user(); // Lebih aman dan dikenali editor
        
        if ($user->role === 'administrator') return redirect()->route('admin.dashboard');
        if ($user->role === 'petugas') return redirect()->route('petugas.dashboard');
        
        return redirect()->route('pimpinan.dashboard');
    })->name('dashboard');

    // --- GRUP AKSES: PETUGAS 📋 ---
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', function () {
            return view('petugas.dashboard');
        })->name('dashboard');

        Route::resource('manajemen_tamu', PetugasController::class)
             ->parameters(['manajemen_tamu' => 'tamu'])
             ->where(['tamu' => '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}']);
        
        Route::patch('manajemen_tamu/{tamu}/status', [PetugasController::class, 'updateStatus'])
             ->name('manajemen_tamu.updateStatus')
             ->where('tamu', '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}');
    });

    // --- GRUP AKSES: ADMINISTRATOR 🛡️ ---
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('users', UserController::class)
             ->parameters(['users' => 'user']);

        Route::get('/audit-log', [UserController::class, 'logs'])->name('logs');
        Route::get('/master', [AdminController::class, 'masterIndex'])->name('master.index');
    });

    // --- GRUP AKSES: PIMPINAN 📊 ---
    Route::middleware('role:pimpinan')->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/dashboard', [TamuController::class, 'pimpinanDashboard'])->name('dashboard');
    });

    // --- FITUR LAPORAN & STATISTIK (SHARED) 📑 ---
    Route::middleware('role:pimpinan,administrator')->group(function () {
        Route::get('/laporan', [TamuController::class, 'report'])->name('laporan.index');
        Route::get('/statistik', [TamuController::class, 'stats'])->name('statistik.index');
    });
});
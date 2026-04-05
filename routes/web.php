<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - SOWAN v2 (Digital Guest Book LPSE Karawang)
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
     * Dashboard Redirector (Otomatis sesuai Role)
     */
    Route::get('/dashboard', function () {
        $user = Auth::user(); 
        
        return match ($user->role) {
            'administrator' => redirect()->route('admin.dashboard'),
            'petugas'       => redirect()->route('petugas.dashboard'),
            'pimpinan'      => redirect()->route('pimpinan.dashboard'),
            default         => abort(403, 'Role tidak terdefinisi.'),
        };
    })->name('dashboard');

    // --- GRUP AKSES: PETUGAS 📋 ---
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', function () {
            return view('petugas.dashboard');
        })->name('dashboard');

        // CRUD Tamu untuk Petugas
        Route::resource('manajemen_tamu', PetugasController::class)
             ->parameters(['manajemen_tamu' => 'tamu']);
        
        Route::patch('manajemen_tamu/{tamu}/status', [PetugasController::class, 'updateStatus'])
             ->name('manajemen_tamu.updateStatus');
    });

    // --- GRUP AKSES: ADMINISTRATOR 🛡️ ---
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard Utama
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Manajemen User (CRUD)
        Route::resource('users', UserController::class)
             ->parameters(['users' => 'user']);

        // --- MASTER DATA (Pusat Kendali Data SOWAN v2) ---
        Route::prefix('master')->name('master.')->group(function() {
            
            // Halaman Utama Master
            Route::get('/', [AdminController::class, 'master_index'])->name('index');

            // CRUD Kategori Layanan
            Route::prefix('layanan')->name('layanan.')->group(function() {
                Route::get('/', [AdminController::class, 'layanan_index'])->name('index');
                Route::get('/create', [AdminController::class, 'layanan_create'])->name('create');
                Route::post('/', [AdminController::class, 'layanan_store'])->name('store');
                Route::get('/{id}', [AdminController::class, 'layanan_show'])->name('show');
                Route::get('/{id}/edit', [AdminController::class, 'layanan_edit'])->name('edit');
                Route::put('/{id}', [AdminController::class, 'layanan_update'])->name('update');
                Route::delete('/{id}', [AdminController::class, 'layanan_destroy'])->name('destroy');
            });

            // CRUD Tujuan Kunjungan
            Route::prefix('tujuan')->name('tujuan.')->group(function() {
                Route::get('/', [AdminController::class, 'tujuan_index'])->name('index');
                Route::get('/create', [AdminController::class, 'tujuan_create'])->name('create');
                Route::post('/store', [AdminController::class, 'tujuan_store'])->name('tujuan_store'); 
                Route::get('/{id}', [AdminController::class, 'tujuan_show'])->name('show');
                Route::get('/{id}/edit', [AdminController::class, 'tujuan_edit'])->name('edit');
                Route::put('/{id}', [AdminController::class, 'tujuan_update'])->name('update');
                Route::delete('/{id}', [AdminController::class, 'tujuan_destroy'])->name('destroy');
            });
        });

        // --- MENU LAPORAN KUNJUNGAN (DISEMPURNAKAN & DISINKRONKAN) 📑 ---
        Route::prefix('laporan')->name('laporan.')->group(function() {
            Route::get('/', [AdminController::class, 'laporan_index'])->name('index');
            
            // PERBAIKAN UTAMA: Nama rute diubah dari 'export.csv' menjadi 'export'
            // Hal ini agar sinkron dengan panggil di Blade: {{ route('admin.laporan.export') }}
            Route::get('/export', [AdminController::class, 'laporan_export'])->name('export');
        });

        // --- AKTIVITAS GLOBAL (Audit Log) 🕵️‍♂️ ---
        Route::prefix('aktivitas')->name('aktivitas.')->group(function() {
            Route::get('/', [AdminController::class, 'aktivitas_global'])->name('index');
        });
    });

    // --- GRUP AKSES: PIMPINAN 📊 ---
    Route::middleware('role:pimpinan')->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/dashboard', [TamuController::class, 'pimpinanDashboard'])->name('dashboard');
        
        // Pimpinan menggunakan rute laporan yang sama secara fungsional
        Route::get('/laporan', [AdminController::class, 'laporan_index'])->name('laporan.index');
        Route::get('/laporan/export', [AdminController::class, 'laporan_export'])->name('laporan.export');
    });

    // --- FITUR STATISTIK (SHARED) 📈 ---
    Route::middleware('role:pimpinan,administrator')->group(function () {
        Route::get('/statistik', [TamuController::class, 'stats'])->name('statistik.index');
    });
});
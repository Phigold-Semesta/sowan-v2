<?php



use App\Http\Controllers\AuthController;

use App\Http\Controllers\TamuController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\PetugasController;

use App\Http\Controllers\AdminController;

use App\Http\Controllers\PimpinanController;

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;



/*

|--------------------------------------------------------------------------

| Web Routes - SOWAN v2 (Digital Guest Book LPSE Karawang)

|--------------------------------------------------------------------------

| Desain Sistem: Emerald Green Luxury

| Build with: Laravel 12

|--------------------------------------------------------------------------

*/
/*
|--------------------------------------------------------------------------
| Web Routes - SOWAN v2
|--------------------------------------------------------------------------
*/
// --- 0. RUTE UTAMA (ROOT) ---
// Memperbaiki masalah 404 saat akses localhost:8000/
Route::get('/', function () {
    return redirect()->route('tamu.onsite.view');
});

// --- 2. PORTAL PUBLIK (Tamu Online) ---
Route::prefix('portal')->name('tamu.')->group(function () {
    Route::get('/', [AuthController::class, 'showPublik'])->name('login.view'); 
    Route::post('/login', [AuthController::class, 'loginOnline'])->name('login.online');
    Route::get('/register', [AuthController::class, 'showSignup'])->name('register.view');
    Route::post('/register/store', [AuthController::class, 'registerOnline'])->name('register.store');
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');
    
    Route::middleware('tamu.auth')->group(function () {
        Route::get('/dashboard', [TamuController::class, 'dashboard'])->name('dashboard');
        Route::get('/profil', [TamuController::class, 'profil'])->name('profil');
        Route::get('/riwayat', [TamuController::class, 'riwayat'])->name('riwayat');
        Route::get('/kunjungan-baru', [TamuController::class, 'kunjunganBaru'])->name('kunjungan.baru');
        Route::post('/kunjungan-simpan', [TamuController::class, 'simpanKunjungan'])->name('kunjungan.simpan');
        // Rute logout untuk portal publik (nama: tamu.logout.tamu)
    // --- TAMBAHAN: Rute Konsultasi Online Tamu ---
       // Rute untuk menampilkan halaman daftar konsultasi
    Route::get('/konsultasi-online', [TamuController::class, 'konsultasiOnline'])->name('konsultasi_online.index');
    
    // Rute untuk memproses pengajuan janji baru dari modal
    Route::post('/konsultasi-simpan', [TamuController::class, 'simpanKonsultasi'])->name('konsultasi.simpan');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout.tamu');
    });
});

// --- 3. JALUR TAMU ONSITE ---
Route::prefix('tamu-onsite')->name('tamu.onsite.')->group(function () {
    Route::get('/', [AuthController::class, 'showTamuOnsite'])->name('view');
    Route::post('/check-email', [AuthController::class, 'checkEmailOnsite'])->name('check-email');
});

// --- 4. PROSES KUNJUNGAN ---
// Grup A: Hanya bisa diakses saat tamu masih login (Form & Store)
Route::prefix('tamu')->name('tamu.')->middleware('tamu.auth')->group(function () {
    Route::get('/form-baru', [TamuController::class, 'showFormBaru'])->name('form_tamu_baru');
    Route::get('/form-lama', [TamuController::class, 'showFormLama'])->name('form_tamu_lama');
    Route::get('/hadir', [TamuController::class, 'index'])->name('index'); 
    Route::post('/simpan', [TamuController::class, 'store'])->name('store');
    Route::get('/panduan/{id}', [TamuController::class, 'downloadPanduan'])->name('panduan.download');
});

// Grup B: Terbuka (Tidak pakai middleware tamu.auth) 
// Agar bisa diakses tepat setelah tamu logout di TamuController@store
Route::prefix('tamu')->name('tamu.')->group(function () {
    Route::get('/sukses/baru/{nama_tamu}', function ($nama_tamu) {
        return view('tamu.success_tamu_baru', ['nama_tamu' => urldecode($nama_tamu)]);
    })->name('success_baru')->where('nama_tamu', '.*');

    Route::get('/sukses/lama/{nama_tamu}', function ($nama_tamu) {
        return view('tamu.success_tamu_lama', ['nama_tamu' => urldecode($nama_tamu)]);
    })->name('success_lama')->where('nama_tamu', '.*');
});

// --- 5. JALUR AUTENTIKASI (Internal) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.proses');
}); // Penutup kurung ini sebelumnya hilang

// --- 6. AREA TERPROTEKSI (WAJIB LOGIN INTERNAL) ---
Route::middleware('auth')->group(function () {
    // Logout internal
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /**
     * Dashboard Redirector (Otomatis sesuai Role)
     * Sudah di dalam grup middleware('auth'), jadi $user tidak akan null
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



// --- GRUP AKSES: PETUGAS 📋 (PERBAIKAN) ---
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');

        Route::resource('manajemen_tamu', PetugasController::class)
             ->parameters(['manajemen_tamu' => 'tamu']);
        
        Route::patch('manajemen_tamu/{tamu}/status', [PetugasController::class, 'updateStatus'])
             ->name('manajemen_tamu.updateStatus');

        // Grup laporan diperbaiki posisinya
        Route::prefix('laporan')->name('laporan.')->group(function() {
            Route::get('/', [PetugasController::class, 'laporanIndex'])->name('index');
            Route::get('/export', [PetugasController::class, 'laporan_export'])->name('export'); 
        });

        // Grup rating sekarang sudah benar berada di dalam grup petugas
        Route::prefix('rating')->name('rating.')->group(function() {
            Route::get('/', [PetugasController::class, 'ratingIndex'])->name('index');
            Route::post('/{id}/tanggapan', [PetugasController::class, 'ratingTanggapan'])->name('tanggapan');
        });

        // --- TAMBAHAN: FITUR KONSULTASI ONLINE PETUGAS ---
        Route::prefix('konsultasi')->name('konsultasi.')->group(function() {
            Route::get('/', [PetugasController::class, 'konsultasiIndex'])->name('index');
            Route::post('/toggle-status', [PetugasController::class, 'toggleStatusLayanan'])->name('toggle');
            Route::post('/{id}/konfirmasi', [PetugasController::class, 'konfirmasiKonsultasi'])->name('konfirmasi');
        });
    });

   
    // --- GRUP AKSES: ADMINISTRATOR 🛡️ ---
    Route::middleware('role:administrator')->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('users', UserController::class)
             ->parameters(['users' => 'user']);

        // --- MASTER DATA ---
        Route::prefix('master')->name('master.')->group(function() {
            
            Route::get('/', [AdminController::class, 'master_index'])->name('index');

            Route::prefix('layanan')->name('layanan.')->group(function() {
                Route::get('/', [AdminController::class, 'layanan_index'])->name('index');
                Route::get('/create', [AdminController::class, 'layanan_create'])->name('create');
                Route::post('/', [AdminController::class, 'layanan_store'])->name('store');
                Route::get('/{id}', [AdminController::class, 'layanan_show'])->name('show');
                Route::get('/{id}/edit', [AdminController::class, 'layanan_edit'])->name('edit');
                Route::put('/{id}', [AdminController::class, 'layanan_update'])->name('update');
                Route::delete('/{id}', [AdminController::class, 'layanan_destroy'])->name('destroy');

                // Mengelola Dokumen Panduan Layanan (Sesuai Use Case)
                Route::get('/{id}/panduan', [AdminController::class, 'layanan_panduan'])->name('panduan');
                Route::post('/{id}/panduan', [AdminController::class, 'layanan_panduan_store'])->name('panduan.store');
                Route::delete('/panduan/{id_dokumen}', [AdminController::class, 'layanan_panduan_destroy'])->name('panduan.destroy');
            });

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

        // --- MENU LAPORAN KUNJUNGAN ---
        Route::prefix('laporan')->name('laporan.')->group(function() {
            Route::get('/', [AdminController::class, 'laporan_index'])->name('index');
            Route::get('/export', [AdminController::class, 'laporan_export'])->name('export');
            Route::get('/{id}', [AdminController::class, 'laporan_show'])->name('show');
            Route::get('/{id}/edit', [AdminController::class, 'laporan_edit'])->name('edit');
            Route::put('/{id}', [AdminController::class, 'laporan_update'])->name('update');
            Route::delete('/{id}', [AdminController::class, 'laporan_destroy'])->name('destroy');
        });

        // --- MANAJEMEN RATING LAYANAN ---
        Route::prefix('rating')->name('rating.')->group(function() {
            Route::get('/', [AdminController::class, 'rating_index'])->name('index');
            Route::get('/{id}', [AdminController::class, 'rating_show'])->name('show');
            Route::put('/{id}', [AdminController::class, 'rating_tanggapan'])->name('update');
            Route::post('/{id}/tanggapan', [AdminController::class, 'rating_tanggapan'])->name('tanggapan');
            Route::delete('/{id}', [AdminController::class, 'rating_destroy'])->name('destroy');
        });

        // --- MANAJEMEN KONSULTASI ONLINE ---
        Route::prefix('konsultasi')->name('konsultasi.')->group(function() {
            Route::get('/', [AdminController::class, 'konsultasi_index'])->name('index');
            Route::delete('/{id}', [AdminController::class, 'konsultasi_destroy'])->name('destroy');
        });

        // Monitoring Aktivitas Global (Audit Log)
        Route::prefix('aktivitas')->name('aktivitas.')->group(function() {
            Route::get('/', [AdminController::class, 'aktivitas_global'])->name('index');
        });
    });


  // --- GRUP AKSES: PIMPINAN 👔 ---
    Route::middleware('role:pimpinan')->prefix('pimpinan')->name('pimpinan.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [PimpinanController::class, 'dashboard'])->name('dashboard');
        
        // Laporan
        Route::get('/laporan', [PimpinanController::class, 'laporanIndex'])->name('laporan.index');
        Route::get('/laporan/export', [PimpinanController::class, 'laporanExport'])->name('laporan.export');
        
        // Monitoring Rating & Detail (AJAX)
        Route::get('/rating', [PimpinanController::class, 'ratingIndex'])->name('rating.index');
        Route::get('/rating/{id}', [PimpinanController::class, 'ratingShow'])->name('rating.show');
        
        // Log Aktivitas
        Route::get('/aktivitas', [PimpinanController::class, 'aktivitasIndex'])->name('aktivitas.index');

        // Fitur Konsultasi Online
        Route::get('/konsultasi', [PimpinanController::class, 'konsultasiIndex'])->name('konsultasi.index');
        Route::post('/konsultasi/toggle-status', [PimpinanController::class, 'toggleStatusLayanan'])->name('konsultasi.toggle');
        Route::post('/konsultasi/{id}/konfirmasi', [PimpinanController::class, 'konfirmasiKonsultasi'])->name('konsultasi.konfirmasi');
    });

    // --- FITUR STATISTIK & GRAFIK (SHARED) ---
    Route::middleware(['role:pimpinan,administrator'])->group(function () {
        Route::get('/statistik', [TamuController::class, 'stats'])->name('statistik.index');
    });

});
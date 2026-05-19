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

| Desain Sistem: Emerald Green Luxury

| Build with: Laravel 12

|--------------------------------------------------------------------------

*/



// --- 1. JALUR PUBLIK & AUTENTIKASI 🚪 ---

Route::get('/', function () {

    return redirect()->route('login');

});



/**

 * PENYESUAIAN ALUR TAMU (SESUAI USE CASE REVISI 4):

 * Alur: Scan QR -> Validasi Gmail -> Tampil Form (Baru/Lama) -> Simpan -> Sukses.

 */

Route::controller(TamuController::class)->group(function () {

    // 1. Halaman Awal scan QR (Menampilkan form input Gmail pertama kali)

    // PERBAIKAN: Diarahkan ke method 'create' atau 'index' yang memanggil view form_tamu_baru

    Route::get('/hadir', 'index')->name('tamu.index'); 

    

    // 2. Validasi Gmail: Menentukan apakah tamu harus ke form_baru atau form_lama

    Route::post('/tamu/cek', 'check')->name('tamu.check');

    

    // Penanganan Error 405: Jika user refresh halaman hasil cek

    Route::get('/tamu/cek', function() {

        return redirect()->route('tamu.index');

    });

    

    // 3. Proses simpan data kunjungan (Finalisasi pendaftaran tamu)

    Route::post('/tamu/simpan', 'store')->name('tamu.store');

    

    // 4. VIEW SUKSES (Tujuan redirect setelah simpan)

    // PERBAIKAN: Menambahkan route eksplisit untuk halaman sukses

    Route::get('/tamu/sukses/baru', 'successBaru')->name('tamu.success_baru');

    Route::get('/tamu/sukses/lama', 'successLama')->name('tamu.success_lama');

    

    // 5. Fitur Tamu: Unduh Dokumen Panduan (Sesuai Use Case)

    Route::get('/panduan/{id}', 'downloadPanduan')->name('tamu.panduan.download');

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
    // Diarahkan ke PetugasController agar variabel $logs dikirim ke view
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');


        // Manajemen Tamu (Resource)

        Route::resource('manajemen_tamu', PetugasController::class)

             ->parameters(['manajemen_tamu' => 'tamu']);

        

        // Update Status Tamu: Belum, Sedang, atau Sudah Dilayani (Sesuai Use Case)

        Route::patch('manajemen_tamu/{tamu}/status', [PetugasController::class, 'updateStatus'])

             ->name('manajemen_tamu.updateStatus');



     /**
         * PENYEMPURNAAN AKTOR PETUGAS
         */
        Route::prefix('laporan')->name('laporan.')->group(function() {
            // 🔥 PERBAIKAN: Ubah 'laporan_index' menjadi 'laporanIndex'
            Route::get('/', [PetugasController::class, 'laporanIndex'])->name('index');
        });

        Route::prefix('rating')->name('rating.')->group(function() {
            // 🔥 PERBAIKAN: Ubah 'rating_index' menjadi 'ratingIndex'
            Route::get('/', [PetugasController::class, 'ratingIndex'])->name('index');
            
            // 🔥 PERBAIKAN: Ubah 'rating_tanggapan' menjadi 'ratingTanggapan' (Sesuai Use Case)
            Route::post('/{id}/tanggapan', [PetugasController::class, 'ratingTanggapan'])->name('tanggapan');
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



        // Monitoring Aktivitas Global (Audit Log)

        Route::prefix('aktivitas')->name('aktivitas.')->group(function() {

            Route::get('/', [AdminController::class, 'aktivitas_global'])->name('index');

        });

    });



    // --- GRUP AKSES: PIMPINAN 👔 ---

    Route::middleware('role:pimpinan')->prefix('pimpinan')->name('pimpinan.')->group(function () {

        Route::get('/dashboard', [TamuController::class, 'pimpinanDashboard'])->name('dashboard');

        Route::get('/laporan', [AdminController::class, 'laporan_index'])->name('laporan.index');

        Route::get('/laporan/export', [AdminController::class, 'laporan_export'])->name('laporan.export');

    });



    // --- FITUR STATISTIK & GRAFIK (SHARED) ---

    Route::middleware('role:pimpinan,administrator')->group(function () {

        Route::get('/statistik', [TamuController::class, 'stats'])->name('statistik.index');

    });

});
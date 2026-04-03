<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tamu;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Tambahkan ini untuk pengecekan kolom

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Admin SOWAN v2
     * Sudah dilengkapi proteksi kolom rating agar tidak menyebabkan SQL Error.
     */
    public function dashboard()
    {
        // 1. Statistik Dasar (Pengguna & Total Tamu)
        $totalUsers = User::count();
        $totalTamu  = Tamu::count();

        // 2. Statistik Real-time (Tamu Hari Ini)
        $tamuHariIni = Tamu::whereDate('created_at', Carbon::today())->count();

        // 3. Menghitung Rata-rata Rating dengan Proteksi Database
        // Cek apakah tabel 'tamu' punya kolom 'rating' untuk menghindari SQL Error 1054
        if (Schema::hasColumn('tamu', 'rating')) {
            $avgValue = Tamu::avg('rating') ?? 0;
            $avgRating = number_format($avgValue, 1);
        } else {
            // Jika kolom belum ada di DB, tampilkan 0.0 secara default (Safe Mode)
            $avgRating = "0.0";
        }

        // 4. Mengambil Log Aktivitas (Audit Log)
        // Menggunakan Eager Loading 'user' untuk performa maksimal
        $latestLogs = [];
        if (class_exists('App\Models\AuditLog')) {
            $latestLogs = AuditLog::with('user')
                ->latest()
                ->take(5)
                ->get();
        }

        // 5. List Tamu Terbaru (Untuk ditampilkan di tabel dashboard)
        $latestTamu = Tamu::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalTamu',
            'tamuHariIni',
            'avgRating',
            'latestLogs',
            'latestTamu'
        ));
    }

    /**
     * Master Data Index
     * Pintu utama pengelolaan database sistem SOWAN v2
     */
    public function master_index()
    {
        // Kita kirimkan statistik kecil untuk halaman master index nanti
        $stats = [
            'total_tamu' => Tamu::count(),
            'total_user' => User::count(),
        ];

        return view('admin.master.index', compact('stats'));
    }

    /**
     * Aktivitas Global
     * Menampilkan seluruh jejak audit sistem dengan pagination
     */
    public function aktivitas_global()
    {
        // Proteksi jika model AuditLog belum siap
        if (!class_exists('App\Models\AuditLog')) {
            return back()->with('error', 'Fitur Audit Log belum dikonfigurasi.');
        }

        $logs = AuditLog::with('user')->latest()->paginate(20);
        return view('admin.aktivitas.index', compact('logs'));
    }
}
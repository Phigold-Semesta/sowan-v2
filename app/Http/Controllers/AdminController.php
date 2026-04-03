<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tamu;
// use App\Models\AuditLog; // Pastikan model ini sudah ada jika ingin dipakai

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Menghitung Total Pengguna
        $totalUsers = User::count();

        // 2. Menghitung Total Tamu (Global)
        $totalTamu = Tamu::count();

        // 3. Menghitung Rata-rata Rating (Jika ada tabel rating)
        // $avgRating = Tamu::avg('rating') ?? 0;

        // 4. Mengambil Log Aktivitas Terbaru (Misal: 5 data terbaru)
        // $latestLogs = AuditLog::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalTamu'
            // 'avgRating',
            // 'latestLogs'
        ));
    }
}
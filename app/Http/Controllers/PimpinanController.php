<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\RatingLayanan;
use App\Models\Layanan;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PimpinanController extends Controller
{
    // ... (fungsi dashboard, laporanIndex, dan ratingIndex tetap sama)

    public function dashboard()
    {
        $stats = [
            'total_kunjungan'    => Kunjungan::count(),
            'kunjungan_hari_ini' => Kunjungan::whereDate('waktu_masuk', Carbon::today())->count(),
            'rata_rata_rating'   => RatingLayanan::avg('skor') ?? 0,
            'layanan_terpopuler' => Layanan::withCount('kunjungans')->orderBy('kunjungans_count', 'desc')->first(),
        ];

        return view('pimpinan.dashboard', compact('stats'));
    }

    public function laporanIndex(Request $request)
    {
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('waktu_masuk', [
                $request->tgl_awal . ' 00:00:00', 
                $request->tgl_akhir . ' 23:59:59'
            ]);
        }

        $laporan = $query->latest('waktu_masuk')->paginate(20)->withQueryString();

        return view('pimpinan.laporan.index', compact('laporan'));
    }

    public function ratingIndex()
    {
        $ratings = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan'])
                    ->latest()
                    ->paginate(15);
                    
        return view('pimpinan.rating.index', compact('ratings'));
    }

    /**
     * Log Aktivitas: Diperbaiki agar sinkron dengan View pimpinan.aktivitas.index
     */
    public function aktivitasIndex(Request $request)
    {
        // Menggunakan query builder agar bisa ditambahkan filter
        $query = AuditLog::with('user')->latest('waktu');

        // Filter Search (Aktivitas atau Nama User)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('aktivitas', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($sub) use ($searchTerm) {
                      $sub->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('waktu', $request->date);
        }

        // Pagination & Per Page
        $perPage = $request->input('per_page', 10);
        
        if ($perPage === 'all') {
            $activities = $query->get();
        } else {
            $activities = $query->paginate((int)$perPage)->withQueryString();
        }
        
        // Mengirimkan variabel $activities agar sinkron dengan View
        return view('pimpinan.aktivitas.index', compact('activities'));
    }
}
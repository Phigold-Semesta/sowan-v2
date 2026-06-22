<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\RatingLayanan;
use App\Models\Layanan;
use App\Models\AuditLog;
use App\Models\Konsultasi;
use App\Models\User;
use App\Exports\KunjunganExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class PimpinanController extends Controller
{
    // --- METHOD EXISTING (TETAP) ---
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

    // --- METHOD KONSULTASI ONLINE ---
    public function konsultasiIndex()
    {
        $konsultasi = Konsultasi::with(['user', 'layanan', 'kunjungan.tamu'])
            ->latest('waktu_konsultasi')
            ->paginate(15);
            
        return view('pimpinan.konsultasi.index', compact('konsultasi'));
    }

    public function toggleStatusLayanan(Request $request)
    {
        // Ambil ID user yang login
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Ambil data user secara fresh dari database untuk menghindari stale object
        $user = User::find($userId);
        
        // Tentukan status baru
        $statusBaru = ($user->status_konsultasi === 'online') ? 'offline' : 'online';
        
        // Gunakan update() yang lebih aman
        $user->update(['status_konsultasi' => $statusBaru]);

        return response()->json([
            'status' => $statusBaru,
            'message' => 'Status berhasil diperbarui'
        ]);
    }

    public function konfirmasiKonsultasi(Request $request, $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        $konsultasi->update([
            'status'           => 'confirmed',
            'link_google_meet' => $request->link_google_meet
        ]);

        return redirect()->back()->with('success', 'Konsultasi berhasil dikonfirmasi.');
    }

    // --- METHOD EXISTING (TETAP) ---
    public function laporanIndex(Request $request)
    {
        $listLayanan = Layanan::all();
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        if ($request->filled('range')) {
            switch ($request->range) {
                case 'hari_ini': $query->whereDate('waktu_masuk', Carbon::today()); break;
                case 'minggu_ini': $query->whereBetween('waktu_masuk', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); break;
                case 'bulan_ini': $query->whereMonth('waktu_masuk', Carbon::now()->month)->whereYear('waktu_masuk', Carbon::now()->year); break;
                case 'tahun_ini': $query->whereYear('waktu_masuk', Carbon::now()->year); break;
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('waktu_masuk', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('id_layanan')) {
            $query->where('id_layanan', $request->id_layanan);
        }

        $kunjungan = $query->latest('waktu_masuk')->paginate(20)->withQueryString();

        return view('pimpinan.laporan.index', compact('kunjungan', 'listLayanan'));
    }

    public function laporanExport(Request $request)
    {
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);
        
        if ($request->filled('range')) {
            switch ($request->range) {
                case 'hari_ini': $query->whereDate('waktu_masuk', Carbon::today()); break;
                case 'minggu_ini': $query->whereBetween('waktu_masuk', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); break;
                case 'bulan_ini': $query->whereMonth('waktu_masuk', Carbon::now()->month)->whereYear('waktu_masuk', Carbon::now()->year); break;
                case 'tahun_ini': $query->whereYear('waktu_masuk', Carbon::now()->year); break;
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('waktu_masuk', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        
        if ($request->filled('id_layanan')) { 
            $query->where('id_layanan', $request->id_layanan); 
        }

        $kunjungan = $query->latest('waktu_masuk')->get();
        $format = $request->format ?? 'csv';
        $fileName = 'Laporan_Kunjungan_' . date('YmdHis');

        if ($format === 'pdf') {
            return Excel::download(new KunjunganExport($kunjungan), $fileName . '.pdf', \Maatwebsite\Excel\Excel::MPDF);
        } elseif ($format === 'excel') {
            return Excel::download(new KunjunganExport($kunjungan), $fileName . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return Excel::download(new KunjunganExport($kunjungan), $fileName . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }

    public function ratingIndex()
    {
        $ratings = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan'])->latest()->paginate(15);
        return view('pimpinan.rating.index', compact('ratings'));
    }

    public function ratingShow($id)
    {
        $rating = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan'])->findOrFail($id);
        return view('pimpinan.rating.show', compact('rating'));
    }

    public function aktivitasIndex(Request $request)
    {
        $query = AuditLog::with('user')->latest('waktu');
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('aktivitas', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($sub) use ($searchTerm) {
                      $sub->where('nama_lengkap', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        if ($request->filled('date')) { $query->whereDate('waktu', $request->date); }

        $perPage = $request->input('per_page', 10);
        $activities = ($perPage === 'all') ? $query->get() : $query->paginate((int)$perPage)->withQueryString();
        
        return view('pimpinan.aktivitas.index', compact('activities'));
    }
}
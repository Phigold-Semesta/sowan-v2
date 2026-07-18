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

  // --- METHOD KONSULTASI ONLINE (DISESUAIKAN DENGAN AKTOR LOGIN) ---
    public function konsultasiIndex()
{
    // Pastikan relasi sudah benar sampai ke tamu
    $konsultasi = Konsultasi::with(['user', 'layanan', 'kunjungan.tamu'])
        ->where('id_user', Auth::id())
        ->latest('created_at')
        ->paginate(15);
            
    return view('pimpinan.konsultasi_online.index', compact('konsultasi'));
}

    public function toggleStatusLayanan(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $statusBaru = ($user->status_konsultasi === 'online') ? 'offline' : 'online';
        
        $user->update(['status_konsultasi' => $statusBaru]);

        return response()->json([
            'status' => $statusBaru,
            'message' => 'Status berhasil diperbarui'
        ]);
    }

    public function konfirmasiKonsultasi(Request $request, $id)
    {
        $request->validate([
            'link_google_meet' => 'required|url'
        ]);

        // Memastikan yang dikonfirmasi adalah milik pimpinan yang sedang login
        $konsultasi = Konsultasi::where('id_konsultasi', $id)
                                ->where('id_user', Auth::id())
                                ->firstOrFail();
        
        $konsultasi->update([
            'status'           => 'dikonfirmasi',
            'link_google_meet' => $request->link_google_meet
        ]);

        return redirect()->back()->with('success', 'Konsultasi berhasil dikonfirmasi.');
    }

    /**
 * Fungsi untuk menyelesaikan konsultasi bagi Pimpinan.
 * Pastikan Anda sudah menggunakan 'use App\Models\Konsultasi;' di atas Controller.
 */
public function selesaikanKonsultasi($id)
{
    $konsultasi = Konsultasi::findOrFail($id);

    /** @var \App\Models\User $user */
    $user = auth()->user();

    // Pastikan pimpinan yang menutup sesi adalah pemateri di sesi tersebut
    if (!$user || $konsultasi->id_user !== $user->id_user) {
        return back()->with('error', 'Anda tidak memiliki akses untuk menyelesaikan sesi ini.');
    }

    // Menggunakan method yang sudah kita buat di model Konsultasi
    $konsultasi->markAsFinished();

    return back()->with('success', 'Konsultasi telah selesai dan sesi ditutup.');
}

public function prosesKonsultasi(Request $request, $id)
{
    // 1. Validasi input aksi
    $request->validate([
        'aksi' => 'required|in:konfirmasi,tolak',
        'link_google_meet' => 'required_if:aksi,konfirmasi|nullable|url',
        'keterangan' => 'required_if:aksi,tolak|nullable|string',
    ]);

    // 2. Ambil data konsultasi dan pastikan milik pimpinan yang sedang login
    $konsultasi = Konsultasi::where('id_konsultasi', $id)
                            ->where('id_user', Auth::id())
                            ->firstOrFail();

    // 3. Logika pembaruan berdasarkan aksi
    if ($request->aksi === 'konfirmasi') {
        $konsultasi->update([
            'status' => Konsultasi::STATUS_DIKONFIRMASI,
            'link_google_meet' => $request->link_google_meet,
            'keterangan' => null // Reset keterangan jika konfirmasi
        ]);
        $pesan = 'Konsultasi berhasil dikonfirmasi.';
    } else {
        $konsultasi->update([
            'status' => Konsultasi::STATUS_DITOLAK,
            'keterangan' => $request->keterangan,
            'link_google_meet' => null // Kosongkan link jika ditolak
        ]);
        $pesan = 'Konsultasi berhasil ditolak.';
    }

    return back()->with('success', $pesan);
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
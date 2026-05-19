<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use App\Models\AuditLog;
use App\Models\RatingLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PetugasController extends Controller
{
    /**
     * TAMPILAN DASHBOARD (SOWAN V2)
     */
    public function dashboard()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $userId = Auth::id();

        // 1. Ambil Statistik Operasional (Sudah disinkronkan ke string database, bos!)
        $stats = [
            'total_hari_ini' => Kunjungan::whereDate('waktu_masuk', $today)->count(), 
            'belum'          => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Belum Dilayani')->count(),
            'sedang'         => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Sedang Dilayani')->count(),
            'sudah'          => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Sudah Dilayani')->count(),
            'avg_rating'     => RatingLayanan::avg('skor') ?? 0, // PERBAIKAN: Disesuaikan kembali ke nama kolom asli database 'skor', bos!
        ];

        // 2. Data Grafik Mingguan (7 Hari Terakhir)
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->toDateString();
            $chartLabels[] = $date->translatedFormat('D'); 
            $chartData[] = Kunjungan::whereDate('waktu_masuk', $formattedDate)->count();
        }

        // 3. Ambil Log Aktivitas Petugas
        $logs = AuditLog::where('id_user', $userId)
                        ->latest('waktu')
                        ->take(5)
                        ->get();

        // 🔥 PERBAIKAN SINKRONISASI DATABASE: Menggunakan 'created_at' & tetap menggunakan 'paginate(5)' agar mendukung method links() di view index, bos!
        $ratings = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan'])
                                ->latest('created_at')
                                ->paginate(5);

        // 🔥 KOREKSI UTAMA: Mengembalikan target view ke file index asli dashboard petugas agar tidak masuk ke halaman rating, bos! AMAN!
        return view('petugas.dashboard', compact('stats', 'chartLabels', 'chartData', 'logs', 'ratings'));
    }

    /**
     * Menampilkan daftar monitoring tamu.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();

        $countMenunggu = Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Belum Dilayani')->count();
        $countDiproses = Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Sedang Dilayani')->count();
        $countSelesai  = Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Sudah Dilayani')->count();

        $perPage = $request->input('per_page', 10); 
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                  ->orWhereHas('tamu', function ($queryTamu) use ($search) {
                      $queryTamu->where('nama_tamu', 'like', "%{$search}%")
                                ->orWhere('nama_instansi', 'like', "%{$search}%");
                  })
                  ->orWhereHas('layanan', function ($queryLayanan) use ($search) {
                      $queryLayanan->where('nama_layanan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('petugas', function ($queryPetugas) use ($search) {
                      $queryPetugas->where('nama_petugas', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $totalData = Kunjungan::count();
        $kunjungans = $query->orderByRaw("FIELD(status, 'Sedang Dilayani', 'Belum Dilayani', 'Sudah Dilayani')")
                            ->latest('waktu_masuk')
                            ->paginate($perPage == 'all' ? $totalData : $perPage)
                            ->withQueryString();

        // PERBAIKAN SINKRONISASI DIREKTORI: Folder disesuaikan ke 'manajemen_tamu' bukan 'data-tamu'
        return view('petugas.manajemen_tamu.index', compact(
            'kunjungans', 
            'countMenunggu', 
            'countDiproses', 
            'countSelesai'
        ));
    }

    /**
     * FITUR BARU: Menampilkan Laporan Kunjungan (SOWAN V2)
     */
    public function laporanIndex(Request $request)
    {
        $layanans = Layanan::all();
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        // Filter Rentang Tanggal
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('waktu_masuk', [$request->tgl_awal . ' 00:00:00', $request->tgl_akhir . ' 23:59:59']);
        }

        // Filter Layanan
        if ($request->filled('id_layanan')) {
            $query->where('id_layanan', $request->id_layanan);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kunjungans = $query->latest('waktu_masuk')->paginate(15)->withQueryString();

        return view('petugas.laporan.index', compact('kunjungans', 'layanans'));
    }

    /**
     * FITUR BARU: Menampilkan Menu Halaman Rating & Saran Layanan (SOWAN V2)
     */
    public function ratingIndex(Request $request)
    {
        $query = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan']);

        // Filter berdasarkan skor rating jika ada
        if ($request->filled('skor_rating')) {
            $query->where('skor', $request->skor_rating); // PERBAIKAN: Diarahkan ke filter kolom database 'skor', bos!
        }

        // Fitur Pencarian berdasarkan nama tamu atau ulasan saran
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('saran', 'like', "%{$search}%")
                  ->orWhereHas('kunjungan.tamu', function ($queryTamu) use ($search) {
                      $queryTamu->where('nama_tamu', 'like', "%{$search}%");
                  });
            });
        }

        // PERBAIKAN: Mengubah pengurutan ke kolom 'created_at' agar halaman indeks ulasan utama tidak meledak, bos!
        $ratings = $query->latest('created_at')->paginate(10)->withQueryString();

        return view('petugas.rating.index', compact('ratings'));
    }

    /**
     * Tampilan form registrasi manual.
     */
    public function create()
    {
        $layanans = Layanan::all();
        $petugasTujuan = PetugasTujuan::all(); 
        
        // PERBAIKAN SINKRONISASI DIREKTORI: Folder disesuaikan ke 'manajemen_tamu' bukan 'data-tamu'
        return view('petugas.manajemen_tamu.create', compact('layanans', 'petugasTujuan'));
    }

    /**
     * PROSES SIMPAN REGISTRASI MANUAL (SOWAN V2)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gmail'         => 'required|email|max:255',
            'nama_tamu'     => 'required|string|max:255',
            'nama_instansi' => 'required|string|max:255',
            'no_wa'         => 'required|string|max:15',
            'id_layanan'    => 'required|exists:layanan,id_layanan',
            'keperluan'     => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $tamu = Tamu::updateOrCreate(
                ['gmail' => $validated['gmail']],
                [
                    'nama_tamu'     => $validated['nama_tamu'],
                    'nama_instansi' => $validated['nama_instansi'],
                    'no_wa'         => $validated['no_wa'],
                    'jenis_tamu'    => 'Non-Penyedia',
                    'hadir_sebagai' => 'Non-Penyedia',
                    'alamat_kantor' => '-', 
                ]
            );

            $kunjungan = Kunjungan::create([
                'gmail'       => $tamu->gmail,
                'id_layanan'  => $validated['id_layanan'],
                'id_petugas'  => Auth::id(), 
                'waktu_masuk' => now(),
                'status'      => 'Belum Dilayani', // Sudah disinkronkan ke kapital
                'keperluan'   => $validated['keperluan'],
            ]);

            AuditLog::create([
                'id_user'    => Auth::id(),
                'aktivitas'  => "Mendaftarkan tamu manual: {$tamu->nama_tamu} (ID Kunjungan: {$kunjungan->id_kunjungan})",
                'waktu'      => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            // 🔥 SINKRONISASI REDIRECT: Diarahkan ke rute resource manajemen_tamu yang benar
            return redirect()->route('petugas.manajemen_tamu.index')
                ->with('success', 'Tamu berhasil didaftarkan secara manual! ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mendaftarkan tamu: ' . $e->getMessage());
        }
    }

    /**
     * Detail kunjungan tamu.
     */
    public function show(string|int $id)
    {
        $kunjungan = Kunjungan::with(['tamu', 'layanan', 'petugas'])->findOrFail($id);
        
        // PERBAIKAN SINKRONISASI DIREKTORI: Folder disesuaikan ke 'manajemen_tamu' bukan 'data-tamu'
        return view('petugas.manajemen_tamu.show', compact('kunjungan'));
    }

    /**
     * Update Status Pelayanan.
     */
    public function updateStatus(Request $request, string|int $id)
    {
        $request->validate([
            'status' => 'required|in:Belum Dilayani,Sedang Dilayani,Sudah Dilayani',
        ]);

        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $statusLama = $kunjungan->status;
            
            $updateData = ['status' => $request->status];
            
            if ($request->status == 'Sudah Dilayani') {
                $updateData['waktu_keluar'] = now();
            }

            $kunjungan->update($updateData);

            AuditLog::create([
                'id_user'    => Auth::id(),
                'aktivitas'  => "Update status kunjungan ID #{$id}: [{$statusLama}] -> [{$request->status}]",
                'waktu'      => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // 🔥 SINKRONISASI REDIRECT: Diarahkan ke rute resource manajemen_tamu yang benar
            return redirect()->route('petugas.manajemen_tamu.index')
                ->with('success', "Status pelayanan {$kunjungan->tamu->nama_tamu} berhasil diperbarui! ✨");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus record kunjungan.
     */
    public function destroy(string|int $id)
    {
        $kunjungan = Kunjungan::with('tamu')->findOrFail($id);
        $namaTamu = $kunjungan->tamu->nama_tamu ?? 'Tamu';

        $kunjungan->delete();

        AuditLog::create([
            'id_user'    => Auth::id(),
            'aktivitas'  => "Menghapus record kunjungan tamu: {$namaTamu}",
            'waktu'      => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // 🔥 SINKRONISASI REDIRECT: Diarahkan ke rute resource manajemen_tamu yang benar
        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Data kunjungan telah berhasil dihapus.');
    }
}
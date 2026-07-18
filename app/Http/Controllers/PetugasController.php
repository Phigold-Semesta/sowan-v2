<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use App\Models\AuditLog;
use App\Models\RatingLayanan;
use App\Exports\KunjunganExport; // Import class export
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel; // Import facade Excel
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

        $stats = [
            'total_hari_ini' => Kunjungan::whereDate('waktu_masuk', $today)->count(), 
            'belum'          => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Belum Dilayani')->count(),
            'sedang'         => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Sedang Dilayani')->count(),
            'sudah'          => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'Sudah Dilayani')->count(),
            'avg_rating'     => RatingLayanan::avg('skor') ?? 0,
        ];

        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $formattedDate = $date->toDateString();
            $chartLabels[] = $date->translatedFormat('D'); 
            $chartData[] = Kunjungan::whereDate('waktu_masuk', $formattedDate)->count();
        }

        $logs = AuditLog::where('id_user', $userId)
                        ->latest('waktu')
                        ->take(5)
                        ->get();

        $ratings = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan'])
                                ->latest('created_at')
                                ->paginate(5);

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

        return view('petugas.manajemen_tamu.index', compact(
            'kunjungans', 
            'countMenunggu', 
            'countDiproses', 
            'countSelesai'
        ));
    }

    /**
     * FITUR LAPORAN KUNJUNGAN
     */
    public function laporanIndex(Request $request)
    {
        $layanans = Layanan::all();
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('waktu_masuk', [$request->tgl_awal . ' 00:00:00', $request->tgl_akhir . ' 23:59:59']);
        }

        if ($request->filled('id_layanan')) {
            $query->where('id_layanan', $request->id_layanan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kunjungans = $query->latest('waktu_masuk')->paginate(15)->withQueryString();

        return view('petugas.laporan.index', compact('kunjungans', 'layanans'));
    }

   /**
     * FITUR EXPORT LAPORAN (DIPERBAIKI)
     * Sekarang melakukan query data berdasarkan filter sebelum dikirim ke Export class
     */
    public function laporan_export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        // 1. Siapkan Query
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        // 2. Terapkan Filter yang sama dengan halaman index
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('waktu_masuk', [$request->tgl_awal . ' 00:00:00', $request->tgl_akhir . ' 23:59:59']);
        }

        if ($request->filled('id_layanan')) {
            $query->where('id_layanan', $request->id_layanan);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Ambil data (Gunakan get() agar menjadi Collection, bukan paginate!)
        $data = $query->latest('waktu_masuk')->get();
        
        // 4. Tentukan nama file
        $fileName = 'Laporan_SOWAN_LPSE_' . date('Ymd_His') . ($format === 'pdf' ? '.pdf' : '.xlsx');
        
        // 5. Kirim data yang sudah di-get ke KunjunganExport
        return Excel::download(new KunjunganExport($data), $fileName);
    }

    /**
     * FITUR RATING & SARAN
     */
    public function ratingIndex(Request $request)
    {
        $query = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan']);

        if ($request->filled('skor_rating')) {
            $query->where('skor', $request->skor_rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('saran', 'like', "%{$search}%")
                  ->orWhereHas('kunjungan.tamu', function ($queryTamu) use ($search) {
                      $queryTamu->where('nama_tamu', 'like', "%{$search}%");
                  });
            });
        }

        $ratings = $query->latest('created_at')->paginate(10)->withQueryString();

        return view('petugas.rating.index', compact('ratings'));
    }

    /**
     * FORM REGISTRASI MANUAL
     */
    public function create()
    {
        $layanans = Layanan::all();
        $petugasTujuan = PetugasTujuan::all(); 
        
        return view('petugas.manajemen_tamu.create', compact('layanans', 'petugasTujuan'));
    }

    /**
     * SIMPAN REGISTRASI MANUAL
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
                'status'      => 'Belum Dilayani',
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
            return redirect()->route('petugas.manajemen_tamu.index')
                ->with('success', 'Tamu berhasil didaftarkan secara manual! ✅');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mendaftarkan tamu: ' . $e->getMessage());
        }
    }

    public function show(string|int $id)
    {
        $kunjungan = Kunjungan::with(['tamu', 'layanan', 'petugas'])->findOrFail($id);
        return view('petugas.manajemen_tamu.show', compact('kunjungan'));
    }

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

            return redirect()->route('petugas.manajemen_tamu.index')
                ->with('success', "Status pelayanan {$kunjungan->tamu->nama_tamu} berhasil diperbarui! ✨");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

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

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Data kunjungan telah berhasil dihapus.');
    }

   // --- FITUR KONSULTASI ONLINE (DISESUAIKAN & DISEMPURNAKAN) ---

    public function konsultasiIndex()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $user = \App\Models\User::find($userId);

        // Logika: Admin melihat semua, Petugas/Pimpinan hanya melihat jadwal miliknya
        $query = \App\Models\Konsultasi::with(['user', 'layanan', 'kunjungan.tamu']);
            
        if ($user->role !== 'administrator') {
            $query->where('id_user', $userId);
        }

        $konsultasi = $query->latest('waktu_mulai')->paginate(15);
            
        return view('petugas.konsultasi_online.index', compact('konsultasi'));
    }

    public function toggleStatusLayanan(Request $request)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $statusBaru = ($user->status_konsultasi === 'online') ? 'offline' : 'online';
        
        $user->update(['status_konsultasi' => $statusBaru]);

        return response()->json([
            'status'  => $statusBaru,
            'message' => 'Status berhasil diperbarui'
        ]);
    }

    public function konfirmasiKonsultasi(Request $request, $id)
    {
        // Validasi link harus URL yang valid
        $request->validate(['link_google_meet' => 'required|url']);

        $konsultasi = \App\Models\Konsultasi::findOrFail($id);
        
        // Perbaikan: Status disesuaikan dengan Enum database ('dikonfirmasi')
        $konsultasi->update([
            'status'           => 'dikonfirmasi',
            'link_google_meet' => $request->link_google_meet
        ]);

        // Catat ke AuditLog
        \App\Models\AuditLog::create([
            'id_user'    => \Illuminate\Support\Facades\Auth::id(),
            'aktivitas'  => "Mengonfirmasi konsultasi online ID #{$id}",
            'waktu'      => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Konsultasi berhasil dikonfirmasi dan link dikirim ke tamu.');
    }

    public function prosesKeputusan(Request $request, $id)
{
    $konsultasi = \App\Models\Konsultasi::findOrFail($id);
    $request->validate([
        'aksi' => 'required|in:konfirmasi,tolak',
        'link_google_meet' => 'required_if:aksi,konfirmasi|url|nullable',
        'alasan_penolakan' => 'required_if:aksi,tolak|string|nullable',
    ]);

    if ($request->aksi === 'konfirmasi') {
        $konsultasi->update([
            'status' => 'dikonfirmasi',
            'link_google_meet' => $request->link_google_meet,
            'alasan_penolakan' => null
        ]);
    } else {
        $konsultasi->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'link_google_meet' => null
        ]);
    }

    return redirect()->back()->with('success', 'Keputusan berhasil dikirim ke tamu.');
}
}
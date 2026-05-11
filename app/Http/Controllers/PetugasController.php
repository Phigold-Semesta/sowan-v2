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

        // 1. Ambil Statistik Operasional
        $stats = [
            'total_hari_ini' => Kunjungan::whereDate('waktu_masuk', $today)->count(), 
            'belum'          => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'belum dilayani')->count(),
            'sedang'         => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'sedang dilayani')->count(),
            'sudah'          => Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'sudah dilayani')->count(),
            'avg_rating'     => RatingLayanan::avg('skor') ?? 0,
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
                        ->latest()
                        ->take(5)
                        ->get();

        return view('petugas.dashboard', compact('stats', 'chartLabels', 'chartData', 'logs'));
    }

    /**
     * Menampilkan daftar monitoring tamu.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();

        $countMenunggu = Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'belum dilayani')->count();
        $countDiproses = Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'sedang dilayani')->count();
        $countSelesai  = Kunjungan::whereDate('waktu_masuk', $today)->where('status', 'sudah dilayani')->count();

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
        $kunjungans = $query->latest()
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
     * Tampilan form registrasi manual.
     */
    public function create()
    {
        $layanans = Layanan::all();
        $petugasTujuan = PetugasTujuan::all(); 
        return view('petugas.manajemen_tamu.create', compact('layanans', 'petugasTujuan'));
    }

    /**
     * PROSES SIMPAN REGISTRASI MANUAL (SOWAN V2)
     * Disesuaikan untuk menangani kolom NOT NULL tanpa default value di database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
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

            // 2. Update atau Create data Tamu (Primary Key: gmail)
            // Menambahkan default value untuk kolom yang tidak boleh NULL di DB
            $tamu = Tamu::updateOrCreate(
                ['gmail' => $validated['gmail']],
                [
                    'nama_tamu'     => $validated['nama_tamu'],
                    'nama_instansi' => $validated['nama_instansi'],
                    'no_wa'         => $validated['no_wa'],
                    'jenis_tamu'    => 'Non-Penyedia', // Menghindari error default value
                    'hadir_sebagai' => 'Non-Penyedia', // Sesuai skema SOWAN V2
                    'alamat_kantor' => '-',            // Solusi error SQL 1364
                ]
            );

            // 3. Simpan data Kunjungan
            $kunjungan = Kunjungan::create([
                'gmail'       => $tamu->gmail,
                'id_layanan'  => $validated['id_layanan'],
                'id_petugas'  => Auth::id(), 
                'waktu_masuk' => now(),
                'status'      => 'belum dilayani',
                'keperluan'   => $validated['keperluan'],
            ]);

            // 4. Audit Log
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

    /**
     * Detail kunjungan tamu.
     */
    public function show(string|int $id)
    {
        $kunjungan = Kunjungan::with(['tamu', 'layanan', 'petugas'])->findOrFail($id);
        return view('petugas.manajemen_tamu.show', compact('kunjungan'));
    }

    /**
     * Update Status Pelayanan.
     */
    public function updateStatus(Request $request, string|int $id)
    {
        $request->validate([
            'status' => 'required|in:belum dilayani,sedang dilayani,sudah dilayani',
        ]);

        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $statusLama = $kunjungan->status;
            
            $updateData = ['status' => $request->status];
            
            if ($request->status == 'sudah dilayani') {
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

    /**
     * Menghapus record kunjungan.
     */
    public function destroy(Request $request, string|int $id)
    {
        $kunjungan = Kunjungan::with('tamu')->findOrFail($id);
        $namaTamu = $kunjungan->tamu->nama_tamu ?? 'Tamu';

        $kunjungan->delete();

        AuditLog::create([
            'id_user'    => Auth::id(),
            'aktivitas'  => "Menghapus record kunjungan tamu: {$namaTamu}",
            'waktu'      => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Data kunjungan telah berhasil dihapus.');
    }
}
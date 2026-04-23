<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan; // PERBAIKAN: Menggunakan model PetugasTujuan, bukan User
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    /**
     * Menampilkan daftar monitoring tamu dengan fitur Global Search & Filter Status.
     */
    public function index(Request $request)
    {
        // Hitung data untuk Card Monitoring agar berfungsi sebagaimana mestinya
        $countMenunggu = Kunjungan::where('status', 'belum dilayani')->count();
        $countDiproses = Kunjungan::where('status', 'sedang dilayani')->count();
        $countSelesai  = Kunjungan::where('status', 'sudah dilayani')->count();

        $perPage = $request->input('per_page', 10); 
        
        // PERBAIKAN: Menggunakan relasi 'petugasTujuan' agar sinkron dengan Model Kunjungan yang baru diperbaiki
        $query = Kunjungan::with(['tamu', 'layanan', 'petugasTujuan']);

        // --- GLOBAL SEARCH (Disesuaikan dengan kolom tabel SOWAN V2) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                  // Cari berdasarkan Nama Tamu atau Instansi
                  ->orWhereHas('tamu', function ($queryTamu) use ($search) {
                      $queryTamu->where('nama_tamu', 'like', "%{$search}%")
                                ->orWhere('nama_instansi', 'like', "%{$search}%");
                  })
                  // Cari berdasarkan Nama Layanan
                  ->orWhereHas('layanan', function ($queryLayanan) use ($search) {
                      $queryLayanan->where('nama_layanan', 'like', "%{$search}%");
                  })
                  // PERBAIKAN: Cari berdasarkan Nama Asli Petugas di tabel petugas_tujuan
                  ->orWhereHas('petugasTujuan', function ($queryPetugas) use ($search) {
                      $queryPetugas->where('nama_petugas', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Status (Konsisten menggunakan lowercase) [cite: 2026-02-01]
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $totalData = Kunjungan::count();
        $kunjungans = $query->latest()
                            ->paginate($perPage == 'all' ? $totalData : $perPage)
                            ->withQueryString();

        // Mengirimkan data ke view (Pastikan folder resources/views/petugas/manajemen_tamu/index.blade.php ada)
        return view('petugas.manajemen_tamu.index', compact(
            'kunjungans', 
            'countMenunggu', 
            'countDiproses', 
            'countSelesai'
        ));
    }

    /**
     * Form pendaftaran tamu manual oleh petugas.
     */
    public function create()
    {
        $layanan = Layanan::all();
        // PERBAIKAN: Mengambil data dari tabel petugas_tujuan untuk dropdown pilihan petugas
        $petugasTujuan = PetugasTujuan::all(); 
        return view('petugas.manajemen_tamu.create', compact('layanan', 'petugasTujuan'));
    }

    /**
     * Menyimpan data tamu manual ke database (Tamu & Kunjungan).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gmail'         => 'required|email',
            'nama_tamu'     => 'required|string|max:255',
            'nama_instansi' => 'required|string|max:255',
            'no_wa'         => 'required|string|max:15',
            'id_layanan'    => 'required|exists:layanan,id_layanan',
            'id_petugas'    => 'required|exists:petugas_tujuan,id_petugas', // PERBAIKAN: Validasi ke tabel petugas_tujuan
        ]);

        DB::transaction(function () use ($validated) {
            $tamu = Tamu::updateOrCreate(
                ['gmail' => $validated['gmail']],
                [
                    'nama_tamu'     => $validated['nama_tamu'],
                    'nama_instansi' => $validated['nama_instansi'],
                    'no_wa'         => $validated['no_wa'],
                ]
            );

            Kunjungan::create([
                'gmail'         => $tamu->gmail,
                'id_layanan'    => $validated['id_layanan'],
                'id_petugas'    => $validated['id_petugas'], 
                'waktu_masuk'   => now(),
                'status'        => 'belum dilayani', // Konsistensi string [cite: 2026-02-01]
            ]);

            AuditLog::create([
                'id_user'   => Auth::id(),
                'aktivitas' => "Mendaftarkan tamu manual: {$tamu->nama_tamu}",
                'waktu'     => now(),
            ]);
        });

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Tamu berhasil didaftarkan secara manual! ✅');
    }

    /**
     * Detail kunjungan tamu.
     */
    public function show($id)
    {
        // PERBAIKAN: Menggunakan relasi 'petugasTujuan' agar detail menampilkan nama asli petugas
        $kunjungan = Kunjungan::with(['tamu', 'layanan', 'petugasTujuan'])->findOrFail($id);
        return view('petugas.manajemen_tamu.show', compact('kunjungan'));
    }

    /**
     * Update Status Cepat (Sinkron dengan halaman data tamu) [cite: 2026-03-16].
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum dilayani,sedang dilayani,sudah dilayani',
        ]);

        try {
            $kunjungan = Kunjungan::findOrFail($id);
            $statusLama = $kunjungan->status;
            
            $updateData = ['status' => $request->status];
            
            // Catat waktu keluar jika layanan selesai
            if ($request->status == 'sudah dilayani') {
                $updateData['waktu_keluar'] = now();
            }

            $kunjungan->update($updateData);

            AuditLog::create([
                'id_user'   => Auth::id(),
                'aktivitas' => "Update status kunjungan ID #{$id}: [{$statusLama}] -> [{$request->status}]",
                'waktu'     => now(),
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
    public function destroy($id)
    {
        $kunjungan = Kunjungan::with('tamu')->findOrFail($id);
        $namaTamu = $kunjungan->tamu->nama_tamu ?? 'Tamu';

        $kunjungan->delete();

        AuditLog::create([
            'id_user'   => Auth::id(),
            'aktivitas' => "Menghapus record kunjungan tamu: {$namaTamu}",
            'waktu'     => now(),
        ]);

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Data kunjungan telah berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use App\Models\RatingLayanan;
use App\Models\AuditLog;
use App\Models\Dokumen; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Import Facade untuk Export
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KunjunganExport;

class AdminController extends Controller
{
    /**
     * Dashboard Utama Admin
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalTamu  = Tamu::count();
        $tamuHariIni = Kunjungan::whereDate('waktu_masuk', Carbon::today())->count();
        
       $avgValue = RatingLayanan::avg('skor') ?? 0;
        $avgRating = number_format($avgValue, 1);

        $latestLogs = AuditLog::with('user')->latest('waktu')->take(5)->get();
        
        $latestKunjungan = Kunjungan::with(['tamu', 'layanan', 'petugas'])
                            ->latest('waktu_masuk')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalTamu', 'tamuHariIni', 'avgRating', 'latestLogs', 'latestKunjungan'
        ));
    }

    public function master_index()
    {
        return view('admin.master.index'); 
    }

    // =========================================================================
    // --- MASTER DATA: LAYANAN & DOKUMEN PANDUAN ---
    // =========================================================================

    public function layanan_index(Request $request)
    {
        $query = Layanan::with('dokumen');
        
        if ($request->filled('search')) {
            $query->where('nama_layanan', 'like', '%' . $request->input('search') . '%');
        }

        $perPage = $request->input('per_page', 10);
        $layanan = ($perPage === 'all') 
            ? $query->latest('id_layanan')->get() 
            : $query->latest('id_layanan')->paginate((int)$perPage)->withQueryString();

        return view('admin.master.layanan.index', compact('layanan'));
    }

    public function layanan_create() 
    { 
        return view('admin.master.layanan.create'); 
    }

    public function layanan_store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255|unique:layanan,nama_layanan'
        ]);

        try {
            Layanan::create(['nama_layanan' => $request->nama_layanan]);
            $this->logActivity("Menambahkan layanan baru: " . $request->nama_layanan);

            return redirect()->route('admin.master.layanan.index')->with('success', 'Layanan berhasil ditambah!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan data layanan.');
        }
    }

    public function layanan_panduan($id)
    {
        $layanan = Layanan::where('id_layanan', $id)->firstOrFail();
        
        $dokumen_list = Dokumen::where('id_layanan', $id)
                               ->where('kategori', 'panduan')
                               ->with('user')
                               ->latest()
                               ->get();

        return view('admin.master.layanan.layanan_panduan', compact('layanan', 'dokumen_list'));
    }

    public function layanan_panduan_store(Request $request, $id)
    {
        $request->validate([
            'file_panduan.*' => 'required|mimes:pdf|max:5120', 
        ]);

        DB::beginTransaction();
        try {
            $layanan = Layanan::where('id_layanan', $id)->firstOrFail();

            if ($request->hasFile('file_panduan')) {
                foreach ($request->file('file_panduan') as $file) {
                    $fileName = 'panduan_' . $id . '_' . uniqid() . '.pdf';
                    $path = $file->storeAs('panduan', $fileName, 'public');

                    Dokumen::create([
                        'nama_dokumen' => $file->getClientOriginalName(),
                        'file_path'    => $path,
                        'kategori'     => 'panduan',
                        'id_layanan'   => $id,
                        'id_user'      => Auth::id()
                    ]);
                }

                $this->logActivity("Mengunggah panduan baru untuk layanan: " . $layanan->nama_layanan);
                DB::commit();
                return redirect()->back()->with('success', 'File panduan berhasil ditambahkan!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    public function layanan_panduan_destroy($id_dokumen)
    {
        try {
            $dokumen = Dokumen::where('id_dokumen', $id_dokumen)->firstOrFail();
            $nama_file = $dokumen->nama_dokumen;
            
            if (Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }

            $dokumen->delete();
            $this->logActivity("Menghapus file panduan: $nama_file");

            return back()->with('success', 'Dokumen "' . $nama_file . '" telah berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function layanan_show($id)
    {
        $layanan = Layanan::with('dokumen')->where('id_layanan', $id)->firstOrFail();
        return view('admin.master.layanan.show', compact('layanan'));
    }

    public function layanan_edit($id)
    {
        $layanan = Layanan::where('id_layanan', $id)->firstOrFail();
        return view('admin.master.layanan.edit', compact('layanan'));
    }

    public function layanan_update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255|unique:layanan,nama_layanan,'.$id.',id_layanan'
        ]);

        Layanan::where('id_layanan', $id)->update(['nama_layanan' => $request->nama_layanan]);
        $this->logActivity("Memperbarui data layanan ID: $id");

        return redirect()->route('admin.master.layanan.index')->with('success', 'Layanan diperbarui!');
    }

    public function layanan_destroy($id)
    {
        try {
            $layanan = Layanan::with('dokumen')->where('id_layanan', $id)->firstOrFail();
            $nama = $layanan->nama_layanan;

            foreach ($layanan->dokumen as $doc) {
                if (Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }
                $doc->delete();
            }

            $layanan->delete();

            $this->logActivity("Menghapus layanan: $nama dan dokumen terkait.");
            return redirect()->route('admin.master.layanan.index')->with('success', 'Layanan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal! Data layanan ini masih digunakan oleh data kunjungan.');
        }
    }

    // =========================================================================
    // --- MASTER DATA: TUJUAN KUNJUNGAN ---
    // =========================================================================

    public function tujuan_index(Request $request)
    {
        $query = PetugasTujuan::query();
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_petugas', 'like', "%$search%")
                  ->orWhere('jabatan', 'like', "%$search%");
        }

        $perPage = $request->input('per_page', 10);
        $tujuan = ($perPage === 'all') 
            ? $query->latest('id_petugas')->get() 
            : $query->latest('id_petugas')->paginate((int)$perPage)->withQueryString();

        return view('admin.master.tujuan.index', compact('tujuan'));
    }

    public function tujuan_create() 
    { 
        return view('admin.master.tujuan.create'); 
    }

    public function tujuan_store(Request $request)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'jabatan'      => 'required|string|max:255'
        ]);

        try {
            PetugasTujuan::create([
                'nama_petugas' => $request->nama_petugas,
                'jabatan'      => $request->jabatan
            ]);
            
            $this->logActivity("Menambahkan petugas tujuan: " . $request->nama_petugas);
            return redirect()->route('admin.master.tujuan.index')->with('success', 'Tujuan kunjungan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }
    }

    public function tujuan_show($id)
    {
        $tujuan = PetugasTujuan::where('id_petugas', $id)->firstOrFail();
        return view('admin.master.tujuan.show', compact('tujuan'));
    }

    public function tujuan_edit($id)
    {
        $tujuan = PetugasTujuan::where('id_petugas', $id)->firstOrFail();
        return view('admin.master.tujuan.edit', compact('tujuan'));
    }

    public function tujuan_update(Request $request, $id)
    {
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'jabatan'      => 'required|string|max:255'
        ]);

        try {
            PetugasTujuan::where('id_petugas', $id)->update([
                'nama_petugas' => $request->nama_petugas,
                'jabatan'      => $request->jabatan
            ]);

            $this->logActivity("Memperbarui data petugas ID: $id");
            return redirect()->route('admin.master.tujuan.index')->with('success', 'Data tujuan kunjungan diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data.');
        }
    }

    public function tujuan_destroy($id)
    {
        DB::beginTransaction();
        try {
            Kunjungan::where('id_petugas', $id)->delete();
            PetugasTujuan::where('id_petugas', $id)->delete();

            DB::commit();
            $this->logActivity("Menghapus petugas tujuan ID: $id dan riwayat terkait.");
            return redirect()->route('admin.master.tujuan.index')->with('success', 'Data petugas dan riwayat kunjungan berhasil dibersihkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    // =========================================================================
    // --- PERBAIKAN: LOG AKTIVITAS (AKTIVITAS GLOBAL) ---
    // =========================================================================

    public function aktivitas_global(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('aktivitas', 'like', "%$search%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('nama_lengkap', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('waktu', $request->input('date'));
        }

        $perPage = $request->input('per_page', 10);
        $activities = ($perPage === 'all') 
            ? $query->latest('waktu')->get() 
            : $query->latest('waktu')->paginate((int)$perPage)->withQueryString();

        return view('admin.aktivitas.index', compact('activities'));
    }

    // =========================================================================
    // --- LAPORAN KUNJUNGAN (DENGAN FILTER HARIAN/MINGGUAN/BULANAN/TAHUNAN) ---
    // =========================================================================

    public function laporan_index(Request $request)
    {
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        // LOGIKA FILTER SPESIFIK (Revisi Dosen)
        $this->applyFilters($query, $request);

        if ($request->filled('id_layanan')) {
            $query->where('id_layanan', $request->id_layanan);
        }

        $perPage = $request->input('per_page', 10);
        $kunjungan = ($perPage === 'all') 
            ? $query->latest('waktu_masuk')->get() 
            : $query->latest('waktu_masuk')->paginate((int)$perPage)->withQueryString();

        $layanan = Layanan::all();
        $listLayanan = $layanan;

        return view('admin.laporan.index', compact('kunjungan', 'layanan', 'listLayanan'));
    }

    /**
     * Helper Function untuk menerapkan filter waktu secara dinamis
     */
    private function applyFilters($query, $request)
    {
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('waktu_masuk', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('waktu_masuk', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('waktu_masuk', Carbon::now()->month)
                          ->whereYear('waktu_masuk', Carbon::now()->year);
                    break;
                case 'this_year':
                    $query->whereYear('waktu_masuk', Carbon::now()->year);
                    break;
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            // Jika user memilih rentang manual
            $query->whereBetween('waktu_masuk', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }
    }

    public function laporan_show($id)
    {
        $kunjungan = Kunjungan::with(['tamu', 'layanan', 'petugas', 'rating'])->where('id_kunjungan', $id)->firstOrFail();
        return view('admin.laporan.show', compact('kunjungan'));
    }

    public function laporan_edit($id)
    {
        $kunjungan = Kunjungan::with(['tamu', 'petugas'])->where('id_kunjungan', $id)->firstOrFail();
        
        $layanan = Layanan::all();
        $listLayanan = $layanan;
        $petugas = PetugasTujuan::all();
        $listPetugas = $petugas;
        
        return view('admin.laporan.edit', compact('kunjungan', 'layanan', 'listLayanan', 'petugas', 'listPetugas'));
    }

    public function laporan_update(Request $request, $id)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanan,id_layanan',
            'status'     => 'required|in:Belum Dilayani,Sedang Dilayani,Sudah Dilayani',
            'perihal'    => 'nullable|string'
        ]);

        try {
            $kunjungan = Kunjungan::where('id_kunjungan', $id)->firstOrFail();
            
            $kunjungan->update([
                'id_layanan' => $request->id_layanan,
                'status'     => $request->status,
                'perihal'    => $request->perihal, 
            ]);

            $this->logActivity("Memperbarui status/layanan kunjungan ID: #$id menjadi $request->status");

            return redirect()->route('admin.laporan.index')->with('success', 'Data kunjungan berhasil diperbarui, bos!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function laporan_export(Request $request)
    {
        $format = $request->input('format', 'csv'); 
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        // Terapkan filter yang sama dengan index
        $this->applyFilters($query, $request);
        
        if ($request->filled('id_layanan')) {
            $query->where('id_layanan', $request->id_layanan);
        }

        $data = $query->latest('waktu_masuk')->get();

        if ($format == 'pdf') {
            return $this->exportToPDF($data);
        } elseif ($format == 'excel') {
            return $this->exportToExcel($data);
        } else {
            return $this->exportToCSV($data);
        }
    }

    private function exportToCSV($data)
    {
        $fileName = 'Laporan_SOWAN_LPSE_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'Waktu Masuk', 'Nama Tamu', 'Instansi', 'Layanan', 'Tujuan Petugas', 'Status'];

        $callback = function() use($data, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($data as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->waktu_masuk,
                    $row->tamu->nama_tamu ?? '-', 
                    $row->tamu->instansi ?? ($row->tamu->nama_instansi ?? '-'),
                    $row->layanan->nama_layanan ?? '-',
                    $row->petugas->nama_petugas ?? '-', 
                    $row->status 
                ]);
            }
            fclose($file);
        };

        $this->logActivity("Mengekspor laporan kunjungan ke CSV.");
        return response()->stream($callback, 200, $headers);
    }

    private function exportToPDF($data)
    {
        $this->logActivity("Mengekspor laporan kunjungan ke PDF.");
        
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('data'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_SOWAN_LPSE_'.now()->format('Ymd_His').'.pdf');
    }

    private function exportToExcel($data)
    {
        $this->logActivity("Mengekspor laporan kunjungan ke Excel.");
        
        $fileName = 'Laporan_SOWAN_LPSE_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new KunjunganExport($data), $fileName);
    }

    // =========================================================================
    // --- MANAJEMEN RATING LAYANAN ---
    // =========================================================================

    public function rating_index(Request $request)
    {
        $query = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan', 'user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('komentar', 'like', "%$search%")
                  ->orWhereHas('kunjungan.tamu', function($qt) use ($search) {
                      $qt->where('nama_tamu', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('skor')) {
            $query->where('skor_rating', $request->skor);
        }

        $perPage = $request->input('per_page', 10);
        $ratings = ($perPage === 'all') 
            ? $query->orderBy('id_kunjungan', 'desc')->get() 
            : $query->orderBy('id_kunjungan', 'desc')->paginate((int)$perPage)->withQueryString();

        return view('admin.rating.index', compact('ratings'));
    }

    public function rating_show($id)
    {
        $rating = RatingLayanan::with(['kunjungan.tamu', 'kunjungan.layanan', 'user'])
                               ->where('id_kunjungan', $id)
                               ->firstOrFail();

        return view('admin.rating.show', compact('rating'));
    }

    public function rating_tanggapan(Request $request, $id)
    {
        $request->validate([
            'tanggapan' => 'required|string|min:5',
        ]);

        try {
            $rating = RatingLayanan::where('id_kunjungan', $id)->firstOrFail();
            
            $rating->update([
                'tanggapan' => $request->tanggapan,
                'id_user'   => Auth::id(), 
            ]);

            $this->logActivity("Memberikan tanggapan pada rating Kunjungan ID: #$id");

            return redirect()->route('admin.rating.index')->with('success', 'Tanggapan berhasil dikirim, bos!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim tanggapan: ' . $e->getMessage());
        }
    }

    public function rating_destroy($id)
    {
        try {
            $rating = RatingLayanan::where('id_kunjungan', $id)->firstOrFail();
            
            $namaTamu = $rating->kunjungan->tamu->nama_tamu ?? 'Unknown';
            $this->logActivity("Menghapus data rating Kunjungan ID: #$id dari tamu " . $namaTamu);

            $rating->delete();

            return redirect()->route('admin.rating.index')->with('success', 'Data rating berhasil dihapus secara permanen, bos!');
        } catch (\Exception $e) {
            return redirect()->route('admin.rating.index')->with('error', 'Gagal menghapus rating: ' . $e->getMessage());
        }
    }
    
// =========================================================================
    // --- MANAJEMEN KONSULTASI ONLINE ---
    // =========================================================================

   /**
     * Manajemen Konsultasi (Admin)
     * Menggunakan filter yang lebih aman terhadap relasi null.
     */
    public function konsultasi_index(Request $request)
    {
        // Gunakan query builder yang bersih
        $query = \App\Models\Konsultasi::with(['user', 'layanan', 'kunjungan.tamu']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter pencarian nama tamu dengan pengecekan relasi yang aman
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kunjungan.tamu', function($q) use ($search) {
                $q->where('nama_tamu', 'like', "%{$search}%");
            });
        }

        // Gunakan 'created_at' sebagai fallback jika 'waktu_konsultasi' tidak ada di model
        $konsultasi = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.konsultasi_online.index', compact('konsultasi'));
    }

    public function konsultasi_destroy($id)
    {
        try {
            $konsultasi = \App\Models\Konsultasi::findOrFail($id);
            
            // Catat log sebelum dihapus
            $this->logActivity("Admin menghapus sesi konsultasi ID: #{$id} - Tamu: " . ($konsultasi->gmail ?? 'N/A'));
            
            $konsultasi->delete();

            return redirect()->route('admin.konsultasi.index')
                             ->with('success', 'Sesi konsultasi berhasil dihapus.');
                             
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus konsultasi: ' . $e->getMessage());
        }
    }

    private function logActivity($aktivitas)
    {
        AuditLog::create([
            'id_user'   => Auth::id(),
            'aktivitas' => $aktivitas, 
            'waktu'     => now(), 
            'ip_address'=> request()->ip(),
            'user_agent'=> request()->userAgent(),
        ]);
    }
}
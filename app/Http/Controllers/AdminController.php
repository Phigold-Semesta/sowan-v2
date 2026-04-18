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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Import Facade untuk Export
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KunjunganExport; // Taruh di paling atas


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

        $latestLogs = AuditLog::with('user')->latest()->take(5)->get();
        
        $latestKunjungan = Kunjungan::with(['tamu', 'layanan', 'petugas'])
                            ->latest('waktu_masuk')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalTamu', 'tamuHariIni', 'avgRating', 'latestLogs', 'latestKunjungan'
        ));
    }

    /**
     * Halaman Master Index
     */
    public function master_index()
    {
        return view('admin.master.index'); 
    }

    // =========================================================================
    // --- MASTER DATA: LAYANAN ---
    // =========================================================================

    public function layanan_index(Request $request)
    {
        $query = Layanan::query();
        
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

    public function layanan_show($id)
    {
        $layanan = Layanan::where('id_layanan', $id)->firstOrFail();
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
            $layanan = Layanan::where('id_layanan', $id)->firstOrFail();
            $nama = $layanan->nama_layanan;
            $layanan->delete();

            $this->logActivity("Menghapus layanan: $nama");
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
    // --- LOG AKTIVITAS (AKTIVITAS GLOBAL) ---
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
            $query->whereDate('created_at', $request->input('date'));
        }

        $perPage = $request->input('per_page', 10);
        $activities = ($perPage === 'all') 
            ? $query->latest()->get() 
            : $query->latest()->paginate((int)$perPage)->withQueryString();

        return view('admin.aktivitas.index', compact('activities'));
    }

    // =========================================================================
    // --- LAPORAN KUNJUNGAN ---
    // =========================================================================

    public function laporan_index(Request $request)
    {
        $query = Kunjungan::with(['tamu', 'layanan', 'petugas']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('waktu_masuk', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

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

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('waktu_masuk', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        
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
        
        // Memuat view untuk PDF. Pastikan bos sudah buat file resources/views/admin/laporan/pdf.blade.php
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
    /**
     * Helper Log Activity (SUDAH DIPERBAIKI: Menambahkan field 'waktu')
     */
    private function logActivity($aktivitas)
    {
        AuditLog::create([
            'id_user'   => Auth::id(),
            'aktivitas' => $aktivitas, 
            'waktu'     => now(), // Tambahkan ini agar tidak error General Error 1364
            'ip_address'=> request()->ip(),
            'user_agent'=> request()->userAgent(),
        ]);
    }
}
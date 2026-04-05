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
        
        $latestKunjungan = Kunjungan::with(['tamu', 'layanan'])
                            ->latest('waktu_masuk')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalTamu', 'tamuHariIni', 'avgRating', 'latestLogs', 'latestKunjungan'
        ));
    }

    /**
     * Halaman Pintu Masuk Master Data
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
            return redirect()->route('admin.master.layanan.index')->with('success', 'Layanan berhasil ditambah!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan data layanan.');
        }
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
        return redirect()->route('admin.master.layanan.index')->with('success', 'Layanan diperbarui!');
    }

    public function layanan_destroy($id)
    {
        try {
            Layanan::where('id_layanan', $id)->delete();
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

        // Filter Pencarian: Nama User atau Deskripsi Aktivitas
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('deskripsi', 'like', "%$search%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('nama_lengkap', 'like', "%$search%");
                  });
            });
        }

        // Filter Berdasarkan Tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        // PENYEMPURNAAN: Logika Per Page yang fleksibel
        $perPage = $request->input('per_page', 10); // Default 10 baris
        
        if ($perPage === 'all') {
            $activities = $query->latest()->get();
        } else {
            $activities = $query->latest()
                                ->paginate((int)$perPage)
                                ->withQueryString();
        }

        return view('admin.aktivitas.index', compact('activities'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\RatingLayanan; // DISESUAIKAN: Menggunakan nama class model yang benar
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    /**
     * Menampilkan daftar monitoring tamu (Dashboard Petugas).
     */
    public function index()
    {
        $kunjungans = Kunjungan::with('tamu')
            ->latest()
            ->paginate(10); 

        return view('petugas.manajemen_tamu.index', compact('kunjungans'));
    }

    /**
     * Form registrasi tamu manual.
     */
    public function create()
    {
        return view('petugas.manajemen_tamu.create');
    }

    /**
     * Menyimpan data tamu dan kunjungan secara atomik.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gmail'         => 'required|email',
            'nama_tamu'     => 'required|string|max:255',
            'nama_instansi' => 'required|string|max:255',
            'no_wa'         => 'required|string|max:15',
            'hadir_sebagai' => 'required|string',
            'tujuan_bidang' => 'nullable|string',
            'keperluan'     => 'required|string',
        ]);

        $userId = Auth::id();

        DB::transaction(function () use ($validated, $userId) {
            $tamu = Tamu::updateOrCreate(
                ['gmail' => $validated['gmail']],
                [
                    'nama_tamu'     => $validated['nama_tamu'],
                    'nama_instansi' => $validated['nama_instansi'],
                    'no_wa'         => $validated['no_wa'],
                ]
            );

            Kunjungan::create([
                'id_tamu'       => $tamu->id_tamu,
                'hadir_sebagai' => $validated['hadir_sebagai'],
                'tujuan_bidang' => $validated['tujuan_bidang'],
                'keperluan'     => $validated['keperluan'],
                'status'        => 'belum dilayani',
            ]);

            AuditLog::create([
                'id_user'   => $userId,
                'aktivitas' => "Mendaftarkan tamu manual: {$tamu->nama_tamu}",
                'waktu'     => now(),
            ]);
        });

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Tamu berhasil didaftarkan secara manual! ✅');
    }

    /**
     * Menampilkan detail kunjungan tamu.
     */
    public function show($id)
    {
        // Menyesuaikan relasi rating ke ratingLayanan sesuai nama di model Kunjungan Anda
        $kunjungan = Kunjungan::with(['tamu', 'ratingLayanan'])->findOrFail($id);
        return view('petugas.manajemen_tamu.show', compact('kunjungan'));
    }

    /**
     * Form Edit data kunjungan.
     */
    public function edit($id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        return view('petugas.manajemen_tamu.edit', compact('kunjungan'));
    }

    /**
     * Update data kunjungan secara umum.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum dilayani,sedang dilayani,sudah dilayani',
        ]);

        $kunjungan = Kunjungan::findOrFail($id);
        $statusLama = $kunjungan->status;
        
        $kunjungan->update(['status' => $request->status]);

        AuditLog::create([
            'id_user'   => Auth::id(),
            'aktivitas' => "Update status kunjungan ID #{$id} dari [{$statusLama}] ke [{$request->status}]",
            'waktu'     => now(),
        ]);

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', "Data pelayanan berhasil diperbarui! ✨");
    }

    /**
     * Update Status Cepat (AJAX/Tombol Cepat).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum dilayani,sedang dilayani,sudah dilayani',
        ]);

        $kunjungan = Kunjungan::findOrFail($id);
        $statusLama = $kunjungan->status;
        
        $kunjungan->update(['status' => $request->status]);

        AuditLog::create([
            'id_user'   => Auth::id(),
            'aktivitas' => "Update status cepat ID #{$id}: [{$statusLama}] -> [{$request->status}]",
            'waktu'     => now(),
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'Status berhasil diperbarui!']);
        }

        return redirect()->back()->with('success', 'Status pelayanan telah diupdate!');
    }

    /**
     * Menampilkan Halaman Laporan Kunjungan.
     */
    public function laporan_index()
    {
        $kunjungans = Kunjungan::with('tamu')->latest()->get();
        return view('petugas.laporan.index', compact('kunjungans'));
    }

    /**
     * Menampilkan Halaman Rating Layanan.
     */
    public function rating_index()
    {
        // DISEMPURNAKAN: Menggunakan RatingLayanan dan memanggil relasi kunjungan.tamu
        $ratings = RatingLayanan::with(['kunjungan.tamu'])->latest()->get();
        return view('petugas.rating.index', compact('ratings'));
    }

    /**
     * Menanggapi Kritik & Saran (Rating).
     */
    public function rating_tanggapan(Request $request, $id)
    {
        $request->validate([
            'tanggapan' => 'required|string',
        ]);

        // DISEMPURNAKAN: Menggunakan primary key id_rating sesuai model Anda
        $rating = RatingLayanan::where('id_rating', $id)->firstOrFail();
        $rating->update([
            'tanggapan' => $request->tanggapan,
            'id_user'   => Auth::id(), // Mencatat petugas yang menanggapi
        ]);

        AuditLog::create([
            'id_user'   => Auth::id(),
            'aktivitas' => "Memberikan tanggapan pada rating ID #{$id}",
            'waktu'     => now(),
        ]);

        return back()->with('success', 'Tanggapan Anda berhasil disimpan! ✅');
    }

    /**
     * Menghapus data kunjungan.
     */
    public function destroy($id)
    {
        $kunjungan = Kunjungan::with('tamu')->findOrFail($id);
        $namaTamu = $kunjungan->tamu->nama_tamu;
        $userId = Auth::id();

        $kunjungan->delete();

        AuditLog::create([
            'id_user'   => $userId,
            'aktivitas' => "Menghapus record kunjungan tamu: {$namaTamu}",
            'waktu'     => now(),
        ]);

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Data kunjungan telah berhasil dihapus dari sistem.');
    }
}
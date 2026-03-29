<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Dokumen;
use App\Models\Layanan; // 🏛️ Tambahkan model Layanan
use Illuminate\Http\Request;

class TamuController extends Controller
{
    /**
     * TAMPILAN PUBLIK: Form Buku Tamu + List Panduan
     */
    public function create()
    {
        // 1. Ambil semua layanan untuk dropdown di form 🏛️
        $layanans = Layanan::orderBy('nama_layanan', 'asc')->get();

        // 2. Ambil semua dokumen panduan agar tamu bisa memilih/melihat 📄
        // (Atau bisa tetap .first() jika hanya ingin satu panduan utama)
        $panduans = Dokumen::where('kategori', 'panduan')->latest()->get();
        
        return view('tamu.form', compact('layanans', 'panduans'));
    }

    /**
     * PROSES PUBLIK: Simpan Kunjungan & Rating
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'instansi'     => 'required|string|max:100',
            'id_layanan'   => 'required|exists:layanan,id_layanan', // 🛡️ Validasi relasi
            'keperluan'    => 'required|string',
            'rating'       => 'nullable|integer|min:1|max:5',
            'saran'        => 'nullable|string|max:500',
        ]);

        Tamu::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'instansi'     => $validated['instansi'],
            'id_layanan'   => $validated['id_layanan'], // 🔗 Simpan kaitan layanan
            'keperluan'    => $validated['keperluan'],
            'rating'       => $validated['rating'], 
            'saran'        => $validated['saran'],   
            'status'       => 'belum dilayani', 
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan! Terima kasih telah berkunjung.');
    }

    /**
     * TAMPILAN INTERNAL: Manajemen Tamu (Eager Loading)
     */
    public function indexStatus()
    {
        // Gunakan with('layanan') agar tidak terjadi N+1 Query Problem 🚀
        $tamus = Tamu::with('layanan')->latest()->get();
        return view('petugas.status', compact('tamus'));
    }

    /**
     * TAMPILAN INTERNAL: Feedback (Rating & Saran)
     */
    public function feedback()
    {
        // Filter feedback yang hanya memiliki isi
        $feedbacks = Tamu::with('layanan')
                         ->where(function($query) {
                             $query->whereNotNull('rating')
                                   ->orWhereNotNull('saran');
                         })
                         ->latest()
                         ->paginate(10);

        return view('petugas.feedback', compact('feedbacks'));
    }

    /**
     * PROSES INTERNAL: Update Status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum dilayani,sedang dilayani,sudah dilayani'
        ]);

        $tamu = Tamu::findOrFail($id);
        $tamu->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status tamu berhasil diperbarui!');
    }
}
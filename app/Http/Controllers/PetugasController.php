<?php

namespace App\Http\Controllers;

use App\Models\Tamu; 
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    /**
     * Menampilkan daftar tamu.
     */
    public function index()
    {
        // Mengambil data tamu terbaru dengan pagination
        $tamus = Tamu::latest()->paginate(10); 

        return view('petugas.manajemen_tamu.index', compact('tamus'));
    }

    /**
     * Menampilkan form registrasi tamu manual.
     */
    public function create()
    {
        return view('petugas.manajemen_tamu.create');
    }

    /**
     * Menyimpan data tamu baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi disesuaikan dengan kolom di model Tamu kamu (menggunakan gmail sebagai PK)
        $validated = $request->validate([
            'gmail'         => 'required|email|unique:tamu,gmail',
            'nama_tamu'     => 'required|string|max:255',
            'nama_instansi' => 'required|string|max:255',
            'no_wa'         => 'required|string|max:15',
            'hadir_sebagai' => 'required|string',
            'tujuan_bidang' => 'nullable|string',
        ]);

        // Default status saat pendaftaran manual
        $validated['status'] = 'belum';

        Tamu::create($validated);

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Tamu berhasil didaftarkan secara manual! ✅');
    }

    /**
     * Menampilkan detail tamu.
     * Kita gunakan $tamu agar konsisten dengan parameter di route.
     */
    public function show(Tamu $tamu)
    {
        return view('petugas.manajemen_tamu.show', compact('tamu'));
    }

    /**
     * Menampilkan form edit status.
     */
    public function edit(Tamu $tamu)
    {
        return view('petugas.manajemen_tamu.edit', compact('tamu'));
    }

    /**
     * Memperbarui data dan status tamu.
     */
    public function update(Request $request, Tamu $tamu)
    {
        // Validasi status agar sesuai dengan enum/pilihan yang ada
        $validated = $request->validate([
            'status' => 'required|in:belum,sedang,sudah',
        ]);

        $tamu->update($validated);

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Status pelayanan tamu berhasil diperbarui! ✨');
    }

    /**
     * Menghapus data kunjungan.
     */
    public function destroy(Tamu $tamu)
    {
        $tamu->delete();

        return redirect()->route('petugas.manajemen_tamu.index')
            ->with('success', 'Data kunjungan telah dihapus.');
    }
}
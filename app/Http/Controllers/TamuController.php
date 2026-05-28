<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TamuController extends Controller
{
    /**
     * LOGIKA IMPLICIT REGISTRATION
     */
    public function checkEmail(Request $request)
    {
        $request->validate(['gmail' => 'required|email|max:255']);
        $gmail = $request->gmail;

        session(['gmail' => $gmail]);

        $tamu = Tamu::where('gmail', $gmail)->first();

        if ($tamu) {
            $layanan = Layanan::with('dokumens')->orderBy('nama_layanan', 'asc')->get();
            $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();
            return view('tamu.form_tamu_lama', compact('tamu', 'layanan', 'petugas', 'gmail'));
        }

        return redirect()->route('tamu.index');
    }

    /**
     * Tampilan form tamu baru.
     */
    public function index()
    {
        $gmail = session('gmail'); 
        
        if (!$gmail) {
            return redirect()->route('tamu.index'); 
        }

        $layanan = Layanan::with('dokumens')
            ->orderBy('nama_layanan', 'asc')
            ->get();
            
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();

        return view('tamu.form_tamu_baru', compact('gmail', 'layanan', 'petugas'));
    }

    /**
     * Simpan Data Kunjungan & Rating.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gmail'         => 'required|email|max:255',
            'nama_tamu'     => 'required|string|max:255',
            'no_wa'         => 'nullable|string|max:15',
            'jenis_tamu'    => 'nullable|string',
            'nama_instansi' => 'nullable|string|max:255',
            'alamat_kantor' => 'nullable|string',
            'hadir_sebagai' => 'nullable|string|max:255',
            'id_layanan'    => 'required|exists:layanan,id_layanan',
            'id_petugas'    => 'required|exists:petugas_tujuan,id_petugas',
            'skor'          => 'nullable|integer|min:0|max:5', 
            'komentar'      => 'nullable|string',
            'is_lama'       => 'nullable|boolean', 
        ]);

        try {
            $result = DB::transaction(function () use ($validated) {
                // 1. Update/Create Profil Tamu
                $tamuModel = Tamu::updateOrCreate(
                    ['gmail' => $validated['gmail']],
                    [
                        'nama_tamu'     => $validated['nama_tamu'],
                        'no_wa'         => $validated['no_wa'],
                        'jenis_tamu'    => $validated['jenis_tamu'],
                        'nama_instansi' => $validated['nama_instansi'],
                        'alamat_kantor' => $validated['alamat_kantor'],
                        'hadir_sebagai' => $validated['hadir_sebagai'],
                    ]
                );

                // 2. Simpan Kunjungan
                $kunjungan = Kunjungan::create([
                    'gmail'       => $tamuModel->gmail,
                    'id_layanan'  => $validated['id_layanan'],
                    'id_petugas'  => $validated['id_petugas'],
                    'waktu_masuk' => now(),
                    'status'      => 'belum dilayani',
                ]);

                // 3. Simpan Rating
                // PENTING: Jika error 1364 masih muncul, pastikan di phpMyAdmin 
                // tabel 'rating_layanan' kolom 'id_rating' sudah dicentang A_I (Auto Increment).
                DB::table('rating_layanan')->insert([
                    'id_kunjungan' => $kunjungan->id_kunjungan,
                    'skor'         => $validated['skor'] ?? 0,
                    'komentar'     => $validated['komentar'] ?? null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                return ['tamu' => $tamuModel, 'is_lama' => $validated['is_lama'] ?? false];
            });

            // Bersihkan session
            session()->forget('gmail');

            // Redirect ke route sukses
            $routeName = $result['is_lama'] ? 'tamu.success_lama' : 'tamu.success_baru';
            
            return redirect()->route($routeName, [
                'nama_tamu' => urlencode($result['tamu']->nama_tamu)
            ]);

        } catch (\Exception $e) {
            // Logging untuk pelacakan jika error di masa depan
            Log::error("Error saat simpan tamu: " . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat data: ' . $e->getMessage());
        }
    }
}
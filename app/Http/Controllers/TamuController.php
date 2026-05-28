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

  public function store(Request $request)
{
    // 1. Validasi tetap sama
    $validated = $request->validate([
        'gmail'         => 'required|email',
        'nama_tamu'     => 'required|string',
        'id_layanan'    => 'required|exists:layanan,id_layanan',
        'id_petugas'    => 'required|exists:petugas_tujuan,id_petugas',
        'skor'          => 'nullable|integer',
        'komentar'      => 'nullable|string',
        'no_wa'         => 'nullable|string',
        'nama_instansi' => 'nullable|string',
        'alamat_kantor' => 'nullable|string',
        'hadir_sebagai' => 'nullable|string',
    ]);

    try {
        // Menggunakan array agar bisa mengembalikan objek Tamu DAN Kunjungan
        $result = DB::transaction(function () use ($validated, $request) {
            // 2. Cek apakah tamu sudah ada
            $tamu = Tamu::where('gmail', $validated['gmail'])->first();

            if ($tamu) {
                // Jika tamu sudah ada (Lama), lakukan update
                if ($request->has('nama_tamu')) {
                    $tamu->update([
                        'nama_tamu'     => $validated['nama_tamu'],
                        'no_wa'         => $validated['no_wa'] ?? $tamu->no_wa,
                        'nama_instansi' => $validated['nama_instansi'] ?? $tamu->nama_instansi,
                        'alamat_kantor' => $validated['alamat_kantor'] ?? $tamu->alamat_kantor,
                        'hadir_sebagai' => $validated['hadir_sebagai'] ?? $tamu->hadir_sebagai,
                    ]);
                }
            } else {
                // Jika tamu belum ada (Baru), buat record baru
                $tamu = Tamu::create([
                    'gmail'         => $validated['gmail'],
                    'nama_tamu'     => $validated['nama_tamu'],
                    'no_wa'         => $validated['no_wa'],
                    'nama_instansi' => $validated['nama_instansi'],
                    'alamat_kantor' => $validated['alamat_kantor'],
                    'hadir_sebagai' => $validated['hadir_sebagai'],
                ]);
            }

            // 3. Logika Nomor Antrean Otomatis (+1)
            $tanggalHariIni = now()->format('Y-m-d');
            $antreanTerakhir = Kunjungan::whereDate('waktu_masuk', $tanggalHariIni)
                                        ->max('nomor_antrean') ?? 0;
            $nomorAntreanBaru = $antreanTerakhir + 1;

            // 4. Simpan Kunjungan dengan nomor antrean
            $kunjungan = Kunjungan::create([
                'gmail'         => $tamu->gmail,
                'id_layanan'    => $validated['id_layanan'],
                'id_petugas'    => $validated['id_petugas'],
                'waktu_masuk'   => now(),
                'nomor_antrean' => $nomorAntreanBaru,
                'status'        => 'belum dilayani',
            ]);

            // 5. Simpan Rating
            DB::table('rating_layanan')->insert([
                'id_kunjungan' => $kunjungan->id_kunjungan,
                'skor'         => $validated['skor'] ?? 0,
                'komentar'     => $validated['komentar'] ?? null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            return ['tamu' => $tamu, 'kunjungan' => $kunjungan];
        });

        // 6. Redirect dengan menyertakan nomor antrean ke view menggunakan Session Flash
        $routeName = $request->has('is_lama') ? 'tamu.success_lama' : 'tamu.success_baru';
        
        session()->forget('gmail');

        // Mengirim data melalui session flash agar terbaca di view
        return redirect()->route($routeName, [
            'nama_tamu' => urlencode($result['tamu']->nama_tamu)
        ])->with('antrean', $result['kunjungan']->nomor_antrean);

    } catch (\Exception $e) {
        Log::error("Error saat simpan tamu: " . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
    }
}
}
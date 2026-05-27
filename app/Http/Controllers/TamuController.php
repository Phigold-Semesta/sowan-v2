<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TamuController extends Controller
{
    /**
     * Tampilan awal saat tamu pertama kali datang (Scan QR).
     */
    public function index()
    {
        $gmail = null;
        $layanan = Layanan::with('dokumens')->orderBy('nama_layanan', 'asc')->get();
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();

        return view('tamu.form_tamu_baru', compact('gmail', 'layanan', 'petugas'));
    }

    /**
     * Validasi Gmail: Membedakan tamu baru dan tamu lama.
     */
    public function check(Request $request)
    {
        $request->validate(['gmail' => 'required|email|max:255']);
        $gmail = $request->gmail;

        $layanan = Layanan::with('dokumens')->orderBy('nama_layanan', 'asc')->get();
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();

        $tamu = Tamu::where('gmail', $gmail)->first();

        if ($tamu) {
            // Tamu lama ditemukan, arahkan ke form tamu lama
            return view('tamu.form_tamu_lama', compact('tamu', 'layanan', 'petugas', 'gmail'));
        }

        // Tamu baru, arahkan ke form tamu baru
        return view('tamu.form_tamu_baru', compact('layanan', 'petugas', 'gmail'));
    }

    /**
     * Simpan Data Kunjungan & Rating secara dinamis.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gmail'         => 'required|email|max:255',
            'nama_tamu'     => 'required_if:tipe_tamu,baru|nullable|string|max:255',
            'no_wa'         => 'nullable|string|max:15',
            'jenis_tamu'    => 'nullable|string',
            'nama_instansi' => 'nullable|string|max:255',
            'alamat_kantor' => 'nullable|string',
            'hadir_sebagai' => 'nullable|string|max:255',
            'id_layanan'    => 'required|exists:layanan,id_layanan',
            'id_petugas'    => 'required|exists:petugas_tujuan,id_petugas',
            'skor'          => 'nullable|integer|min:0|max:5', 
            'komentar'      => 'nullable|string',
            'tipe_tamu'     => 'required|string'
        ]);

        try {
            $result = DB::transaction(function () use ($request, $validated) {
                
                // 1. Sinkronisasi Data Profil Tamu
                if ($request->tipe_tamu === 'baru') {
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
                } else {
                    $tamuModel = Tamu::where('gmail', $validated['gmail'])->firstOrFail();
                }

                // 2. Simpan entri kunjungan
                $kunjungan = Kunjungan::create([
                    'gmail'       => $tamuModel->gmail,
                    'id_layanan'  => $validated['id_layanan'],
                    'id_petugas'  => $validated['id_petugas'],
                    'waktu_masuk' => now(),
                    'status'      => 'belum dilayani', 
                ]);

                // 3. Simpan feedback rating
                DB::table('rating_layanan')->insert([
                    'id_kunjungan' => $kunjungan->id_kunjungan,
                    'skor'         => $validated['skor'] ?? 0,
                    'komentar'     => $validated['komentar'] ?? null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                return $tamuModel; 
            });

            // Redirect dengan membawa data nama agar halaman sukses dinamis
            if ($request->tipe_tamu === 'lama') {
                return redirect()->route('tamu.success_lama', ['nama' => $result->nama_tamu]);
            }

            return redirect()->route('tamu.success_baru', ['nama' => $result->nama_tamu]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat data kunjungan: ' . $e->getMessage());
        }
    }

    public function successBaru(Request $request)
    {
        $nama_tamu = $request->query('nama');
        return view('tamu.success_tamu_baru', compact('nama_tamu'));
    }

    public function successLama(Request $request)
    {
        $nama_tamu = $request->query('nama');
        return view('tamu.success_tamu_lama', compact('nama_tamu'));
    }
}
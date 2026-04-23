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
     * Diarahkan langsung ke form_tamu_baru.
     */
    public function index()
    {
        $gmail = null;

        $layanan = Layanan::with('dokumens')
            ->orderBy('nama_layanan', 'asc')
            ->get();
            
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();

        return view('tamu.form_tamu_baru', compact('gmail', 'layanan', 'petugas'));
    }

    /**
     * Validasi Gmail.
     * Memeriksa apakah tamu sudah pernah berkunjung atau belum.
     */
    public function check(Request $request)
    {
        $request->validate(['gmail' => 'required|email|max:255']);
        $gmail = $request->gmail;

        $layanan = Layanan::with('dokumens')
            ->orderBy('nama_layanan', 'asc')
            ->get();
            
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();

        $tamu = Tamu::where('gmail', $gmail)->first();

        if ($tamu) {
            // Jika tamu lama ditemukan, tampilkan form khusus tamu lama
            return view('tamu.form_tamu_lama', compact('tamu', 'layanan', 'petugas', 'gmail'));
        }

        // Jika tamu baru, tetap di form_tamu_baru dengan gmail yang sudah terisi
        return view('tamu.form_tamu_baru', compact('layanan', 'petugas', 'gmail'));
    }

    /**
     * Simpan Data Kunjungan & Rating.
     * Disempurnakan untuk menangani alur tamu lama & baru secara dinamis.
     */
    public function store(Request $request)
    {
        // Validasi input form: Field profil menjadi nullable jika tipe_tamu adalah 'lama'
        $validated = $request->validate([
            'gmail'         => 'required|email|max:255',
            'nama_tamu'     => 'required_if:tipe_tamu,baru|string|max:255',
            'no_wa'         => 'nullable|string|max:15',
            'jenis_tamu'    => 'nullable|string',
            'nama_instansi' => 'nullable|string|max:255',
            'alamat_kantor' => 'nullable|string',
            'hadir_sebagai' => 'nullable|string|max:255',
            'id_layanan'    => 'required|exists:layanan,id_layanan',
            'id_petugas'    => 'required|exists:petugas_tujuan,id_petugas',
            'skor'          => 'nullable|integer|min:0|max:5', 
            'komentar'      => 'nullable|string',
            'tipe_tamu'     => 'required|string' // 'baru' atau 'lama'
        ]);

        try {
            $result = DB::transaction(function () use ($request, $validated) {
                
                // 1. Logika Sinkronisasi Data Profil Tamu
                if ($request->tipe_tamu === 'baru') {
                    // Jika tamu baru, buat atau update profil lengkap
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
                    // Jika tamu lama, ambil data yang sudah ada berdasarkan gmail
                    $tamuModel = Tamu::where('gmail', $validated['gmail'])->firstOrFail();
                }

                // 2. Simpan entri kunjungan baru
                $kunjungan = Kunjungan::create([
                    'gmail'       => $tamuModel->gmail,
                    'id_layanan'  => $validated['id_layanan'],
                    'id_petugas'  => $validated['id_petugas'],
                    'waktu_masuk' => now(),
                    'status'      => 'belum dilayani',
                ]);

                // 3. Simpan feedback rating (Opsional)
                DB::table('rating_layanan')->insert([
                    'id_kunjungan' => $kunjungan->id_kunjungan,
                    'skor'         => $validated['skor'] ?? 0,
                    'komentar'     => $validated['komentar'] ?? null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                return $tamuModel; 
            });

            // Alur redirect sukses berdasarkan tipe tamu sesuai keinginan Anda
            if ($request->tipe_tamu === 'lama') {
                return view('tamu.success_tamu_lama', [
                    'nama_tamu' => $result->nama_tamu
                ]);
            }

            return view('tamu.success_tamu_baru', [
                'nama_tamu' => $result->nama_tamu
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat data kunjungan: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Dashboard khusus Pimpinan.
     */
    public function pimpinanDashboard()
    {
        return view('pimpinan.dashboard');
    }

    /**
     * Halaman Statistik Admin.
     */
    public function stats()
    {
        return view('admin.statistik.index');
    }
}
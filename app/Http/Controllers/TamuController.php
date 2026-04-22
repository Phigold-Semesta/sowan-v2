<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use App\Models\RatingLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TamuController extends Controller
{
    /**
     * Tampilan awal scan QR.
     * Mengambil layanan beserta relasi dokumen panduannya.
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
     * Memeriksa tamu lama/baru dan tetap membawa data dokumen panduan.
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
            // Jika tamu lama, tampilkan form tamu lama
            return view('tamu.form_tamu_lama', compact('tamu', 'layanan', 'petugas', 'gmail'));
        }

        // Jika tamu baru, tampilkan form tamu baru
        return view('tamu.form_tamu_baru', compact('layanan', 'petugas', 'gmail'));
    }

    /**
     * Simpan Data Kunjungan & Rating.
     * Disempurnakan untuk membedakan redirect sukses tamu lama dan baru.
     */
    public function store(Request $request)
    {
        // Validasi input form
        // Catatan: Jika tamu lama, beberapa field profil (alamat, no_wa, dll) mungkin tidak dikirim, 
        // maka kita gunakan nullable atau ambil dari data lama.
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
            'tipe_tamu'     => 'required|string' // Flag pembeda alur redirect
        ]);

        try {
            $tamu = DB::transaction(function () use ($validated, $request) {
                
                // 1. Update/Create Profil Tamu
                // Jika tamu lama, data yang ada di DB tidak akan tertimpa null karena updateOrCreate
                $tamuModel = Tamu::updateOrCreate(
                    ['gmail' => $validated['gmail']],
                    array_filter([
                        'nama_tamu'     => $validated['nama_tamu'],
                        'no_wa'         => $validated['no_wa'],
                        'jenis_tamu'    => $validated['jenis_tamu'],
                        'nama_instansi' => $validated['nama_instansi'],
                        'alamat_kantor' => $validated['alamat_kantor'],
                        'hadir_sebagai' => $validated['hadir_sebagai'],
                    ])
                );

                // 2. Simpan riwayat di tabel 'kunjungan'
                $kunjungan = Kunjungan::create([
                    'gmail'       => $tamuModel->gmail,
                    'id_layanan'  => $validated['id_layanan'],
                    'id_petugas'  => $validated['id_petugas'],
                    'waktu_masuk' => now(),
                    'status'      => 'Belum Dilayani', 
                ]);

                // 3. Simpan feedback di tabel 'rating_layanan'
                // Menggunakan field 'skor' dan 'komentar' sesuai struktur DB Anda
                DB::table('rating_layanan')->insert([
                    'id_kunjungan' => $kunjungan->id_kunjungan,
                    'skor'         => $validated['skor'] ?? 0,
                    'komentar'     => $validated['komentar'] ?? null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                return $tamuModel; 
            });

            // --- LOGIC REDIRECT DISESUAIKAN ---
            // Jika request datang dari form_tamu_lama
            if ($request->tipe_tamu === 'lama') {
                return view('tamu.success_tamu_lama', [
                    'nama_tamu' => $tamu->nama_tamu
                ]);
            }

            // Default untuk tamu baru
            return view('tamu.success_tamu_baru', [
                'nama_tamu' => $tamu->nama_tamu
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function pimpinanDashboard()
    {
        return view('pimpinan.dashboard');
    }

    public function stats()
    {
        return view('admin.statistik.index');
    }
}
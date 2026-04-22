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
     * Tampilan awal scan QR.
     * Mengambil layanan beserta relasi dokumen panduannya.
     */
    public function index()
    {
        // Set default null agar tidak error Undefined Variable di Blade
        $gmail = null;

        // Ambil data layanan + dokumen terkait (Eager Loading)
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

        // Eager loading dokumens agar PDF bisa tampil dinamis di frontend
        $layanan = Layanan::with('dokumens')
            ->orderBy('nama_layanan', 'asc')
            ->get();
            
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();

        $tamu = Tamu::where('gmail', $gmail)->first();

        if ($tamu) {
            // Jika tamu lama, tampilkan form tamu lama dengan data layanan + dokumen
            return view('tamu.form_tamu_lama', compact('tamu', 'layanan', 'petugas', 'gmail'));
        }

        // Jika tamu baru, tampilkan form tamu baru dengan data layanan + dokumen
        return view('tamu.form_tamu_baru', compact('layanan', 'petugas', 'gmail'));
    }

    /**
     * Simpan Data Kunjungan.
     * Menggunakan DB Transaction untuk keamanan data (Single Identity System).
     */
    public function store(Request $request)
    {
        // Validasi disesuaikan dengan input dari form (termasuk rating & saran)
        $validated = $request->validate([
            'gmail'         => 'required|email|max:255',
            'nama_tamu'     => 'required|string|max:255',
            'no_wa'         => 'required|string|max:15',
            'jenis_tamu'    => 'required|string',
            'nama_instansi' => 'required|string|max:255',
            'alamat_kantor' => 'required|string',
            'hadir_sebagai' => 'required|string|max:255',
            'id_layanan'    => 'required|exists:layanan,id_layanan',
            'id_petugas'    => 'required|exists:petugas_tujuan,id_petugas',
            'rating'        => 'nullable|integer|min:0|max:5', // Tambahan untuk rating
            'saran'         => 'nullable|string',             // Tambahan untuk saran
        ]);

        try {
            // Kita langsung return objek tamu dari dalam transaksi agar IDE tidak menganggap variabel ini null
            $tamu = DB::transaction(function () use ($validated) {
                // 1. Update/Create Profil Tamu (Single Identity)
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

                // 2. Catat Riwayat Kunjungan
                Kunjungan::create([
                    'gmail'       => $tamuModel->gmail,
                    'id_layanan'  => $validated['id_layanan'],
                    'id_petugas'  => $validated['id_petugas'],
                    'waktu_masuk' => now(),
                    'status'      => 'belum dilayani', // Konsistensi string sesuai instruksi
                    'rating'      => $validated['rating'] ?? 0, 
                    'saran'       => $validated['saran'] ?? null, 
                ]);

                return $tamuModel; // Mengembalikan objek tamu ke variabel $tamu di luar closure
            });

            // 3. Redirect ke halaman sukses (success_tamu_baru.blade.php)
            // Sekarang variabel $tamu dijamin sebagai objek, bukan null.
            return view('tamu.success_tamu_baru', [
                'nama_tamu' => $tamu->nama_tamu
            ]);

        } catch (\Exception $e) {
            // Jika gagal, kembali ke form dengan pesan error dan input sebelumnya
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
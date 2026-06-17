<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TamuController extends Controller
{
    /**
     * Tampilan Dashboard Utama Pada Tamu Online
     */
    public function dashboard()
    {
        $tamu = Auth::guard('tamu')->user();
        
        if (!$tamu) {
            return redirect()->route('tamu.login.view')->with('error', 'Sesi Anda telah berakhir.');
        }
        
        $riwayatKunjungan = Kunjungan::where('gmail', $tamu->gmail)
                                    ->orderBy('waktu_masuk', 'desc')
                                    ->paginate(5);

        return view('tamu.dashboard', compact('tamu', 'riwayatKunjungan'));
    }

    /**
     * Tampilan form tamu (Resepsionis Utama)
     * Sekarang menggunakan data dari Guard Tamu, bukan Session yang rentan hilang
     */
    public function index()
    {
        $tamu = Auth::guard('tamu')->user();
        
        if (!$tamu) {
            return redirect()->route('tamu.onsite.view')->with('error', 'Silakan masukkan email terlebih dahulu.');
        }

        $layanan = Layanan::with('dokumens')->orderBy('nama_layanan', 'asc')->get();
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();
        
        // Membedakan apakah tamu baru atau lama berdasarkan field created_at vs updated_at
        // Atau bisa menggunakan flag is_new_guest jika Anda masih ingin mempertahankannya
        $isNewGuest = $tamu->wasRecentlyCreated || ($tamu->nama_tamu === 'Tamu Baru');

        if ($isNewGuest) {
            return view('tamu.form_tamu_baru', ['gmail' => $tamu->gmail, 'layanan' => $layanan, 'petugas' => $petugas]);
        } else {
            return view('tamu.form_tamu_lama', compact('tamu', 'layanan', 'petugas'));
        }
    }

    public function showFormBaru() { return $this->index(); }
    public function showFormLama() { return $this->index(); }

   public function store(Request $request)
{
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
        // 1. Identifikasi status tamu SEBELUM transaksi
        $tamuExist = Tamu::where('gmail', $validated['gmail'])->exists();
        
        $result = DB::transaction(function () use ($validated, $tamuExist) {
            $tamu = Tamu::where('gmail', $validated['gmail'])->firstOrFail();
            
            $tamu->update([
                'nama_tamu'     => $validated['nama_tamu'],
                'no_wa'         => $validated['no_wa'] ?? $tamu->no_wa,
                'nama_instansi' => $validated['nama_instansi'] ?? $tamu->nama_instansi,
                'alamat_kantor' => $validated['alamat_kantor'] ?? $tamu->alamat_kantor,
                'hadir_sebagai' => $validated['hadir_sebagai'] ?? $tamu->hadir_sebagai,
            ]);

            $tanggalHariIni = now()->format('Y-m-d');
            $antreanTerakhir = Kunjungan::whereDate('waktu_masuk', $tanggalHariIni)->max('nomor_antrean') ?? 0;
            
            $kunjungan = Kunjungan::create([
                'gmail'         => $tamu->gmail,
                'id_layanan'    => $validated['id_layanan'],
                'id_petugas'    => $validated['id_petugas'],
                'waktu_masuk'   => now(),
                'nomor_antrean' => $antreanTerakhir + 1,
                'status'        => 'belum dilayani',
            ]);

            if (!empty($validated['skor'])) {
                DB::table('rating_layanan')->insert([
                    'id_kunjungan' => $kunjungan->id_kunjungan,
                    'skor'         => $validated['skor'],
                    'komentar'     => $validated['komentar'] ?? null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            // 2. Kirim status apakah tamu ini baru atau tidak ke luar transaksi
            return [
                'tamu'      => $tamu, 
                'kunjungan' => $kunjungan, 
                'isNew'     => !$tamuExist // Jika sebelumnya tidak ada, maka ini tamu baru
            ];
        });

        // 3. Logout dan gunakan status 'isNew' hasil transaksi
        Auth::guard('tamu')->logout();

        $routeName = $result['isNew'] ? 'tamu.success_baru' : 'tamu.success_lama';
        
        return redirect()->route($routeName, [
            'nama_tamu' => urlencode($result['tamu']->nama_tamu)
        ])->with('antrean', $result['kunjungan']->nomor_antrean);

    } catch (\Exception $e) {
        Log::error("Error saat simpan tamu: " . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem.');
    }
}
}
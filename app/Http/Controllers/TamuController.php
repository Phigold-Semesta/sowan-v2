<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TamuController extends Controller
{
    /**
     * Tampilan Dashboard Utama Tamu Online
     */
    public function dashboard()
    {
        // Perbaikan: Menggunakan Auth Guard 'tamu' yang sudah disinkronkan
        if (!Auth::guard('tamu')->check()) {
            return redirect()->route('tamu.login.view')->with('error', 'Silakan login terlebih dahulu.');
        }

        $tamu = Auth::guard('tamu')->user();
        
        // Mengambil riwayat kunjungan tamu ini berdasarkan gmail
        $riwayatKunjungan = Kunjungan::where('gmail', $tamu->gmail)
                                      ->orderBy('waktu_masuk', 'desc')
                                      ->paginate(5);

        return view('tamu.dashboard', compact('tamu', 'riwayatKunjungan'));
    }

    /**
     * Tampilan form tamu (Resepsionis Utama)
     */
    public function index()
    {
        $gmail = Session::get('gmail'); 
        
        if (!$gmail) {
            return redirect()->route('tamu.login.view')->with('error', 'Sesi Anda telah berakhir.'); 
        }

        $layanan = Layanan::with('dokumens')->orderBy('nama_layanan', 'asc')->get();
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();
        
        $isNewGuest = Session::get('is_new_guest', true);

        if ($isNewGuest) {
            return view('tamu.form_tamu_baru', compact('gmail', 'layanan', 'petugas'));
        } else {
            $tamu = Tamu::where('gmail', $gmail)->first();
            return view('tamu.form_tamu_lama', compact('tamu', 'layanan', 'petugas', 'gmail'));
        }
    }

    // --- FUNGSI TAMBAHAN UNTUK FIX ERROR ROUTE ---
    public function showFormBaru()
    {
        return $this->index();
    }

    public function showFormLama()
    {
        return $this->index();
    }
    // ---------------------------------------------

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
            $result = DB::transaction(function () use ($validated) {
                $tamu = Tamu::where('gmail', $validated['gmail'])->first();

                if ($tamu) {
                    $tamu->update([
                        'nama_tamu'     => $validated['nama_tamu'],
                        'no_wa'         => $validated['no_wa'] ?? $tamu->no_wa,
                        'nama_instansi' => $validated['nama_instansi'] ?? $tamu->nama_instansi,
                        'alamat_kantor' => $validated['alamat_kantor'] ?? $tamu->alamat_kantor,
                        'hadir_sebagai' => $validated['hadir_sebagai'] ?? $tamu->hadir_sebagai,
                    ]);
                } else {
                    $tamu = Tamu::create([
                        'gmail'         => $validated['gmail'],
                        'nama_tamu'     => $validated['nama_tamu'],
                        'no_wa'         => $validated['no_wa'],
                        'nama_instansi' => $validated['nama_instansi'],
                        'alamat_kantor' => $validated['alamat_kantor'],
                        'hadir_sebagai' => $validated['hadir_sebagai'],
                    ]);
                }

                $tanggalHariIni = now()->format('Y-m-d');
                $antreanTerakhir = Kunjungan::whereDate('waktu_masuk', $tanggalHariIni)->max('nomor_antrean') ?? 0;
                $nomorAntreanBaru = $antreanTerakhir + 1;

                $kunjungan = Kunjungan::create([
                    'gmail'         => $tamu->gmail,
                    'id_layanan'    => $validated['id_layanan'],
                    'id_petugas'    => $validated['id_petugas'],
                    'waktu_masuk'   => now(),
                    'nomor_antrean' => $nomorAntreanBaru,
                    'status'        => 'belum dilayani',
                ]);

                if (isset($validated['skor']) && !empty($validated['skor'])) {
                    DB::table('rating_layanan')->insert([
                        'id_kunjungan' => $kunjungan->id_kunjungan,
                        'skor'         => $validated['skor'],
                        'komentar'     => $validated['komentar'] ?? null,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }

                return ['tamu' => $tamu, 'kunjungan' => $kunjungan];
            });

            $isNew = Session::get('is_new_guest');
            $routeName = $isNew ? 'tamu.success_baru' : 'tamu.success_lama';
            
            Session::forget(['gmail', 'is_new_guest', 'tamu_id']);

            return redirect()->route($routeName, [
                'nama_tamu' => urlencode($result['tamu']->nama_tamu)
            ])->with('antrean', $result['kunjungan']->nomor_antrean);

        } catch (\Exception $e) {
            Log::error("Error saat simpan tamu: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem, silakan coba lagi.');
        }
    }
}
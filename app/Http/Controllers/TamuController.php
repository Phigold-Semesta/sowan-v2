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
     * Tampilan Halaman Konsultasi Online (List + Form Dropdown)
     */
    public function konsultasiOnline()
    {
        $tamu = Auth::guard('tamu')->user();
        
        if (!$tamu) {
            return redirect()->route('tamu.login.view')->with('error', 'Sesi Anda telah berakhir.');
        }
        
        $jadwal_konsultasi = DB::table('konsultasi')
                                ->where('gmail', $tamu->gmail)
                                ->orderBy('waktu_mulai', 'asc')
                                ->get();

        $layanan = Layanan::orderBy('nama_layanan', 'asc')->get();
        $petugas = \App\Models\User::whereIn('role', ['admin', 'petugas', 'pimpinan'])
                                    ->orderBy('nama_lengkap', 'asc')
                                    ->get();

        return view('tamu.konsultasi_online.index', compact('tamu', 'jadwal_konsultasi', 'layanan', 'petugas'));
    }

    /**
     * Menyimpan pengajuan janji konsultasi baru oleh tamu
     */
    public function simpanKonsultasi(Request $request)
    {
        $tamu = Auth::guard('tamu')->user();

        $validated = $request->validate([
            'id_layanan'  => 'required|exists:layanan,id_layanan',
            'id_petugas'  => 'required|exists:petugas_tujuan,id_petugas',
            'waktu_mulai' => 'required|date|after:now',
        ]);

        DB::table('konsultasi')->insert([
            'gmail'         => $tamu->gmail,
            'id_layanan'    => $validated['id_layanan'],
            'id_user'       => $request->id_petugas,
            'waktu_mulai'   => $validated['waktu_mulai'],
            'durasi_menit'  => $request->durasi_menit, 
            'status'        => 'pending',
            'created_at'    => now(),
        ]);

        return redirect()->route('tamu.konsultasi_online.index')
                         ->with('success', 'Janji konsultasi berhasil diajukan, silakan tunggu konfirmasi petugas.');
    }

    /**
     * Tampilan form tamu (Resepsionis Utama)
     */
    public function index()
    {
        $tamu = Auth::guard('tamu')->user();
        
        if (!$tamu) {
            return redirect()->route('tamu.onsite.view')->with('error', 'Silakan masukkan email terlebih dahulu.');
        }

        $layanan = Layanan::with('dokumens')->orderBy('nama_layanan', 'asc')->get();
        $petugas = PetugasTujuan::orderBy('nama_petugas', 'asc')->get();
        
        $isNewGuest = ($tamu->nama_tamu === 'Tamu Baru' || is_null($tamu->no_wa));

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
            $tamuRecord = Tamu::where('gmail', $validated['gmail'])->first();
            $isNew = !$tamuRecord;
            
            $result = DB::transaction(function () use ($validated, $tamuRecord, $isNew) {
                $tamu = $tamuRecord ?? new Tamu(['gmail' => $validated['gmail']]);
                
                $tamu->fill([
                    'nama_tamu'     => $validated['nama_tamu'],
                    'no_wa'         => $validated['no_wa'] ?? $tamu->no_wa,
                    'nama_instansi' => $validated['nama_instansi'] ?? $tamu->nama_instansi,
                    'alamat_kantor' => $validated['alamat_kantor'] ?? $tamu->alamat_kantor,
                    'hadir_sebagai' => $validated['hadir_sebagai'] ?? $tamu->hadir_sebagai,
                ]);
                $tamu->save();

                $tanggalHariIni = now()->format('Y-m-d');
                $antreanTerakhir = Kunjungan::whereDate('waktu_masuk', $tanggalHariIni)->max('nomor_antrean') ?? 0;
                
                $kunjungan = Kunjungan::create([
                    'gmail'         => $tamu->gmail,
                    'id_layanan'    => $validated['id_layanan'],
                    'id_petugas'    => $validated['id_petugas'],
                    'waktu_masuk'   => now(),
                    'nomor_antrean' => $antreanTerakhir + 1,
                    'status'        => 'Belum Dilayani',
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

                return [
                    'tamu'      => $tamu, 
                    'kunjungan' => $kunjungan, 
                    'isNew'     => $isNew
                ];
            });

            Auth::guard('tamu')->logout();

            $routeName = $result['isNew'] ? 'tamu.success_baru' : 'tamu.success_lama';
            
            return redirect()->route($routeName, [
                'nama_tamu' => urlencode($result['tamu']->nama_tamu)
            ])->with('antrean', $result['kunjungan']->nomor_antrean);

        } catch (\Exception $e) {
            Log::error("Error saat simpan tamu: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Tampilan halaman Rating dan Saran
     */
    public function ratingIndex()
    {
        $tamu = Auth::guard('tamu')->user();

        if (!$tamu) {
            return redirect()->route('tamu.login.view')->with('error', 'Sesi Anda telah berakhir.');
        }

        // Ambil data rating yang pernah diberikan oleh tamu ini
        $rating = DB::table('rating_layanan')
                    ->join('kunjungan', 'rating_layanan.id_kunjungan', '=', 'kunjungan.id_kunjungan')
                    ->where('kunjungan.gmail', $tamu->gmail)
                    ->orderBy('rating_layanan.created_at', 'desc')
                    ->get();

        return view('tamu.rating.index', compact('tamu', 'rating'));
    }

    /**
     * Menyimpan Rating dan Saran baru
     */
    public function simpanRating(Request $request)
    {
        $validated = $request->validate([
            'id_kunjungan' => 'required|exists:kunjungan,id_kunjungan',
            'skor'         => 'required|integer|min:1|max:5',
            'komentar'     => 'nullable|string',
        ]);

        DB::table('rating_layanan')->insert([
            'id_kunjungan' => $validated['id_kunjungan'],
            'skor'         => $validated['skor'],
            'komentar'     => $validated['komentar'] ?? null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('tamu.rating.index')
                         ->with('success', 'Terima kasih atas rating dan saran Anda!');
    }
}
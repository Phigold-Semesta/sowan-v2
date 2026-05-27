<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login untuk User (Petugas/Admin/Pimpinan)
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses validasi login menggunakan Username untuk Petugas
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // 2. Auth::attempt untuk Petugas
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // 3. Jika gagal
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses Implicit Registration / Login untuk Tamu
     * Menyesuaikan dengan logika tamu baru vs tamu lama
     */
    public function handleGuest(Request $request)
    {
        // 1. Validasi input email
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 2. Cari tamu berdasarkan email (Implicit Check)
        $tamu = Tamu::where('email', $request->email)->first();

        if (!$tamu) {
            // Jika tamu baru: Lakukan registrasi otomatis
            $tamu = Tamu::create([
                'email' => $request->email,
                // Tambahkan field default lainnya jika perlu
            ]);
            Session::put('is_new_guest', true);
        } else {
            // Jika tamu lama: Cukup tandai sebagai tamu kembali
            Session::put('is_new_guest', false);
        }

        // 3. Simpan ID tamu ke session untuk alur selanjutnya
        Session::put('tamu_id', $tamu->id);

        // 4. Redirect ke halaman pengisian data kunjungan
        return redirect()->route('tamu.kunjungan.form');
    }

    /**
     * Proses logout untuk menghancurkan session
     */
    public function logout(Request $request)
    {
        // Logout Petugas
        Auth::logout();
        
        // Menghapus data session, token CSRF, dan data tamu (jika ada)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login untuk Internal (Petugas/Admin/Pimpinan)
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Menampilkan halaman Portal Publik (Tamu)
     */
    public function showPublik()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.publik');
    }

    /**
     * Proses validasi login untuk Internal
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses Implicit Registration / Login untuk Tamu (Portal Publik)
     * Disempurnakan untuk menangani error field database agar tidak crash
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'gmail' => ['required', 'email'],
        ]);

        $tamu = Tamu::where('gmail', $request->gmail)->first();

        if (!$tamu) {
            // Jika tamu baru, isi dengan default agar tidak melanggar aturan database
            $tamu = Tamu::create([
                'gmail'         => $request->gmail,
                'nama_tamu'     => 'Tamu Baru',
                'no_wa'         => '-',
                'nama_instansi' => '-',
                'alamat_kantor' => '-',
                'hadir_sebagai' => '-',
            ]);
            Session::put('is_new_guest', true);
        } else {
            Session::put('is_new_guest', false);
        }

        // --- SINKRONISASI ---
        // Simpan ke session agar TamuController bisa mendeteksi status & email
        Session::put('gmail', $request->gmail); 
        Session::put('tamu_id', $tamu->id);

        return redirect()->route('tamu.index')->with('success', 'Silakan isi data kunjungan Anda.');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        
        $request->session()->flush();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
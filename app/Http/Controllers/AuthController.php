<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Proses validasi login menggunakan Username
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi input: pastikan menggunakan 'username', bukan 'gmail'
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // 2. Auth::attempt akan mencocokkan 'username' dan 'password' 
        // secara otomatis dengan tabel 'user' di database
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();
            
            // Redirect ke dashboard atau halaman yang dituju sebelumnya
            return redirect()->intended('/dashboard');
        }

        // 3. Jika gagal, kembalikan ke form dengan pesan error yang sesuai
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout untuk menghancurkan session
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Menghapus data session dan token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
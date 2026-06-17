<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // =========================================================================
    // 1. PORTAL INTERNAL (Admin, Petugas, Pimpinan)
    // =========================================================================
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

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

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    // =========================================================================
    // 2. PORTAL TAMU ONSITE (Scan QR - Implicit Registration)
    // =========================================================================
    public function showTamuOnsite()
    {
        return view('auth.tamu_onsite');
    }

    public function checkEmailOnsite(Request $request)
    {
        $request->validate(['gmail' => ['required', 'email']]);

        $tamu = Tamu::where('gmail', $request->gmail)->first();

        if (!$tamu) {
            $tamu = Tamu::create([
                'gmail'         => $request->gmail,
                'nama_tamu'     => 'Tamu Baru',
                'no_wa'         => '-',
                'nama_instansi' => '-',
                'alamat_kantor' => '-',
                'hadir_sebagai' => '-',
                'jenis_tamu'    => 'Non-Penyedia',
                'password'      => Hash::make('default123'),
            ]);
            
            // Menggunakan guard login otomatis saat onsite
            Auth::guard('tamu')->login($tamu);
            return redirect()->route('tamu.form_tamu_baru')->with('success', 'Silakan lengkapi data kunjungan.');
        } else {
            // Menggunakan guard login otomatis saat onsite
            Auth::guard('tamu')->login($tamu);
            return redirect()->route('tamu.form_tamu_lama')->with('success', 'Selamat datang kembali, silakan isi data kunjungan.');
        }
    }

    // =========================================================================
    // 3. PORTAL PUBLIK/ONLINE (Frontend Tamu - Manual Register/Login)
    // =========================================================================
    
    public function showPublik()
    {
        return view('auth.tamu.publik');
    }

    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $tamu = Tamu::where('gmail', $request->email)->first();

        if ($tamu) {
            return view('tamu.form_tamu_lama', ['tamu' => $tamu]);
        } else {
            return view('tamu.form_tamu_baru', ['email' => $request->email]);
        }
    }

    public function showSignup()
    {
        return view('auth.tamu.signup');
    }

    public function registerOnline(Request $request)
    {
        $validated = $request->validate([
            'nama_tamu'     => 'required|string|max:100',
            'gmail'         => 'required|email|unique:tamu,gmail',
            'password'      => 'required|min:8',
            'no_wa'         => 'required|string|max:25',
            'nama_instansi' => 'required|string|max:100',
            'alamat_kantor' => 'required|string',
            'hadir_sebagai' => 'required|string|max:50',
            'jenis_tamu'    => 'required|in:Penyedia,Non-Penyedia',
        ]);

        Tamu::create([
            'nama_tamu'     => $validated['nama_tamu'],
            'gmail'         => $validated['gmail'],
            'password'      => Hash::make($validated['password']),
            'no_wa'         => $validated['no_wa'],
            'nama_instansi' => $validated['nama_instansi'],
            'alamat_kantor' => $validated['alamat_kantor'],
            'hadir_sebagai' => $validated['hadir_sebagai'],
            'jenis_tamu'    => $validated['jenis_tamu'],
        ]);

        return redirect()->route('tamu.login.view')->with('success', 'Akun berhasil dibuat! Silakan login menggunakan email Anda.');
    }

    public function loginOnline(Request $request)
    {
        $credentials = $request->validate([
            'gmail'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('tamu')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('tamu.dashboard'));
        }

        return back()->withErrors(['gmail' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('tamu')->check()) {
            Auth::guard('tamu')->logout();
        }
        
        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->flush();
        $request->session()->regenerateToken();
        return redirect('/portal');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini agar Auth dikenali editor
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * TAMPILAN: Daftar Pengguna (Hanya Admin)
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * PROSES: Simpan Pengguna Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:50|unique:user,username', 
            'password'     => 'required|string|min:8|confirmed',
            'role'         => 'required|in:administrator,petugas,pimpinan',
            'jabatan'      => 'required|string|max:100',
        ]);

        User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'username'     => $validated['username'],
            'password'     => Hash::make($validated['password']),
            'role'         => $validated['role'],
            'jabatan'      => $validated['jabatan'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dibuat! 🟢');
    }

    /**
     * TAMPILAN: Form Edit Pengguna
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * PROSES: Perbarui Data Pengguna
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            // ignore() menggunakan id_user karena itu primary key kustom kita
            'username'     => ['required', 'string', 'max:50', Rule::unique('user')->ignore($user->id_user, 'id_user')],
            'password'     => 'nullable|string|min:8|confirmed',
            'role'         => 'required|in:administrator,petugas,pimpinan',
            'jabatan'      => 'required|string|max:100',
        ]);

        $data = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'username'     => $validated['username'],
            'role'         => $validated['role'],
            'jabatan'      => $validated['jabatan'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui! ✨');
    }

    /**
     * PROSES: Hapus Pengguna
     */
    public function destroy(User $user)
    {
        /**
         * SOLUSI GARIS MERAH:
         * Menggunakan Facade Auth::id() lebih "aman" dari deteksi error editor 
         * dibanding helper auth()->id().
         */
        if (Auth::id() == $user->id_user) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri! ❌');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna telah dihapus dari sistem.');
    }
}
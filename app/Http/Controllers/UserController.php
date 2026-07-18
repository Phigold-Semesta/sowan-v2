<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * TAMPILAN: Daftar Pengguna dengan Pencarian & Filter
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                      ->orWhere('username', 'LIKE', "%{$search}%");
                });
            })
            ->when($request->role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest('id_user')
            ->paginate($request->per_page === 'all' ? User::count() : ($request->per_page ?? 10))
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * TAMPILAN: Form Tambah Pengguna
     */
    public function create()
    {
        return view('admin.users.create');
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

        DB::transaction(function () use ($validated) {
            User::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'username'     => $validated['username'],
                'password'     => Hash::make($validated['password']),
                'role'         => $validated['role'],
                'jabatan'      => $validated['jabatan'],
            ]);
        });

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dibuat! 🟢');
    }

    /**
     * TAMPILAN: Detail Profil Pengguna (Sempurnakan di sini) 🔍
     */
    public function show(User $user)
    {
        // Menggunakan Route Model Binding (User $user) otomatis mencari berdasarkan ID
        return view('admin.users.show', compact('user'));
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
            'username'     => ['required', 'string', 'max:50', Rule::unique('user')->ignore($user->id_user, 'id_user')],
            'password'     => 'nullable|string|min:8|confirmed',
            'role'         => 'required|in:administrator,petugas,pimpinan',
            'jabatan'      => 'required|string|max:100',
        ]);

        // Proteksi: Mencegah admin mengubah role-nya sendiri
        if (Auth::id() == $user->id_user && $validated['role'] !== 'administrator' && $user->role === 'administrator') {
            return redirect()->back()->with('error', 'Anda tidak diperbolehkan mengubah Role admin Anda sendiri! ❌');
        }

        $data = $request->except(['password', 'password_confirmation']);

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
        if (Auth::id() == $user->id_user) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri! ❌');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna telah berhasil dihapus.');
    }

    public function toggleStatus(Request $request)
{
    $user = auth()->user();
    // Gunakan update agar langsung tersimpan di database
    $user->status_konsultasi = ($user->status_konsultasi === 'online') ? 'offline' : 'online';
    $user->save();

    return response()->json([
        'status' => 'success',
        'new_status' => $user->status_konsultasi
    ]);
}
}
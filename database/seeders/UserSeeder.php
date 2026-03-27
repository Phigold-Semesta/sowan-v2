<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds sesuai profil personil LPSE Karawang.
     */
    public function run(): void
    {
        // 1. Akun Administrator
        User::create([
            'nama_lengkap' => 'Iqbal Bagus Ramadhan',
            'username' => 'iqbal_admin',
            'password' => Hash::make('admin123'),
            'jabatan' => 'IT Administrator',
            'role' => 'administrator'
        ]);

        // 2. Akun Petugas
        // Kita gunakan Pak Dede Kurniawan sebagai contoh petugas tetap
        User::create([
            'nama_lengkap' => 'Dede Kurniawan',
            'username' => 'dede_petugas',
            'password' => Hash::make('petugas123'),
            'jabatan' => 'Pengelola LPSE Karawang',
            'role' => 'petugas'
        ]);

        // Akun Petugas tambahan (Mahasiswa Magang)
        User::create([
            'nama_lengkap' => 'Andi Wijaya (Magang)',
            'username' => 'andi_magang',
            'password' => Hash::make('magang123'),
            'jabatan' => 'Helpdesk Mahasiswa Magang',
            'role' => 'petugas'
        ]);

        // 3. Akun Pimpinan
        // Pimpinan 1: Pak Tolib
        User::create([
            'nama_lengkap' => 'Tolib Sutrisno, ST',
            'username' => 'tolib_pimpinan',
            'password' => Hash::make('pimpinan123'),
            'jabatan' => 'Ketua Tim LPSE Karawang',
            'role' => 'pimpinan'
        ]);

        // Pimpinan 2: Pak Wahyu
        User::create([
            'nama_lengkap' => 'Wahyu Prasetyo, ST, MM',
            'username' => 'wahyu_pimpinan',
            'password' => Hash::make('pimpinan123'),
            'jabatan' => 'Pimpinan LPSE',
            'role' => 'pimpinan'
        ]);
    }
}
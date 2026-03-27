<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tamu;
use App\Models\Kunjungan;
use App\Models\RatingLayanan;
use App\Models\AuditLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Memanggil Seeder Data Master (Pondasi Aplikasi)
        // Pastikan User, Layanan, dan Petugas sudah ada sebelum data transaksi dibuat
        $this->call([
            LayananSeeder::class,
            PetugasTujuanSeeder::class,
            UserSeeder::class,
        ]);

        // 2. Menjalankan Factories untuk Data Simulasi/Dummy
        // Membuat 50 data tamu acak dengan profil Indonesia
        Tamu::factory(50)->create();

        // Membuat 100 riwayat kunjungan yang terhubung ke Tamu, Layanan, dan Petugas
        Kunjungan::factory(100)->create();

        // Membuat 40 rating/ulasan dari kunjungan yang sudah berstatus 'sudah dilayani'
        RatingLayanan::factory(40)->create();

        // Membuat 30 catatan audit log untuk memantau aktivitas sistem
        AuditLog::factory(30)->create();
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan; // Pastikan memanggil model Layanan

class LayananSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // Daftar layanan sesuai permintaanmu
        $daftarLayanan = [
            [
                'id_layanan' => 1,
                'nama_layanan' => 'Konsultasi aplikasi SPSE/INAPROC'
            ],
            [
                'id_layanan' => 2,
                'nama_layanan' => 'Konsultasi aplikasi SIKAP'
            ],
            [
                'id_layanan' => 3,
                'nama_layanan' => 'Verifikasi aplikasi SIKAP'
            ],
            [
                'id_layanan' => 4,
                'nama_layanan' => 'Konsultasi aplikasi E-Katalog versi 6'
            ],
            [
                'id_layanan' => 5,
                'nama_layanan' => 'Konsultasi aplikasi SIRUP'
            ],
            [
                'id_layanan' => 6,
                'nama_layanan' => 'Lainnya'
            ],
        ];

        // Memasukkan data ke tabel layanan
        foreach ($daftarLayanan as $layanan) {
            Layanan::create($layanan);
        }
    }
}
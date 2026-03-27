<?php

namespace Database\Factories;

use App\Models\Kunjungan;
use App\Models\Tamu;
use App\Models\Layanan;
use App\Models\PetugasTujuan;
use Illuminate\Database\Eloquent\Factories\Factory;

class KunjunganFactory extends Factory
{
    protected $model = Kunjungan::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        // Membuat waktu masuk acak dalam satu bulan terakhir
        $waktuMasuk = $faker->dateTimeBetween('-1 month', 'now');
        
        // Membuat waktu keluar (opsional: tambah 30-120 menit setelah masuk)
        // Kita gunakan copy() agar objek aslinya tidak berubah
        $waktuKeluar = (clone $waktuMasuk)->modify('+' . rand(30, 120) . ' minutes');

        return [
            // Mengambil Gmail dari Tamu yang sudah ada (Relasi "Melakukan")
            'gmail'         => Tamu::inRandomOrder()->first()->gmail ?? Tamu::factory(),
            
            // Relasi "Dipilih" dari Layanan
            'id_layanan'    => Layanan::inRandomOrder()->first()->id_layanan ?? Layanan::factory(),
            
            // Relasi "Menemui" Petugas Tujuan
            'id_petugas'    => PetugasTujuan::inRandomOrder()->first()->id_petugas ?? PetugasTujuan::factory(),
            
            // Atribut sesuai ERD Revisi 2
            'waktu_masuk'   => $waktuMasuk,
            'waktu_keluar'  => $waktuKeluar,
            'status'        => $faker->randomElement(['belum dilayani', 'sedang dilayani', 'sudah dilayani']),
        ];
    }
}
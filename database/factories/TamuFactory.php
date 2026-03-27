<?php

namespace Database\Factories;

use App\Models\Tamu;
use Illuminate\Database\Eloquent\Factories\Factory;

class TamuFactory extends Factory
{
    protected $model = Tamu::class;

    public function definition(): array
    {
        // Menggunakan Faker dengan pelokalan Indonesia
        $faker = \Faker\Factory::create('id_ID');

        return [
            // Gmail sebagai Primary Key dibuat unik agar tidak bentrok
            'gmail'         => $faker->unique()->safeEmail(), 
            'no_wa'         => $faker->phoneNumber(),
            'nama_tamu'     => $faker->name(),
            // Hanya ada dua pilihan sesuai revisi kamu
            'jenis_tamu'    => $faker->randomElement(['Penyedia', 'Non-Penyedia']),
            'nama_instansi' => $faker->company(),
            'alamat_kantor' => $faker->address(),
            'hadir_sebagai' => $faker->jobTitle(),
        ];
    }
}
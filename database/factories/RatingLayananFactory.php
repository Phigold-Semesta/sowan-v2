<?php

namespace Database\Factories;

use App\Models\RatingLayanan;
use App\Models\Kunjungan;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingLayananFactory extends Factory
{
    protected $model = RatingLayanan::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        // Mengambil kunjungan yang sudah selesai agar logis untuk diberi rating
        $kunjungan = Kunjungan::where('status', 'sudah dilayani')->inRandomOrder()->first() 
                     ?? Kunjungan::factory()->create(['status' => 'sudah dilayani']);

        return [
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'skor'  => $faker->numberBetween(1, 5), // Skor 1-5 bintang
            'komentar'     => $faker->optional(0.8)->sentence(6), // 80% kemungkinan ada komentar
        ];
    }
}
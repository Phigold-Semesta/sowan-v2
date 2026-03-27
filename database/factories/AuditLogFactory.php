<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        // Mengambil user secara acak untuk pelakunya
        $user = User::inRandomOrder()->first();

        $aktivitas = [
            'Login ke sistem',
            'Menambah data tamu baru',
            'Mengubah status kunjungan',
            'Menghapus data user',
            'Mencetak laporan bulanan',
            'Logout dari sistem'
        ];

        return [
            'id_user'   => $user->id_user ?? 1, // Default ke ID 1 jika user belum ada
            'aktivitas' => $faker->randomElement($aktivitas),
            'waktu'     => $faker->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
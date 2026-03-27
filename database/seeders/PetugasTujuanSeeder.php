<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PetugasTujuan;

class PetugasTujuanSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // 1. Data Pejabat/Struktural LPSE
        $dataPejabat = [
            [
                'id_petugas' => 1,
                'nama_petugas' => 'Tolib Sutrisno, ST',
                'jabatan' => 'Ketua Tim LPSE Karawang'
            ],
            [
                'id_petugas' => 2,
                'nama_petugas' => 'Dede Kurniawan',
                'jabatan' => 'Pengelola LPSE Karawang'
            ],
        ];

        foreach ($dataPejabat as $pejabat) {
            PetugasTujuan::create($pejabat);
        }

        // 2. Data Helpdesk (Mahasiswa Magang - Nama Acak Indonesia)
        $namaHelpdesk = ['Ahmad Zarkasi', 'Siti Aminah', 'Budi Setiawan', 'Dewi Lestari'];
        
        $idMulai = 3; // Melanjutkan ID setelah pejabat
        foreach ($namaHelpdesk as $nama) {
            PetugasTujuan::create([
                'id_petugas' => $idMulai++,
                'nama_petugas' => $nama,
                'jabatan' => 'Helpdesk (Mahasiswa Magang)'
            ]);
        }
    }
}
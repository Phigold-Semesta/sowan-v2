<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tamu;
use Illuminate\Support\Facades\DB;

class TamuTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_onsite_bisa_menyimpan_kunjungan(): void
    {
        // Memaksa Laravel menampilkan error asli jika ada kegagalan proses
        $this->withoutExceptionHandling(); 

        // 1. SIAPKAN DATA RELASI (Bypass Eloquent Protection)
        DB::table('layanan')->insert([
            'id_layanan'   => 1, 
            'nama_layanan' => 'Layanan Konsultasi IT',
            'created_at'   => now(),
            'updated_at'   => now()
        ]);
        
        DB::table('petugas_tujuan')->insert([
            'id_petugas'   => 1, 
            'nama_petugas' => 'Bapak Fulan',
            'jabatan'      => 'Staf',
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        // 2. SIAPKAN AKUN TAMU
        $tamu = Tamu::create([
            'gmail'         => 'putri@email.com',
            'nama_tamu'     => 'Putri',
            'no_wa'         => '08123456789',
            'jenis_tamu'    => 'Penyedia',
            'alamat_kantor' => 'Jl. Karawang No 1',
            'hadir_sebagai' => 'Tamu Undangan'
        ]);

        // 3. SIMULASI LOGIN DAN KIRIM DATA
        $response = $this->actingAs($tamu, 'tamu')->post('/tamu/simpan', [
            'gmail'         => 'putri@email.com',
            'nama_tamu'     => 'Putri',
            'id_layanan'    => 1, 
            'id_petugas'    => 1, 
            'nama_instansi' => 'cv.sejahtera bersama',
            'no_wa'         => '08123456789',
            'alamat_kantor' => 'Jl. Karawang No 1',
            'hadir_sebagai' => 'Tamu Undangan',
            'skor'          => 5,
            'komentar'      => 'Test sistem SOWAN v2'
        ]);

        // 4. VERIFIKASI REDIRECT SUKSES
        $response->assertStatus(302);

        // 5. VERIFIKASI DATABASE
        // Jika masih gagal, pastikan 'status' di sini sama persis dengan yang ada di database (case sensitive)
        $this->assertDatabaseHas('kunjungan', [
            'gmail'      => 'putri@email.com',
            'id_layanan' => 1,
            'id_petugas' => 1,
            'status'     => 'Belum Dilayani' 
        ]);
    }

    public function test_form_kunjungan_harus_memiliki_data_wajib(): void
    {
        $tamu = Tamu::create([
            'gmail' => 'putri2@email.com',
            'nama_tamu' => 'Putri Dua',
            'no_wa' => '08123456789',
            'jenis_tamu' => 'Penyedia',
            'alamat_kantor' => 'Jl. Karawang No 2',
            'hadir_sebagai' => 'Tamu Undangan'
        ]);

        $response = $this->actingAs($tamu, 'tamu')->post('/tamu/simpan', [
            'gmail'      => '',
            'nama_tamu'  => '',
            'id_layanan' => '', 
            'id_petugas' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['gmail', 'nama_tamu', 'id_layanan', 'id_petugas']);
    }
}
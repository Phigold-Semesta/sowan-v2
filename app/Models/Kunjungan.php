<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kunjungan extends Model
{
    use HasFactory;

    /**
     * Nama tabel sesuai skema SOWAN V2.
     * Tabel ini berfungsi sebagai Tabel Transaksi (Weak Entity) [cite: 2026-03-23].
     */
    protected $table = 'kunjungan';

    /**
     * Primary Key tabel kunjungan.
     */
    protected $primaryKey = 'id_kunjungan';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'gmail', 
        'id_layanan', 
        'id_petugas', 
        'waktu_masuk', 
        'waktu_keluar', 
        'status'
    ];

    /**
     * RELASI: Ke RatingLayanan (Satu kunjungan punya satu rating).
     * Sesuai rencana, rating_layanan menjadi Weak Entity murni [cite: 2026-03-23].
     */
    public function ratingLayanan(): HasOne
    {
        return $this->hasOne(RatingLayanan::class, 'id_kunjungan', 'id_kunjungan');
    }

    /**
     * RELASI: Balik ke Tamu (Banyak kunjungan dilakukan oleh satu tamu).
     * Menggunakan gmail sebagai foreign key sesuai skema fillable [cite: 2026-03-23].
     */
    public function tamu(): BelongsTo
    {
        return $this->belongsTo(Tamu::class, 'gmail', 'gmail')->withDefault([
            'nama_tamu' => 'Tamu Tidak Terdaftar',
            'nama_instansi' => '-'
        ]);
    }

    /**
     * RELASI: Ke Layanan.
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan')->withDefault([
            'nama_layanan' => 'Layanan Umum'
        ]);
    }

    /**
     * PERBAIKAN VITAL: Relasi ke Petugas Tujuan.
     * Menyesuaikan target relasi ke model PetugasTujuan, bukan model User.
     * Menggunakan foreign key 'id_petugas' yang merujuk ke tabel 'petugas_tujuan'.
     */
    
    // Dipakai oleh AdminController atau aktor lain yang memanggil ->with(['petugas'])
    public function petugas(): BelongsTo
    {
        // Diarahkan ke PetugasTujuan untuk mendapatkan nama asli petugas dari database
        return $this->belongsTo(PetugasTujuan::class, 'id_petugas', 'id_petugas')->withDefault([
            'nama_petugas' => 'Petugas Piket'
        ]);
    }

    // Dipakai oleh PetugasController yang memanggil ->with(['petugasTujuan'])
    // Menjamin konsistensi data agar tidak muncul "Petugas Piket" jika data tersedia
    public function petugasTujuan(): BelongsTo
    {
        // Diarahkan ke PetugasTujuan untuk memastikan relasi di PetugasController aman
        return $this->belongsTo(PetugasTujuan::class, 'id_petugas', 'id_petugas')->withDefault([
            'nama_petugas' => 'Petugas Piket'
        ]);
    }
}
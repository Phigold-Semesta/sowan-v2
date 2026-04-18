<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne; // Perbaikan: Gunakan HasOne untuk sesi tunggal

class Kunjungan extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel (SOWAN V2 tidak menggunakan plural)
     */
    protected $table = 'kunjungan';

    /**
     * Tentukan Primary Key
     */
    protected $primaryKey = 'id_kunjungan';

    /**
     * Daftar kolom yang dapat diisi (Mass Assignment)
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
     * Relasi ke Rating_Layanan
     * Penjelasan: Satu sesi kunjungan spesifik menghasilkan SATU rating.
     * Ini mencegah error "Property [skor] does not exist on this collection instance".
     */
    public function rating(): HasOne
    {
        return $this->hasOne(RatingLayanan::class, 'id_kunjungan', 'id_kunjungan');
    }

    /**
     * Relasi balik ke Tamu
     * Penjelasan: Banyak kunjungan dilakukan oleh satu tamu (Many-to-One).
     */
    public function tamu(): BelongsTo
    {
        return $this->belongsTo(Tamu::class, 'gmail', 'gmail');
    }

    /**
     * Relasi ke Layanan
     * Penjelasan: Kunjungan ini memilih satu kategori layanan.
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Relasi ke Petugas_Tujuan
     * Penjelasan: Kunjungan ini menemui satu petugas spesifik.
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(PetugasTujuan::class, 'id_petugas', 'id_petugas');
    }
}
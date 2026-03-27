<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany untuk relasi ke Rating

class Kunjungan extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel
    protected $table = 'kunjungan';

    // 2. Tentukan Primary Key
    protected $primaryKey = 'id_kunjungan';

    // 3. Daftar kolom yang dapat diisi (Mass Assignment)
    // Disesuaikan dengan atribut di ERD: waktu_masuk, waktu_keluar, status
    protected $fillable = [
        'gmail', 
        'id_layanan', 
        'id_petugas', 
        'waktu_masuk', 
        'waktu_keluar', 
        'status'
    ];

    /**
     * Relasi ke Rating_Layanan (Satu kunjungan "Menghasilkan" rating)
     */
    public function rating(): HasMany
    {
        return $this->hasMany(RatingLayanan::class, 'id_kunjungan', 'id_kunjungan');
    }

    /**
     * Relasi balik ke Tamu (Banyak kunjungan "Melakukan" oleh satu tamu)
     */
    public function tamu(): BelongsTo
    {
        return $this->belongsTo(Tamu::class, 'gmail', 'gmail');
    }

    /**
     * Relasi ke Layanan (Banyak kunjungan "Dipilih" dari satu layanan)
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Relasi ke Petugas_Tujuan (Banyak kunjungan "Menemui" satu petugas)
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(PetugasTujuan::class, 'id_petugas', 'id_petugas');
    }
}
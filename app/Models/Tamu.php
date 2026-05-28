<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tamu extends Model
{
    use HasFactory;

    /**
     * Konfigurasi tabel dan primary key.
     * Menggunakan gmail sebagai primary key string dan menonaktifkan auto-increment.
     */
    protected $table = 'tamu';
    protected $primaryKey = 'gmail';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Properti fillable untuk mass assignment.
     * Pastikan semua kolom yang diinputkan dari form ada di sini.
     */
    protected $fillable = [
        'gmail', 
        'no_wa', 
        'nama_tamu', 
        'jenis_tamu', 
        'nama_instansi', 
        'alamat_kantor', 
        'hadir_sebagai'
    ];

    /**
     * Casting tipe data untuk keamanan data.
     */
    protected $casts = [
        'gmail' => 'string',
    ];

    /**
     * RELASI: Satu tamu memiliki banyak riwayat kunjungan.
     */
    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'gmail', 'gmail');
    }

    /**
     * RELASI: Satu tamu dapat memberikan rating berkali-kali.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(RatingLayanan::class, 'gmail', 'gmail');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamu';
    protected $primaryKey = 'gmail';
    public $incrementing = false;
    protected $keyType = 'string';

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
     * RELASI: Satu tamu memiliki banyak riwayat kunjungan.
     */
    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'gmail', 'gmail');
    }

    /**
     * RELASI: Satu tamu dapat memberikan rating berkali-kali (banyak)
     * seiring dengan jumlah kunjungan mereka.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(RatingLayanan::class, 'gmail', 'gmail');
    }
}
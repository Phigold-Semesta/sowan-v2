<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Import trait ini
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RatingLayanan extends Model
{
    use HasFactory; // 2. Gunakan trait ini di dalam class

    // Nama tabel sesuai migrasi
    protected $table = 'rating_layanan';

    // Primary Key sesuai struktur tabel
    protected $primaryKey = 'id_rating';

    // Atribut yang dapat diisi massal
    // Sesuaikan nama kolom dengan yang ada di Factory (skor_rating & komentar)
    protected $fillable = [
        'id_kunjungan', 
        'skor_rating', 
        'komentar', 
        'tanggapan',
        'id_user' // User/Petugas yang menanggapi
    ];

    /**
     * Relasi ke Kunjungan (Satu rating milik satu kunjungan)
     */
    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan', 'id_kunjungan');
    }

    /**
     * Relasi ke User (Petugas yang memberikan tanggapan/balasan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
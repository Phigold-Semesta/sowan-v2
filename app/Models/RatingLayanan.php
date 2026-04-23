<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RatingLayanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel sesuai skema SOWAN V2.
     */
    protected $table = 'rating_layanan';

    /**
     * PERBAIKAN: Menetapkan id_kunjungan sebagai Primary Key.
     * Sebagai Weak Entity (Identifying Relationship), ia tidak punya ID sendiri.
     */
    protected $primaryKey = 'id_kunjungan';

    /**
     * PERBAIKAN: Nonaktifkan auto-increment.
     * Karena PK ini bersifat manual (mengambil value dari tabel kunjungan).
     */
    public $incrementing = false;

    /**
     * Tipe data Primary Key.
     * Menggunakan string atau int sesuai dengan tipe data di tabel kunjungan.
     */
    protected $keyType = 'int';

    /**
     * Atribut yang dapat diisi massal.
     * Pastikan semua kolom yang diperlukan ada di sini.
     */
    protected $fillable = [
        'id_kunjungan', 
        'skor_rating', 
        'komentar', 
        'tanggapan',
        'id_user' 
    ];

    /**
     * RELASI: Ke Kunjungan (Satu rating milik satu kunjungan).
     * Ini adalah jangkar utama sebagai Weak Entity.
     */
    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan', 'id_kunjungan');
    }

    /**
     * RELASI: Ke User (Petugas yang memberikan tanggapan/balasan).
     * Foreign Key: id_user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * TAMBAHAN PENYEMPURNAAN:
     * Fungsi ini memastikan saat pencarian data melalui Eloquent (seperti findOrFail),
     * Laravel mencari berdasarkan id_kunjungan dengan benar.
     */
    public function getIncrementing()
    {
        return false;
    }
}
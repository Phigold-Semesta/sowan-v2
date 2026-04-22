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
     * Nama tabel sesuai skema SOWAN V2 (tanpa plural).
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
     * Menggunakan HasOne untuk menghindari error collection pada saat akses property.
     */
    public function ratingLayanan(): HasOne
    {
        return $this->hasOne(RatingLayanan::class, 'id_kunjungan', 'id_kunjungan');
    }

    /**
     * RELASI: Balik ke Tamu (Banyak kunjungan dilakukan oleh satu tamu).
     * Foreign Key: gmail.
     */
    public function tamu(): BelongsTo
    {
        return $this->belongsTo(Tamu::class, 'gmail', 'gmail');
    }

    /**
     * RELASI: Ke Layanan (Setiap kunjungan memiliki satu jenis layanan).
     * Foreign Key: id_layanan.
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * RELASI: Ke PetugasTujuan (Setiap kunjungan menemui satu petugas).
     * Nama fungsi disamakan dengan pemanggilan di Controller agar tidak error.
     * Foreign Key: id_petugas.
     */
    public function petugasTujuan(): BelongsTo
    {
        return $this->belongsTo(PetugasTujuan::class, 'id_petugas', 'id_petugas');
    }
}
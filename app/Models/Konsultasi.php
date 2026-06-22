<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Konsultasi extends Model
{
    // Nama tabel secara eksplisit
    protected $table = 'konsultasi';

    // Primary key jika bukan 'id'
    protected $primaryKey = 'id_konsultasi';

    // Field yang boleh diisi (mass assignable)
    protected $fillable = [
        'id_kunjungan',
        'id_user',
        'id_layanan',
        'waktu_konsultasi',
        'link_google_meet',
        'status',
    ];

    /**
     * Relasi ke User (Aktor internal yang melayani)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke Layanan
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Relasi ke Kunjungan (Opsional)
     */
    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan', 'id_kunjungan');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Konsultasi extends Model
{
    // Nama tabel secara eksplisit
    protected $table = 'konsultasi';

    // Primary key yang baru disesuaikan (id_konsultasi)
    protected $primaryKey = 'id_konsultasi';

    // Pastikan ini true agar Laravel mengenali auto-increment
    public $incrementing = true;
    protected $keyType = 'int';

    // Definisikan status sebagai konstanta agar sinkron dengan database ENUM
    const STATUS_PENDING = 'pending';
    const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DITOLAK = 'ditolak';

    // Field yang boleh diisi (mass assignable) sesuai kolom database baru
    protected $fillable = [
        'gmail',
        'id_layanan',
        'id_kunjungan', // Ditambahkan agar bisa berelasi dengan tabel kunjungan
        'id_user',
        'topik_konsultasi',
        'waktu_mulai',
        'durasi_menit',
        'link_google_meet',
        'status',
        'keterangan', // Penambahan kolom keterangan (menggantikan alasan_penolakan)
    ];

    /**
     * Relasi ke User (Aktor internal: Admin/Petugas/Pimpinan sebagai pemateri)
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
     * PERBAIKAN: Relasi ke Kunjungan
     * Menambahkan method ini agar PetugasController tidak error saat melakukan with(['kunjungan.tamu'])
     */
    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan', 'id_kunjungan');
    }
}
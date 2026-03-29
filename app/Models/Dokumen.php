<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     * Secara default Laravel akan mencari 'dokumens', jadi kita arahkan ke 'dokumen'.
     */
    protected $table = 'dokumen';

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_dokumen';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'nama_dokumen',
        'file_path',
        'kategori',
        'id_layanan',
        'id_user',
    ];

    /**
     * Relasi ke model Layanan (Inverse dari One-to-Many).
     * Satu dokumen dimiliki oleh satu jenis layanan.
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Relasi ke model User (Inverse dari One-to-Many).
     * Satu dokumen diunggah/dimiliki oleh satu user/admin.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
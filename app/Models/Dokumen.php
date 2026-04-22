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
     * Diarahkan ke 'dokumen' sesuai skema database Anda.
     */
    protected $table = 'dokumen';

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_dokumen';

    /**
     * Menentukan apakah ID merupakan auto-incrementing.
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'int';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Pastikan semua kolom yang diperlukan sudah masuk di sini.
     */
    protected $fillable = [
        'id_layanan',
        'id_user',
        'nama_dokumen',
        'file_path',
        'kategori',
    ];

    /**
     * Casting atribut ke tipe data tertentu.
     * Membantu Laravel mengonversi ID menjadi integer secara otomatis.
     */
    protected $casts = [
        'id_layanan' => 'integer',
        'id_user' => 'integer',
    ];

    /**
     * RELASI: Kebalikan ke model Layanan (Inverse dari One-to-Many).
     * Satu dokumen dimiliki oleh satu jenis layanan.
     */
    public function layanan(): BelongsTo
    {
        // Parameter: ModelTujuan, ForeignKeyDiTabelIni, OwnerKeyDiTabelLayanan
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * RELASI: Kebalikan ke model User (Inverse dari One-to-Many).
     * Satu dokumen diunggah/dikelola oleh satu user/admin.
     */
    public function user(): BelongsTo
    {
        // Parameter: ModelTujuan, ForeignKeyDiTabelIni, OwnerKeyDiTabelUser
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
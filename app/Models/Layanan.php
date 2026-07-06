<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Layanan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     * Menggunakan nama 'layanan' sesuai skema database Anda.
     */
    protected $table = 'layanan';

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_layanan';

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
     */
    protected $fillable = [
        'nama_layanan',
        'deskripsi', 
        'icon',
    ];

    /**
     * Tambahan untuk kebutuhan Testing: Membuka proteksi mass assignment.
     */
    protected $guarded = [];

    /**
     * PERBAIKAN RELASI: Satu layanan bisa memiliki banyak dokumen panduan.
     */
    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'id_layanan', 'id_layanan');
    }

    /**
     * Alias relasi untuk mendukung pemanggilan with('dokumens') di TamuController.
     */
    public function dokumens(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'id_layanan', 'id_layanan');
    }

    /**
     * RELASI: Satu layanan bisa memiliki banyak kunjungan tamu.
     */
    public function kunjungans(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_layanan', 'id_layanan');
    }
}
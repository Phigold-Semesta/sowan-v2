<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'layanan';

    /**
     * Primary key yang digunakan oleh tabel.
     */
    protected $primaryKey = 'id_layanan';

    /**
     * WAJIB TRUE jika di database menggunakan Auto Increment.
     */
    public $incrementing = true; 

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'nama_layanan',
    ];

    /**
     * RELASI: Satu layanan bisa memiliki banyak dokumen panduan.
     * Penjelasan: Ini adalah pasangan dari belongsTo di model Dokumen.
     */
    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'id_layanan', 'id_layanan');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Import trait HasFactory
use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    use HasFactory; // 2. Gunakan trait di dalam class

    // Tentukan nama tabel karena tidak menggunakan jamak (plural)
    protected $table = 'tamu';

    // Atur Primary Key ke kolom 'gmail'
    protected $primaryKey = 'gmail';

    // Beritahu Laravel bahwa PK ini bukan angka auto-increment (karena string)
    public $incrementing = false;

    // Tentukan tipe data PK sebagai string
    protected $keyType = 'string';

    // Daftar kolom yang boleh diisi melalui mass assignment
    protected $fillable = [
        'gmail', 
        'no_wa', 
        'nama_tamu', 
        'jenis_tamu', 
        'nama_instansi', 
        'alamat_kantor', 
        'hadir_sebagai'
    ];
}
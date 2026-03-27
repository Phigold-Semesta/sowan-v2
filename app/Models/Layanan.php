<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan'; // Nama tunggal
    protected $primaryKey = 'id_layanan';
    public $incrementing = false;
    protected $fillable = ['id_layanan', 'nama_layanan'];
}

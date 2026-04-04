<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';

    // WAJIB TRUE jika di database sudah A_I
    public $incrementing = true; 

    protected $fillable = [
        'nama_layanan',
    ];
}
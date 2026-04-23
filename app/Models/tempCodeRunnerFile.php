<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetugasTujuan extends Model
{
    protected $table = 'petugas_tujuan';
    protected $primaryKey = 'id_petugas';
    public $incrementing = false;
    protected $fillable = ['id_petugas', 'nama_petugas', 'jabatan'];
}
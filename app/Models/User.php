<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // 1. Nama tabel sesuai database
    protected $table = 'user';

    // 2. Primary Key kustom
    protected $primaryKey = 'id_user';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'nama_lengkap',
        'username', 
        'password',
        'role',
        'jabatan',
    ];

    /**
     * Atribut yang disembunyikan untuk keamanan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * PENTING: Beritahu Laravel untuk menggunakan 'username' 
     * sebagai kolom identitas saat proses login.
     */
    public function username()
    {
        return 'username';
    }
}
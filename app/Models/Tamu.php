<?php

namespace App\Models;

// PENTING: Menambahkan trait ini agar model bisa digunakan untuk autentikasi (login)
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tamu extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Konfigurasi tabel dan primary key.
     * Menggunakan gmail sebagai primary key string dan menonaktifkan auto-increment.
     */
    protected $table = 'tamu';
    protected $primaryKey = 'gmail';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Properti fillable untuk mass assignment.
     */
    protected $fillable = [
        'gmail', 
        'password', 
        'no_wa', 
        'nama_tamu', 
        'jenis_tamu', 
        'nama_instansi', 
        'alamat_kantor', 
        'hadir_sebagai'
    ];

    /**
     * Sembunyikan password agar tidak terekspos saat model diubah ke array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data untuk keamanan data.
     */
    protected $casts = [
        'gmail'    => 'string',
        'password' => 'hashed',
    ];

    /**
     * PENTING: Memberitahu Laravel bahwa Primary Key untuk autentikasi adalah 'gmail'.
     */
    public function getAuthIdentifierName()
    {
        return 'gmail';
    }

    /**
     * Mengembalikan nilai identifier (gmail) untuk autentikasi.
     */
    public function getAuthIdentifier()
    {
        return $this->gmail;
    }

    /**
     * Pastikan Laravel mengambil kolom password yang benar untuk verifikasi.
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * RELASI: Satu tamu memiliki banyak riwayat kunjungan.
     */
    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'gmail', 'gmail');
    }

    /**
     * RELASI: Satu tamu dapat memberikan rating berkali-kali.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(RatingLayanan::class, 'gmail', 'gmail');
    }
}
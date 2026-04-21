<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    // Nama tabel sesuai database kamu
    protected $table = 'audit_logs';

    // Primary key custom sesuai skema kamu
    protected $primaryKey = 'id_log';

    /**
     * Properti fillable harus mencakup semua kolom yang kita kirim dari Controller
     * agar tidak terkena Mass Assignment Exception atau datanya jadi NULL.
     */
    protected $fillable = [
        'id_user',
        'aktivitas',
        'keterangan',
        'waktu',
        'ip_address', // Ditambahkan agar IP muncul di tabel
        'user_agent', // Ditambahkan untuk pelacakan browser/perangkat
    ];

    /**
     * Menonaktifkan timestamps default Laravel (created_at/updated_at) 
     * jika kamu hanya menggunakan satu kolom 'waktu'.
     * Namun, jika tabelmu punya created_at, biarkan ini menjadi true.
     */
    public $timestamps = true; 

    /**
     * Casting kolom 'waktu' menjadi objek Carbon agar bisa menggunakan 
     * fungsi format() atau diffForHumans() di Blade.
     */
    protected $casts = [
        'waktu' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke User (Siapa yang melakukan aktivitas)
     * Menggunakan id_user sebagai foreign key dan owner key.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Helper untuk mendapatkan warna status atau icon (Opsional)
     * Bisa kamu panggil di Blade jika ingin tampilan lebih mewah.
     */
    public function getStatusColorAttribute()
    {
        return 'emerald';
    }
}
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
        'aktivitas', // Pastikan kolom ini ada agar sesuai dengan logActivity() di Controller
        'keterangan',
        'waktu',
        'ip_address', // Penting: Sudah ditambahkan agar tidak error 1054
        'user_agent', // Penting: Sudah ditambahkan untuk pelacakan browser/perangkat
    ];

    /**
     * Tetap aktifkan timestamps karena Laravel mencoba mengisi created_at dan updated_at
     * sesuai dengan pesan error SQL yang muncul tadi.
     */
    public $timestamps = true; 

    /**
     * Casting kolom menjadi objek Carbon agar bisa menggunakan 
     * fungsi format() atau diffForHumans() di Blade secara instan.
     */
    protected $casts = [
        'waktu' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
     * Bisa kamu panggil di Blade jika ingin tampilan lebih mewah khas SOWAN.
     */
    public function getStatusColorAttribute()
    {
        // Logika warna emerald sesuai branding SOWAN v2
        return 'emerald';
    }

    /**
     * Accessor tambahan untuk memudahkan tampilan waktu yang lebih humanis
     * Contoh penggunaan di blade: $log->format_waktu
     */
    public function getFormatWaktuAttribute()
    {
        return $this->waktu ? $this->waktu->translatedFormat('d F Y (H:i)') . ' WIB' : '-';
    }
}
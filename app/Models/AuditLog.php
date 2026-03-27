<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Import trait
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory; // 2. Gunakan trait

    protected $table = 'audit_logs';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_user',
        'aktivitas',
        'keterangan', // Tambahkan ini agar sesuai dengan Factory
        'waktu',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    /**
     * Relasi ke User (Siapa yang melakukan aktivitas)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
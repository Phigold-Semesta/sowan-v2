<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk tabel rating_layanan sesuai ERD.
     */
    public function up(): void
    {
        Schema::create('rating_layanan', function (Blueprint $table) {
            $table->id('id_rating'); // Primary Key sesuai ERD

            // Foreign Key ke tabel kunjungan
            $table->foreignId('id_kunjungan')
                  ->constrained('kunjungan', 'id_kunjungan')
                  ->onDelete('cascade');

            // Foreign Key ke tabel user (untuk yang menanggapi rating)
            $table->foreignId('id_user')
                  ->nullable() // Nullable jika belum ada tanggapan dari petugas/admin
                  ->constrained('user', 'id_user')
                  ->onDelete('set null');

            $table->integer('skor'); // Sesuai atribut 'skor' di ERD
            $table->text('komentar')->nullable(); // Sesuai atribut 'komentar' di ERD
            $table->text('tanggapan')->nullable(); // Sesuai atribut 'tanggapan' di ERD (untuk balasan admin)
            
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_layanan'); // Nama tabel tunggal tanpa akhiran 's'
    }
};
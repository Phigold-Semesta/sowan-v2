<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk tabel dokumen.
     */
    public function up(): void
    {
        // Menggunakan nama tabel 'dokumen' (tanpa akhiran 's')
        Schema::create('dokumen', function (Blueprint $table) {
            // Primary Key kustom
            $table->id('id_dokumen'); 
            
            // Kolom informasi dokumen
            $table->string('nama_dokumen');
            $table->string('file_path');
            $table->string('kategori')->default('panduan'); 

            /**
             * PERBAIKAN RELASI:
             * 1. id_layanan: Menggunakan integer() tanpa unsigned() jika tabel asal adalah INT standar.
             * 2. id_user: Menggunakan unsignedBigInteger() agar cocok dengan standar Laravel/tabel user.
             *
             */
            $table->integer('id_layanan')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();

            $table->timestamps();

            // Definisi Foreign Key agar integritas data terjaga
            $table->foreign('id_layanan')->references('id_layanan')->on('layanan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
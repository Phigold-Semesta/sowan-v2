<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('kunjungan', function (Blueprint $table) {
        $table->id('id_kunjungan');
        // Foreign Key ke tabel tamu (gmail)
        $table->string('gmail');
        $table->foreign('gmail')->references('gmail')->on('tamu')->onDelete('cascade');
        
        // Foreign Key ke tabel layanan
        $table->integer('id_layanan');
        $table->foreign('id_layanan')->references('id_layanan')->on('layanan');

        // Foreign Key ke tabel petugas_tujuan
        $table->integer('id_petugas');
        $table->foreign('id_petugas')->references('id_petugas')->on('petugas_tujuan');

        $table->dateTime('waktu_masuk');
        $table->dateTime('waktu_keluar')->nullable();
        $table->enum('status', ['Belum Dilayani', 'Sedang Dilayani', 'Sudah Dilayani'])->default('Belum Dilayani');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};

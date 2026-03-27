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
    Schema::create('petugas_tujuan', function (Blueprint $table) {
        $table->integer('id_petugas')->primary();
        $table->string('nama_petugas', 100);
        $table->string('jabatan', 50);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas_tujuan');
    }
};

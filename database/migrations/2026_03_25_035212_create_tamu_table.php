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
    Schema::create('tamu', function (Blueprint $table) {
        $table->string('gmail')->primary();
        $table->string('no_wa', 25);
        $table->string('nama_tamu', 100);
        $table->enum('jenis_tamu', ['Penyedia', 'Non-Penyedia']);
        $table->string('nama_instansi', 100)->nullable();
        $table->text('alamat_kantor');
        $table->string('hadir_sebagai', 50);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamu');
    }
};

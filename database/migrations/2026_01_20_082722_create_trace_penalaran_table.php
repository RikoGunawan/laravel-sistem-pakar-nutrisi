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
        Schema::create('trace_penalaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analisis_nutrisi_id')->constrained('analisis_nutrisi')->onDelete('cascade');

            $table->string('fakta_awal'); // "Makanan: Ayam, Nutrisi Awal: ..."
            $table->string('rule_used'); // Kode rule yang digunakan
            $table->text('proses'); // Deskripsi proses penalaran
            $table->string('fakta_baru'); // Hasil setelah rule diterapkan

            $table->integer('step_order')->default(1); // Urutan langkah

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trace_penalaran');
    }
};

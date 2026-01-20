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
        Schema::create('analisis_nutrisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('makanan_id')->constrained('makanan')->onDelete('cascade');

            // Data nutrisi mentah (copy dari makanan)
            $table->json('nutrisi_mentah');

            // Hasil komparasi semua metode
            $table->json('hasil_komparasi')->nullable();

            // Summary & insights
            $table->text('summary')->nullable();
            $table->string('metode_terbaik')->nullable();

            // User info (optional, untuk tracking)
            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_nutrisi');
    }
};

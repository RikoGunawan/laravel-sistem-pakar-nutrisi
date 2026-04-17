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
        Schema::create('analisis_metode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analisis_nutrisi_id')->constrained('analisis_nutrisi')->onDelete('cascade');
            $table->foreignId('metode_pengolahan_id')->constrained('metode_pengolahan')->onDelete('cascade');

            //  UPDATE: Kolom rule_id (nullable)
            $table->foreignId('rule_id')
                ->nullable()
                ->constrained('rules')
                ->onDelete('set null');

            // Hasil perhitungan nutrisi setelah diolah
            $table->json('nutrisi_hasil');
            $table->json('perubahan_persen');

            $table->timestamps();

            $table->unique(['analisis_nutrisi_id', 'metode_pengolahan_id'], 'unique_analisis_metode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_metode');
    }
};

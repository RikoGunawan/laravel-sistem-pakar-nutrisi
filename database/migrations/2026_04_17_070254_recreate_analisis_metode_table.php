<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop tabel lama
        Schema::dropIfExists('analisis_metode');

        // Buat ulang dengan rule_id nullable
        Schema::create('analisis_metode', function (Blueprint $table) {
            $table->id();

            $table->foreignId('analisis_nutrisi_id')
                  ->constrained('analisis_nutrisi')
                  ->onDelete('cascade');

            $table->foreignId('metode_pengolahan_id')
                  ->constrained('metode_pengolahan')
                  ->onDelete('cascade');

            // Kolom rule_id yang kita inginkan (nullable)
            $table->foreignId('rule_id')
                  ->nullable()
                  ->constrained('rules')
                  ->onDelete('set null');

            $table->json('nutrisi_hasil');
            $table->json('perubahan_persen');

            $table->timestamps();

            // Index
            $table->index(['analisis_nutrisi_id', 'metode_pengolahan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis_metode');
    }
};

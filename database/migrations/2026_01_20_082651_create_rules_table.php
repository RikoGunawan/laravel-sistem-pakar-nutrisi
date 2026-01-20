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
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metode_pengolahan_id')->constrained('metode_pengolahan')->onDelete('cascade');
            $table->string('kode_rule')->unique(); // FR1, BL1, ST1, GR1, SF1

            // Kondisi IF (untuk forward chaining)
            $table->text('kondisi'); // "IF menggoreng"

            // Perubahan nutrisi (THEN) dalam persen
            $table->json('perubahan_nutrisi'); // {"protein": 0, "lemak": 35, "vitamin_c": -20}

            // Prioritas rule
            $table->integer('prioritas')->default(1);

            // Penjelasan mengapa terjadi perubahan
            $table->text('penjelasan')->nullable();

            // Sumber referensi
            $table->text('sumber_referensi')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('metode_pengolahan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};

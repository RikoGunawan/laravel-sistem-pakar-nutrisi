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
        Schema::create('metode_pengolahan', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Goreng, Kukus, Rebus, Bakar, Tumis
            $table->string('icon')->nullable(); // emoji atau path icon
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metode_pengolahan');
    }
};

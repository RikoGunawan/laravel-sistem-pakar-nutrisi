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
        Schema::create('informasi_gizi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori'); // tips, fakta
            $table->text('konten');
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('sumber')->nullable(); 
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi_gizi');
    }
};

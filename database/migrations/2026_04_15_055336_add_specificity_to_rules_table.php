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
        Schema::table('rules', function (Blueprint $table) {
            Schema::table('rules', function (Blueprint $table) {
            // Kolom untuk rule spesifik satu makanan
            $table->foreignId('makanan_id')
                  ->nullable()
                  ->constrained('makanan')
                  ->onDelete('cascade')
                  ->after('kode_rule');

            $table->string('kategori')->nullable()->after('kode_rule');

            $table->index(['metode_pengolahan_id', 'makanan_id']);
            $table->index(['metode_pengolahan_id', 'kategori']);
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rules', function (Blueprint $table) {
            $table->dropForeign(['makanan_id']);
            $table->dropColumn(['makanan_id', 'kategori']);
        });
    }
};

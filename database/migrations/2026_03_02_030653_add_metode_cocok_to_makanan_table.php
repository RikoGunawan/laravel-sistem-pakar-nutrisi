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
        Schema::table('makanan', function (Blueprint $table) {
            // Array ID metode pengolahan yang cocok untuk makanan ini
            $table->json('metode_cocok')->nullable()->after('sumber_data');

            // Catatan khusus jika ada
            $table->text('catatan_pengolahan')->nullable()->after('metode_cocok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makanan', function (Blueprint $table) {
            $table->dropColumn(['metode_cocok', 'catatan_pengolahan']);
        });
    }
};

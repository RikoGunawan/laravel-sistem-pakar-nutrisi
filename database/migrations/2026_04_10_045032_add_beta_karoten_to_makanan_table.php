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
            $table->decimal('beta_karoten', 10, 2)
                  ->nullable()
                  ->default(null)
                  ->after('vitamin_a');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makanan', function (Blueprint $table) {
            $table->dropColumn('beta_karoten');
        });
    }
};

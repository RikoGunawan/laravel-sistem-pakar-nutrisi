<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('makanan', function (Blueprint $table) {

            $table->decimal('protein', 8, 3)->nullable()->change();
            $table->decimal('lemak', 8, 3)->nullable()->change();
            $table->decimal('karbohidrat', 8, 3)->nullable()->change();
            $table->decimal('kalori', 8, 2)->nullable()->change();
            $table->decimal('serat', 8, 3)->nullable()->change();
            $table->decimal('gula', 8, 3)->nullable()->change();

            $table->decimal('vitamin_a', 10, 3)->nullable()->change();
            $table->decimal('vitamin_b1', 8, 4)->nullable()->change();
            $table->decimal('vitamin_b2', 8, 4)->nullable()->change();
            $table->decimal('vitamin_b3', 8, 3)->nullable()->change();
            $table->decimal('vitamin_b5', 8, 3)->nullable()->change();
            $table->decimal('vitamin_b6', 8, 3)->nullable()->change();
            $table->decimal('vitamin_b12', 10, 4)->nullable()->change();
            $table->decimal('vitamin_c', 8, 3)->nullable()->change();

            $table->decimal('natrium', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('makanan', function (Blueprint $table) {
            $table->decimal('protein', 8, 2)->default(0)->change();
            $table->decimal('lemak', 8, 2)->default(0)->change();
            $table->decimal('karbohidrat', 8, 2)->default(0)->change();
            $table->decimal('kalori', 8, 2)->default(0)->change();
            $table->decimal('serat', 8, 2)->default(0)->change();
            $table->decimal('gula', 8, 2)->default(0)->change();

            $table->decimal('vitamin_a', 8, 2)->default(0)->change();
            $table->decimal('vitamin_b1', 8, 2)->default(0)->change();
            $table->decimal('vitamin_b2', 8, 2)->default(0)->change();
            $table->decimal('vitamin_b3', 8, 2)->default(0)->change();
            $table->decimal('vitamin_b5', 8, 2)->default(0)->change();
            $table->decimal('vitamin_b6', 8, 2)->default(0)->change();
            $table->decimal('vitamin_b12', 8, 2)->default(0)->change();
            $table->decimal('vitamin_c', 8, 2)->default(0)->change();

            $table->decimal('natrium', 8, 2)->default(0)->change();
        });
    }
};

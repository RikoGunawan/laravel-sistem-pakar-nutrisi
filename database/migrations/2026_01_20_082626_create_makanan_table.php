<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('makanan', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kategori'); // Protein, Sayuran, Karbohidrat, dll
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('sumber_data')->default('USDA Data Central'); // USDA, TKPI, dll

            // Nutrisi per 100 gram (mentah)
            $table->decimal('protein', 8, 2)->default(0); // gram
            $table->decimal('lemak', 8, 2)->default(0); // gram
            $table->decimal('karbohidrat', 8, 2)->default(0); // gram
            $table->decimal('kalori', 8, 2)->default(0); // kkal
            $table->decimal('serat', 8, 2)->default(0); // gram
            $table->decimal('gula', 8, 2)->default(0); // gram

            // Vitamin (dalam mg atau IU, disimpan sebagai decimal)
            $table->decimal('vitamin_a', 8, 2)->default(0);
            $table->decimal('vitamin_b1', 8, 2)->default(0);
            $table->decimal('vitamin_b2', 8, 2)->default(0);
            $table->decimal('vitamin_b3', 8, 2)->default(0);
            $table->decimal('vitamin_b5', 8, 2)->default(0);
            $table->decimal('vitamin_b6', 8, 2)->default(0);
            $table->decimal('vitamin_b12', 8, 2)->default(0);
            $table->decimal('vitamin_c', 8, 2)->default(0);

            // Mineral
            $table->decimal('natrium', 8, 2)->default(0); // mg

            $table->timestamps();

            // Indexes
            $table->index('kategori');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makanan');
    }
};

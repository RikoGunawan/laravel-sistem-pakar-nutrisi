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
        Schema::dropIfExists('trace_penalaran');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('trace_penalaran', function (Blueprint $table) {
        $table->id();
        $table->foreignId('analisis_nutrisi_id')->constrained()->cascadeOnDelete();
        $table->text('fakta_awal')->nullable();
        $table->string('rule_used')->nullable();
        $table->text('proses')->nullable();
        $table->text('fakta_baru')->nullable();
        $table->integer('step_order')->default(0);
        $table->timestamps();
    });
    }
};

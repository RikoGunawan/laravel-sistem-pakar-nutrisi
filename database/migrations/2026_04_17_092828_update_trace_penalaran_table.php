<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trace_penalaran', function (Blueprint $table) {
            $table->text('fakta_awal')->change();
            $table->text('fakta_baru')->change();
        });
    }

    public function down(): void
    {
        Schema::table('trace_penalaran', function (Blueprint $table) {
            $table->string('fakta_awal')->change();
            $table->string('fakta_baru')->change();
        });
    }
};
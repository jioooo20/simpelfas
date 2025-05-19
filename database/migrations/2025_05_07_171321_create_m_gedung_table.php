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
        Schema::create('m_gedung', function (Blueprint $table) {
            $table->id('gedung_id');
            $table->string('gedung_kode', 10)->unique();
            $table->string('gedung_nama', 100);
            $table->string('gedung_keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_gedung');
    }
};

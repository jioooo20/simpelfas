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
        Schema::create('m_lantai', function (Blueprint $table) {
            $table->id('lantai_id');
            $table->unsignedBigInteger('gedung_id')->index();
            $table->string('lantai_kode', 10)->unique();
            $table->string('lantai_nama', 100);
            $table->string('lantai_deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('gedung_id')->references('gedung_id')->on('m_gedung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_lantai');
    }
};

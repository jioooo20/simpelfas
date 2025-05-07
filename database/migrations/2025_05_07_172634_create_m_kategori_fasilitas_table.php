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
        Schema::create('m_kategori_fasilitas', function (Blueprint $table) {
            $table->id('kategori_fasilitas_id');
            $table->string('kategori_fasilitas_kode', 10)->unique();
            $table->string('kategori_fasilitas_nama', 100);
            $table->string('kategori_fasilitas_deskripsi')->nullable();    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kategori_fasilitas');
    }
};

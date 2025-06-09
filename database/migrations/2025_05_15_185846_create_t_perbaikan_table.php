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
        Schema::create('t_perbaikan', function (Blueprint $table) {
            $table->id('perbaikan_id');
            $table->unsignedBigInteger('pelaporan_id')->index();
            $table->string('perbaikan_kode', 30)->unique();
            $table->string('perbaikan_deskripsi', 255);
            $table->timestamps();

            $table->foreign('pelaporan_id')->references('pelaporan_id')->on('m_pelaporan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_perbaikan');
    }
};

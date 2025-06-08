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
        Schema::create('t_status_pelaporan', function (Blueprint $table) {
            $table->id('status_pelaporan_id');
            $table->unsignedBigInteger('pelaporan_id')->index();
            $table->enum('status_pelaporan', ['Menunggu','Diterima', 'Diproses', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->timestamps();

            $table->foreign('pelaporan_id')->references('pelaporan_id')->on('m_pelaporan');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_status_pelaporan');
    }
};

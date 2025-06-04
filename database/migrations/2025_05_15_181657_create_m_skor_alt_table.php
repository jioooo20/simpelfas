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
        Schema::create('m_skor_alt', function (Blueprint $table) {
            $table->id('skor_alt_id');
            $table->string('skor_alt_kode', 10);
            $table->unsignedBigInteger('pelaporan_id')->index();
            $table->unsignedBigInteger('kriteria_id')->index();
            $table->double('nilai_skor');
            $table->timestamps();

            $table->foreign('pelaporan_id')->references('pelaporan_id')->on('m_pelaporan');
            $table->foreign('kriteria_id')->references('kriteria_id')->on('m_kriteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_skor_alt');
    }
};

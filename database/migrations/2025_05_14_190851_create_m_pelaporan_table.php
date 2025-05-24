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
        Schema::create('m_pelaporan', function (Blueprint $table) {
            $table->id('pelaporan_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('fasilitas_id')->index();
            $table->string('pelaporan_kode', 10)->unique();
            $table->text('pelaporan_deskripsi');
            $table->mediumText("pelaporan_gambar")->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('fasilitas_id')->references('fasilitas_id')->on('t_fasilitas');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_pelaporan');
    }
};

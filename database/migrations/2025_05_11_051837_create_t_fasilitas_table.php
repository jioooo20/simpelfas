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
        Schema::create('t_fasilitas', function (Blueprint $table) {
            $table->id('fasilitas_id');
            $table->unsignedBigInteger('ruang_id')->index();
            $table->unsignedBigInteger('barang_id')->index();
            $table->string('fasilitas_kode', 20)->unique();
            $table->enum('fasilitas_status', ['Baik', 'Dalam Perbaikan', 'Rusak'])->default('Baik');
            $table->timestamps();

            $table->foreign('ruang_id')->references('ruang_id')->on('m_ruang');
            $table->foreign('barang_id')->references('barang_id')->on('m_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_fasilitas', function (Blueprint $table) {
            $table->dropForeign(['ruang_id']);
        });

        Schema::dropIfExists('t_fasilitas');
    }
};

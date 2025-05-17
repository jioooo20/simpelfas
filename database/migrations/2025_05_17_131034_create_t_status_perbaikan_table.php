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
        Schema::create('t_status_perbaikan', function (Blueprint $table) {
            $table->id('status_perbaikan_id');
            $table->unsignedBigInteger('perbaikan_id')->index();
            $table->mediumText("perbaikan_gambar")->nullable();
            $table->enum('perbaikan_status', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->timestamps();

            $table->foreign('perbaikan_id')->references('perbaikan_id')->on('t_perbaikan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_status_perbaikan');
    }
};

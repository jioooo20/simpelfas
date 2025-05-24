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
        Schema::create('t_gdss_result', function (Blueprint $table) {
            $table->id('gdss__id');
            $table->string('gdss_kode', 30)->unique(); //format angka dtk-mnt-jam-day-bln-thn
            $table->unsignedBigInteger('pelaporan_id')->index();
            $table->integer('nilai_skor');
            $table->integer('rank');
            $table->timestamps();

            $table->foreign('pelaporan_id')->references('pelaporan_id')->on('t_pelaporan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_gdss_result');
    }
};

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
        Schema::create('t_perbaikan_petugas', function (Blueprint $table) {
            $table->id('perbaikan_petugas_id');
            $table->unsignedBigInteger('perbaikan_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();

            $table->foreign('perbaikan_id')->references('perbaikan_id')->on('t_perbaikan');
            $table->foreign('user_id')->references('user_id')->on('m_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_perbaikan_petugas');
    }
};

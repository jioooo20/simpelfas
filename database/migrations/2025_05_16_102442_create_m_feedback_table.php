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
        Schema::create('m_feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->unsignedBigInteger('pelaporan_id')->index();
            $table->string('feedback_text', 1000)->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();

            $table->foreign('pelaporan_id')->references('pelaporan_id')->on('m_pelaporan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_feedback');
    }
};

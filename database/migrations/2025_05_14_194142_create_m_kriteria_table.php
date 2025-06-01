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
        Schema::create('m_kriteria', function (Blueprint $table) {
            $table->id('kriteria_id');
            $table->string('kriteria_kode', 10)->unique();
            $table->string('kriteria_nama', 50);
            $table->enum('kriteria_jenis', ['Benefit', 'Cost']);
            $table->float('w1_mhs');
            $table->float('w2_dsn');
            $table->float('w3_stf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kriteria');
    }
};

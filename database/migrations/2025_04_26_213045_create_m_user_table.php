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
        Schema::create('m_user', function (Blueprint $table) {
            $table->id("user_id");
            $table->unsignedBigInteger("role_id")->index();//indexing untuk fk
            $table->string("identitas", 32)->unique();//uniq agar tidak ada yg sama
           $table->string('profile_image')->nullable();
            $table->string("nama", 50);
            $table->string("password");
            $table->string("email");
            $table->timestamps();


            $table->foreign('role_id')->references('role_id')->on('m_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user');
    }
};

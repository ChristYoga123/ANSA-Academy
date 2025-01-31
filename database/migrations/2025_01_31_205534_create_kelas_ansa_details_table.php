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
        Schema::create('kelas_ansa_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_ansa_id')->constrained('programs')->cascadeOnDelete();
            $table->dateTime('waktu_open_registrasi');
            $table->dateTime('waktu_close_registrasi');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->text('link_meet');
            $table->unsignedInteger('kuota');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_ansa_details');
    }
};

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
        Schema::create('lombas', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique();
            $table->string('slug')->unique();
            $table->string('penyelenggara');
            $table->longText('deskripsi');
            $table->dateTime('waktu_open_registrasi');
            $table->dateTime('waktu_close_registrasi');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->text('link_pendaftaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lombas');
    }
};

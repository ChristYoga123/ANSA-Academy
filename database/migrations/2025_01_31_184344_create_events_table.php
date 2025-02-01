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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique();
            $table->string('slug')->unique();
            $table->longText('deskripsi');
            $table->enum('pricing', ['gratis', 'berbayar']);
            $table->unsignedBigInteger('harga')->nullable();
            $table->enum('tipe', ['online', 'offline']);
            $table->text('link_meet')->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('waktu_open_registrasi');
            $table->dateTime('waktu_close_registrasi');
            $table->unsignedBigInteger('kuota');
            $table->text('link_resource')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

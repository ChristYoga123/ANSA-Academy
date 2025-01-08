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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();
            $table->string('judul_program')->unique();
            $table->string('slug')->unique();
            $table->string('judul_kegiatan')->unique();
            $table->longText('konten');
            $table->double('lat');
            $table->double('long');
            $table->dateTime('open_regis_panitia');
            $table->dateTime('close_regis_panitia');
            $table->text('gform_panitia');
            $table->dateTime('open_regis_peserta');
            $table->dateTime('close_regis_peserta');
            $table->text('gform_peserta');
            $table->json('jadwal_kegiatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};

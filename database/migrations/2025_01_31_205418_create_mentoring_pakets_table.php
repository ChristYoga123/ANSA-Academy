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
        Schema::create('mentoring_pakets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentoring_id')->constrained('programs')->cascadeOnDelete();
            $table->string('label');
            $table->enum('jenis', ['Pemula', 'Lanjutan']);
            $table->unsignedInteger('jumlah_pertemuan');
            $table->unsignedBigInteger('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_pakets');
    }
};

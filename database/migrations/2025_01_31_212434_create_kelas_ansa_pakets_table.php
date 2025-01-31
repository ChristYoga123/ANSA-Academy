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
        Schema::create('kelas_ansa_pakets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_ansa_id')->constrained('programs')->cascadeOnDelete();
            $table->string('label');
            $table->unsignedBigInteger('harga');
            $table->text('link_resource')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_ansa_pakets');
    }
};

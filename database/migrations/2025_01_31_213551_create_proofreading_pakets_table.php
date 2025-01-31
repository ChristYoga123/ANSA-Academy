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
        Schema::create('proofreading_pakets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proofreading_id')->constrained('programs')->cascadeOnDelete();
            $table->string('label');
            $table->unsignedInteger('hari_pengerjaan');
            $table->unsignedInteger('lembar_minimum');
            $table->unsignedInteger('lembar_maksimum');
            $table->unsignedBigInteger('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proofreading_pakets');
    }
};

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
        Schema::create('loker_mentor_bidang_kualifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loker_mentor_bidang_id')->constrained()->cascadeOnDelete();
            $table->text('kualifikasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loker_mentor_bidang_kualifikasis');
    }
};

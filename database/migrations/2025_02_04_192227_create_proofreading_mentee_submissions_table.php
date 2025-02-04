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
        Schema::create('proofreading_mentee_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proofreading_mentee_id')->constrained('program_mentees')->cascadeOnDelete();
            $table->boolean('is_selesai')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proofreading_mentee_submissions');
    }
};

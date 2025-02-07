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
        Schema::create('mentoring_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentoring_mentee_id')->nullable()->constrained('program_mentees')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->string('jadwal');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->text('link_meet')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->boolean('is_selesai')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentoring_jadwals');
    }
};

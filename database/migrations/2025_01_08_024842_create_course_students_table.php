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
        Schema::create('course_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('murid_id');
            $table->boolean('status_penyelesaian')->default(false);
            $table->timestamps();

            $table->foreign('murid_id')->references('id')->on('users')->restrictOnDelete();
            $table->unique(['course_id', 'murid_id'], 'course_student_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_students');
    }
};

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_student_id')->restrictOnDelete();
            $table->string('order_id')->unique();
            $table->text('snap_midtrans_token')->nullable();
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedInteger('persentase_diskon')->default(0);
            $table->unsignedBigInteger('total_harga');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();

            $table->foreign('discount_id')->references('id')->on('discounts');
            $table->unique(['course_student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

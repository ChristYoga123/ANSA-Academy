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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('mentee_id')->constrained('users')->cascadeOnDelete();
            $table->string('transaksiable_type');
            $table->unsignedBigInteger('transaksiable_id');
            $table->string('referral_code')->nullable();
            $table->unsignedBigInteger('total_harga');
            $table->enum('status', ['Menunggu', 'Sukses', 'Dibatalkan'])->default('Menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->unsignedInteger('persentase');
            $table->enum('tipe', ['dibatasi_tanggal', 'dibatasi_penggunaan']);
            $table->dateTime('tanggal_kadaluarsa')->nullable();
            $table->unsignedInteger('batas_penggunaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};

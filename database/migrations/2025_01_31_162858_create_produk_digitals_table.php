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
        Schema::create('produk_digitals', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->unique();
            $table->string('slug')->unique();
            $table->longText('deskripsi');
            $table->enum('platform', ['file', 'url']);
            $table->string('url')->nullable();
            $table->unsignedBigInteger('harga');
            $table->boolean('is_unlimited')->default(true);
            $table->unsignedInteger('qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_digitals');
    }
};

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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mentor_id');
            $table->enum('kategori', ['Programming', 'Cyber Security', 'Design', 'Digital Marketing']);
            $table->string('judul')->unique();
            $table->string('slug')->unique();
            $table->enum('level', ['Semua Level', 'Pemula', 'Menengah', 'Mahir']);
            $table->enum('tipe', ['Gratis', 'Berbayar']);
            $table->unsignedBigInteger('harga')->default(0);
            $table->longText('konten');
            $table->boolean('is_draft')->default(true);
            $table->text('drive_url')->nullable();
            $table->timestamps();

            $table->foreign('mentor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

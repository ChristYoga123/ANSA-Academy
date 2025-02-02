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
        Schema::create('web_resources', function (Blueprint $table) {
            $table->id();
            $table->longText('visi');
            $table->longText('tentang');
            $table->string('youtube_url');
            $table->string('quote');
            $table->json('faqs');
            $table->json('media_sosial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_resources');
    }
};

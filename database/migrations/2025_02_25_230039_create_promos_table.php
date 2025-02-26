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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['kupon', 'produk', 'kategori']);

            // Kupon
            $table->string('kode')->nullable()->unique(); // Untuk kupon
            $table->date('tanggal_berakhir')->nullable(); // Tanggal berakhir
            
            // Produk / Kategori
            $table->string('promoable_type')->nullable(); // Model class (App\Models\Program, App\Models\ProdukDigital, App\Models\Event)
            $table->unsignedBigInteger('promoable_id')->nullable(); // ID dari model terkait
            $table->enum('kategori', ['Mentoring', 'Kelas ANSA', 'Proofreading', 'Produk Digital', 'Event'])->nullable(); // Untuk kategori
            
            // Pricing
            $table->unsignedInteger('persentase'); // Persentase

            // Status
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            
            // Index untuk polymorphic relation
            $table->index(['promoable_type', 'promoable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};

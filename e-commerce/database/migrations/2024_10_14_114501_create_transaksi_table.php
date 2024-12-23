<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id(); // Kolom ID sebagai primary key
            $table->unsignedBigInteger('produk_id'); // Foreign key untuk produk
            $table->unsignedBigInteger('pelanggan_id'); // Foreign key untuk pelanggan
            $table->integer('jumlah'); // Jumlah produk yang dibeli
            $table->decimal('total_harga', 10, 2); // Total harga transaksi
            $table->timestamps(); // Kolom created_at dan updated_at
    
            // Menambahkan foreign key constraint untuk produk
            $table->foreign('produk_id')->references('id')->on('products')->onDelete('cascade');
            
            // Menambahkan foreign key constraint untuk pelanggan
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('cascade');
            
            $table->index('produk_id');
            $table->index('pelanggan_id');
            $table->index('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};

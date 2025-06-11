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
        Schema::create('movement_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // Tipe pergerakan stok: initial_stock, purchase, sale, adjustment, return
            $table->string('type');

            // Jumlah barang yang bergerak. Bisa positif (masuk) atau negatif (keluar).
            $table->integer('quantity');

            // Stok produk SETELAH pergerakan ini terjadi
            $table->integer('stock_after');

            // Untuk menautkan ke sumber transaksi (mis: ID nota penjualan atau ID pesanan pembelian)
            $table->nullableMorphs('reference');

            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_stocks');
    }
};

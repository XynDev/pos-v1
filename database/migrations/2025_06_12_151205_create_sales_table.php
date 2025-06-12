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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->comment('Kasir yang bertugas')->constrained('users')->onDelete('cascade');
            // Customer bisa null jika penjualan dilakukan tanpa data pelanggan
            $table->foreignId('customer_id')->nullable();

            $table->bigInteger('total_amount')->comment('Total harga produk sebelum diskon/pajak');
            $table->bigInteger('discount_amount')->default(0);
            $table->bigInteger('tax_amount')->default(0);
            $table->bigInteger('final_amount')->comment('Total akhir yang harus dibayar');

            $table->string('payment_method');
            $table->bigInteger('amount_paid');
            $table->bigInteger('change_due');

            // Status: completed, refunded, void
            $table->string('status')->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

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
        Schema::create('cashier_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('Kasir yang bertugas')->constrained('users');

            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->bigInteger('start_balance')->comment('Modal awal di laci kas');
            $table->bigInteger('end_balance')->nullable()->comment('Uang fisik di laci saat tutup');

            $table->bigInteger('calculated_sales')->nullable()->comment('Total penjualan tunai dari sistem');
            $table->bigInteger('difference')->nullable()->comment('Selisih antara uang fisik dan sistem');

            $table->string('status')->default('open'); // open, closed
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashier_sessions');
    }
};

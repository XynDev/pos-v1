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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique()->comment('Nomor Purchase Order');
            $table->foreignId('supplier_id')->constrained('management_suppliers')->onDelete('cascade');
            $table->foreignId('user_id')->comment('User yang membuat PO')->constrained('users')->onDelete('cascade');
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();

            // Status: pending, processing, shipped, completed, cancelled
            $table->string('status')->default('pending');

            $table->bigInteger('total_amount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->comment('Stock Keeping Unit');
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->bigInteger('purchase_price')->default(0)->comment('Harga Beli');
            $table->bigInteger('selling_price')->default(0)->comment('Harga Jual');

            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);

            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');

            $table->string('barcode')->nullable();
            $table->string('internal_code')->unique()->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

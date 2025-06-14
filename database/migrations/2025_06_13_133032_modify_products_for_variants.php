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
        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->default('simple')->after('id');
            $table->foreignId('parent_id')->nullable()->constrained('products')->onDelete('cascade')->after('type');
            $table->string('sku')->nullable()->change();
            $table->bigInteger('purchase_price')->nullable()->change();
            $table->bigInteger('selling_price')->nullable()->change();
            $table->integer('stock')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn('type');
        });
    }
};

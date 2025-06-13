<?php

namespace App\Models\Purchase;

use App\Models\ManagementProduct\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItems extends Model
{
    use HasFactory;

    /**
     * FIX: Mendefinisikan nama tabel secara eksplisit.
     * Ini adalah baris yang Anda pilih, dan ini adalah langkah yang benar.
     */
    protected $table = 'purchase_order_items';

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function purchaseOrder(): BelongsTo
    {
        /**
         * FIX: Mendefinisikan foreign key ('purchase_order_id') secara eksplisit.
         */
        return $this->belongsTo(PurchaseOrders::class, 'purchase_order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

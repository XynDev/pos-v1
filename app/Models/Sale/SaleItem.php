<?php

namespace App\Models\Sale;

use App\Models\ManagementProduct\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    // Sebuah item penjualan dimiliki oleh satu Penjualan utama
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    // Sebuah item penjualan merujuk ke satu Produk
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models\ManagementProduct;

use App\Models\Branch\Location;
use App\Models\Sale\SaleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'sku',
        'description',
        'image',
        'purchase_price',
        'selling_price',
        'stock',
        'is_active',
        'category_id',
        'brand_id',
        'barcode',
        'internal_code'
    ];

    protected static function boot()
    {
        parent::boot();

        // Otomatis buat kode internal unik saat produk baru dibuat
        static::creating(static function ($product) {
            if (empty($product->internal_code)) {
                $lastProduct = static::orderBy('id', 'desc')->first();
                $nextId = $lastProduct ? $lastProduct->id + 1 : 1;
                $product->internal_code = 'PRODUCT-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function variantDetail(): HasOne
    {
        return $this->hasOne(ProductVariant::class);
    }

    public function bundleComponents(): HasMany
    {
        return $this->hasMany(ProductBundle::class, 'bundle_product_id');
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class)
            ->withPivot('stock')
            ->withTimestamps();
    }
}

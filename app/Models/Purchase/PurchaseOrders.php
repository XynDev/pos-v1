<?php

namespace App\Models\Purchase;

use App\Models\Supplier\ManagementSupplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrders extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'order_date',
        'expected_delivery_date',
        'status',
        'total_amount',
        'notes',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(ManagementSupplier::class, 'supplier_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        /**
         * FIX: Mendefinisikan foreign key ('purchase_order_id') secara eksplisit.
         * Laravel mungkin akan mencari 'purchase_orders_id' jika tidak didefinisikan.
         */
        return $this->hasMany(PurchaseOrderItems::class, 'purchase_order_id');
    }
}

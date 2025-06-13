<?php

namespace App\Models\Sale;

use App\Models\Crm\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    // Karena nama model singular (Sale), Laravel otomatis tahu nama tabelnya 'sales'

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_id',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'final_amount',
        'payment_method',
        'amount_paid',
        'change_due',
        'status',
        'notes',
    ];

    // Sebuah penjualan dilakukan oleh satu User (Kasir)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Sebuah penjualan bisa dimiliki oleh satu Customer
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Sebuah penjualan memiliki banyak item produk
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}

<?php

namespace App\Models\Crm;

use App\Models\Sale\Sale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    // Seorang customer bisa memiliki banyak transaksi penjualan
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}

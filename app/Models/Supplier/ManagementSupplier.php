<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementSupplier extends Model
{
    use HasFactory;
    protected $table = 'management_suppliers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'contact_person',
    ];
}

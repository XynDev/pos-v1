<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\Controller;
use App\Models\Sale\Sale;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function showSaleReceipt(Sale $sale)
    {
        // Muat relasi yang dibutuhkan
        $sale->load(['user', 'customer', 'items.product']);

        // Anda bisa menambahkan detail toko di sini dari config atau database
        $storeDetails = [
            'name' => 'TOKO ANDA',
            'address' => 'Jl. Raya Sejahtera No. 123, Kota Bahagia',
            'phone' => '0812-3456-7890',
            'footer_note' => 'Terima kasih telah berbelanja!',
        ];

        return view('receipts.sale-receipt', compact('sale', 'storeDetails'));
    }
}

<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\Controller;
use App\Models\Sale\Sale;
use App\Models\Setting\Setting;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function showSaleReceipt(Sale $sale)
    {
        // Muat relasi yang dibutuhkan
        $sale->load(['user', 'customer', 'items.product']);

        // Anda bisa menambahkan detail toko di sini dari config atau database
        $settings = Setting::all()->keyBy('key');

        $storeDetails = [
            'name' => $settings->get('store_name')->value ?? 'TOKO ANDA',
            'address' => $settings->get('store_address')->value ?? 'Alamat Toko Anda',
            'phone' => $settings->get('store_phone')->value ?? 'Telepon Toko Anda',
            'footer_note' => $settings->get('receipt_footer_note')->value ?? 'Terima kasih telah berbelanja!',
            'logo' => $settings->get('store_logo')->value ?? null,
        ];

        return view('receipts.sale-receipt', compact('sale', 'storeDetails'));
    }
}

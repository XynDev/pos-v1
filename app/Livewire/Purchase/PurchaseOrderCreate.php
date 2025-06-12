<?php

namespace App\Livewire\Purchase;

use App\Models\ManagementProduct\Product;
use App\Models\Purchase\PurchaseOrders;
use App\Models\Supplier\ManagementSupplier;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseOrderCreate extends Component
{
    // Data Utama PO
    public $supplier_id;
    public $order_date;
    public $notes;
    public $status = 'pending';

    // Item Pesanan
    public array $orderItems = [];

    // Fungsionalitas Pencarian Produk
    public string $searchQuery = '';
    public $searchResults = [];

    // Total
    public int $grandTotal = 0;

    protected $rules = [
        'supplier_id' => 'required|exists:management_suppliers,id',
        'order_date' => 'required|date',
        'orderItems' => 'required|array|min:1',
    ];

    protected $messages = [
        'supplier_id.required' => 'Pemasok harus dipilih.',
        'order_date.required' => 'Tanggal pesanan harus diisi.',
        'orderItems.required' => 'Pesanan harus memiliki setidaknya satu produk.',
    ];

    public function mount(): void
    {
        // Set tanggal hari ini sebagai default
        $this->order_date = now()->format('Y-m-d');
    }

    public function updatedSearchQuery(): void
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->searchResults = Product::where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
                ->where('is_active', true)
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addProduct(Product $product): void
    {
        // Cek apakah produk sudah ada di keranjang
        if (isset($this->orderItems[$product->id])) {
            $this->orderItems[$product->id]['quantity']++;
        } else {
            $this->orderItems[$product->id] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'quantity' => 1,
                'price' => $product->purchase_price, // Ambil harga beli default
            ];
        }

        $this->searchQuery = '';
        $this->searchResults = [];
        $this->calculateGrandTotal();
    }

    public function removeProduct($productId): void
    {
        unset($this->orderItems[$productId]);
        $this->calculateGrandTotal();
    }

    // Dipanggil setiap kali kuantitas atau harga diubah dari view
    public function updatedOrderItems(): void
    {
        $this->calculateGrandTotal();
    }

    public function calculateGrandTotal(): void
    {
        $this->grandTotal = 0;
        foreach ($this->orderItems as $item) {
            $quantity = is_numeric($item['quantity']) ? (int)$item['quantity'] : 0;
            $price = is_numeric($item['price']) ? (int)$item['price'] : 0;
            $this->grandTotal += $quantity * $price;
        }
    }

    public function saveOrder()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // 1. Buat PO Utama
                $purchaseOrder = PurchaseOrders::create([
                    'po_number' => 'PO-' . now()->timestamp,
                    'supplier_id' => $this->supplier_id,
                    'user_id' => Auth::id(),
                    'order_date' => $this->order_date,
                    'status' => $this->status,
                    'total_amount' => $this->grandTotal,
                    'notes' => $this->notes,
                ]);

                // 2. Buat Item-item PO
                foreach ($this->orderItems as $item) {
                    $purchaseOrder->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ]);
                }
            });

            session()->flash('message', 'Pesanan Pembelian berhasil dibuat.');
            return redirect()->route('purchases.orders');

        } catch (Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan pesanan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $suppliers = ManagementSupplier::all();

        return view('livewire.purchase.purchase-order-create', [
            'suppliers' => $suppliers
        ])->layout('layouts.app');
    }
}

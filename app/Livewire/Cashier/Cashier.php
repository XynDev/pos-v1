<?php

namespace App\Livewire\Cashier;

use App\Models\Crm\Customer;
use App\Models\ManagementProduct\Product;
use App\Models\Sale\Sale;
use App\Models\Stock\MovementStock;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cashier extends Component
{
    // Keranjang Belanja
    public array $cart = [];

    // Fungsionalitas Pencarian
    public string $searchQuery = '';
    public array $searchResults = [];

    // Data Pembayaran
    public int $subtotal = 0;
    public int $total = 0;
    public string $paymentMethod = 'cash';
    public $paymentAmount;
    public int $changeDue = 0;

    public bool $isPaymentModalOpen = false;

    public string $customerSearchQuery = '';
    public array $customerSearchResults = [];
    public ?array $selectedCustomer = null;

    public function updatedSearchQuery(): void
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->searchResults = Product::where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->limit(10)
                ->get()
                ->all(); // <-- FIX: Konversi Collection ke array of objects
        } else {
            $this->searchResults = [];
        }
    }

    public function addToCart(Product $product)
    {
        if (isset($this->cart[$product->id])) {
            if ($this->cart[$product->id]['quantity'] < $product->stock) {
                $this->cart[$product->id]['quantity']++;
            }
        } else {
            $this->cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'quantity' => 1,
                'stock' => $product->stock,
            ];
        }
        $this->calculateTotals();
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function incrementQuantity($productId): void
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] < $this->cart[$productId]['stock']) {
                $this->cart[$productId]['quantity']++;
                $this->calculateTotals();
            }
        }
    }

    public function decrementQuantity($productId): void
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] > 1) {
                $this->cart[$productId]['quantity']--;
            } else {
                unset($this->cart[$productId]);
            }
            $this->calculateTotals();
        }
    }

    public function removeFromCart($productId): void
    {
        unset($this->cart[$productId]);
        $this->calculateTotals();
    }

    public function calculateTotals(): void
    {
        $this->subtotal = 0;
        foreach ($this->cart as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        $this->total = $this->subtotal; // Untuk saat ini total = subtotal
    }

    public function openPaymentModal(): void
    {
        if (count($this->cart) > 0) {
            $this->paymentAmount = $this->total;
            $this->calculateChange();
            $this->isPaymentModalOpen = true;
        }
    }

    public function closePaymentModal(): void
    {
        $this->isPaymentModalOpen = false;
    }

    public function updatedPaymentAmount(): void
    {
        $this->calculateChange();
    }

    public function calculateChange(): void
    {
        $payment = (int)str_replace('.', '', $this->paymentAmount);
        $this->changeDue = ($payment >= $this->total) ? $payment - $this->total : 0;
    }

    public function processSale(): void
    {
        $this->validate([
            'paymentMethod' => 'required',
            'cart' => 'required|array|min:1'
        ]);

        $paymentAmount = (int)str_replace('.', '', $this->paymentAmount);
        if($paymentAmount < $this->total){
            $this->addError('paymentAmount', 'Jumlah bayar tidak mencukupi.');
            return;
        }

        try {
            DB::transaction(function () use ($paymentAmount) {
                // 1. Simpan data penjualan utama
                $sale = Sale::create([
                    'invoice_number' => 'INV-' . now()->timestamp,
                    'user_id' => Auth::id(),
                    'customer_id' => $this->selectedCustomer['id'] ?? null,
                    'total_amount' => $this->subtotal,
                    'final_amount' => $this->total,
                    'payment_method' => $this->paymentMethod,
                    'amount_paid' => $paymentAmount,
                    'change_due' => $this->changeDue,
                ]);

                if ($this->selectedCustomer) {
                    $customer = Customer::find($this->selectedCustomer['id']);
                    $pointsEarned = floor($this->total / 1000);

                    if ($pointsEarned > 0) {
                        $customer->increment('points', $pointsEarned);
                    }
                }

                // 2. Simpan item penjualan, kurangi stok, dan catat pergerakan
                foreach ($this->cart as $item) {
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    $product = Product::find($item['product_id']);
                    $newStock = $product->stock - $item['quantity'];
                    $product->update(['stock' => $newStock]);

                    MovementStock::create([
                        'product_id' => $product->id,
                        'type' => 'sale',
                        'quantity' => -$item['quantity'], // Jumlah keluar (negatif)
                        'stock_after' => $newStock,
                        'reference_id' => $sale->id,
                        'reference_type' => Sale::class,
                        'notes' => 'Penjualan via Kasir, Invoice #' . $sale->invoice_number,
                        'user_id' => Auth::id(),
                    ]);
                }
            });

            // Reset state setelah berhasil
            $this->reset('cart', 'subtotal', 'total', 'paymentAmount', 'changeDue', 'selectedCustomer', 'customerSearchQuery');
            $this->closePaymentModal();
            session()->flash('message', 'Transaksi berhasil disimpan.');

        } catch (Exception $e) {
            session()->flash('error', 'Transaksi Gagal: ' . $e->getMessage());
        }
    }

    public function updatedCustomerSearchQuery()
    {
        if (strlen($this->customerSearchQuery) >= 2) {
            $this->customerSearchResults = Customer::where('name', 'like', '%' . $this->customerSearchQuery . '%')
                ->orWhere('phone', 'like', '%' . $this->customerSearchQuery . '%')
                ->limit(5)
                ->get()
                ->toArray();
        } else {
            $this->customerSearchResults = [];
        }
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->selectedCustomer = $customer->toArray();
            $this->customerSearchQuery = $customer->name;
            $this->customerSearchResults = [];
        }
    }

    public function removeCustomer()
    {
        $this->selectedCustomer = null;
        $this->customerSearchQuery = '';
    }

    public function render()
    {
        return view('livewire.cashier.cashier')->layout('layouts.app');
    }
}

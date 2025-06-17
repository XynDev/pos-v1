<?php

namespace App\Livewire\Cashier;

use App\Models\Branch\Location;
use App\Models\Cashier\CashierSession;
use App\Models\Cashier\HeldTransaction;
use App\Models\Crm\Customer;
use App\Models\ManagementProduct\Product;
use App\Models\Sale\Sale;
use App\Models\Stock\MovementStock;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Cashier extends Component
{
    public $locations;
    public $operatingLocationId;

    public array $cart = [];

    public string $searchQuery = '';
    public array $searchResults = [];

    public int $subtotal = 0;
    public int $total = 0;
    public string $paymentMethod = 'cash';
    public $paymentAmount = null;
    public int $changeDue = 0;
    public bool $isPaymentModalOpen = false;

    public string $customerSearchQuery = '';
    public array $customerSearchResults = [];
    public ?array $selectedCustomer = null;

    public Collection $heldTransactions;
    public bool $isHoldModalOpen = false;
    public string $holdReferenceName = '';

    public ?Product $productForVariantSelection = null;
    public Collection $variantsOfSelectedProduct;
    public bool $isVariantModalOpen = false;

    public function mount(): void
    {
        $this->locations = Location::where('is_active', true)->get();
        $this->loadHeldTransactions();
    }

    public function updatedSearchQuery(): void
    {
        if (strlen($this->searchQuery) >= 2 && $this->operatingLocationId) {
            $this->searchResults = Product::where('is_active', true)
                ->where(function($query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('sku', 'like', '%' . $this->searchQuery . '%');
                })
                ->where(function ($query) {
                    $query->where(function($subQuery) {
                        $subQuery->where('type', 'simple')
                            ->whereHas('locations', function ($locationQuery) {
                                $locationQuery->where('location_id', $this->operatingLocationId)->where('stock', '>', 0);
                            });
                    })->orWhere(function($subQuery) {
                        $subQuery->where('type', 'variable')
                            ->whereHas('variants.locations', function ($locationQuery) {
                                $locationQuery->where('location_id', $this->operatingLocationId)->where('stock', '>', 0);
                            });
                    })->orWhere(function($subQuery) {
                        $subQuery->where('type', 'bundle')->where('stock', '>', 0);
                    });
                })
                ->limit(10)
                ->get()
                ->all();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct(Product $product): void
    {
        if ($product->type === 'simple') {
            $this->addVariantToCart($product);
        } elseif ($product->type === 'variable') {
            $this->productForVariantSelection = $product;
            $this->variantsOfSelectedProduct = $product->variants()
                ->whereHas('locations', function($query) {
                    $query->where('location_id', $this->operatingLocationId)
                        ->where('stock', '>', 0);
                })
                ->get();
            $this->isVariantModalOpen = true;
        } elseif ($product->type === 'bundle') {
            $this->addBundleToCart($product);
        }
    }

    private function getStockAtCurrentLocation(Product $product): int
    {
        return $product->locations()->where('location_id', $this->operatingLocationId)->first()->pivot->stock ?? 0;
    }

    public function addVariantToCart(Product $variant): void
    {
        $stockAtLocation = $this->getStockAtCurrentLocation($variant);
        if ($stockAtLocation <= 0) {
            session()->flash('error', 'Stok produk ' . $variant->name . ' habis di lokasi ini.');
            return;
        }

        if (isset($this->cart[$variant->id])) {
            if ($this->cart[$variant->id]['quantity'] < $stockAtLocation) {
                $this->cart[$variant->id]['quantity']++;
            }
        } else {
            $this->cart[$variant->id] = [
                'product_id' => $variant->id, 'name' => $variant->name,
                'price' => $variant->selling_price, 'quantity' => 1,
                'stock' => $stockAtLocation, 'type' => 'variant',
            ];
        }
        $this->calculateTotals();
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->isVariantModalOpen = false;
    }

    public function addBundleToCart(Product $bundle): void
    {
        if (isset($this->cart[$bundle->id])) {
            $this->cart[$bundle->id]['quantity']++;
        } else {
            $this->cart[$bundle->id] = [
                'product_id' => $bundle->id,
                'name' => $bundle->name,
                'price' => $bundle->selling_price,
                'quantity' => 1,
                'type' => 'bundle',
                'components' => $bundle->bundleComponents,
            ];
        }
        $this->calculateTotals();
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function closeVariantModal(): void
    {
        $this->isVariantModalOpen = false;
        $this->productForVariantSelection = null;
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
        $this->total = $this->subtotal;
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
        ], ['operatingLocationId.required' => 'Lokasi operasional harus dipilih.']);

        $paymentAmount = (int)str_replace('.', '', $this->paymentAmount);
        if($paymentAmount < $this->total){
            $this->addError('paymentAmount', 'Jumlah bayar tidak mencukupi.');
            return;
        }

        try {
            DB::transaction(function () use ($paymentAmount) {
                foreach ($this->cart as $item) {
                    if ($item['type'] === 'bundle') {
                        foreach ($item['components'] as $component) {
                            $componentProduct = Product::find($component['component_product_id']);
                            $stockAtLocation = $this->getStockAtCurrentLocation($componentProduct);
                            $requiredStock = $component['quantity'] * $item['quantity'];
                            if ($stockAtLocation < $requiredStock) {
                                throw new Exception("Stok komponen '{$componentProduct->name}' tidak mencukupi di lokasi ini.");
                            }
                        }
                    }
                }
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

                $locationName = Location::find($this->operatingLocationId)->name;

                foreach ($this->cart as $item) {
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);

                    $product = Product::find($item['product_id']);
//                    $newStock = $product->stock - $item['quantity'];
//                    $product->update(['stock' => $newStock]);

                    if ($item['type'] === 'bundle') {
                        $newBundleStock = $product->stock - $item['quantity'];
                        $product->update([
                            'stock' => $newBundleStock
                        ]);
                        MovementStock::create([
                            'product_id' => $product->id,
                            'type' => 'sale_bundle',
                            'quantity' => -$item['quantity'],
                            'stock_after' => $newBundleStock,
                            'reference_id' => $sale->id,
                            'reference_type' => Sale::class,
                            'notes' => "Penjualan Bundle di {$locationName}",
                            'user_id' => Auth::id()
                        ]);

                        foreach ($item['components'] as $component) {
                            $componentProduct = Product::find($component->component_product_id);
                            $quantityToReduce = $component->quantity * $item['quantity'];
                            $componentProduct->locations()->where('location_id', $this->operatingLocationId)->decrement('stock', $quantityToReduce);
                            $newStock = $this->getStockAtCurrentLocation($componentProduct);
                            MovementStock::create([
                                'product_id' => $componentProduct->id,
                                'type' => 'sale_component',
                                'quantity' => -$quantityToReduce,
                                'stock_after' => $newStock,
                                'reference_id' => $sale->id,
                                'reference_type' => Sale::class,
                                'notes' => "Komponen untuk bundle #{$sale->invoice_number} di {$locationName}",
                                'user_id' => Auth::id()
                            ]);
                        }
                    } else {
                        $pivot = $product->locations()->where('location_id', $this->operatingLocationId)->first()->pivot;
                        $newStock = $pivot->stock - $item['quantity'];
                        $product->locations()->updateExistingPivot($this->operatingLocationId, ['stock' => $newStock]);
                        MovementStock::create([
                            'product_id' => $product->id,
                            'type' => 'sale',
                            'quantity' => -$item['quantity'],
                            'stock_after' => $newStock,
                            'reference_id' => $sale->id,
                            'reference_type' => Sale::class,
                            'notes' => "Penjualan di {$locationName}",
                            'user_id' => Auth::id()
                        ]);
                    }
                }
            });

            $this->reset('cart', 'subtotal', 'total', 'paymentAmount', 'changeDue', 'selectedCustomer', 'customerSearchQuery');
            $this->closePaymentModal();
            session()->flash('message', 'Transaksi berhasil disimpan.');

        } catch (Exception $e) {
            session()->flash('error', 'Transaksi Gagal: ' . $e->getMessage());
        }
    }

    public function updatedCustomerSearchQuery(): void
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

    public function selectCustomer($customerId): void
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $this->selectedCustomer = $customer->toArray();
            $this->customerSearchQuery = $customer->name;
            $this->customerSearchResults = [];
        }
    }

    public function removeCustomer(): void
    {
        $this->selectedCustomer = null;
        $this->customerSearchQuery = '';
    }

    public function loadHeldTransactions(): void
    {
        $this->heldTransactions = HeldTransaction::with('customer')->latest()->get();
    }

    public function openHoldModal(): void
    {
        if (count($this->cart) > 0) {
            $this->holdReferenceName = $this->selectedCustomer['name'] ?? 'Transaksi ' . now()->format('H:i');
            $this->isHoldModalOpen = true;
        }
    }

    public function holdTransaction(): void
    {
        $this->validate(['holdReferenceName' => 'required|string|max:255']);

        HeldTransaction::create([
            'reference_name' => $this->holdReferenceName,
            'user_id' => Auth::id(),
            'customer_id' => $this->selectedCustomer['id'] ?? null,
            'cart_data' => $this->cart,
            'total_amount' => $this->total,
        ]);

        $this->reset('cart', 'subtotal', 'total', 'selectedCustomer', 'customerSearchQuery');
        $this->isHoldModalOpen = false;
        $this->loadHeldTransactions();
        session()->flash('message', 'Transaksi berhasil ditahan.');
    }

    public function resumeTransaction(HeldTransaction $heldTransaction): void
    {
        if (!empty($this->cart)) {
            session()->flash('error', 'Keranjang harus kosong sebelum melanjutkan transaksi lain.');
            return;
        }

        $this->cart = $heldTransaction->cart_data;
        $this->selectedCustomer = $heldTransaction->customer ? $heldTransaction->customer->toArray() : null;

        $this->calculateTotals();

        $heldTransaction->delete();
        $this->loadHeldTransactions();
    }

    public function deleteHeldTransaction(HeldTransaction $heldTransaction): void
    {
        $heldTransaction->delete();
        $this->loadHeldTransactions();
    }

    public function render()
    {
        $activeSession = CashierSession::where('status', 'open')->exists();

        if (!$activeSession) {
            return view('livewire.cashier.no-session')->layout('layouts.app');
        }
        return view('livewire.cashier.cashier')->layout('layouts.app');
    }
}

<?php

namespace App\Livewire\Sale;

use App\Models\Sale\Sale;
use App\Models\Sale\SaleItem;
use App\Models\Stock\MovementStock;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SaleDetail extends Component
{
    public Sale $sale;
    public bool $isRefundModalOpen = false;
    public array $refundItems = [];
    public int $totalRefundAmount = 0;

    public function mount(Sale $sale): void
    {
        // Muat relasi agar efisien dan tersedia di view
        $this->sale = $sale->load(['user', 'customer', 'items.product']);
    }

    public function openRefundModal(): void
    {
        // Siapkan data item untuk form refund
        $this->refundItems = [];
        foreach ($this->sale->items as $item) {
            $this->refundItems[$item->id] = [
                'name' => $item->product->name,
                'price' => $item->price,
                'max_quantity' => $item->quantity,
                'quantity' => 0, // Default kuantitas refund
            ];
        }
        $this->isRefundModalOpen = true;
    }

    public function closeRefundModal(): void
    {
        $this->isRefundModalOpen = false;
        $this->refundItems = [];
        $this->totalRefundAmount = 0;
    }

    public function updatedRefundItems(): void
    {
        // Hitung ulang total refund setiap kali kuantitas diubah
        $this->totalRefundAmount = 0;
        foreach ($this->refundItems as $item) {
            $quantity = is_numeric($item['quantity']) ? (int)$item['quantity'] : 0;
            // Pastikan kuantitas refund tidak melebihi yang dibeli
            if ($quantity > $item['max_quantity']) {
                $quantity = $item['max_quantity'];
            }
            $this->totalRefundAmount += $quantity * $item['price'];
        }
    }

    public function processRefund(): void
    {
        // Validasi: pastikan setidaknya ada satu item yang di-refund
        $totalRefundQuantity = array_sum(array_column($this->refundItems, 'quantity'));
        if ($totalRefundQuantity <= 0) {
            $this->addError('refund', 'Tentukan kuantitas untuk setidaknya satu produk yang akan direfund.');
            return;
        }

        try {
            DB::transaction(function () {
                foreach ($this->refundItems as $saleItemId => $refundData) {
                    $refundQuantity = (int)$refundData['quantity'];
                    if ($refundQuantity > 0) {
                        $saleItem = SaleItem::find($saleItemId);
                        $product = $saleItem->product;

                        // 1. Tambah kembali stok produk
                        $newStock = $product->stock + $refundQuantity;
                        $product->update(['stock' => $newStock]);

                        // 2. Catat pergerakan stok sebagai 'refund'
                        MovementStock::create([
                            'product_id' => $product->id,
                            'type' => 'refund',
                            'quantity' => $refundQuantity, // Stok masuk (positif)
                            'stock_after' => $newStock,
                            'reference_id' => $this->sale->id,
                            'reference_type' => Sale::class,
                            'notes' => 'Refund dari Invoice #' . $this->sale->invoice_number,
                            'user_id' => Auth::id(),
                        ]);
                    }
                }

                // 3. Update status penjualan utama menjadi 'refunded'
                // (Untuk simplifikasi, kita anggap semua refund (penuh/sebagian) statusnya sama)
                $this->sale->update(['status' => 'refunded']);

                // 4. Refresh data di halaman
                $this->sale->refresh();
            });

            session()->flash('message', 'Proses refund berhasil dan stok telah diperbarui.');
            $this->closeRefundModal();

        } catch (Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memproses refund: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sale.sale-detail')->layout('layouts.app');
    }
}

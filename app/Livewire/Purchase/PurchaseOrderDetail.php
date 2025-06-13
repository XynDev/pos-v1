<?php

namespace App\Livewire\Purchase;

use App\Models\Purchase\PurchaseOrders;
use App\Models\Stock\MovementStock;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseOrderDetail extends Component
{
    public PurchaseOrders $purchaseOrder;

    public function mount(PurchaseOrders $purchaseOrder): void
    {
        // Load relasi agar efisien
        $this->purchaseOrder = $purchaseOrder->load(['supplier', 'user', 'items.product']);
    }

    /**
     * Proses utama untuk menerima barang dan memperbarui stok.
     */
    public function receiveGoods(): void
    {
        // Hanya bisa dijalankan jika status masih pending
        if ($this->purchaseOrder->status !== 'pending') {
            session()->flash('error', 'Pesanan ini tidak bisa diproses karena statusnya sudah ' . $this->purchaseOrder->status);
            return;
        }

        try {
            DB::transaction(function () {
                // 1. Loop melalui setiap item di PO
                foreach ($this->purchaseOrder->items as $item) {
                    $product = $item->product;
                    $newStock = $product->stock + $item->quantity;

                    // 2. Update stok di tabel produk
                    $product->update(['stock' => $newStock]);

                    // 3. Buat catatan pergerakan stok
                    MovementStock::create([
                        'product_id' => $product->id,
                        'type' => 'purchase',
                        'quantity' => $item->quantity, // Jumlah masuk (positif)
                        'stock_after' => $newStock,
                        'reference_id' => $this->purchaseOrder->id,
                        'reference_type' => PurchaseOrders::class,
                        'notes' => 'Penerimaan barang dari PO #' . $this->purchaseOrder->po_number,
                        'user_id' => Auth::id(),
                    ]);
                }

                // 4. Update status PO menjadi 'completed'
                $this->purchaseOrder->update(['status' => 'completed']);

                // Refresh data PO di komponen
                $this->purchaseOrder->refresh();
            });

            session()->flash('message', 'Barang dari PO berhasil diterima dan stok telah diperbarui.');

        } catch (Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Membatalkan pesanan pembelian.
     */
    public function cancelOrder(): void
    {
        if ($this->purchaseOrder->status !== 'pending') {
            session()->flash('error', 'Pesanan ini tidak bisa dibatalkan.');
            return;
        }

        $this->purchaseOrder->update(['status' => 'cancelled']);
        $this->purchaseOrder->refresh();
        session()->flash('message', 'Pesanan Pembelian telah dibatalkan.');
    }

    public function render()
    {
        return view('livewire.purchase.purchase-order-detail')->layout('layouts.app');
    }
}

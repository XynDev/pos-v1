<?php

namespace App\Livewire\Product;

use App\Models\ManagementProduct\Product;
use Livewire\Component;

class PrintQueue extends Component
{
    public array $selectedForPrint = [];

    // Jika tombol 'Kosongkan' di antrian diklik
    public function clearSelection()
    {
        // Teriak ke induk dengan event 'queue-cleared'
        $this->dispatch('show-global-loading');
        $this->dispatch('queue-cleared');
    }

    // Jika tombol 'x' di antrian diklik
    public function removeFromQueue($productId)
    {
        // Teriak ke induk dengan event 'queue-item-removed' sambil bawa ID produk
        $this->dispatch('show-global-loading');
        $this->dispatch('queue-item-removed', productId: $productId);
    }

    // Jika qty diubah
    public function updateQuantity($productId, $quantity)
    {
        // Kita bungkus datanya dalam satu array
        $payload = [
            'productId' => $productId,
            'quantity' => $quantity,
        ];

        // Kirim event dengan satu paket data (payload)
        $this->dispatch('queue-item-updated', payload: $payload);
    }

    public function getTotalLabelCount(): int
    {
        return collect($this->selectedForPrint)->sum('quantity');
    }

    public function render()
    {
        $selectedProductIds = array_keys($this->selectedForPrint);

        $selectedProducts = !empty($selectedProductIds)
            ? Product::whereIn('id', $selectedProductIds)->get()->keyBy('id')
            : collect();

        $sortedSelectedProducts = collect($this->selectedForPrint)
            ->keys()
            ->map(fn ($id) => $selectedProducts->get($id))
            ->filter();

        return view('livewire.product.print-queue', [
            'selectedProducts' => $sortedSelectedProducts
        ]);
    }
}

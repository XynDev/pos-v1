<?php

namespace App\Livewire\Inventory;

use App\Models\ManagementProduct\Product;
use App\Models\Stock\MovementStock;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StockOpname extends Component
{
    public string $searchQuery = '';
    public $searchResults = [];
    public ?Product $selectedProduct = null;

    public $physical_stock;
    public $notes;
    public $difference = 0;

    public function render()
    {
        return view('livewire.inventory.stock-opname')->layout('layouts.app');
    }

    public function updatedSearchQuery(): void
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->searchResults = Product::whereIn('type', ['simple', 'variant'])
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('sku', 'like', '%' . $this->searchQuery . '%');
                })
                ->limit(7)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct(Product $product): void
    {
        $this->selectedProduct = $product;
        $this->searchQuery = $product->name;
        $this->searchResults = [];
        $this->updatedPhysicalStock();
    }

    public function clearSelection(): void
    {
        $this->reset(['selectedProduct', 'searchQuery', 'searchResults', 'physical_stock', 'notes', 'difference']);
    }

    public function updatedPhysicalStock(): void
    {
        if ($this->selectedProduct && is_numeric($this->physical_stock)) {
            $this->difference = (int)$this->physical_stock - $this->selectedProduct->stock;
        } else {
            $this->difference = 0;
        }
    }

    public function saveAdjustment(): void
    {
        $this->validate([
            'physical_stock' => 'required|integer|min:0',
            'notes' => 'required|string|min:5',
        ]);

        if (!$this->selectedProduct || $this->difference == 0) {
            session()->flash('error', 'Tidak ada penyesuaian yang perlu disimpan.');
            return;
        }

        MovementStock::create([
            'product_id' => $this->selectedProduct->id,
            'type' => 'adjustment',
            'quantity' => $this->difference,
            'stock_after' => $this->physical_stock,
            'notes' => $this->notes,
            'user_id' => Auth::id(),
        ]);

        $this->selectedProduct->update(['stock' => $this->physical_stock]);

        session()->flash('message', 'Penyesuaian stok berhasil disimpan.');
        $this->clearSelection();
    }
}

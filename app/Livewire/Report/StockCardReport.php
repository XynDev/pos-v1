<?php

namespace App\Livewire\Report;

use App\Models\ManagementProduct\Product;
use App\Models\Stock\MovementStock;
use Livewire\Component;
use Livewire\WithPagination;

class StockCardReport extends Component
{
    use WithPagination;

    // Properti untuk Pencarian dan Pemilihan Produk
    public string $searchQuery = '';
    public $searchResults = [];
    public ?Product $selectedProduct = null;
    public $selectedProductId;

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) >= 2) {
            $this->searchResults = Product::where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('sku', 'like', '%' . $this->searchQuery . '%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct(Product $product)
    {
        $this->selectedProduct = $product;
        $this->selectedProductId = $product->id;
        $this->searchQuery = $product->name; // Tampilkan nama di input
        $this->searchResults = []; // Sembunyikan hasil pencarian
        $this->resetPage(); // Reset paginasi setiap kali produk baru dipilih
    }

    public function clearSelection()
    {
        $this->selectedProduct = null;
        $this->selectedProductId = null;
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->resetPage();
    }

    public function render()
    {
        $movements = collect(); // Defaultnya koleksi kosong
        if ($this->selectedProduct) {
            $movements = MovementStock::where('product_id', $this->selectedProduct->id)
                ->with(['user', 'reference']) // Load relasi
                ->latest() // Tampilkan yang terbaru di atas
                ->paginate(15);
        }

        return view('livewire.report.stock-card-report', [
            'movements' => $movements
        ])->layout('layouts.app');
    }
}

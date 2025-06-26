<?php

namespace App\Livewire\Product;

use App\Models\ManagementProduct\Brand;
use App\Models\ManagementProduct\Category;
use App\Models\ManagementProduct\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PrintBarcodeManager extends Component
{
    use WithPagination;

    public string $search = '';
    public array $selectedForPrint = [];
    public $categories = [];
    public $selectedCategory = '';
    public $brands = [];
    public $selectedBrand = '';

    public function mount()
    {
        $this->categories = Category::all();
        $this->brands = Brand::all();
    }

    public function updatedSelectedForPrint($value, $key)
    {
        if ($value === true) {
            $this->selectedForPrint[$key] = ['quantity' => 1];
        } else {
            unset($this->selectedForPrint[$key]);
        }
    }

    public function removeFromQueue($productId)
    {
        unset($this->selectedForPrint[$productId]);
    }
    public function updateQuantity($productId, $quantity)
    {
        $validatedQuantity = max(1, (int)$quantity);
        if (isset($this->selectedForPrint[$productId])) {
            $this->selectedForPrint[$productId]['quantity'] = $validatedQuantity;
        }
    }

    public function getTotalLabelCount(): int
    {
        return collect($this->selectedForPrint)->sum('quantity');
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->selectedBrand = '';
        $this->resetPage();
    }

    public function clearSelection(): void
    {
        $this->selectedForPrint = [];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedBrand(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Ambil dulu daftar ID produk yang sudah dipilih
        $selectedProductIds = array_keys($this->selectedForPrint);

        $productsQuery = Product::query()
            ->select('id', 'name', 'purchase_price', 'category_id', 'brand_id', 'internal_code')
            ->whereNotNull('internal_code')
            // Bungkus semua filter dalam satu grup where()
            ->where(function ($query) {
                // Filter berdasarkan kategori
                $query->when($this->selectedCategory, function ($subQuery) {
                    $subQuery->where('category_id', $this->selectedCategory);
                });
                // Filter berdasarkan brand
                $query->when($this->selectedBrand, function ($subQuery) {
                    $subQuery->where('brand_id', $this->selectedBrand);
                });
                // Filter berdasarkan pencarian
                $query->when($this->search, function ($subQuery) {
                    $subQuery->where(function ($searchQuery) {
                        $searchQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('internal_code', 'like', '%' . $this->search . '%');
                    });
                });
            })
            // tampilkan juga produk APAPUN yang ID-nya sudah dipilih
            ->orWhereIn('id', $selectedProductIds);

        $products = $productsQuery->orderBy('id', 'asc')->paginate(6);

        $selectedProductIds = array_keys($this->selectedForPrint);

        $selectedProducts = !empty($selectedProductIds)
            ? Product::whereIn('id', $selectedProductIds)->get()->keyBy('id')
            : collect();

        $sortedSelectedProducts = collect($this->selectedForPrint)
            ->keys()
            ->map(fn ($id) => $selectedProducts->get($id))
            ->filter();

        return view('livewire.product.print-barcode-manager', [
            'products' => $products,
            'selectedProducts' => $sortedSelectedProducts
        ])->layout('layouts.app'); // Gunakan layout utama Anda
    }
}

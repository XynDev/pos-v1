<?php

namespace App\Livewire\Product;

use App\Models\ManagementProduct\Brand;
use App\Models\ManagementProduct\Category;
use App\Models\ManagementProduct\Product;
use App\Models\Stock\MovementStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class ListProduct extends Component
{
    use WithPagination, WithFileUploads;

    // Properti untuk Modal dan Form
    public bool $isModalOpen = false;
    public bool $isEditMode = false;

    // Properti untuk Data Produk
    public $productId;
    public $name, $sku, $description;
    public $purchase_price, $selling_price, $stock;
    public $category_id, $brand_id;
    public $is_active = true;

    // Properti untuk Upload Gambar
    public $image; // Untuk menampilkan gambar yang ada
    public $newImage; // Untuk menghandle upload gambar baru

    // Properti untuk Fungsionalitas Tabel
    public string $search = '';
    public int $perPage = 10;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'sku' => ['required', 'string', Rule::unique('products')->ignore($this->productId)],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'is_active' => ['boolean'],
            // Validasi untuk gambar baru, opsional saat edit
            'newImage' => ['nullable', 'image', 'max:2048'], // Maks 2MB
        ];
    }

    public function render()
    {
        $products = Product::with(['category', 'brand'])
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('sku', 'like', '%'.$this->search.'%')
            ->paginate($this->perPage);

        // Data untuk dropdown di form
        $categories = Category::all();
        $brands = Brand::all();

        return view('livewire.product.list-product', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ])->layout('layouts.app');
    }

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function create(): void
    {
        $this->isEditMode = false;
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id): void
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description;
        $this->purchase_price = $product->purchase_price;
        $this->selling_price = $product->selling_price;
        $this->stock = $product->stock;
        $this->is_active = $product->is_active;
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->image = $product->image; // Simpan path gambar lama

        $this->isEditMode = true;
        $this->openModal();
    }

    public function store(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
        ];

        if ($this->newImage) {
            $data['image'] = $this->newImage->store('products', 'public');
        }

        $product = Product::updateOrCreate(['id' => $this->productId], $data);

        if (!$this->isEditMode && $product->stock > 0) {
            MovementStock::create([
                'product_id' => $product->id,
                'type' => 'initial_stock',
                'quantity' => $product->stock,
                'stock_after' => $product->stock,
                'notes' => 'Stok awal saat pembuatan produk',
                'user_id' => Auth::id(),
            ]);
        }

        session()->flash('message', $this->productId ? 'Produk berhasil diupdate.' : 'Produk berhasil dibuat.');

        $this->closeModal();
    }

    public function delete($id): void
    {
        // Hapus juga file gambar jika ada
        $product = Product::find($id);
        if ($product && $product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        session()->flash('message', 'Produk berhasil dihapus.');
    }

    private function resetInputFields(): void
    {
        $this->productId = null;
        $this->name = '';
        $this->sku = '';
        $this->description = '';
        $this->purchase_price = 0;
        $this->selling_price = 0;
        $this->stock = 0;
        $this->is_active = true;
        $this->category_id = null;
        $this->brand_id = null;
        $this->image = null;
        $this->newImage = null;
    }
}

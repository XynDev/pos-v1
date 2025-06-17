<?php

namespace App\Livewire\Product;

use App\Models\Branch\Location;
use App\Models\ManagementProduct\AttributeValue;
use App\Models\ManagementProduct\Brand;
use App\Models\ManagementProduct\Category;
use App\Models\ManagementProduct\Product;
use App\Models\ManagementProduct\ProductAttribute;
use App\Models\ManagementProduct\ProductVariant;
use App\Models\Stock\MovementStock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product;
    public $name, $description, $category_id, $brand_id, $is_active = true;
    public $productType = 'simple';

    public $image;
    public $newImage;

    public $sku, $purchase_price, $selling_price, $stock;

    public $productAttributesData = [];
    public $variants = [];

    public $bundleComponents = [];
    public $componentSearchQuery = '';
    public $componentSearchResults = [];

    public $stocks = [];

    public function mount($productId = null)
    {
        if ($productId) {
            $this->product = Product::with(['variants.locations', 'variants.variantDetail.attributeValues.productAttribute', 'bundleComponents.component', 'locations'])->findOrFail($productId);
        } else {
            $this->product = new Product();
        }

        $locations = Location::where('is_active', true)->get();
        foreach ($locations as $location) {
            $this->stocks[$location->id] = 0;
        }

        if ($this->product->exists) {
            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->category_id = $this->product->category_id;
            $this->brand_id = $this->product->brand_id;
            $this->is_active = $this->product->is_active;
            $this->productType = $this->product->type;
            $this->image = $this->product->image;

            if ($this->productType === 'simple') {
                $this->sku = $this->product->sku;
                $this->purchase_price = $this->product->purchase_price;
                $this->selling_price = $this->product->selling_price;
                foreach($this->product->locations as $location) {
                    if (isset($this->stocks[$location->id])) {
                        $this->stocks[$location->id] = $location->pivot->stock;
                    }
                }
            } elseif ($this->productType === 'variable') {
                $this->loadExistingVariants();
            } elseif ($this->productType === 'bundle') {
                $this->selling_price = $this->product->selling_price;
                $this->stock = $this->product->stock;
                $this->loadExistingBundleComponents();
            }
        }
    }

    public function loadExistingVariants()
    {
        $attributes = new Collection();
        $variantsData = [];

        foreach ($this->product->variants as $variant) {

            $currentVariantAttributes = [];

            if ($variant->variantDetail) {
                foreach ($variant->variantDetail->attributeValues as $value) {
                    $attributes->put($value->productAttribute->id, $value->productAttribute->name);
                    $currentVariantAttributes[$value->productAttribute->name] = $value->value;
                }
            }

            $variantStocks = [];
            foreach($variant->locations as $location) {
                $variantStocks[$location->id] = $location->pivot->stock;
            }

            $variantsData[] = [
                'id' => $variant->id,
                'name' => $variant->name,
                'attributes' => $currentVariantAttributes,
                'sku' => $variant->sku,
                'purchase_price' => $variant->purchase_price,
                'selling_price' => $variant->selling_price,
                'stocks' => $variantStocks,
            ];
        }
        $this->variants = $variantsData;
        $this->productAttributesData = $attributes->map(function ($name, $id) {
            $values = collect($this->variants)->pluck('attributes.' . $name)->unique()->filter()->implode(', ');
            return ['id' => $id, 'values' => $values];
        })->values()->toArray();
    }

    public function loadExistingBundleComponents(): void
    {
        $this->bundleComponents = [];
        foreach($this->product->bundleComponents as $component)
        {
            if($component->component){
                $this->bundleComponents[$component->component_product_id] = [
                    'component_id' => $component->component_product_id,
                    'name' => $component->component->name,
                    'sku' => $component->component->sku,
                    'quantity' => $component->quantity,
                ];
            }
        }
    }

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:3', 'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id', 'description' => 'nullable|string',
            'productType' => 'required|in:simple,variable,bundle', 'newImage' => 'nullable|image|max:2048',
        ];

        $productId = $this->product->id ?? 'NULL';

        if ($this->productType === 'simple') {
            $rules['sku'] = "required|string|unique:products,sku,{$productId}";
            $rules['purchase_price'] = 'required|numeric|min:0';
            $rules['selling_price'] = 'required|numeric|min:0';
            $rules['stocks.*'] = 'required|integer|min:0';
        } elseif ($this->productType === 'variable') {
            $rules['productAttributesData'] = 'required|array|min:1';
            $rules['variants'] = 'required|array|min:1';

            // FIX: Validasi SKU yang lebih cerdas untuk varian
            $ignoreIds = $this->product->exists ? $this->product->variants()->pluck('id')->toArray() : [];
            foreach ($this->variants as $index => $variant) {
                $rules['variants.' . $index . '.sku'] = ['required', 'distinct', Rule::unique('products', 'sku')->ignore($variant['id'] ?? null)];
            }

            $rules['variants.*.purchase_price'] = 'required|numeric|min:0';
            $rules['variants.*.selling_price'] = 'required|numeric|min:0';
            $rules['variants.*.stocks.*'] = 'required|integer|min:0';
        } else {
            $rules['selling_price'] = 'required|numeric|min:0';
            $rules['stock'] = 'required|integer|min:0';
            $rules['bundleComponents'] = 'required|array|min:1';
            $rules['bundleComponents.*.quantity'] = 'required|integer|min:1';
        }
        return $rules;
    }

    public function addAttribute(): void
    {
        $this->productAttributesData[] = ['id' => null, 'values' => ''];
    }

    public function removeAttribute($index): void
    {
        unset($this->productAttributesData[$index]);
        $this->productAttributesData = array_values($this->productAttributesData);
    }

    public function generateVariants(): void
    {
        $this->variants = [];
        $attributeValues = [];

        foreach ($this->productAttributesData as $attribute) {
            if (!empty($attribute['id']) && !empty($attribute['values'])) {
                $values = array_map('trim', explode(',', $attribute['values']));
                $attributeName = ProductAttribute::find($attribute['id'])->name;
                $attributeValues[$attributeName] = $values;
            }
        }

        if (empty($attributeValues)) {
            return;
        }

        $combinations = $this->getCombinations(array_values($attributeValues));
        $attributeNames = array_keys($attributeValues);

        foreach ($combinations as $combination) {
            $variantName = $this->name . ' (' . implode(' / ', $combination) . ')';
            $this->variants[] = [
                'name' => $variantName,
                'attributes' => array_combine($attributeNames, $combination),
                'sku' => '',
                'purchase_price' => 0,
                'selling_price' => 0,
                'stock' => 0,
            ];
        }
    }

    private function getCombinations(array $arrays): array
    {
        $result = [[]];
        foreach ($arrays as $key => $values) {
            $tmp = [];
            foreach ($result as $combination) {
                foreach ($values as $value) {
                    $tmp[] = array_merge($combination, [$value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function updatedComponentSearchQuery()
    {
        if (strlen($this->componentSearchQuery) >= 2) {
            $this->componentSearchResults = Product::whereIn('type', ['simple', 'variant'])
            ->where('name', 'like', '%' . $this->componentSearchQuery . '%')
                ->limit(5)
                ->get();
        } else {
            $this->componentSearchResults = [];
        }
    }

    public function addComponent(Product $component)
    {
        if (!isset($this->bundleComponents[$component->id])) {
            $this->bundleComponents[$component->id] = [
                'component_id' => $component->id,
                'name' => $component->name,
                'sku' => $component->sku,
                'quantity' => 1,
            ];
        }
        $this->componentSearchQuery = '';
        $this->componentSearchResults = [];
    }

    public function removeComponent($componentId)
    {
        unset($this->bundleComponents[$componentId]);
    }


    public function save()
    {
        $this->validate();
        DB::transaction(function () {
            if ($this->productType === 'simple') {
                $this->saveSimpleProduct();
            } elseif ($this->productType === 'variable') {
                $this->saveVariableProduct();
            } else {
                $this->saveBundleProduct();
            }
        });
        session()->flash('message', 'Produk berhasil disimpan.');
        return redirect()->route('products.index');
    }

    private function saveSimpleProduct(): void
    {
        $data = [
            'name' => $this->name,
            'type' => 'simple',
            'description' => $this->description,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'is_active' => $this->is_active,
            'sku' => $this->sku,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
        ];

        if ($this->newImage) {
            $data['image'] = $this->newImage->store('products', 'public');
        }

        $product = Product::updateOrCreate(['id' => $this->product->id], $data);

        $stocksToSync = [];
        foreach ($this->stocks as $locationId => $stockCount) {
            $stocksToSync[$locationId] = ['stock' => $stockCount];
        }
        $product->locations()->sync($stocksToSync);

        if (!$this->product->exists) {
            foreach ($this->stocks as $locationId => $stock) {
                if ($stock > 0) {
                    MovementStock::create([
                        'product_id' => $product->id,
                        'type' => 'initial_stock',
                        'quantity' => $stock,
                        'stock_after' => $stock,
                        'notes' => 'Stok awal di lokasi ' . Location::find($locationId)->name,
                        'user_id' => auth()->id()
                    ]);
                }
            }
        }
    }

    private function saveVariableProduct(): void
    {
        $parentData = [
            'name' => $this->name,
            'type' => 'variable',
            'description' => $this->description,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'is_active' => $this->is_active,
        ];
        if ($this->newImage) {
            $parentData['image'] = $this->newImage->store('products', 'public');
        }
        $parentProduct = Product::updateOrCreate(['id' => $this->product->id], $parentData);

        $parentProduct->variants()->delete();
        $keptVariantIds = [];
        foreach ($this->variants as $variantData) {
            $variantProduct = $parentProduct->variants()->create([
                'name' => $variantData['name'],
                'type' => 'variant',
                'sku' => $variantData['sku'],
                'purchase_price' => $variantData['purchase_price'],
                'selling_price' => $variantData['selling_price'],
                'category_id' => $parentProduct->category_id,
                'brand_id' => $parentProduct->brand_id,
                'is_active' => true,
            ]);

            $variantStocks = $variantData['stocks'] ?? [];
            $stocksToSync = [];
            foreach ($variantStocks as $locationId => $stockCount) {
                $stocksToSync[$locationId] = ['stock' => $stockCount];
            }
            $variantProduct->locations()->sync($stocksToSync);

            if (!$this->product->exists) {
                foreach ($variantStocks as $locationId => $stock) {
                    if ($stock > 0) {
                        MovementStock::create([
                            'product_id' => $variantProduct->id,
                            'type' => 'initial_stock',
                            'quantity' => $stock,
                            'stock_after' => $stock,
                            'notes' => 'Stok awal varian di lokasi ' . Location::find($locationId)->name,
                            'user_id' => auth()->id()
                        ]);
                    }
                }
            }

            $variantDetail = ProductVariant::create([
                'product_id' => $variantProduct->id
            ]);
            $attributeValueIds = [];
            foreach ($this->productAttributesData as $attr) {
                if (!empty($attr['id'])) {
                    $attributeName = ProductAttribute::find($attr['id'])->name;
                    if (isset($variantData['attributes'][$attributeName])) {
                        $value = $variantData['attributes'][$attributeName];
                        $attributeValue = AttributeValue::firstOrCreate([
                            'product_attribute_id' => $attr['id'],
                            'value' => $value
                        ]);
                        $attributeValueIds[] = $attributeValue->id;
                    }
                }
            }
            $variantDetail->attributeValues()->sync($attributeValueIds);
        }
    }

    private function saveBundleProduct(): void
    {
        $data = [
            'name' => $this->name,
            'type' => 'bundle',
            'description' => $this->description,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'is_active' => $this->is_active,
            'selling_price' => $this->selling_price,
            'stock' => $this->stock,
        ];

        if ($this->newImage) {
            $data['image'] = $this->newImage->store('products', 'public');
        }

        $bundleProduct = Product::updateOrCreate(['id' => $this->product->id], $data);

        $bundleProduct->bundleComponents()->delete();
        foreach ($this->bundleComponents as $componentData) {
            $bundleProduct->bundleComponents()->create([
                'component_product_id' => $componentData['component_id'],
                'quantity' => $componentData['quantity'],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.product.product-form', [
            'categories' => Category::all(),
            'brands' => Brand::all(),
            'productAttributes' => ProductAttribute::all(),
            'locations' => Location::where('is_active', true)->get(),
        ])->layout('layouts.app');
    }
}

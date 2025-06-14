<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label class="block font-medium text-sm text-gray-700">SKU</label>
        <input type="text" wire:model.defer="sku" class="mt-1 block w-full form-input rounded-md shadow-sm">
        @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700">Harga Beli</label>
        <input type="number" wire:model.defer="purchase_price" class="mt-1 block w-full form-input rounded-md shadow-sm">
        @error('purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700">Harga Jual</label>
        <input type="number" wire:model.defer="selling_price" class="mt-1 block w-full form-input rounded-md shadow-sm">
        @error('selling_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700">Stok</label>
        <input type="number" wire:model.defer="stock" class="mt-1 block w-full form-input rounded-md shadow-sm">
        @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
</div>

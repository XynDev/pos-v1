<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Harga & Stok</h2>
    </header>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">SKU</label>
                <input type="text" id="sku" wire:model.defer="sku" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: SKU-001">
                @error('sku') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="purchase_price" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Harga Beli</label>
                <input type="number" id="purchase_price" wire:model.defer="purchase_price" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: 10000">
                @error('purchase_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="selling_price" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Harga Jual</label>
                <input type="number" id="selling_price" wire:model.defer="selling_price" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: 15000">
                @error('selling_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mt-6 border-t pt-6">
            <h4 class="font-medium text-gray-800">Stok Awal per Lokasi</h4>
            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($locations as $location)
                    <div>
                        <label class="block text-sm text-gray-700">{{ $location->name }}</label>
                        <input type="number" wire:model.defer="stocks.{{ $location->id }}" class="mt-1 block w-full form-input">
                    </div>
                @endforeach
            </div>
            @error('stocks.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Harga & Stok</h2>
    </header>
    <div class="p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
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
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Stok</label>
                <input type="number" id="stock" wire:model.defer="stock" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: 100">
                @error('stock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

<div>
    <h3 class="text-lg font-medium text-gray-900 mb-4">Komponen & Harga Produk Bundle/Racikan</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <label class="block font-medium text-sm text-gray-700">Harga Jual Paket</label>
            <input type="number" wire:model.defer="selling_price" class="mt-1 block w-full form-input rounded-md shadow-sm">
            @error('selling_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block font-medium text-sm text-gray-700">Stok Paket (Promo)</label>
            <input type="number" wire:model.defer="stock" class="mt-1 block w-full form-input rounded-md shadow-sm">
            @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="mb-6 relative">
        <label for="component_search" class="block text-sm font-medium text-gray-700">Tambah Komponen</label>
        <input type="text" id="component_search" wire:model.live.debounce.300ms="componentSearchQuery" placeholder="Cari produk komponen..." class="mt-1 block w-full form-input rounded-md shadow-sm">

        @if(!empty($componentSearchResults))
            <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg">
                @forelse($componentSearchResults as $component)
                    <li wire:click="addComponent({{ $component->id }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                        {{ $component->name }} (SKU: {{ $component->sku }})
                    </li>
                @empty
                    <li class="px-4 py-2 text-gray-500">Produk tidak ditemukan.</li>
                @endforelse
            </ul>
        @endif
    </div>

    <div class="overflow-x-auto">
        <h4 class="text-md font-medium text-gray-800 mb-2">Daftar Komponen</h4>
        @error('bundleComponents') <span class="text-red-500 text-xs mb-2 d-block">{{ $message }}</span> @enderror
        <table class="w-full">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Produk Komponen</th>
                <th class="p-2 text-left">SKU</th>
                <th class="p-2 text-left w-32">Jumlah</th>
                <th class="p-2 text-center w-20">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bundleComponents as $componentId => $component)
                <tr class="border-b">
                    <td class="p-2">{{ $component['name'] }}</td>
                    <td class="p-2 font-mono">{{ $component['sku'] }}</td>
                    <td class="p-2">
                        <input type="number" wire:model="bundleComponents.{{ $componentId }}.quantity" class="w-full form-input text-sm">
                        @error('bundleComponents.'.$componentId.'.quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </td>
                    <td class="p-2 text-center">
                        <button wire:click="removeComponent({{ $componentId }})" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada komponen yang ditambahkan.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

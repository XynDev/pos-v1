<div class="space-y-6">

    {{-- Card Harga & Stok Paket --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Harga & Stok Paket</h2>
        </header>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bundle_selling_price" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Harga Jual Paket</label>
                    <input type="number" id="bundle_selling_price" wire:model.defer="selling_price" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: 150000">
                    @error('selling_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="bundle_stock" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Stok Paket (Promo)</label>
                    <input type="number" id="bundle_stock" wire:model.defer="stock" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Jumlah paket yang tersedia">
                    @error('stock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Card Tambah Komponen --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Tambah Komponen</h2>
        </header>
        <div class="p-5">
            <div class="relative">
                <label for="component_search" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Cari Produk Komponen</label>
                <input type="text" id="component_search" wire:model.live.debounce.300ms="componentSearchQuery" placeholder="Ketik nama atau SKU produk..." class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">

                @if(!empty($componentSearchResults) && strlen($componentSearchQuery) > 1)
                    <ul class="absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg mt-1 shadow-lg max-h-60 overflow-y-auto">
                        @forelse($componentSearchResults as $component)
                            <li wire:click="addComponent({{ $component->id }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $component->name }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">(SKU: {{ $component->sku }})</span>
                            </li>
                        @empty
                            <li class="px-4 py-2 text-gray-500">Produk tidak ditemukan.</li>
                        @endforelse
                    </ul>
                @endif
            </div>
        </div>
    </div>


    {{-- Card Daftar Komponen --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Daftar Komponen Bundle</h2>
        </header>
        <div class="p-3">
            @error('bundleComponents') <div class="text-red-500 text-xs mb-4 p-3 bg-red-50 dark:bg-red-900/30 rounded-lg">{{ $message }}</div> @enderror
            <div class="overflow-x-auto">
                <table class="table-auto w-full dark:text-gray-300">
                    <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                    <tr>
                        <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk Komponen</div></th>
                        <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">SKU</div></th>
                        <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left w-32">Jumlah</div></th>
                        <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center w-20">Aksi</div></th>
                    </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($bundleComponents as $componentId => $component)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                            <td class="p-2">
                                <div class="font-medium text-gray-800 dark:text-gray-100">{{ $component['name'] }}</div>
                            </td>
                            <td class="p-2">
                                <div class="font-mono">{{ $component['sku'] }}</div>
                            </td>
                            <td class="p-2">
                                <input type="number" wire:model="bundleComponents.{{ $componentId }}.quantity" class="form-input w-full text-sm dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                @error('bundleComponents.'.$componentId.'.quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </td>
                            <td class="p-2 text-center">
                                <button wire:click="removeComponent({{ $componentId }})" class="btn-sm bg-red-500 hover:bg-red-600 text-white">&times;</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                Belum ada komponen yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

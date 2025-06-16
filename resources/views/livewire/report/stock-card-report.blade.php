<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Laporan Kartu Stok
        </x-page-header>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Pilih Produk</h2>
                </header>
                <div class="p-5">
                    <div class="relative">
                        <div class="flex items-center">
                            <input type="text"
                                   wire:model.live.debounce.300ms="searchQuery"
                                   placeholder="Ketik nama atau SKU produk untuk memulai..."
                                   class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200"
                                   {{ $selectedProduct ? 'disabled' : '' }}
                                   autocomplete="off">

                            @if($selectedProduct)
                                <button wire:click="clearSelection" class="btn bg-red-500 hover:bg-red-600 text-white ml-3 whitespace-nowrap">
                                    Ganti Produk
                                </button>
                            @endif
                        </div>

                        @if(!empty($searchResults))
                            <ul class="absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg mt-1 shadow-lg max-h-60 overflow-y-auto">
                                @forelse($searchResults as $product)
                                    <li wire:click="selectProduct({{ $product->id }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $product->name }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">(SKU: {{ $product->sku }})</span>
                                    </li>
                                @empty
                                    <li class="px-4 py-2 text-gray-500">Produk tidak ditemukan.</li>
                                @endforelse
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            @if($selectedProduct)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Riwayat Stok: <span class="text-indigo-500">{{ $selectedProduct->name }}</span></h2>
                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Stok Saat Ini: <span class="text-indigo-500 text-lg">{{ $selectedProduct->stock }}</span></p>
                    </header>
                    <div class="p-3">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full dark:text-gray-300">
                                <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                                <tr>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Tanggal</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Tipe</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Jumlah</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Stok Akhir</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Catatan / Referensi</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Oleh</div></th>
                                </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                                @forelse($movements as $movement)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                        <td class="p-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($movement->created_at)->format('d M Y, H:i') }}</td>
                                        <td class="p-2 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $movement->quantity > 0 ? 'bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-800/30 text-red-800 dark:text-red-300' }}">
                                                {{ ucfirst(str_replace('_', ' ', $movement->type)) }}
                                            </span>
                                        </td>
                                        <td class="p-2 whitespace-nowrap text-center font-bold {{ $movement->quantity > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                        </td>
                                        <td class="p-2 whitespace-nowrap text-center font-bold text-gray-800 dark:text-gray-100">{{ $movement->stock_after }}</td>
                                        <td class="p-2 whitespace-nowrap text-gray-600 dark:text-gray-400 italic">{{ $movement->notes }}</td>
                                        <td class="p-2 whitespace-nowrap">{{ $movement->user->name ?? 'Sistem' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada riwayat pergerakan stok untuk produk ini.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 px-3">
                            {{ $movements->links('components.pagination-numeric') }}
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-16 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl mt-6">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-200">Silakan pilih produk</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan mencari nama atau SKU produk di atas untuk melihat riwayat stoknya.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Kartu Stok') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Produk</h3>

                    <!-- Pencarian Produk -->
                    <div class="relative">
                        <div class="flex items-center">
                            <input type="text"
                                   wire:model.live.debounce.300ms="searchQuery"
                                   placeholder="Ketik nama atau SKU produk untuk memulai..."
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                {{ $selectedProduct ? 'readonly' : '' }}>

                            @if($selectedProduct)
                                <button wire:click="clearSelection" class="ml-2 text-sm text-red-600 hover:text-red-900">
                                    Ganti Produk
                                </button>
                            @endif
                        </div>

                        @if(!empty($searchResults))
                            <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg">
                                @forelse($searchResults as $product)
                                    <li wire:click="selectProduct({{ $product->id }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                        {{ $product->name }} (SKU: {{ $product->sku }})
                                    </li>
                                @empty
                                    <li class="px-4 py-2 text-gray-500">Produk tidak ditemukan.</li>
                                @endforelse
                            </ul>
                        @endif
                    </div>

                    <!-- Hasil Laporan -->
                    @if($selectedProduct)
                        <div class="mt-8 border-t border-gray-200 pt-8">
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <h4 class="font-bold text-xl text-gray-800">{{ $selectedProduct->name }}</h4>
                                <p class="text-sm text-gray-600">SKU: {{ $selectedProduct->sku }}</p>
                                <p class="text-sm font-bold text-gray-800 mt-2">Stok Saat Ini: <span class="text-blue-600 text-lg">{{ $selectedProduct->stock }}</span></p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Akhir</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan / Referensi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Oleh</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($movements as $movement)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($movement->created_at)->format('d M Y, H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $movement->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $movement->type)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-center {{ $movement->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold text-center">{{ $movement->stock_after }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $movement->notes }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $movement->user->name ?? 'Sistem' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada riwayat pergerakan stok untuk produk ini.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $movements->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 border-t border-dashed mt-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Silakan pilih produk</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan mencari nama atau SKU produk di atas untuk melihat riwayat stoknya.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

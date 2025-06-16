<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Buat Pesanan Pembelian Baru

            <x-slot name="actions">
                <a href="{{ route('purchases.orders') }}" class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                    Batal
                </a>
                <button wire:click.prevent="saveOrder" class="btn bg-indigo-600 hover:bg-indigo-700 text-white">
                    <span wire:loading.remove wire:target="saveOrder">Simpan Pesanan</span>
                    <span wire:loading wire:target="saveOrder">Menyimpan...</span>
                </button>
            </x-slot>
        </x-page-header>

        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border-t-4 border-red-500 rounded-b text-red-900 dark:text-red-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p class="font-bold">Gagal</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Pesanan</h2>
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Pemasok</label>
                            <select wire:model="supplier_id" id="supplier_id" class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                <option value="">Pilih Pemasok</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="order_date" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tanggal Pesan</label>
                            <input type="date" wire:model="order_date" id="order_date" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                            @error('order_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Tambah Produk ke Pesanan</h2>
                </header>
                <div class="p-5">
                    <div class="relative">
                        <label for="search" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Cari Produk</label>
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Ketik nama atau SKU produk..." class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">

                        @if(!empty($searchResults) && strlen($searchQuery) > 1)
                            <ul class="absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg mt-1 shadow-lg max-h-60 overflow-y-auto">
                                @foreach($searchResults as $product)
                                    <li wire:click="addProduct({{ $product->id }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $product->name }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">(SKU: {{ $product->sku }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Rincian Pesanan</h2>
                </header>
                <div class="p-3">
                    @error('orderItems') <span class="text-red-500 text-xs mb-2 d-block px-2">{{ $message }}</span> @enderror
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left" style="width: 120px;">Kuantitas</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left" style="width: 150px;">Harga Beli</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left" style="width: 150px;">Subtotal</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Aksi</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($orderItems as $productId => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                    <td class="p-2"><div class="font-medium text-gray-800 dark:text-gray-100">{{ $item['product_name'] }}</div></td>
                                    <td class="p-2">
                                        <input type="number" wire:model.live="orderItems.{{ $productId }}.quantity" class="form-input w-full text-sm dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    </td>
                                    <td class="p-2">
                                        <input type="number" wire:model.live="orderItems.{{ $productId }}.price" class="form-input w-full text-sm dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    </td>
                                    <td class="p-2 whitespace-nowrap">Rp {{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}</td>
                                    <td class="p-2 text-center">
                                        <button wire:click="removeProduct({{ $productId }})" class="btn-sm bg-red-500 hover:bg-red-600 text-white">&times;</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada produk yang ditambahkan.</td>
                                </tr>
                            @endforelse
                            </tbody>
                            @if (!empty($orderItems))
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="3" class="p-2 text-right text-sm font-bold text-gray-700 dark:text-gray-200 uppercase">Total Keseluruhan</td>
                                    <td colspan="2" class="p-2 text-left text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                                </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Catatan (Opsional)</h2>
                </header>
                <div class="p-5">
                    <textarea wire:model="notes" id="notes" rows="3" class="form-textarea w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Tambahkan catatan untuk pesanan ini..."></textarea>
                </div>
            </div>

        </div>
    </div>
</div>

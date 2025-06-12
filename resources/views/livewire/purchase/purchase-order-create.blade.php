<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Pesanan Pembelian Baru') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    @if (session()->has('error'))
                        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Form Utama PO -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Pemasok</label>
                            <select wire:model="supplier_id" id="supplier_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Pilih Pemasok</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="order_date" class="block text-sm font-medium text-gray-700">Tanggal Pesan</label>
                            <input type="date" wire:model="order_date" id="order_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('order_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Pencarian Produk -->
                    <div class="mb-6 relative">
                        <label for="search" class="block text-sm font-medium text-gray-700">Tambah Produk</label>
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Ketik nama atau SKU produk..." class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">

                        @if(!empty($searchResults))
                            <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg">
                                @foreach($searchResults as $product)
                                    <li wire:click="addProduct({{ $product->id }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                                        {{ $product->name }} (SKU: {{ $product->sku }})
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Tabel Item Pesanan -->
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Kuantitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Harga Beli</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Subtotal</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orderItems as $productId => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item['product_name'] }}</td>
                                    <td class="px-6 py-4">
                                        <input type="number" wire:model.live="orderItems.{{ $productId }}.quantity" class="w-24 form-input border-gray-300 rounded-md shadow-sm">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" wire:model.live="orderItems.{{ $productId }}.price" class="w-36 form-input border-gray-300 rounded-md shadow-sm">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button wire:click="removeProduct({{ $productId }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">Belum ada produk yang ditambahkan.</td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-700">Total Keseluruhan</td>
                                <td colspan="2" class="px-6 py-3 text-left text-sm font-bold text-gray-900">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            </tr>
                            </tfoot>
                        </table>
                        @error('orderItems') <span class="text-red-500 text-xs mt-2 d-block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan dan Tombol Simpan -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                        <textarea wire:model="notes" id="notes" rows="3" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('purchases.orders') }}" class="bg-gray-200 hover:bg-gray-300 text-black font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button wire:click.prevent="saveOrder" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-3">
                            <span wire:loading.remove wire:target="saveOrder">Simpan Pesanan</span>
                            <span wire:loading wire:target="saveOrder">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

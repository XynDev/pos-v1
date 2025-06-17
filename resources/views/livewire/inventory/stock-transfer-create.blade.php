<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Buat Transfer Stok Baru

            <x-slot name="actions">
                <a href="{{ route('inventory.transfers.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                    Batal
                </a>
                <button wire:click.prevent="saveTransfer" class="btn bg-indigo-600 hover:bg-indigo-700 text-white">
                    Simpan Transfer
                </button>
            </x-slot>
        </x-page-header>

        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border-t-4 border-red-500 rounded-b text-red-900 dark:text-red-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Transfer</h2>
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="transfer_date" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tanggal Transfer</label>
                            <input type="date" wire:model="transfer_date" id="transfer_date" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                            @error('transfer_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="from_location_id" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Dari Lokasi (Asal)</label>
                            <select wire:model.live="from_location_id" id="from_location_id" class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                <option value="">Pilih Lokasi Asal</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('from_location_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="to_location_id" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Ke Lokasi (Tujuan)</label>
                            <select wire:model="to_location_id" id="to_location_id" class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                <option value="">Pilih Lokasi Tujuan</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('to_location_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            @if($from_location_id)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Rincian Item Transfer</h2>
                    </header>
                    <div class="p-5">
                        <div class="relative mb-6">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tambah Produk</label>
                            <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Cari produk di lokasi asal..." class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                            @if(!empty($searchResults))
                                <ul class="absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg mt-1 shadow-lg max-h-60 overflow-y-auto">
                                    @forelse($searchResults as $product)
                                        <li wire:click="addProduct({{ $product->id }})" class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $product->name }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">(Stok: {{ $product->locations->find($from_location_id)->pivot->stock }})</span>
                                        </li>
                                    @empty
                                        <li class="px-4 py-2 text-gray-500">Produk tidak ditemukan.</li>
                                    @endforelse
                                </ul>
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="table-auto w-full dark:text-gray-300">
                                <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                                <tr>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Stok di Asal</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left" style="width: 130px;">Jml Transfer</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center" style="width: 80px;">Aksi</div></th>
                                </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                                @forelse($transferItems as $productId => $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                        <td class="p-2">{{ $item['name'] }}</td>
                                        <td class="p-2 text-center">{{ $item['stock_at_source'] }}</td>
                                        <td class="p-2">
                                            <input type="number" wire:model="transferItems.{{ $productId }}.quantity" class="form-input w-full text-sm dark:bg-gray-700/50 dark:border-gray-600" max="{{ $item['stock_at_source'] }}">
                                        </td>
                                        <td class="p-2 text-center">
                                            <button wire:click="removeProduct({{ $productId }})" class="btn-sm bg-red-500 hover:bg-red-600 text-white">&times;</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada produk yang ditambahkan.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                            @error('transferItems') <span class="text-red-500 text-xs mt-2 d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Catatan (Opsional)</h2>
                </header>
                <div class="p-5">
                    <textarea wire:model="notes" id="notes" rows="3" class="form-textarea w-full dark:bg-gray-700/50 dark:border-gray-600" placeholder="Tambahkan catatan untuk transfer ini..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

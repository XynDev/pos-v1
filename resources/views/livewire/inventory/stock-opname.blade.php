<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Stock Opname / Penyesuaian Stok
        </x-page-header>

        @if (session()->has('message'))
            <div class="bg-teal-100 dark:bg-teal-900/30 border-t-4 border-teal-500 rounded-b text-teal-900 dark:text-teal-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border-t-4 border-red-500 rounded-b text-red-900 dark:text-red-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">1. Cari Produk</h2>
                </header>
                <div class="p-5">
                    <div class="relative">
                        <label for="search" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Cari Produk (Simple atau Varian)</label>
                        <input type="text"
                               id="search"
                               wire:model.live.debounce.300ms="searchQuery"
                               placeholder="Ketik nama atau SKU produk..."
                               class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200"
                               autocomplete="off">

                        @if(!empty($searchResults) && strlen($searchQuery) > 1)
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
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">2. Lakukan Penyesuaian</h2>
                        <button wire:click="clearSelection" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">Pilih Produk Lain</button>
                    </header>
                    <div class="p-5">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg mb-6">
                            <h3 class="font-bold text-xl text-gray-800 dark:text-gray-100">{{ $selectedProduct->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">SKU: {{ $selectedProduct->sku }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Stok Sistem</label>
                                <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $selectedProduct->stock }}</p>
                            </div>
                            <div>
                                <label for="physical_stock" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Stok Fisik (Hasil Hitung)</label>
                                <input type="number" wire:model.live="physical_stock" id="physical_stock" class="form-input w-full mt-1 dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                @error('physical_stock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Selisih</label>
                                <p class="text-2xl font-bold {{ $difference == 0 ? 'text-gray-800 dark:text-gray-100' : ($difference > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400') }}">
                                    {{ $difference > 0 ? '+' : '' }}{{ $difference }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Alasan Penyesuaian</label>
                            <textarea wire:model="notes" id="notes" rows="2" class="form-textarea w-full mt-1 dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: Stok rusak, Kesalahan input, dll."></textarea>
                            @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-6 text-right">
                            <button wire:click="saveAdjustment" class="btn bg-indigo-600 hover:bg-indigo-700 text-white disabled:opacity-50 disabled:cursor-not-allowed" @if($difference == 0) disabled @endif>
                                Simpan Penyesuaian
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

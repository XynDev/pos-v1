<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Stock Opname / Penyesuaian Stok') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                @if (session()->has('message'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mb-6" role="alert">
                        <p>{{ session('message') }}</p>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-6" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="mb-6">
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari Produk (Simple atau Varian)</label>
                        <div class="relative">
                            <input type="text"
                                   id="search"
                                   wire:model.live.debounce.300ms="searchQuery"
                                   placeholder="Ketik nama atau SKU produk..."
                                   class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                   autocomplete="off">

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
                    </div>

                    @if($selectedProduct)
                        <div class="border-t pt-6">
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-bold text-xl text-gray-800">{{ $selectedProduct->name }}</h3>
                                    <button wire:click="clearSelection" class="text-sm text-blue-600 hover:text-blue-900 font-semibold">Pilih Produk Lain</button>
                                </div>
                                <p class="text-sm text-gray-600">SKU: {{ $selectedProduct->sku }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Stok Sistem</label>
                                    <p class="text-2xl font-bold text-gray-800">{{ $selectedProduct->stock }}</p>
                                </div>
                                <div>
                                    <label for="physical_stock" class="block text-sm font-medium text-gray-700">Stok Fisik (Hasil Hitung)</label>
                                    <input type="number" wire:model.live="physical_stock" id="physical_stock" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('physical_stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Selisih</label>
                                    <p class="text-2xl font-bold {{ $difference == 0 ? 'text-gray-800' : ($difference > 0 ? 'text-green-600' : 'text-red-600') }}">
                                        {{ $difference > 0 ? '+' : '' }}{{ $difference }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Alasan Penyesuaian</label>
                                <textarea wire:model="notes" id="notes" rows="2" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Contoh: Stok rusak, Kesalahan input, dll."></textarea>
                                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-6 text-right">
                                <button wire:click="saveAdjustment" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" @if($difference == 0) disabled @endif>
                                    Simpan Penyesuaian
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

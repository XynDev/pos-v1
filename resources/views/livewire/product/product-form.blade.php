<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            {{ $product->exists ? 'Edit Produk' : 'Buat Produk Baru' }}

            <x-slot name="actions">
                <button wire:click="save" class="btn bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6">
                    <span wire:loading.remove wire:target="save">Simpan Produk</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </x-slot>
        </x-page-header>
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                    <h2 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Umum</h2>
                </header>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Kolom Kiri & Tengah untuk Input --}}
                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="sm:col-span-2">
                                <label for="name"
                                       class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Nama
                                    Produk</label>
                                <input type="text" id="name" wire:model.defer="name"
                                       class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200"
                                       placeholder="Masukkan nama produk">
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="productType"
                                       class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Tipe
                                    Produk</label>
                                <select wire:model.live="productType" id="productType"
                                        class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    <option value="simple">Simple</option>
                                    <option value="variable">Variable</option>
                                    <option value="bundle">Bundle/Racikan</option>
                                </select>
                            </div>
                            <div>
                                <label for="category_id"
                                       class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Kategori</label>
                                <select wire:model.defer="category_id" id="category_id"
                                        class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span
                                    class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="brand_id"
                                       class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Merek</label>
                                <select wire:model.defer="brand_id" id="brand_id"
                                        class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">Pilih Merek</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <span
                                    class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="description"
                                       class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Deskripsi</label>
                                <textarea id="description" wire:model.defer="description" rows="4"
                                          class="form-textarea w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200"
                                          placeholder="Deskripsi singkat produk..."></textarea>
                            </div>
                        </div>

                        {{-- Kolom Kanan untuk Gambar --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Gambar
                                Utama</label>
                            <div
                                class="mt-1 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-center">
                                <div class="mb-4">
                                    @if ($newImage)
                                        <img src="{{ $newImage->temporaryUrl() }}"
                                             class="w-full h-48 object-contain rounded-lg mx-auto">
                                    @elseif ($image)
                                        <img src="{{ asset('storage/' . $image) }}"
                                             class="w-full h-48 object-contain rounded-lg mx-auto">
                                    @else
                                        <div
                                            class="w-full h-48 bg-gray-100 dark:bg-gray-700/50 flex items-center justify-center rounded-lg">
                                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-500"
                                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" wire:model="newImage" id="newImage"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/50 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900"/>
                                <div wire:loading wire:target="newImage" class="text-sm text-gray-500 mt-2">
                                    Uploading...
                                </div>
                                @error('newImage') <span
                                    class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Form Dinamis berdasarkan Tipe Produk --}}
            <div>
                @if($productType == 'simple')
                    @include('livewire.product._simple-product-form')
                @elseif($productType == 'variable')
                    @include('livewire.product._variable-product-form')
                @else
                    @include('livewire.product._bundle-product-form')
                @endif
            </div>
        </div>
    </div>
</div>

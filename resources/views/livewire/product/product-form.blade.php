<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->exists ? 'Edit Produk' : 'Buat Produk Baru' }}
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Nama Produk</label>
                                <input type="text" wire:model.defer="name" class="mt-1 block w-full form-input rounded-md shadow-sm">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Tipe Produk</label>
                                <select wire:model.live="productType" class="mt-1 block w-full form-select rounded-md shadow-sm">
                                    <option value="simple">Simple</option>
                                    <option value="variable">Variable</option>
                                    <option value="bundle">Bundle/Racikan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Kategori</label>
                                <select wire:model.defer="category_id" class="mt-1 block w-full form-select rounded-md shadow-sm">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Merek</label>
                                <select wire:model.defer="brand_id" class="mt-1 block w-full form-select rounded-md shadow-sm">
                                    <option value="">Pilih Merek</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                <textarea wire:model.defer="description" rows="4" class="mt-1 block w-full form-textarea rounded-md shadow-sm"></textarea>
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Gambar Utama</label>
                            <div class="mt-1 p-4 border-2 border-dashed rounded-md">
                                <input type="file" wire:model="newImage" id="newImage" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                <div wire:loading wire:target="newImage" class="text-sm text-gray-500 mt-2">Uploading...</div>
                                @error('newImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror

                                <div class="mt-4">
                                    @if ($newImage)
                                        <img src="{{ $newImage->temporaryUrl() }}" class="w-full h-auto object-cover rounded">
                                    @elseif ($image)
                                        <img src="{{ asset('storage/' . $image) }}" class="w-full h-auto object-cover rounded">
                                    @else
                                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center rounded">
                                            <span class="text-gray-400">Preview Gambar</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-6 border-t border-gray-200"></div>

                    @if($productType == 'simple')
                        @include('livewire.product._simple-product-form')
                    @elseif($productType == 'variable')
                        @include('livewire.product._variable-product-form')
                    @else
                    @include('livewire.product._bundle-product-form')
                    @endif

                    <div class="mt-8 flex justify-end">
                        <button wire:click="save" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">
                            Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

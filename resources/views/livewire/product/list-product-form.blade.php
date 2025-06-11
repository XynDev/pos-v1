<div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        {{ $isEditMode ? 'Edit Produk' : 'Buat Produk Baru' }}
                    </h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div>
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Produk:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="name" wire:model.defer="name">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="sku" class="block text-gray-700 text-sm font-bold mb-2">SKU (Stock Keeping Unit):</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="sku" wire:model.defer="sku">
                                @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Kategori:</label>
                                <select wire:model.defer="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="brand_id" class="block text-gray-700 text-sm font-bold mb-2">Merek:</label>
                                <select wire:model.defer="brand_id" id="brand_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                                    <option value="">-- Pilih Merek --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                                <textarea id="description" wire:model.defer="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- Kolom Kanan -->
                        <div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="purchase_price" class="block text-gray-700 text-sm font-bold mb-2">Harga Beli:</label>
                                    <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="purchase_price" wire:model.defer="purchase_price">
                                    @error('purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                                <div class="mb-4">
                                    <label for="selling_price" class="block text-gray-700 text-sm font-bold mb-2">Harga Jual:</label>
                                    <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="selling_price" wire:model.defer="selling_price">
                                    @error('selling_price') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stok Awal:</label>
                                <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="stock" wire:model.defer="stock">
                                @error('stock') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Gambar Produk:</label>
                                <input type="file" id="newImage" wire:model="newImage" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                <div wire:loading wire:target="newImage" class="text-sm text-gray-500 mt-1">Uploading...</div>
                                @error('newImage') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror

                                <!-- Image Preview -->
                                <div class="mt-4">
                                    @if ($newImage)
                                        <img src="{{ $newImage->temporaryUrl() }}" class="w-32 h-32 object-cover rounded">
                                    @elseif ($image)
                                        <img src="{{ asset('storage/' . $image) }}" class="w-32 h-32 object-cover rounded">
                                    @endif
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="is_active" class="flex items-center">
                                    <input type="checkbox" id="is_active" wire:model.defer="is_active" class="form-checkbox h-5 w-5 text-blue-600">
                                    <span class="ml-2 text-gray-700">Produk Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button wire:click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

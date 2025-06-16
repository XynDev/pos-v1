<div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center sm:items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        {{-- Latar belakang overlay --}}
        <div class="fixed inset-0 bg-gray-500/50 dark:bg-gray-900/70 backdrop-blur-sm" aria-hidden="true"></div>

        {{-- Trik untuk memusatkan modal secara vertikal --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700">
            <form>
                {{-- Header Modal --}}
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-800 dark:text-gray-100" id="modal-title">
                        {{ $isEditMode ? 'Edit Produk' : 'Buat Produk Baru' }}
                    </h3>
                </header>

                {{-- Konten Form --}}
                <div class="bg-white dark:bg-gray-800 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Nama Produk</label>
                                <input type="text" id="name" wire:model.defer="name" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: Kopi Robusta">
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">SKU (Stock Keeping Unit)</label>
                                <input type="text" id="sku" wire:model.defer="sku" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: KOP-ROB-001">
                                @error('sku') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Kategori</label>
                                <select wire:model.defer="category_id" id="category_id" class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="brand_id" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Merek</label>
                                <select wire:model.defer="brand_id" id="brand_id" class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    <option value="">-- Pilih Merek --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Deskripsi</label>
                                <textarea id="description" wire:model.defer="description" rows="4" class="form-textarea w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Deskripsi singkat mengenai produk..."></textarea>
                                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="purchase_price" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Harga Beli</label>
                                    <input type="number" id="purchase_price" wire:model.defer="purchase_price" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: 50000">
                                    @error('purchase_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="selling_price" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Harga Jual</label>
                                    <input type="number" id="selling_price" wire:model.defer="selling_price" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: 75000">
                                    @error('selling_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Stok Awal</label>
                                <input type="number" id="stock" wire:model.defer="stock" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: 100">
                                @error('stock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Gambar Produk</label>
                                <input type="file" id="newImage" wire:model="newImage" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/50 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900"/>
                                <div wire:loading wire:target="newImage" class="text-sm text-gray-500 mt-1">Uploading...</div>
                                @error('newImage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror

                                <!-- Image Preview -->
                                <div class="mt-4">
                                    @if ($newImage)
                                        <img src="{{ $newImage->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                    @elseif ($image)
                                        <img src="{{ asset('storage/' . $image) }}" class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label for="is_active" class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="is_active" wire:model.defer="is_active" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-900 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Produk Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <footer class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                    {{-- Tombol Simpan (Aksi Utama) --}}
                    <button wire:click.prevent="store()" type="button" class="btn w-full sm:w-auto sm:ml-3 bg-indigo-600 hover:bg-indigo-700 text-white">
                        Simpan
                    </button>
                    {{-- Tombol Batal (Aksi Sekunder) --}}
                    <button wire:click="closeModal()" type="button" class="btn w-full sm:w-auto mt-3 sm:mt-0 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                        Batal
                    </button>
                </footer>
            </form>
        </div>
    </div>
</div>

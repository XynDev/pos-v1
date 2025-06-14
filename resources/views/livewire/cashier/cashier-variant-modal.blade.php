<div class="fixed z-30 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                    Pilih Varian untuk: {{ $productForVariantSelection->name }}
                </h3>
                <div class="max-h-60 overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Varian</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Stok</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($variantsOfSelectedProduct as $variant)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-800">{{ str_replace($productForVariantSelection->name, '', $variant->name) }}</td>
                                <td class="px-4 py-3 text-sm text-center">{{ $variant->stock }}</td>
                                <td class="px-4 py-3 text-sm text-right">Rp {{ number_format($variant->selling_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="addVariantToCart({{ $variant->id }})" class="bg-indigo-600 text-white px-3 py-1 text-xs rounded hover:bg-indigo-700">Pilih</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada varian yang tersedia.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button wire:click="closeVariantModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

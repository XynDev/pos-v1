<div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                    Proses Refund & Retur Barang
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Beli</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Refund</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($refundItems as $saleItemId => $item)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['name'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-center">{{ $item['max_quantity'] }}</td>
                                <td class="px-4 py-2 text-center">
                                    <input type="number"
                                           wire:model.live="refundItems.{{ $saleItemId }}.quantity"
                                           class="w-20 form-input border-gray-300 rounded-md shadow-sm text-center"
                                           min="0"
                                           max="{{ $item['max_quantity'] }}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @error('refund') <span class="text-red-500 text-xs mt-2 d-block">{{ $message }}</span> @enderror
                </div>
                <div class="mt-4 pt-4 border-t text-right">
                    <p class="text-sm text-gray-600">Total Nilai Refund</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalRefundAmount, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                <button wire:click="processRefund" wire:confirm="Anda yakin ingin memproses refund ini? Stok akan dikembalikan dan tidak bisa dibatalkan." type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:w-auto sm:text-sm">
                    Proses Refund
                </button>
                <button wire:click="closeRefundModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

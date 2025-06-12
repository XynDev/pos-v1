<div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                    Proses Pembayaran
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between text-xl font-bold border-b pb-2">
                        <span>Total Tagihan:</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <label for="paymentMethod" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <select wire:model="paymentMethod" id="paymentMethod" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="debit_card">Kartu Debit</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div>
                        <label for="paymentAmount" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                        <input type="text" id="paymentAmount" wire:model.live="paymentAmount" class="mt-1 block w-full text-right text-lg font-bold p-2 border-gray-300 rounded-md shadow-sm" placeholder="0">
                        @error('paymentAmount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Kembalian:</span>
                        <span>Rp {{ number_format($changeDue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                <button wire:click.prevent="processSale" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:w-auto sm:text-sm">
                    <span wire:loading.remove wire:target="processSale">Selesaikan Transaksi</span>
                    <span wire:loading wire:target="processSale">Memproses...</span>
                </button>
                <button wire:click="closePaymentModal" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

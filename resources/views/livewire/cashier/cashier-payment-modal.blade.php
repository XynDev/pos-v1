<div
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-data="{ show: @entangle('isPaymentModalOpen') }"
    x-show="show"
    @keydown.escape.window="show = false"
    style="display: none;"
>
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500/50 dark:bg-gray-900/70 backdrop-blur-sm transition-opacity"
        aria-hidden="true"
    ></div>

    <div class="fixed inset-0 z-50 w-screen overflow-y-auto" @click="show = false">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                x-show="show"
                @click.stop
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700"
            >
                <form>
                    <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-semibold text-gray-800 dark:text-gray-100" id="modal-title">
                            Proses Pembayaran
                        </h3>
                    </header>

                    <div class="bg-white dark:bg-gray-800 p-6 space-y-4">
                        <div class="flex justify-between text-xl font-bold border-b dark:border-gray-700 pb-2">
                            <span class="text-gray-800 dark:text-gray-100">Total Tagihan:</span>
                            <span class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <label for="paymentMethod" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Metode Pembayaran</label>
                            <select wire:model="paymentMethod" id="paymentMethod" class="form-select w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                <option value="cash">Tunai (Cash)</option>
                                <option value="debit_card">Kartu Debit</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div>
                            <label for="paymentAmount" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Jumlah Bayar</label>
                            <input type="text" id="paymentAmount" wire:model.live="paymentAmount" class="form-input w-full text-right text-lg font-bold p-2 dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="0">
                            @error('paymentAmount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-between text-lg font-semibold pt-2 border-t dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-300">Kembalian:</span>
                            <span class="text-gray-800 dark:text-gray-100">Rp {{ number_format($changeDue, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <footer class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                        <button wire:click.prevent="processSale" type="button" class="btn w-full sm:w-auto sm:ml-3 bg-indigo-600 hover:bg-indigo-700 text-white">
                            <span wire:loading.remove wire:target="processSale">Selesaikan Transaksi</span>
                            <span wire:loading wire:target="processSale">Memproses...</span>
                        </button>
                        <button wire:click="closePaymentModal" type="button" class="btn w-full sm:w-auto mt-3 sm:mt-0 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                            Batal
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>

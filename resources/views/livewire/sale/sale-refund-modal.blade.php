<div
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-data="{ show: @entangle('isRefundModalOpen') }"
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
                class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200 dark:border-gray-700"
            >
                <form>
                    <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-semibold text-gray-800 dark:text-gray-100" id="modal-title">
                            Proses Refund & Retur Barang
                        </h3>
                    </header>

                    <div class="bg-white dark:bg-gray-800 p-6">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full dark:text-gray-300">
                                <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                                <tr>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Produk</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Jml Beli</div></th>
                                    <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Jml Refund</div></th>
                                </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                                @foreach($refundItems as $saleItemId => $item)
                                    <tr>
                                        <td class="p-2 whitespace-nowrap">
                                            <div class="font-medium text-gray-800 dark:text-gray-100">{{ $item['name'] }}</div>
                                        </td>
                                        <td class="p-2 whitespace-nowrap text-center">{{ $item['max_quantity'] }}</td>
                                        <td class="p-2 text-center">
                                            <input type="number"
                                                   wire:model.live="refundItems.{{ $saleItemId }}.quantity"
                                                   class="form-input w-24 text-center dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200"
                                                   min="0"
                                                   max="{{ $item['max_quantity'] }}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @error('refund') <span class="text-red-500 text-xs mt-2 d-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Nilai Refund</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-500">Rp {{ number_format($totalRefundAmount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <footer class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="processRefund" wire:confirm="Anda yakin ingin memproses refund ini? Stok akan dikembalikan dan tidak bisa dibatalkan." type="button" class="btn w-full sm:w-auto sm:ml-3 bg-red-600 hover:bg-red-700 text-white">
                            Proses Refund
                        </button>
                        <button wire:click="closeRefundModal" type="button" class="btn w-full sm:w-auto mt-3 sm:mt-0 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                            Batal
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>

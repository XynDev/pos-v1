<div
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-data="{ show: @entangle('isVariantModalOpen') }"
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
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-semibold text-gray-800 dark:text-gray-100" id="modal-title">
                        Pilih Varian untuk: <span class="text-indigo-500">{{ $productForVariantSelection->name ?? '' }}</span>
                    </h3>
                </header>

                <div class="bg-white dark:bg-gray-800 p-3">
                    <div class="overflow-y-auto max-h-80 custom-scrollbar">
                        <table class="table-auto w-full dark:text-gray-300">
                            <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                            <tr>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Varian</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Stok</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-right">Harga</div></th>
                                <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Aksi</div></th>
                            </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($variantsOfSelectedProduct as $variant)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                    <td class="p-2">
                                        <div class="font-medium text-gray-800 dark:text-gray-100">{{ str_replace($productForVariantSelection->name, '', $variant->name) }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-center">{{ $variant->stock }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-right font-semibold">Rp {{ number_format($variant->selling_price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="p-2 text-center">
                                        <button wire:click="addVariantToCart({{ $variant->id }})" class="btn-sm bg-indigo-600 hover:bg-indigo-700 text-white">Pilih</button>
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

                <footer class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeVariantModal" type="button" class="btn w-full sm:w-auto bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                        Batal
                    </button>
                </footer>
            </div>
        </div>
    </div>
</div>

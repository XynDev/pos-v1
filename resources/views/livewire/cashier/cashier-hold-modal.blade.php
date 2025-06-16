<div
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-data="{ show: @entangle('isHoldModalOpen') }"
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
                            Tahan Transaksi
                        </h3>
                    </header>

                    <div class="bg-white dark:bg-gray-800 p-6 space-y-4">
                        <div>
                            <label for="holdReferenceName" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Nama Referensi</label>
                            <input type="text" id="holdReferenceName" wire:model="holdReferenceName" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Contoh: Pelanggan Baju Merah">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Beri nama agar mudah dikenali nanti.</p>
                            @error('holdReferenceName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <footer class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                        <button wire:click.prevent="holdTransaction" type="button" class="btn w-full sm:w-auto sm:ml-3 bg-yellow-500 hover:bg-yellow-600 text-white">
                            Tahan Transaksi
                        </button>
                        <button wire:click="$set('isHoldModalOpen', false)" type="button" class="btn w-full sm:w-auto mt-3 sm:mt-0 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                            Batal
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>

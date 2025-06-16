<div
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
    x-data="{ show: @entangle('isModalOpen') }"
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
                            {{ $isEditMode ? 'Edit Pemasok' : 'Buat Pemasok Baru' }}
                        </h3>
                    </header>

                    <div class="bg-white dark:bg-gray-800 p-6 space-y-4">
                        <div>
                            <label for="supplier_name" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Nama Pemasok</label>
                            <input type="text" id="supplier_name" placeholder="Masukkan Nama Pemasok" wire:model.defer="name" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="supplier_email" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" id="supplier_email" placeholder="contoh@mail.com" wire:model.defer="email" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="supplier_phone" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Telepon</label>
                            <input type="text" id="supplier_phone" placeholder="08123456789" wire:model.defer="phone" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="supplier_contact_person" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Contact Person (PIC)</label>
                            <input type="text" id="supplier_contact_person" placeholder="Nama PIC" wire:model.defer="contact_person" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                            @error('contact_person') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="supplier_address" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Alamat</label>
                            <textarea id="supplier_address" wire:model.defer="address" rows="3" class="form-textarea w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Masukkan alamat lengkap..."></textarea>
                            @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <footer class="bg-gray-50 dark:bg-gray-800/50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-xl border-t border-gray-200 dark:border-gray-700">
                        <button wire:click.prevent="store()" type="button" class="btn w-full sm:w-auto sm:ml-3 bg-indigo-600 hover:bg-indigo-700 text-white">
                            Simpan
                        </button>
                        <button wire:click="closeModal()" type="button" class="btn w-full sm:w-auto mt-3 sm:mt-0 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                            Batal
                        </button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>

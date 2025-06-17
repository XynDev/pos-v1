<div>
    <form wire:submit.prevent="save" id="settings-form">
        <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
            <x-page-header>
                Pengaturan Aplikasi

                <x-slot name="actions">
                    <button type="submit" form="settings-form" class="btn bg-indigo-600 hover:bg-indigo-700 text-white">
                        Simpan Pengaturan
                    </button>
                </x-slot>
            </x-page-header>

            @if (session()->has('message'))
                <div class="bg-teal-100 dark:bg-teal-900/30 border-t-4 border-teal-500 rounded-b text-teal-900 dark:text-teal-300 px-4 py-3 shadow-md mb-6" role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Toko</h2>
                    </header>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="store_name" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Nama Toko</label>
                                <input type="text" wire:model.defer="store_name" id="store_name" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                @error('store_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="store_email" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Email Toko</label>
                                <input type="email" wire:model.defer="store_email" id="store_email" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                @error('store_email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="store_phone" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Telepon Toko</label>
                                <input type="text" wire:model.defer="store_phone" id="store_phone" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                @error('store_phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="store_address" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Alamat Toko</label>
                                <textarea wire:model.defer="store_address" id="store_address" rows="3" class="form-textarea w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200"></textarea>
                                @error('store_address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Branding & Struk</h2>
                    </header>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                            <!-- Logo Toko -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Logo Toko</label>
                                <div class="mt-2 flex items-center space-x-4">
                                    @if ($newLogo)
                                        <img src="{{ $newLogo->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-full">
                                    @elseif ($store_logo)
                                        <img src="{{ asset('storage/' . $store_logo) }}" class="h-16 w-16 object-cover rounded-full">
                                    @else
                                        <span class="inline-block h-16 w-16 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                                            <svg class="h-full w-full text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.997A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        </span>
                                    @endif
                                    <div>
                                        <input type="file" wire:model="newLogo" id="newLogo" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/50 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900">
                                        @error('newLogo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="receipt_footer_note" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Catatan Kaki di Struk</label>
                                <input type="text" wire:model.defer="receipt_footer_note" id="receipt_footer_note" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Terima kasih telah berbelanja!">
                                @error('receipt_footer_note') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

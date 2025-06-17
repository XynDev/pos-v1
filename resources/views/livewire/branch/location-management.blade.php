<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Manajemen Lokasi (Gudang/Cabang)

            <x-slot name="actions">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari lokasi..."
                           class="form-input w-full md:w-64 pl-9 dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                    <div class="absolute inset-y-0 left-0 flex items-center justify-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                </div>
                <button wire:click="create()" class="btn bg-indigo-600 hover:bg-indigo-700 text-white whitespace-nowrap">
                    <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"></path>
                    </svg>
                    <span class="hidden xs:block ml-2">Tambah Lokasi</span>
                </button>
            </x-slot>
        </x-page-header>

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
            <div class="p-3">
                @if (session()->has('message'))
                    <div class="bg-teal-100 dark:bg-teal-900/30 border-t-4 border-teal-500 rounded-b text-teal-900 dark:text-teal-300 px-4 py-3 shadow-md my-3" role="alert">
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="table-auto w-full dark:text-gray-300">
                        <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 rounded-xs">
                        <tr>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Nama Lokasi</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-left">Alamat</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Status</div></th>
                            <th class="p-2 whitespace-nowrap"><div class="font-semibold text-center">Aksi</div></th>
                        </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($locations as $location)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                <td class="p-2">
                                    <div class="font-medium text-gray-800 dark:text-gray-100">{{ $location->name }}</div>
                                </td>
                                <td class="p-2">
                                    <div>{{ $location->address }}</div>
                                </td>
                                <td class="p-2">
                                    <div class="text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $location->is_active ? 'bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-800/30 text-red-800 dark:text-red-300' }}">
                                            {{ $location->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-2">
                                    <div class="flex justify-center items-center space-x-2">
                                        <button wire:click="edit({{ $location->id }})" class="btn-sm bg-yellow-500 hover:bg-yellow-600 text-white">Edit</button>
                                        <button wire:click="delete({{ $location->id }})" class="btn-sm bg-red-500 hover:bg-red-600 text-white">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-4 text-center text-gray-500 dark:text-gray-400" colspan="4">
                                    Tidak ada data lokasi untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                <div class="mt-4 px-3">
                    {{ $locations->links('components.pagination-numeric') }}
                </div>
            </div>
        </div>
    </div>

    @if($isModalOpen)
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
                                    {{ $isEditMode ? 'Edit Lokasi' : 'Buat Lokasi Baru' }}
                                </h3>
                            </header>
                            <div class="bg-white dark:bg-gray-800 p-6 space-y-4">
                                <div>
                                    <label for="location_name" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Nama Lokasi</label>
                                    <input type="text" id="location_name" placeholder="Contoh: Gudang Utama" wire:model.defer="name" class="form-input w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200">
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="location_address" class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Alamat</label>
                                    <textarea id="location_address" wire:model.defer="address" rows="3" class="form-textarea w-full dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-200" placeholder="Masukkan alamat lengkap lokasi..."></textarea>
                                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                                </div>
                                <div>
                                    <label for="is_active" class="flex items-center cursor-pointer">
                                        <input type="checkbox" id="is_active" wire:model.defer="is_active" class="form-checkbox h-4 w-4 text-indigo-600 rounded border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-900 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Lokasi Aktif</span>
                                    </label>
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
    @endif
</div>

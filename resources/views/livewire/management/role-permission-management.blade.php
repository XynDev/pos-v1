<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Manajemen Role & Permission

            <x-slot name="actions">
                <button wire:click="create()" class="btn bg-indigo-600 hover:bg-indigo-700 text-white whitespace-nowrap">
                    <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"></path>
                    </svg>
                    <span class="hidden xs:block ml-2">Buat Role Baru</span>
                </button>
            </x-slot>
        </x-page-header>

        @if (session()->has('message'))
            <div class="bg-teal-100 dark:bg-teal-900/30 border-t-4 border-teal-500 rounded-b text-teal-900 dark:text-teal-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 dark:bg-red-900/30 border-t-4 border-red-500 rounded-b text-red-900 dark:text-red-300 px-4 py-3 shadow-md mb-6" role="alert">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        @if($isModalOpen)
            @include('livewire.management.role-permission-form')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($roles as $role)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col">
                    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">{{ $role['name'] }}</h3>
                        @if($role['name'] !== 'Super Admin')
                            <div class="flex items-center space-x-2">
                                <button wire:click="edit({{ $role['id'] }})" class="btn-sm bg-yellow-500 hover:bg-yellow-600 text-white">Edit</button>
                                <button wire:click="delete({{ $role['id'] }})" class="btn-sm bg-red-500 hover:bg-red-600 text-white">Hapus</button>
                            </div>
                        @endif
                    </header>
                    <div class="p-5 flex-grow">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Permissions:</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($role['permissions'] as $permission)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                    {{ $permission['name'] }}
                                </span>
                            @empty
                                <span class="text-xs text-gray-500 italic">Tidak ada permission.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data role untuk ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Role & Permission') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    @if (session()->has('message'))
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert">
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    @endif


                    <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">
                        Buat Role Baru
                    </button>

                    @if($isModalOpen)
                            @include('livewire.management.role-permission-form')
                    @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($roles as $role)
                                <div class="bg-white shadow-lg rounded-lg p-6">
                                    <!-- --- FIX: Menggunakan sintaks array ['key'] bukan objek->key --- -->
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-xl font-bold text-gray-800">{{ $role['name'] }}</h3>
                                        @if($role['name'] !== 'Super Admin')
                                            <div>
                                                <!-- Menggunakan id dari array -->
                                                <button wire:click="edit({{ $role['id'] }})" class="text-sm text-blue-500 hover:text-blue-700">Edit</button>
                                                <button wire:click="delete({{ $role['id'] }})" class="text-sm text-red-500 hover:text-red-700 ml-2">Hapus</button>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 mb-4">Permissions:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <!-- --- FIX: Mengakses relasi permissions sebagai array --- -->
                                        @forelse($role['permissions'] as $permission)
                                            <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $permission['name'] }}</span>
                                        @empty
                                            <span class="text-gray-500">Tidak ada permission.</span>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

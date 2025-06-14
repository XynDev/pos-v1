<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kasir') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white text-center p-12 shadow-xl sm:rounded-lg">
                <h3 class="text-xl font-bold text-red-600">Tidak Ada Sesi Aktif</h3>
                <p class="text-gray-600 mt-2">Anda tidak dapat melakukan transaksi. Silakan mulai sesi baru terlebih dahulu.</p>
                <a href="{{ route('sessions.index') }}" class="mt-6 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Buka Halaman Sesi Kasir
                </a>
            </div>
        </div>
    </div>
</div>

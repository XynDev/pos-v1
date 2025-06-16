<div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <x-page-header>
            Kasir
        </x-page-header>

        <div class="text-center bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 p-12">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-800/30">
                <svg class="h-8 w-8 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <h3 class="mt-5 text-xl font-bold text-red-600 dark:text-red-400">Tidak Ada Sesi Aktif</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Anda tidak dapat melakukan transaksi. Silakan mulai sesi kasir baru terlebih dahulu.
            </p>
            <div class="mt-6">
                <a href="{{ route('sessions.index') }}" class="btn bg-green-600 hover:bg-green-700 text-white">
                    Buka Halaman Sesi Kasir
                </a>
            </div>
        </div>
    </div>
</div>

<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <div class="sm:flex sm:justify-between sm:items-center mb-8">

        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard</h1>
        </div>

        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <x-dropdown-filter align="right"/>
            <x-datepicker/>
            <button class="btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white">
                <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"/>
                </svg>
                <span class="max-xs:sr-only">Add View</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">

        <div class="flex flex-col col-span-12 sm:col-span-6 xl:col-span-3 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Penjualan Hari Ini</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                Rp {{ number_format($dailySales, 0, ',', '.') }}</p>
        </div>

        <div class="flex flex-col col-span-12 sm:col-span-6 xl:col-span-3 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Transaksi Hari Ini</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $dailyTransactions }}</p>
        </div>

        <div class="flex flex-col col-span-12 sm:col-span-6 xl:col-span-3 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pelanggan Baru (Bulan Ini)</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $monthlyNewCustomers }}</p>
        </div>

        <div class="flex flex-col col-span-12 sm:col-span-6 xl:col-span-3 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Produk Terlaris (Bulan Ini)</p>
            <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $bestSellingProduct->product->name ?? 'Belum ada data' }}</p>
            @if($bestSellingProduct)
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $bestSellingProduct->total_quantity }} Terjual</p>
            @endif
        </div>

        <div class="col-span-12 bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Grafik Penjualan (7 Hari Terakhir)</h3>
                <div class="mt-4 h-96">
                    <div class="relative h-full w-full">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script id="salesChartData" type="application/json">
        @json($salesChartData)
    </script>
@endpush

<div>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dasbor') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Kartu Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Penjualan Hari Ini -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Penjualan Hari Ini</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
                            Rp {{ number_format($dailySales, 0, ',', '.') }}</p>
                    </div>
                    <!-- Transaksi Hari Ini -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Transaksi Hari Ini</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $dailyTransactions }}</p>
                    </div>
                    <!-- Pelanggan Baru -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Pelanggan Baru (Bulan Ini)</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $monthlyNewCustomers }}</p>
                    </div>
                    <!-- Produk Terlaris -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6">
                        <p class="text-sm font-medium text-gray-500 truncate">Produk Terlaris (Bulan Ini)</p>
                        <p class="mt-1 text-xl font-semibold text-gray-900 truncate">{{ $bestSellingProduct->product->name ?? 'Belum ada data' }}</p>
                        @if($bestSellingProduct)
                            <p class="text-sm text-gray-600">{{ $bestSellingProduct->total_quantity }} Terjual</p>
                        @endif
                    </div>
                </div>

                <!-- Grafik Penjualan -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">Grafik Penjualan (7 Hari Terakhir)</h3>
                        <!-- FIX: Menggunakan struktur wrapper untuk memastikan responsivitas -->
                        <div class="mt-4 h-96">
                            <div class="relative h-full w-full">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Import Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('livewire:navigated', () => {
            // Hancurkan instance chart yang lama jika ada untuk mencegah error
            if (window.mySalesChart instanceof Chart) {
                window.mySalesChart.destroy();
            }

            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesData = @json($salesChartData);

            // Simpan instance chart baru ke window object
            window.mySalesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.labels,
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: salesData.data,
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: {
                    // Opsi responsif sudah benar
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush

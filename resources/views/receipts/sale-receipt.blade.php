@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan - {{ $sale->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif, system-ui;
            -webkit-print-color-adjust: exact;
        }

        @page {
            size: 80mm;
            margin: 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .print-button-container {
                display: none;
            }
            .receipt-container {
                page-break-inside: avoid;
            }
        }
        .receipt-container {
            width: 280px;
            margin: 0 auto;
            padding: 15px;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Source+Code+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 dark:bg-gray-900">

<div class="print-button-container text-center py-5">
    <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow">Cetak Struk</button>
</div>

<div class="receipt-container bg-white dark:bg-gray-800 shadow-lg font-mono">
    <header class="text-center mb-4">
        @if($storeDetails['logo'])
            <img src="{{ asset('storage/' . $storeDetails['logo']) }}" alt="Logo Toko" class="mx-auto h-16 w-auto object-contain mb-2">
        @endif
        <h1 class="text-lg font-bold text-gray-900 dark:text-white" style="font-family: 'Inter', sans-serif;">{{ $storeDetails['name'] }}</h1>
        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $storeDetails['address'] }}</p>
        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $storeDetails['phone'] }}</p>
    </header>

    <div class="border-t border-b border-dashed border-gray-300 dark:border-gray-600 py-2 my-2 text-xs">
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">No. Invoice:</span>
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $sale->invoice_number }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Tanggal:</span>
            <span class="text-gray-800 dark:text-gray-200">{{ Carbon::parse($sale->created_at)->format('d/m/y H:i') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Kasir:</span>
            <span class="text-gray-800 dark:text-gray-200">{{ $sale->user->name }}</span>
        </div>
    </div>

    <div class="item-list my-3 text-xs">
        @foreach($sale->items as $item)
            <div class="item mb-2">
                <p class="font-semibold text-gray-800 dark:text-gray-100" style="font-family: 'Inter', sans-serif;">{{ $item->product->name }}</p>
                <div class="flex justify-between text-gray-600 dark:text-gray-300">
                    <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                    <span class="font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="totals border-t border-dashed border-gray-300 dark:border-gray-600 pt-2 text-sm">
        <div class="flex justify-between text-gray-700 dark:text-gray-200">
            <span>Subtotal</span>
            <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold text-base text-gray-900 dark:text-white pt-2 mt-2 border-t border-gray-200 dark:border-gray-600">
            <span style="font-family: 'Inter', sans-serif;">TOTAL</span>
            <span>Rp {{ number_format($sale->final_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payment-details border-t border-dashed border-gray-300 dark:border-gray-600 pt-2 mt-2 text-xs">
        <div class="flex justify-between text-gray-600 dark:text-gray-300">
            <span>{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
            <span>Rp {{ number_format($sale->amount_paid, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-gray-600 dark:text-gray-300">
            <span>Kembali</span>
            <span>Rp {{ number_format($sale->change_due, 0, ',', '.') }}</span>
        </div>
    </div>


    <footer class="text-center mt-4 pt-2 border-t border-dashed border-gray-300 dark:border-gray-600">
        <p class="text-xs text-gray-600 dark:text-gray-400" style="font-family: 'Inter', sans-serif;">{{ $storeDetails['footer_note'] }}</p>
    </footer>
</div>
</body>
</html>

@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan - {{ $sale->invoice_number }}</title>
    <style>
        /* CSS untuk tampilan struk thermal printer */
        @page {
            size: 80mm; /* Sesuaikan lebar kertas printer Anda */
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }

        .container {
            width: 100%;
        }

        .header, .footer {
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 14pt;
        }

        .header p {
            margin: 2px 0;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .meta-info, .item-list, .totals {
            width: 100%;
        }

        .meta-info td {
            padding: 1px 0;
        }

        .item-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-list th, .item-list td {
            padding: 2px 0;
        }

        .item-list .item-name {
            text-align: left;
        }

        .item-list .item-qty, .item-list .item-price, .item-list .item-subtotal {
            text-align: right;
        }

        .totals table {
            width: 100%;
        }

        .totals td {
            padding: 1px 0;
        }

        .totals .label {
            text-align: left;
        }

        .totals .value {
            text-align: right;
        }

        .footer p {
            margin-top: 10px;
            font-style: italic;
        }

        /* Sembunyikan tombol saat mencetak */
        @media print {
            .print-button-container {
                display: none;
            }
        }

        .print-button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-button {
            padding: 10px 20px;
            font-size: 12pt;
            cursor: pointer;
        }

    </style>
</head>
<body>

<div class="print-button-container">
    <button onclick="window.print()" class="print-button">Cetak Struk</button>
</div>

<div class="container">
    <header class="header">
        <h1>{{ $storeDetails['name'] }}</h1>
        <p>{{ $storeDetails['address'] }}</p>
        <p>{{ $storeDetails['phone'] }}</p>
    </header>

    <div class="divider"></div>

    <table class="meta-info">
        <tr>
            <td>No. Inv:</td>
            <td>{{ $sale->invoice_number }}</td>
        </tr>
        <tr>
            <td>Tanggal:</td>
            <td>{{ Carbon::parse($sale->created_at)->format('d/m/y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir:</td>
            <td>{{ $sale->user->name }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="item-list">
        <table>
            <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td colspan="2" class="item-name">{{ $item->product->name }}</td>
                </tr>
                <tr>
                    <td>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="item-subtotal">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="divider"></div>

    <div class="totals">
        <table>
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">{{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total</td>
                <td class="value">{{ number_format($sale->final_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</td>
                <td class="value">{{ number_format($sale->amount_paid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Kembali</td>
                <td class="value">{{ number_format($sale->change_due, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <footer class="footer">
        <p>{{ $storeDetails['footer_note'] }}</p>
    </footer>
</div>
</body>
</html>

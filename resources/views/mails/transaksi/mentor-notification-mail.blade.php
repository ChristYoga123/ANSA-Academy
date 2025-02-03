<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice Transaksi ANSA Academy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            background-color: #f4f4f4;
            padding: 10px;
        }

        .invoice-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .item-list {
            width: 100%;
            border-collapse: collapse;
        }

        .item-list th,
        .item-list td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Pesanan Terbaru</h1>
    </div>

    <div class="invoice-details">
        <p>Order ID: {{ $transaksi->order_id }}</p>
        <p>Tanggal Transaksi: {{ $transaksi->created_at->format('d M Y H:i') }}</p>
        <p>
            Anda memiliki pesanan baru dari <strong>{{ $transaksi->mentee->name }}</strong> untuk menjadi mentor.
        </p>
    </div>

    <table class="item-list">
        <thead>
            <tr>
                <th>Item</th>
                <th>Tipe</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['judul'] }}</td>
                    <td>{{ $item['tipe'] }}</td>
                    <td>Rp. {{ number_format($item['harga'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="total">Total</td>
                <td class="total">Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="text-align: center; font-size: 0.8em;">
        Silakan login ke dashboard mentor dan masuk menu Mentoring untuk informasi lebih lanjut.
    </p>

    <div class="footer">
        <p>© {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
    </div>
</body>

</html>

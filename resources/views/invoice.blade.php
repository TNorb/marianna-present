<!DOCTYPE html>
<html lang="hu">
<head>
    {{-- Számla pdf html --}}
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            box-sizing: border-box;
        }
        .invoice-container {
            margin: auto;
            border: 1px solid #ddd;
        }
        .header {
            text-align: right;
            padding: 8px;
        }
        .customer-details {
            background-color: #eee;
            padding: 10px;
        }
        .table-frame {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f4f4f4;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="invoice-container">

        {{-- Cégadatok és logó --}}
        <table style="width: 100%; margin-bottom: 5px; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; vertical-align: middle; width: 70%;">
                    <h2>Marianna Present Kft.</h2>
                    <p>2118 Dány, Dózsa utca 25.</p>
                    <p>Phone: +36-20-842-0324</p>
                </td>
                <td style="text-align: right; vertical-align: middle; width: 30%;">
                    <img src="{{ public_path('images/marianna-present-logo-bw.png') }}" width="250">
                </td>
            </tr>
        </table>

        {{-- Számla azonosító és dátunm --}}
        <div class="header">
            <h2>Invoice</h2>
            <p><strong>Invoice ID:</strong> {{ $order->ref_code }}</p>
            <p><strong>Date:</strong> {{ date('Y-m-d') }}</p>
        </div>

        {{-- Vásárló adatai --}}
        <div class="customer-details">
            <p><strong>Customer details:</strong></p>
            <p>{{ $order->name }}</p>
            <p>{{ $order->billing_address }}</p>
            <p>{{ $order->phone }}</p>
            <p>{{ $order->email }}</p>
        </div>

        {{-- Vásárolt termékek listája név, mennyiség, egységár és összeggel --}}
        <table class="table-frame">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ intval($item->item->price * (1 - $item->item->discount / 100) / 5) * 5 }} Ft</td>
                        <td>{{ (intval($item->item->price * (1 - $item->item->discount / 100) / 5) * 5) * $item->quantity }} Ft</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Végösszeg --}}
        <p class="header"><strong>Total: {{ $order->total_price }} Ft</strong></p>

        <div class="footer">
            <p>Thank you for your purchase!</p>
        </div>
    </div>
</body>
</html>

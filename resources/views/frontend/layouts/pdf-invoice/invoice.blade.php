<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $ref_id }}</title>
    <style>
        body {
            font-family: 'almarai', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #f6f6f6;
        }

        .invoice-box {
            background: #fff;
            max-width: 800px;
            margin: auto;
            padding: 30px 50px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            font-size: 14px;
            line-height: 24px;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 8px;
            vertical-align: top;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 40px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 30px;
        }

        .invoice-box table tr.heading td {
            background: #3498db;
            color: white;
            font-weight: bold;
            font-size: 15px;
            text-transform: uppercase;
        }

        .invoice-box table tr.details td,
        .invoice-box table tr.item td,
        .invoice-box table tr.total td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item td {
            padding: 10px 8px;
        }

        .invoice-box table tr.total td {
            font-weight: bold;
            background: #fafafa;
        }

        .logo {
            width: 70px;
        }

        .title-text {
            font-weight: bold;
            font-size: 22px;
            color: #333;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td,
            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        @page {
            header: page-header;
            footer: page-footer;
        }
    </style>
</head>
<body>
<div class="invoice-box">
    <table>
        <tr class="top">
            <td colspan="3">
                <table>
                    <tr>
                        <td class="title">
                            <img src="{{ base_path('public/frontend/img/logo.jpg') }}" class="logo" />
                        </td>
                        <td></td>
                        <td style="text-align:right;">
                            <span class="title-text">Invoice #{{ $ref_id }}</span><br>
                            Created: {{ \Carbon\Carbon::parse($created_at)->format('M d, Y') }}<br>
                            Due: {{ \Carbon\Carbon::parse($created_at)->format('M d, Y') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="3">
                <table>
                    <tr>
                        <td>
                            <strong>From:</strong><br>
                            MoSoft<br>
                            12345 Cairo<br>
                            Cairo, EG 12345
                        </td>
                        <td></td>
                        <td style="text-align:right;">
                            <strong>To:</strong><br>
                            {{ $user['full_name'] }}<br>
                            {{ $user['mobile'] }}<br>
                            {{ $user['email'] }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Payment Method</td>
            <td></td>
            <td style="text-align:right;">Amount</td>
        </tr>

        <tr class="details">
            <td>{{ $payment_method['name'] }}</td>
            <td></td>
            <td style="text-align:right;">{{ $currency_symbol . number_format($total, 2) }}</td>
        </tr>

        <tr class="heading">
            <td>Item</td>
            <td style="text-align:center;">Quantity</td>
            <td style="text-align:right;">Price</td>
        </tr>

        @foreach($products as $product)
            <tr class="item">
                <td>{{ $product['name'] }}</td>
                <td style="text-align:center;">{{ $product['pivot']['quantity'] }}</td>
                <td style="text-align:right;">{{ $currency_symbol . number_format($product['price'] * $product['pivot']['quantity'], 2) }}</td>
            </tr>
        @endforeach

        <tr class="total">
            <td></td>
            <td style="text-align:center;">Subtotal</td>
            <td style="text-align:right;">{{ $currency_symbol . number_format($subtotal, 2) }}</td>
        </tr>

        <tr class="total">
            <td></td>
            <td style="text-align:center;">Discount</td>
            <td style="text-align:right;">{{ $currency_symbol . number_format($discount, 2) }}</td>
        </tr>

        <tr class="total">
            <td></td>
            <td style="text-align:center;">Tax</td>
            <td style="text-align:right;">{{ $currency_symbol . number_format($tax, 2) }}</td>
        </tr>

        <tr class="total">
            <td></td>
            <td style="text-align:center;">Shipping</td>
            <td style="text-align:right;">{{ $currency_symbol . number_format($shipping, 2) }}</td>
        </tr>

        <tr class="total">
            <td></td>
            <td style="text-align:center;">Total</td>
            <td style="text-align:right;">{{ $currency_symbol . number_format($total, 2) }}</td>
        </tr>
    </table>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice - {{ $order?->order_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@100..900&display=swap" rel="stylesheet">
    <style>
        @font-face {
            font-family: "Noto Sans Bengali", sans-serif;
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Noto Sans Bengali', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .currency {
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            line-height: 20px;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        /* Header Styles */
        .title {
            font-size: 45px;
            color: #D19C97;
            font-weight: bold;
            text-decoration: none;
        }

        /* Items Table */
        .heading td {
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            padding: 10px;
        }

        .item td {
            border-bottom: 1px solid #eee;
            padding: 10px;
        }

        /* Summary Table */
        .summary-table td {
            padding: 3px 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .status-badge {
            text-transform: uppercase;
            color: #D19C97;
            font-weight: bold;
            border: 2px solid #D19C97;
            padding: 5px 15px;
            display: inline-block;
            margin-bottom: 10px;
            font-size: 20px;
            transform: rotate(338deg);
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table>
            <tr>
                <td style="width: 50%;">
                    <span class="title">
                        <img src="{{ $logoBase64 }}" alt="Logo" style="max-width: 250px;">
                    </span>
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Invoice Number:</strong> {{ $order?->order_number }}<br>
                    <strong>Date:</strong> {{ $order?->created_at?->format('d-M-Y') }}<br>
                    <strong>Order ID:</strong> #{{ $order?->id }}
                </td>
            </tr>
        </table>

        <br>

        <table>
            <tr>
                <td style="width: 50%;">
                    <strong>From:</strong><br>
                    {{ $siteSettings?->site_title }}<br>
                    {{ $siteSettings?->contact_address }}<br>
                    {{ $siteSettings?->contact_phone }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>To:</strong><br>
                    {{ $shippingAddress?->name }}<br>
                    {{ $shippingAddress?->address }}<br>
                    {{ $shippingAddress?->thana }}, {{ $shippingAddress?->district }},
                    {{ $shippingAddress?->division }}<br>
                    Phone: {{ $shippingAddress?->mobile }}
                </td>
            </tr>
        </table>

        <br>

        <table>
            <thead>
                <tr class="heading">
                    <td style="width: 50%;">Item Details</td>
                    <td style="width: 25%; text-align: center;">Price*Qty</td>
                    <td style="width: 25%; text-align: right;">Total</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderProducts ?? [] as $orderProduct)
                    <tr class="item">
                        <td>
                            <span>{{ $orderProduct->product_name }}</span><br>
                            @if ($orderProduct?->size_name || $orderProduct?->color_name)
                                <small style="color: #666;">
                                    {{ $orderProduct->size_name ? 'Size: ' . $orderProduct->size_name : '' }}
                                    {{ $orderProduct->color_name ? ($orderProduct->size_name ? ' | ' : '') . 'Color: ' . $orderProduct->color_name : '' }}
                                </small>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span
                                class="currency">{{ $siteSettings?->currency_symbol }}</span>{{ formatBDT($orderProduct?->price) }}
                            * {{ $orderProduct->quantity }}
                        </td>
                        <td style="text-align: right;">
                            <span
                                class="currency">{{ $siteSettings?->currency_symbol }}</span>{{ formatBDT($orderProduct?->price * $orderProduct?->quantity) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>

        <table style="width: 100%;">
            <tr>
                <td>
                    <table class="summary-table">
                        <tr>
                            <td style="text-align: right;">Subtotal:</td>
                            <td style="text-align: right;">
                                <span
                                    class="currency">{{ $siteSettings?->currency_symbol }}</span>{{ formatBDT($order?->sub_total) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Delivery Charge:</td>
                            <td style="text-align: right;">
                                <span
                                    class="currency">{{ $siteSettings?->currency_symbol }}</span>{{ formatBDT($order?->shipping_charge) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Discount
                                @if ($order?->coupon_code)
                                    ({{ $order?->coupon_code }})
                                @endif:
                            </td>
                            <td style="text-align: right; color: green;">
                                @if ($order?->coupon_code)
                                    <span>-</span>
                                @endif
                                <span
                                    class="currency">{{ $siteSettings?->currency_symbol }}</span>{{ formatBDT($order?->coupon_discount) }}
                            </td>
                        </tr>
                        <tr style="font-weight: bold; font-size: 16px; border-top: 1px solid #333;">
                            <td style="text-align: right; padding-top: 10px;">Total:</td>
                            <td style="text-align: right; padding-top: 10px;">
                                <span
                                    class="currency">{{ $siteSettings?->currency_symbol }}</span>{{ formatBDT($order?->grand_total) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="footer">
            <div class="status-badge">{{ $order?->payment_status }}</div>
            <p>Thank you for your business! For any queries, contact us at <b>{{ $siteSettings?->contact_email }}</b>
            </p>
            <p><i>This is a computer-generated invoice. No signature required.</i></p>
        </div>
    </div>
</body>

</html>

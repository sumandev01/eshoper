<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $siteSettings?->site_title }} - {{ $order?->order_number }}</title>
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .button-box {
            max-width: 880px;
            margin: auto;
            padding-bottom: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 14px;
            line-height: 24px;
            color: #555;
            background: #fff;
        }

        @media print {
            .button-box {
                display: none;
            }

            .invoice-box {
                max-width: 100%;
                margin: auto;
                padding: 0px;
                border: 0;
                box-shadow: 0 0 0px rgba(0, 0, 0, 0);
                font-size: 14px;
                line-height: 24px;
                color: #555;
                background: #fff;
            }
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.heading td {
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            padding: 10px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
            padding: 10px;
        }

        .invoice-box table tr.total {
            font-weight: bold;
            font-size: 16px;
            color: #000;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-size: 12px;
        }

        .status-paid {
            text-transform: uppercase;
            color: #D19C97 !important;
            font-weight: bold;
            border: 2px solid #D19C97 !important;
            padding: 5px;
            display: inline-block;
            transform: rotate(-5deg);
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="button-box">
        <button onclick="window.history.back();" style="background: #D19C97; color: white; border: none; padding: 10px 30px; text-decoration: none; border-radius: 3px; font-size: 14px; margin-left: 10px; margin-right: 10px; cursor: pointer;">
            <i class="fa fa-arrow-left" aria-hidden="true" style="margin-right: 15px;"></i>
            Back
        </button>


        <button onclick="window.print();" style="background: #D19C97; border: none; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; font-size: 14px; margin-left: 10px; margin-right: 10px; cursor: pointer;">Print Invoice</button>

        <a href="{{ route('order.invoice.download', $order->id) }}" style="background: #D19C97; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px; font-size: 14px; margin-left: 10px;">
            Download PDF
        </a>
    </div>
    <div class="invoice-box">
        <table class="invoice-table">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h2 style="margin: 0;">
                                    <a href="{{ route('root') }}" target="_blank"
                                        style="color: #D19C97 !important; text-decoration: none;">
                                        <img src="{{ $siteSettings?->site_logo }}" alt="Logo"
                                            style="max-width: 250px;">
                                    </a>
                                </h2>
                            </td>
                            <td>
                                <strong>Invoice Number:</strong> {{ $order?->order_number }}<br>
                                <strong>Date:</strong> {{ $order?->created_at?->format('d-M-Y') }}<br>
                                <strong>Order ID:</strong> #{{ $order?->id }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>From:</strong><br>
                                {{ $siteSettings?->site_title }}<br>
                                {{ $siteSettings?->contact_address }}<br>
                                {{ $siteSettings?->contact_phone }}
                            </td>
                            <td>
                                <strong>To:</strong><br>
                                {{ $shippingAddress?->name }}<br>
                                {{ $shippingAddress?->address }}<br>
                                {{ $shippingAddress?->thana }}, {{ $shippingAddress?->district }},
                                {{ $shippingAddress?->division }}<br>
                                Phone: {{ $shippingAddress?->mobile }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table>
                        <tr class="heading">
                            <td>Item Details</td>
                            <td>Price*Qty</td>
                            <td style="text-align: right;">Total</td>
                        </tr>

                        @foreach ($orderProducts ?? [] as $orderProduct)
                            <tr class="item">
                                <td>
                                    <p style="margin: 0 !important">{{ $orderProduct->product_name }}</p>
                                    @if ($orderProduct?->size_name !== null || $orderProduct?->color_name !== null)
                                        <small>
                                            @if ($orderProduct->size_name)
                                                Size: {{ $orderProduct->size_name }}
                                            @endif
                                            @if ($orderProduct?->size_name && $orderProduct?->color_name)
                                                |
                                            @endif
                                            @if ($orderProduct?->color_name)
                                                Color: {{ $orderProduct?->color_name }}
                                            @endif
                                        </small>
                                    @endif
                                </td>
                                <td style="white-space: nowrap;">
                                    <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                    <span>{{ formatBDT($orderProduct?->price) }}</span>
                                    *
                                    <span>{{ $orderProduct->quantity }}</span>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                    <span>{{ formatBDT($orderProduct?->price * $orderProduct?->quantity) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>

            <tr class="total">
                <td>
                    <table>
                        <tr>
                            <td style="text-align: right;">
                                Subtotal:
                            </td>
                            <td>
                                <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                <span class="cart-subtotal">{{ formatBDT($order?->sub_total) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">
                                Delivery Charge:
                            </td>
                            <td>
                                <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                <span class="shipping-charge">{{ formatBDT($order?->shipping_charge) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">
                                Discount @if ($order?->coupon_code)
                                    ({{ $order?->coupon_code }})
                                @endif :
                            </td>
                            <td>
                                @if ($order?->coupon_code)
                                    <span>-</span>
                                @endif
                                <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                <span class="coupon-discount">{{ formatBDT($order?->coupon_discount) }}</span>
                            </td>
                        </tr>
                        <tr style="border-top: 2px solid #eee;">
                            <td style="text-align: right;">
                                Total:
                            </td>
                            <td>
                                <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                <span class="grand-total">{{ formatBDT($order?->grand_total) }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="footer">
            <div class="status-paid">{{ $order?->payment_status }}</div>
            <p>Thank you for your business! If you have any questions about this invoice, please contact us at
                <b>{{ $siteSettings?->contact_email }}</b></p>
            <p><i>This is a computer-generated invoice. No signature required.</i></p>
        </div>
    </div>
</body>
</html>

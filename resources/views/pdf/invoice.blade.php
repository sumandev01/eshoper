<!DOCTYPE html>
<html lang="bn">
<head>
    <style>
        @font-face {
            font-family: 'Nirmala';
            src: url('{{ public_path("fonts/Nirmala.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 13px;
        }

        @page {
            margin: 40px 40px 80px 40px;
        }

        .currency {
            font-family: 'Nirmala', sans-serif;
            font-weight: normal;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: none;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            line-height: 16px;
        }

        table td {
            padding: 2px 5px;
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
            font-size: 14px;
        }

        .item td {
            border-bottom: 1px solid #eee;
            padding: 5px 10px;
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

        .page-footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .status-badge {
            position: fixed;
            top: 35%;
            left: 15%;
            transform: rotate(-45deg);
            text-transform: uppercase;
            color: rgba(209, 156, 151, 0.3); /* #D19C97 with 0.3 opacity */
            font-weight: bold;
            border: 8px solid rgba(209, 156, 151, 0.3);
            padding: 20px 60px;
            font-size: 80px;
            z-index: 1000;
            letter-spacing: 5px;
            border-radius: 20px;
        }
    </style>
</head>

<body>
    <div class="status-badge">{{ $order?->payment_status?->value }}</div>
    <div class="invoice-box">
        <table>
            <tr>
                <td style="width: 50%;">
                    <span class="title">
                        <img src="{{ $logoBase64 }}" alt="Logo" style="max-width: 250px;">
                    </span>
                </td>
                <td style="width: 50%; text-align: right; white-space: nowrap;">
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
                    {{ ($siteSettings->site_title ?? null) }}<br>
                    {{ ($siteSettings->contact_address ?? null) }}<br>
                    {{ ($siteSettings->contact_phone ?? null) }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>To:</strong><br>
                    {{ $shippingAddress?->name }}<br>
                    {{ $shippingAddress?->address }}<br>
                    {{ $shippingAddress?->city }}, {{ $shippingAddress?->state_name }}, {{ $shippingAddress?->country_name }}<br>
                    {{ $shippingAddress?->mobile }}<br>
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
                            <span>{{ $orderProduct->product_name }}</span>
                            @if ($orderProduct?->size_name || $orderProduct?->color_name)
                                <div style="margin-top: 3px; line-height: 12px;">
                                    <small style="color: #666; font-size: 11px;">
                                        {{ $orderProduct->size_name ? 'Size: ' . $orderProduct->size_name : '' }}
                                        {{ $orderProduct->color_name ? ($orderProduct->size_name ? ' | ' : '') . 'Color: ' . $orderProduct->color_name : '' }}
                                    </small>
                                </div>
                            @endif
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($orderProduct?->price) }}*{{ $orderProduct->quantity }}
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($orderProduct?->price * $orderProduct?->quantity) }}
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
                            <td style="width: 50%; text-align: right;">
                                Subtotal:
                            </td>
                            <td style="width: 50%; text-align: right;">
                                <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($order?->sub_total) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">
                                Discount @if ($order?->coupon_code) ({{ $order?->coupon_code }}) @endif :
                            </td>
                            <td style="text-align: right;">
                                @if ($order?->coupon_code)
                                    - 
                                @endif
                                <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($order?->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">
                                Delivery Charge:
                            </td>
                            <td style="text-align: right;">
                                <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($order?->shipping_charge) }}
                            </td>
                        </tr>
                        <tr style="border-top: 2px solid #eee;">
                            <td style="text-align: right; padding-top: 10px;">
                                <strong>Total:</strong>
                            </td>
                            <td style="text-align: right; padding-top: 10px;">
                                <strong>
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($order?->grand_total) }}
                                </strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p><strong>Thank you for shopping with us!</strong></p>
            <p style="margin: 5px 0;">If you have any questions concerning this invoice or your order, please feel free to contact us at:</p>
            <p style="margin: 5px 0;">
                <strong>Email:</strong> {{ ($siteSettings->contact_email ?? null) }} &nbsp;|&nbsp; 
                <strong>Phone:</strong> {{ ($siteSettings->contact_phone ?? null) }}
            </p>
            <p style="margin-top: 15px; font-size: 11px; color: #999;"><i>This is a computer-generated document and does not require a physical signature.</i></p>
        </div>
    </div>

    <!-- Fixed Footer that repeats on every page -->
    <div class="page-footer">
        Downloaded on: <strong>{{ \Carbon\Carbon::now()->format('d-M-Y H:i:A') }}</strong> &nbsp;|&nbsp; 
        <a href="{{ url('/') }}" style="color: #777; text-decoration: none;">{{ url('/') }}</a>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $x = $pdf->get_width() - 80;
            $y = $pdf->get_height() - 35;
            $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
            $font = $fontMetrics->get_font("sans-serif", "normal");
            $size = 8;
            $color = array(0.46, 0.46, 0.46);
            $word_space = 0.0;
            $char_space = 0.0;
            $angle = 0.0;
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>

</html>


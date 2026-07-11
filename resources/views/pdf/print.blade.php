<style>
    @media print {
        @page {
            margin: 0.5cm;
        }
        /* Hide everything else directly under body */
        body > *:not(#print-area) {
            display: none !important;
        }
        
        #print-area {
            display: block !important;
            padding: 0 !important;
            margin: 0 !important;
            background: #fff;
            color: #333;
            font-family: 'Arial', 'DejaVu Sans', sans-serif;
            font-size: 14pt; 
            line-height: 1.6; /* Increased line-height */
        }
        
        .invoice-print-box {
            max-width: 100%;
            margin: auto;
            padding: 0px;
            border: 0;
            box-shadow: none;
            background: #fff;
        }

        .invoice-print-box table {
            width: 100%;
            border-collapse: collapse;
            line-height: 1.6; /* Increased line-height */
        }

        .invoice-print-box table td {
            padding: 5px 8px; /* Increased padding */
            vertical-align: top;
        }

        .invoice-print-box .title {
            font-size: 30pt;
            color: #D19C97;
            font-weight: bold;
            text-decoration: none;
        }

        .invoice-print-box .heading td {
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            padding: 12px;
            font-size: 15pt;
        }

        .invoice-print-box .item td {
            border-bottom: 1px solid #eee;
            padding: 12px 10px;
            font-size: 14pt;
        }

        .invoice-print-box .summary-table td {
            padding: 10px 10px;
            font-size: 15pt;
        }
        
        .invoice-print-box .summary-table {
            position: relative;
        }

        .invoice-print-box .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12pt;
            color: #777;
        }

        .invoice-print-box .status-badge {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            text-transform: uppercase;
            color: #D19C97;
            font-weight: bold;
            border: 8px solid #D19C97;
            padding: 20px 60px;
            font-size: 60pt;
            opacity: 0.15;
            z-index: 1000;
            letter-spacing: 5px;
            border-radius: 20px;
            pointer-events: none;
        }

        .footer-url {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12pt;
            color: #777;
            background-color: #fff;
            padding-bottom: 10px;
        }
    }
</style>

<script>
    // Move print-area to body level to safely hide all other wrappers during print without breaking layout
    window.addEventListener('beforeprint', function() {
        let printArea = document.getElementById('print-area');
        if (printArea.parentNode !== document.body) {
            document.body.appendChild(printArea);
        }
    });
</script>

<div id="print-area" style="display: none;">
    <div class="invoice-print-box">
        <table>
            <tr>
                <td style="width: 50%;">
                    <a href="{{ route('root') }}" target="_blank" style="text-decoration: none;">
                        <img src="{{ ($siteSettings->site_logo ?? null) }}" alt="Logo" style="max-width: 250px;">
                    </a>
                </td>
                <td style="width: 50%; text-align: right; white-space: nowrap;">
                    <strong>Invoice Number:</strong> {{ $order?->order_number }}<br>
                    <strong>Date:</strong> {{ $order?->created_at?->format('d-M-Y') }}<br>
                    <strong>Order ID:</strong> #{{ $order?->id }}
                </td>
            </tr>
        </table>
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
                    {{ $shippingAddress?->mobile }}
                </td>
            </tr>
        </table>
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
                                    <small style="color: #666; font-size: 13pt;">
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
        <table style="width: 100%;">
            <tr>
                <td>
                    <table class="summary-table">
                        <tr>
                            <td style="width: 50%; text-align: right; padding: 0;">
                                Subtotal:
                            </td>
                            <td style="width: 50%; text-align: right; padding: 0;">
                                <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($order?->sub_total) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding: 0;">
                                Delivery Charge:
                            </td>
                            <td style="text-align: right; padding: 0;">
                                <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($order?->shipping_charge) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding: 0;">
                                Discount @if ($order?->coupon_code) ({{ $order?->coupon_code }}) @endif :
                            </td>
                            <td style="text-align: right; padding: 0;">
                                @if ($order?->coupon_code)
                                    - 
                                @endif
                                <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($order?->coupon_discount) }}
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
                    </table>
                </td>
            </tr>
        </table>
        
        <div class="status-badge">{{ $order?->payment_status?->value }}</div>

        <div class="footer">
            <p><strong>Thank you for shopping with us!</strong></p>
            <p style="margin: 5px 0;">If you have any questions concerning this invoice or your order, please feel free to contact us at:</p>
            <p style="margin: 5px 0;">
                <strong>Email:</strong> {{ ($siteSettings->contact_email ?? null) }} &nbsp;|&nbsp; 
                <strong>Phone:</strong> {{ ($siteSettings->contact_phone ?? null) }}
            </p>
            <p style="margin-top: 15px; font-size: 13pt; color: #999;"><i>This is a computer-generated document and does not require a physical signature.</i></p>
        </div>
    </div>
    <div class="footer-url">
        {{ url('/') }}
    </div>
</div>

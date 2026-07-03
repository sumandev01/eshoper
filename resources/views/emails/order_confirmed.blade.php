<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; }
        .header { text-align: center; border-bottom: 2px solid #D19C97; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #D19C97; margin: 0; }
        .content p { font-size: 16px; color: #333; line-height: 1.5; }
        .order-details { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-details th, .order-details td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .order-details th { background-color: #f1f4f8; }
        .footer { text-align: center; margin-top: 30px; font-size: 14px; color: #777; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $siteTitle }}</h1>
            <h3>Order Confirmation</h3>
        </div>
        
        <div class="content">
            <p>Dear {{ $order->user_name }},</p>
            <p>Thank you for your purchase! Your order <strong>{{ $order->order_number }}</strong> has been successfully confirmed and paid.</p>
            
            <table class="order-details">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderProducts as $item)
                    <tr>
                        <td>
                            {{ optional($item->product)->name ?? 'Product' }}
                            @if($item->size_name) ({{ $item->size_name }}) @endif
                            @if($item->color_name) - {{ $item->color_name }} @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $order->shippingCost->currency ?? '৳' }}{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="text-align: right;">Subtotal:</td>
                        <td>{{ number_format($order->sub_total, 2) }}</td>
                    </tr>
                    @if($order->coupon_discount > 0)
                    <tr>
                        <td colspan="2" style="text-align: right;">Discount:</td>
                        <td>- {{ number_format($order->coupon_discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="text-align: right;">Shipping:</td>
                        <td>{{ number_format($order->shipping_charge, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="2" style="text-align: right;">Grand Total:</td>
                        <td>{{ number_format($order->grand_total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            
            <p>If you have any questions about your order, please contact our support team.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $siteTitle }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

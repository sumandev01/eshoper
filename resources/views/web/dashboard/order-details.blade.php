@extends('web.layouts.app')
@section('title', 'Order Details' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'User Dashboard', 'url' => route('user.dashboard')],
            ['name' => 'Order Details', 'url' => '']
        ]
    ])
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container pt-1">
        <div class="row">
            @include('web.dashboard.sidebar')
            <div class="col-12 col-lg-9 mb-2">
                <div class="dash-card p-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 border-bottom-0 rounded-top" style="border-radius: 12px 12px 0 0;">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('user.orders') }}" class="btn btn-sm rounded-circle theme-shadow hover-up transition-all me-3" title="Back to Orders" style="width: 38px; height: 38px; line-height: 22px; display: flex; align-items: center; justify-content: center; background-color: color-mix(in srgb, var(--primary) 10%, white); color: var(--primary); border: 1px solid color-mix(in srgb, var(--primary) 20%, white);">
                                <i class="fas fa-arrow-left" style="font-size: 14px;"></i>
                            </a>
                            <h5 class="font-weight-semi-bold m-0" style="color: color-mix(in srgb, var(--primary) 60%, #111); font-size: 1.2rem;">Order #{{ $order?->order_number }}</h5>
                        </div>
                        <div class="d-flex gap-3">
                            <button onclick="window.print();" class="btn hover-up transition-all px-4 py-2" style="border-radius: 8px; font-weight: 500; color: var(--primary); background-color: color-mix(in srgb, var(--primary) 10%, white); border: 1px solid color-mix(in srgb, var(--primary) 30%, white);">
                                <i class="fa fa-print me-1"></i> Print Invoice
                            </button>
                            <a href="{{ route('order.invoice.download', $order->id) }}" class="btn btn-primary theme-shadow hover-up transition-all px-4 py-2" style="border-radius: 8px; font-weight: 500;">
                                <i class="fa fa-download me-1"></i> Download PDF
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">Order Info</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><strong>Date:</strong> {{ $order?->created_at?->format('d-M-Y') }}</li>
                                    <li class="mb-2"><strong>Status:</strong> 
                                        <span class="badge rounded-pill dash-badge badge-soft-{{ $order?->order_status?->color() === 'success' ? 'success' : ($order?->order_status?->color() === 'warning' ? 'warning' : 'info') }} px-2 py-1">
                                            {{ ucfirst($order?->order_status?->value) }}
                                        </span>
                                    </li>
                                    <li class="mb-2"><strong>Payment Status:</strong> 
                                        <span class="badge rounded-pill dash-badge badge-soft-{{ $order?->payment_status?->color() === 'success' ? 'success' : ($order?->payment_status?->color() === 'warning' ? 'warning' : 'info') }} px-2 py-1">
                                            {{ ucfirst($order?->payment_status?->value) }}
                                        </span>
                                    </li>
                                    <li><strong>Payment Method:</strong> 
                                        @php
                                            $paymentMethodStr = strtoupper($order->payment_method ?? 'N/A');
                                            $paymentData = $order->payment_data ? json_decode($order->payment_data, true) : null;
                                            
                                            if ($order->payment_method === 'sslcommerz' && $paymentData && isset($paymentData['card_type'])) {
                                                // card_type format is often "NAGAD-Nagad", we can just split or show directly
                                                $brand = explode('-', $paymentData['card_type'])[0] ?? $paymentData['card_type'];
                                                $paymentMethodStr = 'SSLCOMMERZ - ' . strtoupper($brand);
                                            } elseif ($order->payment_method === 'manual' && $paymentData && isset($paymentData['sender_number'])) {
                                                $paymentMethodStr = 'MANUAL (Sender: ' . $paymentData['sender_number'] . ')';
                                            } elseif ($order->payment_method === 'cashOnDelivery') {
                                                $paymentMethodStr = 'CASH ON DELIVERY';
                                            }
                                        @endphp
                                        <span class="text-dark font-weight-medium">
                                            {{ $paymentMethodStr }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mb-3 text-md-end">
                                <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">Shipping Address</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="font-weight-semi-bold text-dark">{{ $shippingAddress?->name }}</li>
                                    <li class="text-muted">{{ $shippingAddress?->address }}</li>
                                    <li class="text-muted">{{ $shippingAddress?->city }}, {{ $shippingAddress?->state_name }}, {{ $shippingAddress?->country_name }}</li>
                                    <li class="mt-2"><i class="fas fa-phone-alt text-muted me-1"></i> {{ $shippingAddress?->mobile }}</li>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table dash-table table-hover mb-0">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-start">Product Name</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderProducts ?? [] as $orderProduct)
                                        <tr class="align-middle">
                                            <td>
                                                <a class="text-decoration-none text-dark font-weight-semi-bold" href="{{ route('product.details', $orderProduct->product?->slug) }}">
                                                    {{ $orderProduct->product_name }}
                                                </a>
                                                @if ($orderProduct?->size_name || $orderProduct?->color_name)
                                                    <div class="text-muted small mt-1">
                                                        {{ $orderProduct->size_name ? 'Size: ' . $orderProduct->size_name : '' }}
                                                        {{ $orderProduct->color_name ? ($orderProduct->size_name ? ' | ' : '') . 'Color: ' . $orderProduct->color_name : '' }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($orderProduct?->price) }}</td>
                                            <td class="text-center">{{ $orderProduct->quantity }}</td>
                                            <td class="text-end font-weight-semi-bold">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($orderProduct?->price * $orderProduct?->quantity) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end pt-3" style="border-top: 2px solid #dee2e6 !important; border-bottom: none !important; border-left: none !important; border-right: none !important;">Subtotal:</th>
                                        <th class="text-end pt-3" style="border-top: 2px solid #dee2e6 !important; border-bottom: none !important; border-left: none !important; border-right: none !important;">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($order?->sub_total) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end pt-0 pb-1" style="border: none !important;">Discount {{ $order?->coupon_code ? '('.$order->coupon_code.')' : '' }}:</th>
                                        <th class="text-end pt-0 pb-1 {{ $order?->coupon_discount > 0 ? 'text-danger' : '' }}" style="border: none !important;">
                                            @if($order?->coupon_discount > 0)
                                                - {{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($order?->coupon_discount) }}
                                            @else
                                                {{ ($siteSettings->currency_symbol ?? null) }}0
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end pt-1 pb-3" style="border: none !important;">Delivery Charge:</th>
                                        <th class="text-end pt-1 pb-3" style="border: none !important;">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($order?->shipping_charge) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end pt-3" style="border-top: 2px solid #dee2e6 !important; border-bottom: none !important; border-left: none !important; border-right: none !important;"><h5 class="mb-0 fw-bold">Total:</h5></th>
                                        <th class="text-end pt-3" style="border-top: 2px solid #dee2e6 !important; border-bottom: none !important; border-left: none !important; border-right: none !important;"><h5 class="mb-0 fw-bold text-primary">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($order?->grand_total) }}</h5></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->

    <!-- Include Print Invoice Template (Hidden on web, visible on print) -->
    @include('pdf.print')

@endsection
@push('styles')
    <style>

    </style>
@endpush


@extends('web.layouts.app')
@section('title', 'Order Confirmed - ' . $order?->order_number . ' | ' . ($siteSettings->site_title ?? 'E-Shopper'))

@section('content')
    <style>
        .success-icon {
            font-size: 5.5rem;
            color: var(--primary) !important;
            animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-shadow: 0 10px 20px color-mix(in srgb, var(--primary) 30%, transparent);
        }
        .order-card {
            border-radius: 1.2rem;
            border: 1px solid color-mix(in srgb, var(--primary) 15%, transparent);
            box-shadow: 0 15px 35px color-mix(in srgb, var(--primary) 8%, rgba(0,0,0,0.1));
            background: #ffffff;
        }
        .order-number-alert {
            background-color: color-mix(in srgb, var(--primary) 12%, transparent) !important;
            color: var(--primary) !important;
            border: 1px solid color-mix(in srgb, var(--primary) 20%, transparent) !important;
            font-size: 1.1rem;
        }
        .summary-box {
            background-color: color-mix(in srgb, var(--primary) 4%, #f8f9fa);
            border-radius: 0.8rem;
            border: 1px dashed color-mix(in srgb, var(--primary) 25%, transparent);
        }
        .btn-continue-shopping {
            background-color: var(--primary) !important;
            color: #ffffff !important;
            border: none;
            box-shadow: 0 6px 15px color-mix(in srgb, var(--primary) 30%, transparent);
            transition: all 0.3s ease;
        }
        .btn-continue-shopping:hover {
            background-color: color-mix(in srgb, var(--primary) 80%, black) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px color-mix(in srgb, var(--primary) 40%, transparent);
            color: #ffffff !important;
        }
        @keyframes popIn {
            0% { transform: scale(0) rotate(-10deg); opacity: 0; }
            80% { transform: scale(1.1) rotate(5deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
    </style>

    <div class="container py-5 min-vh-100 d-flex justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-7 col-md-10">
                
                <!-- Main Card -->
                <div class="card order-card p-4 p-md-5">
                    
                    <!-- Success Header -->
                    <div class="text-center mb-4">
                        <i class="bi bi-check-circle-fill success-icon"></i>
                        <h1 class="mt-4 fw-bold text-dark">Thank You for Your Order!</h1>
                        <p class="text-muted fs-5">Your order has been successfully placed and is being processed.</p>
                    </div>

                    <!-- Order Info Alert -->
                    <div class="alert order-number-alert text-center rounded-3 mb-4 py-3" role="alert">
                        <strong>Order Number:</strong> {{ $order?->order_number }}
                    </div>

                    <!-- Order Details Grid -->
                    <div class="row g-4 mb-4">
                        
                        <!-- Shipping Address -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3" style="color: var(--primary);"><i class="bi bi-geo-alt me-2"></i>Shipping Address</h5>
                            <div class="p-3 border rounded-3 h-100" style="background-color: #fafafa;">
                                <p class="mb-1 fw-bold fs-5">{{ $shippingAddress?->name }}</p>
                                <p class="mb-1 text-muted">{{ $shippingAddress?->address }}</p>
                                <p class="mb-1 text-muted">{{ $shippingAddress?->city }}, {{ $shippingAddress?->state_name }}, {{ $shippingAddress?->country_name }}</p>
                                <p class="mb-0 text-muted mt-2"><i class="bi bi-telephone-fill me-1"></i> {{ $shippingAddress?->mobile ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3" style="color: var(--primary);"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                            <div class="summary-box p-3 h-100">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal ({{ $order->orderProducts->count() }} items)</span>
                                    <span class="fw-semibold">{{ ($siteSettings->currency_symbol ?? null) }} {{ formatBDT($order?->sub_total) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Shipping</span>
                                    <span class="fw-semibold">{{ ($siteSettings->currency_symbol ?? null) }} {{ formatBDT($order?->shipping_charge) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Discount</span>
                                    <span class="fw-semibold">{{ ($siteSettings->currency_symbol ?? null) }} {{ formatBDT($order?->coupon_discount ?? 0) }}</span>
                                </div>
                                <hr style="border-color: color-mix(in srgb, var(--primary) 20%, transparent);">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-dark fs-5">Total</span>
                                    <span class="fw-bold fs-4" style="color: var(--primary);">{{ ($siteSettings->currency_symbol ?? null) }} {{ formatBDT($order?->grand_total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center mt-5">
                        <a href="{{ route('shop') }}" class="btn btn-continue-shopping btn-lg px-5 py-3 rounded-pill fw-bold">
                            <i class="bi bi-cart-check me-2"></i>Continue Shopping
                        </a>
                    </div>

                </div>
                <!-- End Main Card -->
                
                <!-- Help Text -->
                <div class="text-center mt-4 text-muted">
                    <p>Need help? <a href="{{ route('contact') }}" class="text-decoration-none fw-bold" style="color: var(--primary);">Contact our support</a></p>
                </div>

            </div>
        </div>
    </div>
@endsection



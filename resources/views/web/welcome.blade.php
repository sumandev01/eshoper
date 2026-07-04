<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - {{ ($siteSettings->site_title ?? null) }}</title>    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-icon {
            font-size: 5rem;
            color: #D19C97 !important;
            animation: popIn 0.5s ease-in-out;
        }
        .order-card {
            border-radius: 1rem;
            border: none;
        }
        .summary-box {
            background-color: #f1f4f8;
            border-radius: 0.5rem;
        }
        @keyframes popIn {
            0% { transform: scale(0); opacity: 0; }
            80% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>

    <div class="container py-5 min-vh-100 d-flex justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-lg-8 col-md-10">
                
                <!-- Main Card -->
                <div class="card order-card shadow-lg p-4 p-md-5">
                    
                    <!-- Success Header -->
                    <div class="text-center mb-4">
                        <i class="bi bi-check-circle-fill success-icon"></i>
                        <h1 class="mt-3 fw-bold text-dark">Thank You for Your Order!</h1>
                        <p class="text-muted fs-5">Your order has been successfully placed.</p>
                    </div>

                    <!-- Order Info Alert -->
                    <div class="alert alert-primary text-center border-0 rounded-3 mb-4" role="alert">
                        <strong>Order Number:</strong> {{ $order?->order_number }}
                    </div>

                    <!-- Order Details Grid -->
                    <div class="row g-4 mb-4">
                        
                        <!-- Shipping Address -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Shipping Address</h5>
                            <div class="p-3 border rounded-3 h-100">
                                <p class="mb-1 fw-bold">{{ $shippingAddress?->name }}</p>
                                <p class="mb-1 text-muted">{{ $shippingAddress?->address }}</p>
                                <p class="mb-1 text-muted">{{ $shippingAddress?->thana }}, {{ $shippingAddress?->division }}</p>
                                <p class="mb-0 text-muted">Phone: {{ $shippingAddress?->mobile ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
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
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-dark fs-5">Total</span>
                                    <span class="fw-bold text-primary fs-5">{{ ($siteSettings->currency_symbol ?? null) }} {{ formatBDT($order?->grand_total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center mt-4">
                        <a href="{{ route('orderTracking') }}" class="btn btn-primary btn-lg px-4 gap-3">
                            <i class="bi bi-truck me-2"></i>Track Order
                        </a>
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary text-primary btn-lg px-4">
                            <i class="bi bi-cart me-2"></i>Continue Shopping
                        </a>
                    </div>

                </div>
                <!-- End Main Card -->
                
                <!-- Help Text -->
                <div class="text-center mt-4 text-muted">
                    <p>Need help? <a href="{{ route('contact') }}" class="text-decoration-none">Contact our support</a></p>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

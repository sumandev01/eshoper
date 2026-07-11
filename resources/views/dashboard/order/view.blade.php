@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Order Details - ' . $order->order_number)
@section('content')
    <!-- Main Content Area -->
    <div id="printableArea">
        <div class="container-fluid">

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1 order-title">Order Details</h2>
                    <p class="text-muted mb-0">Order ID: <strong class="text-primary">#ORD-2026-9910</strong></p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm shadow-sm px-3 back-btn" onclick="window.history.back()">
                        <i class="fa fa-arrow-left me-2" aria-hidden="true"></i>
                        Back to Orders
                    </button>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print">
                        <i class="fa fa-print" aria-hidden="true"></i> Print Invoice
                    </button>
                </div>
            </div>

            <!-- Info Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3 text-primary">
                                <i class="fa fa-user-circle fa-lg me-2"></i>
                                <h6 class="card-title mb-0 fw-bold">Customer Info</h6>
                            </div>
                            <p class="mb-1 fw-bold text-dark">{{ $order?->user?->name ?? $order?->user_name }}</p>
                            <p class="mb-1 text-muted small">Email: {{ $order?->user?->email ?? $order?->user_email }}</p>
                            <p class="mb-0 text-muted small">Phone: {{ $shippingAddress?->mobile }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3 text-success">
                                <i class="fa fa-truck fa-lg me-2"></i>
                                <h6 class="card-title mb-0 fw-bold">Shipping Address</h6>
                            </div>
                            <p class="mb-1 fw-bold text-dark">{{ $shippingAddress?->address }}, <br>
                                {{ $shippingAddress?->thana }}, {{ $shippingAddress?->division }}</p>
                            <p class="mb-1 text-muted small">Post Code: {{ $shippingAddress?->zip }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm border-start border-warning border-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3 text-warning">
                                <i class="fa fa-credit-card fa-lg me-2"></i>
                                <h6 class="card-title mb-0 fw-bold">Payment Details</h6>
                            </div>

                            @php
                                $paymentMethodStr = strtoupper($order->payment_method ?? 'N/A');
                                $paymentData = $order->payment_data ? json_decode($order->payment_data) : null;
                                
                                if ($order->payment_method === 'sslcommerz' && $paymentData && isset($paymentData->card_type)) {
                                    $brand = explode('-', $paymentData->card_type)[0] ?? $paymentData->card_type;
                                    $paymentMethodStr = 'SSLCOMMERZ - ' . strtoupper($brand);
                                } elseif ($order->payment_method === 'manual' && $paymentData && isset($paymentData->sender_number)) {
                                    $paymentMethodStr = 'MANUAL';
                                } elseif ($order->payment_method === 'cashOnDelivery') {
                                    $paymentMethodStr = 'CASH ON DELIVERY';
                                }
                            @endphp

                            <p class="mb-1 fw-bold text-dark">Method:
                                <span class="text-uppercase">{{ $paymentMethodStr }}</span>
                            </p>
                            <p class="mb-2">Status:
                                <span
                                    class="badge bg-success-subtle text-success border border-success px-2">{{ $order?->payment_status }}</span>
                            </p>

                            @if ($paymentData)
                                <hr class="my-2 border-secondary border-opacity-25">

                                <div class="small">
                                    @if ($order->payment_method === 'sslcommerz')
                                        @if (isset($paymentData->bank_tran_id))
                                            <p class="mb-1"><span class="text-muted">TrxID:</span> <strong
                                                    class="text-dark">{{ $paymentData->bank_tran_id }}</strong></p>
                                        @endif

                                        @if (isset($paymentData->card_issuer))
                                            <p class="mb-1"><span class="text-muted">Paid via:</span> <span
                                                    class="text-dark">{{ $paymentData->card_issuer }}</span></p>
                                        @endif

                                        @if (isset($paymentData->card_no) && !empty($paymentData->card_no))
                                            <p class="mb-0"><span class="text-muted">A/C No:</span> <span
                                                    class="text-dark">{{ $paymentData->card_no }}</span></p>
                                        @endif
                                    @elseif ($order->payment_method === 'manual')
                                        @if (isset($paymentData->sender_number))
                                            <p class="mb-1"><span class="text-muted">Sender Number:</span> <strong
                                                    class="text-dark">{{ $paymentData->sender_number }}</strong></p>
                                        @endif
                                        @if ($order->transaction_id)
                                            <p class="mb-1"><span class="text-muted">TrxID:</span> <strong
                                                    class="text-dark">{{ $order->transaction_id }}</strong></p>
                                        @endif
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Order Items</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">Product</th>
                                    <th class="text-center py-3">Price*Quantity</th>
                                    <th class="text-end pe-4 py-3">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderProducts ?? [] as $orderProduct)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $orderProduct->product?->name }}</div>
                                                    @if ($orderProduct?->size_name !== null || $orderProduct?->color_name !== null)
                                                        <small class="text-muted pt-2 d-block">
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
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                            <span>{{ formatBDT($orderProduct->price) }}</span>
                                            *
                                            <span>{{ $orderProduct->quantity }}</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                            <span>{{ formatBDT($orderProduct?->price * $orderProduct?->quantity) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary Footer -->
                <div class="card-footer bg-white py-4">
                    <div class="row justify-content-end">
                        <div class="col-md-4 col-lg-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-bold">
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                    <span>{{ formatBDT($order?->sub_total) }}</span>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping:</span>
                                <span class="fw-bold">
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                    <span>{{ formatBDT($order?->shipping_charge) }}</span>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Discount(-):</span>
                                <span class="fw-bold">
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                    <span>{{ formatBDT($order?->coupon_discount) }}</span>
                                </span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 fw-bold mb-0 text-dark">Grand Total:</span>
                                <span class="h5 fw-bold mb-0 text-primary">
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                    <span>{{ formatBDT($order?->grand_total) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="print-footer no-print-screen">
            Website: {{ url('/') }}
        </div>
    </div>
@endsection
@push('styles')
    <style>
        @media screen {
            .no-print-screen {
                display: none;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printableArea,
            #printableArea * {
                visibility: visible;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 99%;
                margin: 0;
                padding: 0;
                padding-top: 5px;
                padding-bottom: 5px;
            }

            .order-title {
                font-size: 20px !important;
            }

            .row.g-4.mb-4>div:nth-child(1),
            .row.g-4.mb-4>div:nth-child(2) {
                width: 50% !important;
                float: left;
            }

            .row.g-4.mb-4>div:nth-child(1) .card-body,
            .row.g-4.mb-4>div:nth-child(2) .card-body {
                padding: 10px !important;
            }

            .row.g-4.mb-4>div:nth-child(3) {
                width: 100% !important;
                clear: both;
                margin-top: 20px;
            }

            .row.g-4.mb-4>div:nth-child(3) .card-body {
                padding: 10px !important;
            }

            .card {
                border: 1px solid #dee2e6 !important;
                box-shadow: none !important;
                margin-bottom: 10px !important;
            }

            html,
            body {
                height: auto !important;
                background-color: #fff !important;
            }

            .btn,
            .no-print {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            .print-footer {
                display: block !important;
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                text-align: center;
                font-size: 10px;
                color: #555;
                border-top: 1px solid #ccc;
                padding-top: 5px;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        function printOrder() {
            window.print();
        }
    </script>
@endpush



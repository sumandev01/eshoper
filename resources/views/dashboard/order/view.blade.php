@extends('dashboard.layouts.app')
@section('content')
    <!-- Main Content Area -->
    <div id="printableArea">
        <div class="container-fluid">

            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Order Details</h2>
                    <p class="text-muted mb-0">Order ID: <strong class="text-primary">#ORD-2026-9910</strong></p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm no-print">
                        <i class="fa fa-print" aria-hidden="true"></i> Print Invoice
                    </button>
                    <button class="btn btn-primary btn-sm shadow-sm px-3">
                        Mark as Delivered
                    </button>
                </div>
            </div>

            <!-- Info Cards -->
            <div class="row g-4 mb-4">
                <!-- Customer Info -->
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

                <!-- Shipping Address -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3 text-success">
                                <i class="fa fa-truck fa-lg me-2"></i>
                                <h6 class="card-title mb-0 fw-bold">Shipping Address</h6>
                            </div>
                            <p class="mb-1 fw-bold text-dark">{{ $shippingAddress?->address }}, <br> {{ $shippingAddress?->thana }}, {{ $shippingAddress?->division }}</p>
                            <p class="mb-1 text-muted small">Post Code: {{ $shippingAddress?->zip }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm border-start border-warning border-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3 text-warning">
                                <i class="fa fa-credit-card fa-lg me-2"></i>
                                <h6 class="card-title mb-0 fw-bold">Payment Status</h6>
                            </div>
                            <p class="mb-1 fw-bold text-dark">Method: {{ $order?->payment_method }}</p>
                            <span class="badge bg-success-subtle text-success border border-success px-3">{{ $order?->payment_status }}</span>
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
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
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
                                                    <div class="fw-bold text-dark">{{ $orderProduct->product->name }}</div>
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
                                            <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                            <span>{{ formatBDT($orderProduct->price) }}</span>
                                            *
                                            <span>{{ $orderProduct->quantity }}</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
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
                                    <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                    <span>{{ formatBDT($order?->sub_total) }}</span>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping:</span>
                                <span class="fw-bold">
                                    <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                    <span>{{ formatBDT($order?->shipping_charge) }}</span>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Discount(-):</span>
                                <span class="fw-bold">
                                    <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                    <span>{{ formatBDT($order?->coupon_discount) }}</span>
                                </span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 fw-bold mb-0 text-dark">Grand Total:</span>
                                <span class="h5 fw-bold mb-0 text-primary">
                                    <span class="currency">{{ $siteSettings?->currency_symbol }}</span>
                                    <span>{{ formatBDT($order?->grand_total) }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printableArea,
            #printableArea * {
                visibility: visible;
                padding-top: 10px;
                line-height: 1;
            }

            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .btn,
            .no-print {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 20mm;
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

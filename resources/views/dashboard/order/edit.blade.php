@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Edit Order Status - ' . $order->order_number)

@section('content')
    <div class="container-fluid px-0">
        <!-- Page Header (Outside Card, Left-Aligned) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Edit Order Status</h4>
                <p class="text-muted mb-0">Order ID: <strong class="text-primary">#{{ $order->order_number }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.order.view', $order->id) }}" class="btn btn-info btn-sm text-white shadow-sm px-3">
                    <i class="mdi mdi-eye me-1"></i> View Order Details
                </a>
                <a href="{{ route('admin.order.index') }}" class="btn btn-primary btn-sm shadow-sm px-3">
                    <i class="mdi mdi-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
        </div>

        <!-- 2-Column Split Layout -->
        <div class="row g-4">
            {{-- Left Column: Update Status Form Card --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="card-title mb-0 fw-bold">
                            <i class="mdi mdi-square-edit-outline text-primary me-1"></i> Update Status & Courier Information
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.order.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Payment Status Component --}}
                            <x-select 
                                label="Payment Status" 
                                id="type" 
                                class="text-capitalize" 
                                name="payment_status"
                                :options="$paymentStatusEnums" 
                                :value="$order->payment_status->value" 
                                :required="true" 
                            />

                            {{-- Order Status Component --}}
                            <x-select 
                                label="Order Status" 
                                id="order_status_select" 
                                class="text-capitalize" 
                                name="order_status"
                                :options="$orderStatusEnums" 
                                :value="$order->order_status->value" 
                                :required="true" 
                            />

                            <div id="delivery_error" class="alert alert-danger py-2 px-3 mt-2 small" style="display: none;">
                                <i class="mdi mdi-alert-circle-outline me-1"></i> Order cannot be marked as <strong>Delivered</strong> unless Payment Status is <strong>Paid</strong>!
                            </div>

                            {{-- Courier Section --}}
                            <div id="courier_section" class="mt-4 pt-3 border-top" style="display: none;">
                                <h6 class="fw-bold mb-3 text-primary"><i class="mdi mdi-truck-delivery me-1"></i> Shipping & Courier Info</h6>
                                
                                <x-select 
                                    label="Courier Service" 
                                    id="courier_id" 
                                    name="courier_id" 
                                    :options="$couriers" 
                                    :value="$order->courier_id" 
                                    placeholder="Select a Courier" 
                                    :required="false" 
                                />

                                <x-input 
                                    label="Tracking Number" 
                                    name="courier_tracking_id" 
                                    :value="old('courier_tracking_id', $order->courier_tracking_id)" 
                                    placeholder="Enter courier tracking ID (e.g. STE-123456)" 
                                    :required="false" 
                                />
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm border-0">
                                    <i class="mdi mdi-check-circle me-1"></i> Update Order Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column: Mini Order Summary Card --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="card-title mb-0 fw-bold">
                            <i class="mdi mdi-information-outline text-info me-1"></i> Quick Order Summary
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3 d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted small">Order Number:</span>
                            <span class="fw-bold text-dark">#{{ $order->order_number }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted small">Customer Name:</span>
                            <span class="fw-semibold text-dark">{{ $order->user_name ?? 'Guest Customer' }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted small">Total Items:</span>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1">
                                {{ $order->orderProducts->count() }} items
                            </span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted small">Grand Total Amount:</span>
                            <span class="fw-bold text-success fs-6">
                                {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($order->grand_total) }}
                            </span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted small">Payment Status:</span>
                            <span class="badge rounded-pill bg-{{ $order?->payment_status?->color() }}">
                                {{ ucfirst($order?->payment_status?->value) }}
                            </span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center pb-2 border-bottom">
                            <span class="text-muted small">Order Status:</span>
                            <span class="badge rounded-pill bg-{{ $order?->order_status?->color() }}">
                                {{ ucfirst($order?->order_status?->value) }}
                            </span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Order Date:</span>
                            <span class="text-muted small">{{ $order->created_at->format('d M, Y (h:i A)') }}</span>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('admin.order.view', $order->id) }}" class="btn btn-outline-primary btn-sm w-100 shadow-sm py-2">
                                <i class="mdi mdi-eye me-1"></i> View Complete Order Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentStatusSelect = document.getElementById('type');
        const orderStatusSelect = document.getElementById('order_status_select');
        const courierSection = document.getElementById('courier_section');
        const deliveryError = document.getElementById('delivery_error');
        
        let previousOrderStatus = orderStatusSelect ? orderStatusSelect.value : '';

        function toggleCourierSection() {
            if (!orderStatusSelect) return;
            const status = orderStatusSelect.value ? orderStatusSelect.value.toLowerCase() : '';
            const courierSelect = document.getElementById('courier_id');
            if (status === 'shipped') {
                courierSection.style.display = 'block';
                if (courierSelect) courierSelect.required = true;
            } else {
                courierSection.style.display = 'none';
                if (courierSelect) courierSelect.required = false;
            }
        }

        function validateStatus() {
            if (!orderStatusSelect || !paymentStatusSelect) return;
            const orderStatus = orderStatusSelect.value ? orderStatusSelect.value.toLowerCase() : '';
            const paymentStatus = paymentStatusSelect.value ? paymentStatusSelect.value.toLowerCase() : '';

            if (orderStatus === 'delivered' && paymentStatus !== 'paid') {
                deliveryError.style.display = 'block';
                orderStatusSelect.value = previousOrderStatus !== 'delivered' ? previousOrderStatus : 'pending';
            } else {
                deliveryError.style.display = 'none';
                previousOrderStatus = orderStatusSelect.value;
            }
        }

        if (orderStatusSelect) {
            orderStatusSelect.addEventListener('change', function() {
                validateStatus();
                toggleCourierSection();
            });
        }

        if (paymentStatusSelect) {
            paymentStatusSelect.addEventListener('change', function() {
                if (orderStatusSelect.value.toLowerCase() === 'delivered' && this.value.toLowerCase() !== 'paid') {
                    validateStatus();
                }
            });
        }

        toggleCourierSection(); // Run on page load
    });
</script>
@endpush

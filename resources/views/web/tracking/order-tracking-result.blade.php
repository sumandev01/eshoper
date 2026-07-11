@extends('web.layouts.app')
@section('title', 'Order Status' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Breadcrumb Start -->
    @include('web.components.breadcrumb', ['title' => 'Order Status'])
    <!-- Page Breadcrumb End -->
    <!-- Page header section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">Order Status</h1>
            <p class="lead text-muted">Tracking details for your order {{ $order?->order_number }}</p>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    
                    <!-- Order Info Summary Cards -->
                    <div class="row justify-content-center text-center mb-5">
                        <div class="col-md-4 col-6 mb-3">
                            <div class="bg-light p-3 rounded h-100">
                                <span class="text-muted d-block small text-uppercase fw-bold">Customer Name</span>
                                <span class="fw-bold text-dark">{{ $order?->user_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="bg-light p-3 rounded h-100">
                                <span class="text-muted d-block small text-uppercase fw-bold">Order Date</span>
                                <span class="fw-bold text-dark">{{ $order?->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="bg-light p-3 rounded h-100">
                                <span class="text-muted d-block small text-uppercase fw-bold">Total Amount</span>
                                <span class="fw-bold text-dark">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($order?->grand_total) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-6 mb-3">
                            <div class="bg-light p-3 rounded h-100">
                                <span class="text-muted d-block small text-uppercase fw-bold">Payment Status</span>
                                <span class="fw-bold text-dark">{{ ucfirst($order?->payment_status->value ?? $order?->payment_status) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-6 mb-3">
                            <div class="bg-light p-3 rounded h-100">
                                <span class="text-muted d-block small text-uppercase fw-bold">Order Status</span>
                                <span class="fw-bold text-success">{{ ucfirst(str_replace('_', ' ', $order?->order_status->value ?? $order?->order_status)) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($order->order_status->value === 'shipped' || $order->order_status->value === 'delivered')
                        @php
                            $courier = \App\Models\Courier::find($order->courier_id);
                        @endphp
                        @if($courier && $order->courier_tracking_id)
                        <div class="card shadow-sm border-0 mb-5 bg-primary text-white text-center">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Parcel Shipped via {{ $courier->name }}</h5>
                                <p class="mb-3">Tracking Number: <strong>{{ $order->courier_tracking_id }}</strong></p>
                                <p class="small mb-3">Copy your tracking number and click the button below to track your parcel on the courier's website.</p>
                                <a href="{{ $courier->tracking_url }}" target="_blank" class="btn btn-light fw-bold px-4">Track on Courier Website</a>
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- Visual Progress Timeline -->
                    <div class="card shadow-sm border-0 mb-5">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="mb-4 font-weight-light">Shipment Progress</h4>
                            <div class="track-timeline">
                                @php
                                    use App\Enums\OrderStatusEnums;
                                    
                                    $currentStatus = is_string($order->order_status) ? OrderStatusEnums::tryFrom($order->order_status) : $order->order_status;
                                    
                                    $timelineSteps = [
                                        OrderStatusEnums::PENDING,
                                        OrderStatusEnums::PROCESSING,
                                        OrderStatusEnums::SHIPPED,
                                        OrderStatusEnums::DELIVERED
                                    ];
                                    
                                    if ($currentStatus === OrderStatusEnums::CANCELLED) {
                                        $timelineSteps = [
                                            OrderStatusEnums::PENDING,
                                            OrderStatusEnums::CANCELLED
                                        ];
                                    }

                                    $currentStatusFound = false;
                                @endphp

                                @foreach($timelineSteps as $step)
                                    @php
                                        $isActive = $currentStatus === $step;
                                        if ($isActive) $currentStatusFound = true;
                                        
                                        $state = 'pending';
                                        if ($isActive) {
                                            $state = 'active';
                                        } elseif (!$currentStatusFound) {
                                            $state = 'completed';
                                        }
                                        if($currentStatus === OrderStatusEnums::CANCELLED && $step === $currentStatus) {
                                            $state = 'canceled';
                                        }
                                    @endphp
                                    <div class="timeline-step {{ $state }}">
                                        <div class="step-icon">
                                            <i class="fas {{ $step->icon() }}"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6 class="fw-bold mb-1 {{ $state == 'active' ? 'text-primary' : ($state == 'canceled' ? 'text-danger' : '') }}">{{ $step->label() }}</h6>
                                            <p class="text-muted small mb-0">{{ $step->description() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Details Table -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h4 class="mb-4 font-weight-light">Product Details</h4>
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $orderProducts = $order->orderProducts()->get();
                                        @endphp
                                        @foreach ($orderProducts as $product)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-bold text-dark">{{ $product->product?->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center text-muted">{{ $product->quantity }}</td>
                                                <td class="text-end fw-bold text-dark">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($product->product?->price) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Back to tracking button -->
                    <div class="text-center mt-4">
                        <a href="{{ route('orderTracking') }}" class="btn btn-outline-primary px-4">Track Another Order</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .hero-section {
            background-color: #f8f9fa;
            padding: 60px 0;
        }
        
        .extra-small {
            font-size: 11px;
            display: block;
        }

        /* Timeline Base Styling */
        .track-timeline {
            position: relative;
            padding-left: 35px;
            margin-left: 15px;
            border-start: 2px solid #e9ecef;
        }

        .timeline-step {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-step:last-child {
            padding-bottom: 0;
            border-start-color: transparent !important;
        }

        /* Icons for steps */
        .step-icon {
            position: absolute;
            left: -51px;
            top: 0;
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            border-radius: 50%;
            font-size: 14px;
            z-index: 2;
        }

        /* State 1: Completed Steps */
        .timeline-step.completed .step-icon {
            background-color: #28a745;
            color: white;
        }
        .timeline-step.completed .step-content h6 {
            color: #28a745;
        }

        /* State 2: Active Step */
        .timeline-step.active .step-icon {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 0 0 4px var(--primary-soft);
        }

        /* State 3: Pending Steps */
        .timeline-step.pending .step-icon {
            background-color: #e9ecef;
            color: #6c757d;
        }
        .timeline-step.pending .step-content h6 {
            color: #6c757d;
        }

        /* State 4: Canceled Steps */
        .timeline-step.canceled .step-icon {
            background-color: #dc3545;
            color: white;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.2);
        }
    </style>
@endpush



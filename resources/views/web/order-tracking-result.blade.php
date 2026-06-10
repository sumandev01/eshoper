@extends('web.layouts.app')
@section('title', 'Order Status' . ' - ' . $siteSettings?->site_title)
@section('content')
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
                    {{-- <div class="row text-center mb-5">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="bg-light p-3 rounded">
                                <span class="text-muted d-block small text-uppercase font-weight-bold">Order Date</span>
                                <span class="font-weight-bold text-dark">{{ $order?->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="bg-light p-3 rounded">
                                <span class="text-muted d-block small text-uppercase font-weight-bold">Total Amount</span>
                                <span class="font-weight-bold text-dark">{{ $siteSettings?->currency_symbol }}{{ formatBDT($order?->grand_total) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="bg-light p-3 rounded">
                                <span class="text-muted d-block small text-uppercase font-weight-bold">Payment Method</span>
                                <span class="font-weight-bold text-dark">{{ $order?->payment_method }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="bg-light p-3 rounded">
                                <span class="text-muted d-block small text-uppercase font-weight-bold">Estimated Delivery</span>
                                <span class="font-weight-bold text-success">{{ $order?->created_at?->copy()->addDays(7)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Visual Progress Timeline -->
                    {{-- <div class="card shadow-sm border-0 mb-5">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="mb-4 font-weight-light">Shipment Progress</h4>
                            <div class="track-timeline">
                                <!-- Step 1: Completed -->
                                <div class="timeline-step completed">
                                    <div class="step-icon">
                                        <i class="fas fa-shopping-basket"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6 class="font-weight-bold mb-1">Order Placed</h6>
                                        <p class="text-muted small mb-0">We have received your order.</p>
                                        <span class="text-muted extra-small">June 04, 10:30 AM</span>
                                    </div>
                                </div>

                                <!-- Step 2: Completed -->
                                <div class="timeline-step completed">
                                    <div class="step-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6 class="font-weight-bold mb-1">Processing & Packing</h6>
                                        <p class="text-muted small mb-0">Your item has been packed and ready for shipment.</p>
                                        <span class="text-muted extra-small">June 05, 02:15 PM</span>
                                    </div>
                                </div>

                                <!-- Step 3: Active/Current Status -->
                                <div class="timeline-step active">
                                    <div class="step-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6 class="font-weight-bold mb-1 text-primary">On The Way</h6>
                                        <p class="text-muted small mb-0">The package has been handed over to the courier company.</p>
                                        <span class="text-muted extra-small">June 06, 09:00 AM</span>
                                    </div>
                                </div>

                                <!-- Step 4: Pending -->
                                <div class="timeline-step pending">
                                    <div class="step-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="step-content">
                                        <h6 class="font-weight-bold mb-1">Delivered</h6>
                                        <p class="text-muted small mb-0">Package arrived safely at your doorstep.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="card shadow-sm border-0 mb-5 p-4 bg-primary text-white">
                        This section is under development for now 👀
                    </div>

                    <!-- Order Items Details Table -->
                    {{-- <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h4 class="mb-4 font-weight-light">Product Details</h4>
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Price</th>
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
                                                        <span class="font-weight-bold text-dark">{{ $product->product->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center text-muted">{{ $product->quantity }}</td>
                                                <td class="text-right font-weight-bold text-dark">${{ $product->product->price }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}

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
            border-left: 2px solid #e9ecef;
        }

        .timeline-step {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-step:last-child {
            padding-bottom: 0;
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
            background-color: #007bff;
            color: white;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.2);
        }

        /* State 3: Pending Steps */
        .timeline-step.pending .step-icon {
            background-color: #e9ecef;
            color: #6c757d;
        }
        .timeline-step.pending .step-content h6 {
            color: #6c757d;
        }
    </style>
@endpush
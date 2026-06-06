@extends('web.layouts.app')
@section('title', 'Track Your Order' . ' - ' . $siteSettings?->site_title)
@section('content')
    <!-- Page header section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">Track Your Order</h1>
            <p class="lead text-muted">To track your order please enter your Order ID and Billing Email.</p>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 p-4">
                        <p class="text-muted small text-center mb-4">
                            You can find your Order ID in the confirmation email or invoice sent to you after making the purchase.
                        </p>
                        
                        <!-- Track Form -->
                        <form action="#" method="GET">
                            <div class="form-group mb-3">
                                <label for="order_id" class="font-weight-bold">Order ID</label>
                                <input type="text" id="order_id" name="order_id" class="form-control" placeholder="Found in your confirmation email (e.g., #12345)" required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="billing_email" class="font-weight-bold">Billing Email Address</label>
                                <input type="email" id="billing_email" name="billing_email" class="form-control" placeholder="Email used during checkout" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 text-uppercase font-weight-bold">Track Order</button>
                        </form>
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
        .card {
            border-radius: 8px;
        }
    </style>
@endpush
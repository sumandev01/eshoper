@extends('web.layouts.app')
@section('title', 'Track Your Order' . ' - ' . $siteSettings?->site_title)
@section('content')
    <!-- Page header section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">Track Your Order</h1>
            <p class="lead text-muted">To track your order please enter your Order ID.</p>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 shadow p-5">
                        <p class="text-muted small text-center mb-4">
                            Enter your Order ID to track your order
                        </p>
                        
                        <!-- Track Form -->
                        <form action="{{ route('web.order-tracking-result') }}" method="GET">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="order_number" class="font-weight-bold">Order ID</label>
                                <input type="text" id="order_number" name="order_number" class="form-control" placeholder="Enter your Order ID" value="{{ old('order_number') }}" required>
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
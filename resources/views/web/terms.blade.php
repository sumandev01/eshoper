@extends('web.layouts.app')
@section('title', 'Terms & Conditions' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page header section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">Terms & Conditions</h1>
            <p class="lead text-muted">Please read these terms carefully before using our e-commerce platform.</p>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="content-box bg-white p-4 p-md-5 rounded shadow-sm">
                        <p class="text-muted">Last updated: June 06, 2026</p>
                        
                        <h3 class="mt-4 mb-3">1. Terms of Use</h3>
                        <p>By accessing and shopping on this website, you agree to comply with and be bound by these Terms & Conditions. If you do not agree with any part of these terms, you must not use our website services.</p>

                        <h3 class="mt-4 mb-3">2. Account Responsibility</h3>
                        <p>When you create an account on our shop, you are responsible for maintaining the confidentiality of your account password and user information. You agree to accept responsibility for all activities that happen under your account.</p>

                        <h3 class="mt-4 mb-3">3. Pricing and Product Availability</h3>
                        <p>All prices listed on our platform are subject to change without prior notice. We make every effort to display accurate stock availability and pricing, but occasional errors may occur. In such cases, we reserve the right to cancel or adjust orders.</p>

                        <h3 class="mt-4 mb-3">4. Delivery and Cancellations</h3>
                        <p>Delivery timeframes are estimates and may vary depending on external factors. Customers can cancel an order before it has been processed or shipped. Once an order enters the shipping phase, standard return policies will apply.</p>

                        <h3 class="mt-4 mb-3">5. Intellectual Property</h3>
                        <p>All contents present on this platform including logos, graphics, text, icons, and software are the property of our e-commerce brand and are protected under international copyright regulations.</p>
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
        .content-box h3 {
            font-size: 1.5rem;
            color: #333;
            font-weight: 600;
        }
        .content-box p {
            color: #6c757d;
            line-height: 1.7;
        }
    </style>
@endpush

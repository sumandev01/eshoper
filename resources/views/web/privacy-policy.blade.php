@extends('web.layouts.app')
@section('title', 'Privacy Policy' . ' - ' . $siteSettings?->site_title)
@section('content')
    <!-- Page header section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">Privacy Policy</h1>
            <p class="lead text-muted">Learn how we collect, use, and protect your personal information.</p>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="content-box bg-white p-4 p-md-5 rounded shadow-sm">
                        <p class="text-muted">Last updated: June 06, 2026</p>
                        
                        <h3 class="mt-4 mb-3">1. Information We Collect</h3>
                        <p>We collect personal information that you provide to us directly when creating an account, placing an order, or contacting our team. This includes your name, email address, billing address, shipping address, and phone number.</p>

                        <h3 class="mt-4 mb-3">2. How We Use Your Information</h3>
                        <p>We use the collected data to process your orders, manage your account, deliver products, send order confirmations, and notify you about important website updates or promotions if you choose to receive them.</p>

                        <h3 class="mt-4 mb-3">3. Data Protection and Security</h3>
                        <p>Your security is important to us. We implement modern security protocols and encryption methods to safeguard your personal data. We never sell or rent your personal information to third parties for marketing purposes.</p>

                        <h3 class="mt-4 mb-3">4. Cookies</h3>
                        <p>Our website uses cookies to enhance your browsing experience, remember shopping cart items, and analyze site traffic. You can disable cookies through your browser settings, though some features of the shop may stop working correctly.</p>

                        <h3 class="mt-4 mb-3">5. Contact Information</h3>
                        <p>If you have any questions or concerns regarding this privacy policy, feel free to contact our support team through our dedicated contact page.</p>
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
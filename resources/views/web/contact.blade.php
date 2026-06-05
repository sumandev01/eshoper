@extends('web.layouts.app')
@section('content')
<!-- Page header section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">Contact Us</h1>
            <p class="lead text-muted">Have any questions? We would love to hear from you.</p>
        </div>
    </section>

    <!-- Main contact form and info section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Left side: Contact Form -->
                <div class="col-md-7 mb-5 mb-md-0">
                    <h3 class="font-weight-light mb-4">Send Us a Message</h3>
                    <form action="" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Your Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Your Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                    </form>
                </div>

                <!-- Right side: Contact Info -->
                <div class="col-md-5">
                    <h3 class="font-weight-light mb-4">Our Store</h3>
                    
                    <div class="d-flex mb-3">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h5>Address</h5>
                            <p class="text-muted">{{ $siteSettings->contact_address }}</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h5>Phone</h5>
                            <p class="text-muted">{{ $siteSettings->contact_phone }}</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p class="text-muted">{{ $siteSettings->contact_email }}</p>
                        </div>
                    </div>

                    <!-- Google Map Placeholder -->
                    <h4 class="font-weight-light mb-3">Find Us on Map</h4>
                    <div class="map-container rounded shadow-sm">
                        {!! $siteSettings->google_map !!}
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
            padding: 80px 0;
        }
        .contact-info-icon {
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            margin-right: 15px;
        }
        .map-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }
        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
@endpush
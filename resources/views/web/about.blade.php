@extends('web.layouts.app')
@section('content')
<section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">About Us</h1>
            <p class="lead text-muted">Delivering quality products to your doorstep since 2020.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="https://via.placeholder.com/600x400" alt="Our Story" class="img-fluid rounded shadow-sm">
                </div>
                <div class="col-md-6">
                    <h2 class="font-weight-light">Our Story</h2>
                    <p class="text-muted mt-3">We started with a simple idea: to make high-quality products accessible to everyone. What began in a small garage has now grown into a full-scale e-commerce platform serving thousands of happy customers daily.</p>
                    <p class="text-muted">Our dedicated team works around the clock to source the best materials, ensure top-notch quality control, and provide customer service that makes you feel like family.</p>
                    <a href="#" class="btn btn-primary mt-2">Shop Now</a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-light py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                    <h4 class="font-weight-light">Our Mission</h4>
                    <p class="text-muted">To provide exceptional value and a seamless shopping experience for every customer, every time.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                    <h4 class="font-weight-light">Our Vision</h4>
                    <p class="text-muted">To become the most trusted and preferred online shopping destination globally.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <i class="fas fa-gem fa-3x text-primary mb-3"></i>
                    <h4 class="font-weight-light">Our Values</h4>
                    <p class="text-muted">Integrity, customer-first approach, continuous innovation, and sustainable practices.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="font-weight-light text-center mb-5">Meet Our Team</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-4 team-member">
                    <img src="https://via.placeholder.com/150" alt="Team Member 1" class="img-fluid shadow-sm">
                        <h5 class="mb-0">John Doe</h5>
                    <span class="text-muted">Founder & CEO</span>
                </div>
                <div class="col-md-4 mb-4 team-member">
                    <img src="https://via.placeholder.com/150" alt="Team Member 2" class="img-fluid shadow-sm">
                    <h5 class="mb-0">Jane Smith</h5>
                    <span class="text-muted">Head of Operations</span>
                </div>
                <div class="col-md-4 mb-4 team-member">
                    <img src="https://via.placeholder.com/150" alt="Team Member 3" class="img-fluid shadow-sm">
                    <h5 class="mb-0">Mike Johnson</h5>
                    <span class="text-muted">Lead Developer</span>
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
        .team-member img {
            border-radius: 50%;
            transition: transform 0.3s ease;
        }
        .team-member img:hover {
            transform: scale(1.05);
        }
    </style>
@endpush
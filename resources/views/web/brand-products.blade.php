@extends('web.layouts.app')
@section('title', 'About' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">{{ $aboutPages?->top_header ?? 'About Us' }}</h1>
            <p class="lead text-muted">{{ $aboutPages?->top_sub_header ?? 'Discover Our Story' }}</p>
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ $media }}" alt="Our Story" class="img-fluid rounded shadow-sm">
                </div>
                <div class="col-md-6">
                    <h2 class="font-weight-light">{{ $aboutPages?->heading ?? 'Our Story' }}</h2>
                    <p class="text-muted mt-3">{!! $aboutPages?->description ?? 'Discover Our Story' !!}</p>
                    <a href="{{ $aboutPages?->button_link ?? '#' }}"
                        class="btn btn-primary mt-2">{{ $aboutPages?->button_text ?? 'Read More' }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-light py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                    <h4 class="font-weight-light">Our Mission</h4>
                    <p class="text-muted">
                        {{ $aboutPages?->our_mission ?? 'To become the most trusted and preferred online shopping destination globally.' }}
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                    <h4 class="font-weight-light">Our Vision</h4>
                    <p class="text-muted">
                        {{ $aboutPages?->our_vision ?? 'To revolutionize the way people shop online by providing a seamless and enjoyable shopping experience.' }}
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <i class="fas fa-gem fa-3x text-primary mb-3"></i>
                    <h4 class="font-weight-light">Our Values</h4>
                    <p class="text-muted">
                        {{ $aboutPages?->our_values ?? 'Integrity, Innovation, and Customer Satisfaction.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <h2 class="font-weight-light text-center mb-5">Meet Our Team</h2>
            <div class="row text-center">
                @foreach ($teamMembers ?? [] as $teamMember)
                    <div class="col-md-4 mb-4 team-member">
                        <img src="{{ Storage::url($teamMember?->media?->src) }}" alt="Team Member 1"
                            class="img-fluid shadow-sm">
                        <h5 class="mb-0 mt-3">{{ $teamMember?->name }}</h5>
                        <span class="text-muted">{{ $teamMember?->position }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .hero-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }

        .team-member img {
            aspect-ratio: 1/1;
            transition: transform 0.3s ease;
        }

        .team-member img:hover {
            transform: scale(1.05);
        }
    </style>
@endpush



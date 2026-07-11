@extends('web.layouts.app')
@section('title', 'About' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [['name' => 'About Us', 'url' => '']],
    ])
    <div class="container pt-1">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="font-weight-light">{{ $aboutPages?->top_header ?? 'About Us' }}</h2>
                <p class="lead text-muted mb-0">{{ $aboutPages?->top_sub_header ?? 'Discover Our Story' }}</p>
            </div>
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
                        class="btn btn-primary mt-2 px-4">{{ $aboutPages?->button_text ?? 'Read More' }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-5" style="background-color: color-mix(in srgb, var(--primary) 4%, white);">
        <div class="container">
            <div class="row text-center align-items-stretch">
                <div class="col-md-4 mb-4">
                    <div class="theme-shadow transition-all hover-up p-5 h-100 d-flex flex-column align-items-center"
                        style="background: white; border-radius: 12px;">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; border-radius: 50%; background-color: color-mix(in srgb, var(--primary) 12%, white);">
                            <i class="fas fa-bullseye fa-2x text-primary"></i>
                        </div>
                        <h4 class="font-weight-semi-bold mb-3" style="color: color-mix(in srgb, var(--primary) 60%, #111);">
                            {{ $aboutPages?->mission_header ?? 'Our Mission' }}</h4>
                        <p class="text-muted mb-0">
                            {{ $aboutPages?->our_mission ?? 'To become the most trusted and preferred online shopping destination globally.' }}
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="theme-shadow transition-all hover-up p-5 h-100 d-flex flex-column align-items-center"
                        style="background: white; border-radius: 12px;">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; border-radius: 50%; background-color: color-mix(in srgb, var(--primary) 12%, white);">
                            <i class="fas fa-eye fa-2x text-primary"></i>
                        </div>
                        <h4 class="font-weight-semi-bold mb-3" style="color: color-mix(in srgb, var(--primary) 60%, #111);">
                            {{ $aboutPages?->vision_header ?? 'Our Vision' }}</h4>
                        <p class="text-muted mb-0">
                            {{ $aboutPages?->our_vision ?? 'To revolutionize the way people shop online by providing a seamless and enjoyable shopping experience.' }}
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="theme-shadow transition-all hover-up p-5 h-100 d-flex flex-column align-items-center"
                        style="background: white; border-radius: 12px;">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; border-radius: 50%; background-color: color-mix(in srgb, var(--primary) 12%, white);">
                            <i class="fas fa-gem fa-2x text-primary"></i>
                        </div>
                        <h4 class="font-weight-semi-bold mb-3" style="color: color-mix(in srgb, var(--primary) 60%, #111);">
                            {{ $aboutPages?->values_header ?? 'Our Values' }}</h4>
                        <p class="text-muted mb-0">
                            {{ $aboutPages?->our_values ?? 'Integrity, Innovation, and Customer Satisfaction.' }}
                        </p>
                    </div>
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
                        <div class="bg-white theme-shadow transition-all hover-up h-100 d-flex flex-column" style="border-radius: 12px; overflow: hidden;">
                            <div class="overflow-hidden" style="border-radius: 12px 12px 0 0;">
                                <img src="{{ Storage::url($teamMember?->media?->src) }}" alt="{{ $teamMember?->name ?? 'Team Member' }}"
                                class="img-fluid w-100">
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1 justify-content-center">
                                <h5 class="font-weight-semi-bold mb-1" style="color: color-mix(in srgb, var(--primary) 60%, #111);">{{ $teamMember?->name }}</h5>
                                <span class="text-primary font-weight-bold" style="font-size: 0.9rem;">{{ $teamMember?->position }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .team-member img {
            aspect-ratio: 1/1;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .team-member:hover img {
            transform: scale(1.05);
        }
    </style>
@endpush

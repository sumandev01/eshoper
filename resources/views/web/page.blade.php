@extends('web.layouts.app')
@section('title', $page->title . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page header section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4">{{ $page->title }}</h1>
        </div>
    </section>

    <!-- Main Content Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="content-box bg-white p-4 p-md-5 rounded shadow-sm">
                        <p class="text-muted">Last updated: {{ $page->updated_at->format('F d, Y') }}</p>
                        
                        <div class="page-content">
                            {!! $page->content !!}
                        </div>
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

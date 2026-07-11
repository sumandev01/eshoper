@extends('web.layouts.app')
@section('title', $page->title . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [['name' => $page->title, 'url' => '']],
    ])
    <div class="container pt-1">
        <div class="row">
            <div class="col-12 text-center mb-2">
                <h2 class="font-weight-light">{{ $page->title }}</h2>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row justify-content-center py-5">
        <div class="col-md-10">
            <div class="content-box bg-white p-4 p-md-5 rounded shadow-sm">
                <p class="text-muted">Last updated: {{ $page->updated_at->format('F d, Y') }}</p>

                <div class="page-content">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
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

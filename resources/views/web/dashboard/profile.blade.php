@extends('web.layouts.app')
@section('title', 'User Profile' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'User Dashboard', 'url' => route('user.dashboard')],
            ['name' => 'Profile', 'url' => '']
        ]
    ])
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container pt-1">
        <div class="row">
            @include('web.dashboard.sidebar')
            <div class="col-lg-9 mb-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="dash-card p-4">
                            <div class="card-header bg-transparent py-3 border-bottom-0 rounded-top" style="border-radius: 12px 12px 0 0;">
                                <h5 class="mb-0 font-weight-semi-bold" style="color: color-mix(in srgb, var(--primary) 60%, #111); font-size: 1.2rem;">Profile Information</h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <x-input label="Name" name="name" value="{{ $user?->name }}"
                                        placeholder="Enter your name" />
                                    <x-input label="Email" name="email" value="{{ $user?->email }}"
                                        placeholder="Enter your email" />
                                    <button type="submit" class="btn btn-primary theme-shadow transition-all hover-up px-4 py-2 mt-2" style="border-radius: 8px; font-weight: 500;">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->
@endsection




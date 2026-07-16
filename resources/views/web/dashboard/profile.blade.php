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
            <div class="col-12 col-lg-9 mb-5">
                    <div class="col-12">
                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Left Column: Avatar -->
                                <div class="col-12 col-lg-4 mb-4">
                                    <div class="dash-card p-3 p-md-4 text-center h-100">
                                        <div class="position-relative d-inline-block mb-3 mt-lg-3">
                                            <img src="{{ $userProfileImage }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 140px; height: 140px; object-fit: cover; border: 3px solid color-mix(in srgb, var(--primary) 20%, white);">
                                            <label for="avatar" class="position-absolute bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 36px; height: 36px; bottom: 8px; right: 8px; cursor: pointer; transition: all 0.3s ease;">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                            <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="previewImage(this)">
                                        </div>
                                        <h5 class="font-weight-semi-bold mb-1">{{ $user?->name }}</h5>
                                        <p class="text-muted mb-0 small">{{ $user?->email }}</p>
                                    </div>
                                </div>

                                <!-- Right Column: Profile Info -->
                                <div class="col-12 col-lg-8 mb-4">
                                    <div class="dash-card p-3 p-md-4 h-auto">
                                        <h5 class="mb-4 font-weight-semi-bold" style="color: color-mix(in srgb, var(--primary) 60%, #111); font-size: 1.2rem;"><i class="fas fa-user-edit text-primary mr-2"></i>Personal Information</h5>
                                        <div class="row">
                                            <div class="col-12 col-md-6 mb-3">
                                                <x-input label="Full Name" name="name" value="{{ $user?->name }}" placeholder="Enter your name" />
                                            </div>
                                            <div class="col-12 col-md-6 mb-3">
                                                <x-input label="Email Address" name="email" value="{{ $user?->email }}" readonly="true" />
                                            </div>
                                            <div class="col-12 col-md-6 mb-3">
                                                <x-input label="Phone Number" name="phone" value="{{ $user?->phone }}" placeholder="e.g. +8801..." />
                                            </div>
                                            <div class="col-12 col-md-6 mb-3">
                                                <x-input type="date" label="Date of Birth" name="date_of_birth" value="{{ $user?->date_of_birth }}" />
                                            </div>
                                            <div class="col-12 col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label class="form-label">Gender</label>
                                                    <select name="gender" class="custom-select form-control" style="border-radius: 12px; height: calc(1.5em + 1.5rem + 2px);">
                                                        <option value="">Select Gender</option>
                                                        <option value="Male" {{ $user?->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ $user?->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                        <option value="Other" {{ $user?->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3 mt-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_subscribed" name="is_subscribed" value="1" {{ $user?->is_subscribed ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_subscribed">I want to receive promotional offers and newsletters.</label>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-3 text-right">
                                                <button type="submit" class="btn btn-primary theme-shadow transition-all hover-up px-4 py-2" style="border-radius: 8px; font-weight: 500;">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Danger Zone -->
                        <div class="dash-card p-3 p-md-4 mt-2 border-danger" style="border: 1px solid rgba(220, 53, 69, 0.2);">
                            <h5 class="mb-2 font-weight-semi-bold text-danger" style="font-size: 1.1rem;"><i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone</h5>
                            <p class="text-muted small mb-3">Once you deactivate your account, you will be logged out and cannot access your profile or orders until an administrator reactivates it.</p>
                            <form action="{{ route('user.profile.deactivate') }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate your account?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger px-4 py-2 hover-up transition-all" style="border-radius: 8px; font-weight: 500;">Deactivate My Account</button>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->
@endsection
@push('styles')
    <style>
        /* Match Input Styling for Profile Forms to Address Forms */
        .dash-card .form-label, .dash-card label {
            font-size: 0.9rem;
            font-weight: 600 !important;
            color: color-mix(in srgb, var(--primary) 60%, #111);
            margin-bottom: 8px;
        }

        .dash-card .form-control, .dash-card .custom-select {
            border-radius: 12px !important;
            padding: 12px 20px !important;
            border: 1px solid color-mix(in srgb, var(--primary) 20%, #ccc) !important;
            background-color: #fcfcfc !important;
            transition: all 0.3s ease;
        }

        .dash-card .form-control:focus, .dash-card .custom-select:focus {
            background-color: white !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 15%, transparent) !important;
            outline: none !important;
        }
        
        /* Specific adjustments for date picker vertically centered text */
        input[type="date"].form-control {
            height: calc(1.5em + 1.5rem + 2px) !important;
            display: flex;
            align-items: center;
        }
    </style>
@endpush
@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(input).siblings('img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush




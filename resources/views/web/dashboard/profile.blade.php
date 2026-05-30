@extends('web.layouts.app')
@section('title', 'User Profile' . ' - ' . $siteSettings?->site_title)
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li><a class="nav-link p-0" href="{{ route('user.dashboard') }}">User Dashboard</a></li>
                        <li>Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container-fluid pt-2">
        <div class="row px-xl-5">
            @include('web.dashboard.sidebar')
            <div class="col-lg-9 mb-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header">
                                <h5 class="mb-0">Profile Information</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <x-input label="Name" name="name" value="{{ $user?->name }}"
                                        placeholder="Enter your name" />
                                    <x-input label="Email" name="email" value="{{ $user?->email }}"
                                        placeholder="Enter your email" />
                                    <button type="submit" class="btn btn-primary text-white">Update Profile</button>
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

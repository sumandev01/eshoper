@extends('auth.layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="row w-100 justify-content-center align-items-center min-vh-100">
    <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="card auth-card border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <a href="{{ route('root') }}" class="d-inline-block mb-3">
                        <img src="{{ $siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('default.webp') }}" alt="Logo" style="height: 50px;">
                    </a>
                    <h2>Reset Password</h2>
                    <p>Enter your email to receive a reset link</p>
                </div>
                
                @if (session('success'))
                    <div class="alert alert-success small">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4 material-floating">
                        <input type="email" id="email" name="email" class="form-control" placeholder=" " value="{{ old('email') }}" required autofocus>
                        <label for="email" class="text-muted">Email Address</label>
                        @error('email')
                            <span class="text-danger small mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 theme-shadow hover-up" style="border-radius: 12px; font-weight: 600;">
                        Send Password Reset Link
                    </button>
                    
                    <div class="text-center mt-4 pt-2">
                        <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                            <i class="ti-arrow-left me-1"></i> Back to login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

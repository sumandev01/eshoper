@extends('auth.layouts.app')

@section('title', 'Set New Password')

@section('content')
<div class="row w-100 justify-content-center align-items-center min-vh-100">
    <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="card auth-card border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <a href="{{ route('root') }}" class="d-inline-block mb-3">
                        <img src="{{ $siteSettings->site_logo ? asset($siteSettings->site_logo) : asset('default.webp') }}" alt="Logo" style="height: 50px;">
                    </a>
                    <h2>New Password</h2>
                    <p>Enter your new password below</p>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $request->email }}">

                    <div class="mb-3 position-relative">
                        <div class="material-floating">
                            <input class="form-control" type="password" placeholder=" " name="password" id="password" style="padding-right: 40px;" required autofocus>
                            <label for="password" class="text-muted">New Password</label>
                        </div>
                        <span class="position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; z-index: 10;" onclick="const p=document.getElementById('password'); p.type = p.type==='password'?'text':'password';">
                            <i class="ti-eye text-muted"></i>
                        </span>
                    </div>
                    
                    <div class="mb-4 position-relative">
                        <div class="material-floating">
                            <input class="form-control" type="password" placeholder=" " name="password_confirmation" id="password_confirmation" style="padding-right: 40px;" required>
                            <label for="password_confirmation" class="text-muted">Confirm Password</label>
                        </div>
                        <span class="position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; z-index: 10;" onclick="const p=document.getElementById('password_confirmation'); p.type = p.type==='password'?'text':'password';">
                            <i class="ti-eye text-muted"></i>
                        </span>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 theme-shadow hover-up mb-4" style="border-radius: 12px; font-weight: 600;">
                        Reset Password
                    </button>

                    <div class="text-center mt-3 pt-2 border-top">
                        <a href="{{ route('login') }}" class="text-primary text-decoration-none mt-3 d-inline-block">
                            <i class="ti-arrow-left me-1"></i> Back to login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

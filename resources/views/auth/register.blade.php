@extends('auth.layouts.app')
@section('title', 'Signup')
@section('content')
    <div class="row w-100 justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card auth-card border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <a href="{{ route('root') }}" class="d-inline-block mb-3">
                            <img src="{{ $siteSettings->site_logo ?? asset('auth/images/logo-2.svg') }}" alt="Logo" style="max-height: 40px;">
                        </a>
                        <h2>Signup</h2>
                        <p>Create your free account</p>
                    </div>
                    
                    <form action="{{ route('register.submit') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3 material-floating">
                            <input type="text" id="name" name="name" class="form-control" placeholder=" " value="{{ old('name') }}">
                            <label for="name" class="text-muted">Full Name</label>
                            @error('name')
                                <span class="text-danger small mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3 material-floating">
                            <input type="email" id="email" name="email" class="form-control" placeholder=" " value="{{ old('email') }}">
                            <label for="email" class="text-muted">Email</label>
                            @error('email')
                                <span class="text-danger small mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3 position-relative">
                            <div class="material-floating">
                                <input class="form-control" type="password" placeholder=" " name="password" id="regPassword" style="padding-right: 40px;">
                                <label for="regPassword" class="text-muted">Password</label>
                            </div>
                            <span class="position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; z-index: 10;" onclick="const p=document.getElementById('regPassword'); p.type = p.type==='password'?'text':'password';">
                                <i class="ti-eye text-muted"></i>
                            </span>
                            @error('password')
                                <span class="text-danger small mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-4 position-relative">
                            <div class="material-floating">
                                <input class="form-control" type="password" placeholder=" " name="password_confirmation" id="regPasswordConf" style="padding-right: 40px;">
                                <label for="regPasswordConf" class="text-muted">Confirm Password</label>
                            </div>
                            <span class="position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer; z-index: 10;" onclick="const p=document.getElementById('regPasswordConf'); p.type = p.type==='password'?'text':'password';">
                                <i class="ti-eye text-muted"></i>
                            </span>
                            @error('password_confirmation')
                                <span class="text-danger small mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">Signup</button>
                        
                        <div class="text-center mb-3">
                            <span class="text-muted small">OR</span>
                        </div>
                        
                        <div class="d-flex gap-2 mb-4">
                            <a href="{{ route('social.login', 'google') }}" class="btn btn-danger w-50 d-flex justify-content-center align-items-center">
                                <i class="ti-google me-2"></i> Google
                            </a>
                            <a href="{{ route('social.login', 'facebook') }}" class="btn btn-primary text-white w-50 d-flex justify-content-center align-items-center" style="background-color: #1877f2 !important; border-color: #1877f2 !important;">
                                <i class="ti-facebook me-2"></i> Facebook
                            </a>
                        </div>
                        
                        <p class="text-center text-muted m-0">Already have an account? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

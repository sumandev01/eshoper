@extends('auth.layouts.app')
@section('content')
    <div class="wpo-login-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form class="wpo-accountWrapper" action="{{ route('loginRequest') }}" method="POST">
                        @csrf
                        <div class="wpo-accountInfo">
                            <div class="wpo-accountInfoHeader">
                                <a href="index.html"><img src="{{ asset('auth/images/logo-2.svg') }}"
                                        alt=""></a>
                                <a class="wpo-accountBtn" href="{{ route('register') }}">
                                    <span class="">Create Account</span>
                                </a>
                            </div>
                            <div class="image">
                                <img src="{{ asset('auth/images/login.svg') }}" alt="">
                            </div>
                            <div class="back-home">
                                <a class="wpo-accountBtn" href="{{ route('root') }}">
                                    <span class="">Back To Home</span>
                                </a>
                            </div>
                        </div>
                        <div class="wpo-accountForm form-style">
                            <div class="fromTitle">
                                <h2>Login</h2>
                                <p>Sign into your pages account</p>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-12 mb-2">
                                    <label>Email</label>
                                    <input type="text" id="email" name="email" placeholder="Your email here.." value="{{ old('email') }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-12 col-md-12 col-12 mb-2">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input class="pwd6" type="password" placeholder="Your password here.." name="password">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default reveal6" type="button"><i
                                                    class="ti-eye"></i></button>
                                        </span>
                                    </div>
                                    @error('password')
                                        <span class="text-danger mb-2">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="check-box-wrap">

                                        <div class="forget-btn">
                                            <a href="forgot.html">Forgot Password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <button type="submit" class="wpo-accountBtn">Login</button>
                                </div>
                            </div>
                            <h4 class="or"><span>OR</span></h4>
                            <ul class="wpo-socialLoginBtn">
                                <li><button class="bg-danger" tabindex="0" type="button"><span><i
                                                class="ti-google"></i></span></button></li>
                                <li>
                                    <button class="bg-secondary" tabindex="0" type="button"><span><i
                                                class="ti-github"></i></span></button>
                                </li>
                            </ul>
                            <p class="subText">Don't have an account? <a href="{{ route('register') }}">Create free
                                    account</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
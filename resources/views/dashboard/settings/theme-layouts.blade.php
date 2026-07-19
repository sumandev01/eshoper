@extends('dashboard.layouts.app')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Theme Layouts</h4>
                <p class="card-description"> Choose the active layouts for your website </p>
                <form class="forms-sample" action="{{ route('admin.settings.theme-layouts.update') }}" method="POST">
                    @csrf
                    
                    <!-- Header Layouts -->
                    <h5 class="mt-4 mb-3">Header Layout</h5>
                    <div class="row">
                        @php
                            $activeHeader = get_setting('header_layout') ?: \App\Enums\Layouts\HeaderLayoutEnum::HEADER_1->value;
                        @endphp
                        @foreach(\App\Enums\Layouts\HeaderLayoutEnum::cases() as $header)
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="header_layout" value="{{ $header->value }}" {{ $activeHeader == $header->value ? 'checked' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $header->value)) }}
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Home Layouts -->
                    <h5 class="mt-4 mb-3">Home Page Layout</h5>
                    <div class="row">
                        @php
                            $activeHome = get_setting('home_layout') ?: \App\Enums\Layouts\HomeLayoutEnum::HOME_1->value;
                        @endphp
                        @foreach(\App\Enums\Layouts\HomeLayoutEnum::cases() as $home)
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="home_layout" value="{{ $home->value }}" {{ $activeHome == $home->value ? 'checked' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $home->value)) }}
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Shop Layouts -->
                    <h5 class="mt-4 mb-3">Shop Page Layout</h5>
                    <div class="row">
                        @php
                            $activeShop = get_setting('shop_layout') ?: \App\Enums\Layouts\ShopLayoutEnum::SHOP_LEFT_SIDEBAR->value;
                        @endphp
                        @foreach(\App\Enums\Layouts\ShopLayoutEnum::cases() as $shop)
                        <div class="col-md-4 mb-3">
                            <div class="form-check form-check-flat form-check-primary">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="shop_layout" value="{{ $shop->value }}" {{ $activeShop == $shop->value ? 'checked' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $shop->value)) }}
                                    <i class="input-helper"></i>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary me-2 mt-4">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

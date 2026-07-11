@extends('web.layouts.app')
@section('title', ucfirst(Route::currentRouteName()) . ' - ' . ($siteSettings->site_title ?? null))
@section('meta_description', $siteSettings->site_description ?? null)
@section('meta_keywords', $siteSettings->site_keywords ?? null)
@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [['name' => 'Shop', 'url' => '']],
    ])
    <div class="container pt-1">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                @include('web.shop.partials.filter_sidebar', ['showCategory' => true])
            </div>

            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    @include('web.shop.partials.shop_toolbar')
                </div>
                <div id="product-data-container">
                    @include('web.components.product_list')
                </div>
            </div>
        </div>
    </div>
@endsection
@include('web.shop.partials.filter_scripts', ['filterUrl' => route('shop')])

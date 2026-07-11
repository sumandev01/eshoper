@extends('web.layouts.app')
@section('title', ($category->meta_title ?? ucfirst($category->name)) . ' - ' . ($siteSettings->site_title ?? null) )
@section('meta_description', $category->meta_description ?? 'Explore our latest collection of ' . $category->name . '. Find the best prices for ' . $category->name . ' here.')
@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => $category->name, 'url' => '']
        ]
    ])
    <div class="container pt-1">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                @include('web.shop.partials.filter_sidebar')
            </div>

            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    @include('web.shop.partials.shop_toolbar')
                </div>
                <div id="product-data-container">
                    @include('web.components.category_product_list')
                </div>
            </div>
        </div>
    </div>
@endsection
@include('web.shop.partials.filter_scripts', ['filterUrl' => route('category.products', $category->slug)])




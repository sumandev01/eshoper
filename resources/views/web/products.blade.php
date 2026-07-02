@extends('web.layouts.app')
@section('title', ucfirst(Route::currentRouteName()) . ' - ' . ($siteSettings->site_title ?? null))
@section('meta_description', ($siteSettings->site_description ?? null))
@section('meta_keywords', ($siteSettings->site_keywords ?? null))
@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li>Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-2">
        <div class="row px-xl-5">
            <div class="col-lg-3 col-md-12">
                @include('web.layouts.partial.filter_sidebar', ['showCategory' => true])
            </div>

            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <form onsubmit="return false;">
                                <input type="text" id="search-product" class="form-control border-5"
                                    placeholder="Search by Product Name" value="{{ request('search') }}">
                            </form>
                            <div class="dropdown ml-4">
                                <button class="btn border dropdown-toggle" type="button" id="triggerId"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Sort by
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="triggerId">
                                    <a class="dropdown-item sort-item" href="#" data-sort="latest">Latest</a>
                                    <a class="dropdown-item sort-item" href="#" data-sort="price_low">Price: Low to
                                        High</a>
                                    <a class="dropdown-item sort-item" href="#" data-sort="price_high">Price: High to
                                        Low</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="product-data-container">
                    @include('web.layouts.partial.product_list')
                </div>
            </div>
        </div>
    </div>
@endsection
@include('web.layouts.partial.filter_scripts', ['filterUrl' => route('products')])


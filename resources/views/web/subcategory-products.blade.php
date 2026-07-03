@extends('web.layouts.app')
@section('title', ($subcategory?->meta_title ?? ucfirst($subcategory?->name)) . ' - ' . ($siteSettings->site_title ?? null))
@section('meta_description', $subcategory?->meta_description ?? 'Explore our latest collection of ' . $subcategory?->name . '. Find the best prices for ' . $subcategory?->name . ' here.')
@section('content')
    @include('web.layouts.partial.breadcrumb', ['title' => $subcategory?->name])
    <div class="container-fluid pt-2">
        <div class="row px-xl-5">
            <div class="col-lg-3 col-md-12">
                @include('web.layouts.partial.filter_sidebar')
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
                    @include('web.layouts.partial.category_product_list')
                </div>
            </div>
        </div>
    </div>
@endsection
@include('web.layouts.partial.filter_scripts', ['filterUrl' => route('subcategoryProducts', $subcategory->slug)])

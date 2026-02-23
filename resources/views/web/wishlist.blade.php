@extends('web.layouts.app')
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li>Wishlist</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-md-12 mx-auto table-responsive mb-5">
                <h4 class="font-weight-semi-bold mb-2">Your Wishlist</h4>
                <p>There are {{ count($wishlists) }} products in this list</p>
                <table class="table table-bordered table-hover text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Image</th>
                            <th class="text-left">Products</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @forelse ($wishlists ?? [] as $item)
                            <tr data-item-id="{{ $item?->id }}">
                                <td class="" style="max-width: 100px;">
                                    <img src="{{ $item->product?->thumbnail }}" alt="" style="width: 100px;">
                                </td>
                                <td class="text-left">
                                    <a class="text-dark nav-link px-0" style="text-decoration: none;"
                                        href="{{ route('productDetails', $item?->product?->slug) }}"
                                        title="{{ $item?->product?->name }}">{{ Str::limit($item?->product?->name, 30, '...') }}</a>
                                    <div class="d-flex mb-3">
                                        <div class="text-primary mr-2">
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star-half-alt"></small>
                                            <small class="far fa-star"></small>
                                        </div>
                                        <small class="pt-1">(50 Reviews)</small>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if ($item?->product?->stock > 0)
                                        <span class="badge badge-success p-2 rounded">In Stock</span>
                                    @else
                                        <span class="badge badge-danger p-2 rounded">Out of Stock</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <button onclick="window.location.href = '{{ route('productDetails', $item->product?->slug) }}';"
                                        class="btn btn-sm btn-primary btn-add p-2 rounded">
                                        <i class="fa fa-shopping-cart"></i>
                                        Buy Now
                                    </button>
                                </td>
                                <td class="align-middle">
                                    <button onclick="window.location.href = '{{ route('removeFromWishlist', $item->id) }}';"
                                        class="btn btn-sm btn-primary btn-remove">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Product added to wishlist Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Cart End -->
@endsection

@extends('web.layouts.app')
@section('title', ucfirst(Route::currentRouteName()) . ' - ' . ($siteSettings->site_title ?? null))
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
                                    <div class="img-wrapper">
                                        <div class="img-spinner"></div>
                                        <img class="img-fluid w-100 optimized-image" src="{{ $item->product?->thumbnail }}"
                                            alt="{{ $item->product?->name }}" loading="lazy" 
                                            onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                                            onerror="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                                        <script>
                                            (function() {
                                                let img = document.currentScript.previousElementSibling;
                                                if (img && img.complete) {
                                                    img.style.opacity = '1';
                                                    img.previousElementSibling.style.display = 'none';
                                                }
                                            })();
                                        </script>
                                    </div>
                                </td>
                                <td class="text-left">
                                    <a class="text-dark nav-link px-0" style="text-decoration: none;"
                                        href="{{ route('productDetails', $item?->product?->slug) }}"
                                        title="{{ $item?->product?->name }}">{{ Str::limit($item?->product?->name, 30, '...') }}</a>
                                    @php
                                        $productId = $item?->product?->id;
                                        $reviewSum = $productReviews?->where('product_id', $productId)->sum('rating');
                                        $reviewCount = $productReviews?->where('product_id', $productId)->count();
                                        $finalRating = 0;
                                        if ($reviewCount > 0) {
                                            $finalRating = round($reviewSum / $reviewCount);
                                        }
                                    @endphp
                                    <div class="d-flex mb-3">
                                        <div class="star-group">
                                            <input type="hidden" class="rating-value-active" value="{{ $finalRating }}">
                                            <button type="button" class="star-btn-active far fa-star" data-value="1"
                                                style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                            <button type="button" class="star-btn-active far fa-star" data-value="2"
                                                style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                            <button type="button" class="star-btn-active far fa-star" data-value="3"
                                                style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                            <button type="button" class="star-btn-active far fa-star" data-value="4"
                                                style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                            <button type="button" class="star-btn-active far fa-star" data-value="5"
                                                style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                        </div>
                                        <small class="pt-1">({{ $item?->product?->reviews?->count() ?? 0 }}
                                            Reviews)</small>
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
                                    <button
                                        onclick="window.location.href = '{{ route('productDetails', $item->product?->slug) }}';"
                                        class="btn btn-sm btn-primary btn-add p-2 rounded">
                                        <i class="fa fa-shopping-cart"></i>
                                        Buy Now
                                    </button>
                                </td>
                                <td class="align-middle">
                                    <button
                                        onclick="window.location.href = '{{ route('removeFromWishlist', $item->id) }}';"
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
                <div class="mt-3">
                    {{ $wishlists->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('.star-group').each(function() {
                let $group = $(this);
                let dbValue = parseInt($group.find('.rating-value-active').val());
                if (dbValue > 0) {
                    $group.find('.star-btn-active').each(function(index) {
                        if (index < dbValue) {
                            $(this).removeClass('far').addClass('fas');
                        }
                    });
                }
            });
        });
    </script>
@endpush


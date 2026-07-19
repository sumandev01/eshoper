@extends('web.layouts.app')
@section('title', ucfirst(Route::currentRouteName()) . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Breadcrumb Start -->
    @include('web.components.breadcrumb', ['title' => 'Wishlist'])
    <!-- Page Breadcrumb End -->
    <!-- Cart Start -->
    <div class="container pt-1">
        <div class="row">
            <div class="col-md-12 mb-5">
                <h4 class="font-weight-semi-bold mb-2">Your Wishlist</h4>
                <p>There are {{ $wishlists->total() }} products in this list</p>
                <div class="card-shadow table-responsive">
                    <table class="table table-borderless table-hover text-center mb-0 p-4 rounded" id="wishlistTable"
                        style="border-collapse: separate; border-spacing: 0;">
                        <thead class="bg-secondary text-primary">
                            <tr>
                                <th>Image</th>
                                <th class="text-start">Products</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @forelse ($wishlists ?? [] as $item)
                                <tr data-item-id="{{ $item?->id }}">
                                    <td class="" style="width: 50px; height: 50px;">
                                        <div class="img-wrapper">
                                            <div class="img-spinner"></div>
                                            <img class="img-fluid w-100 optimized-image"
                                                src="{{ $item->product?->thumbnail }}" alt="{{ $item->product?->name }}"
                                                loading="lazy"
                                                onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                                                onerror="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                                            <script>
                                                (function() {
                                                    let img = document.currentScript.previousElementSibling;
                                                    if (img && img.complete) {
                                                        img.style.opacity = '1';
                                                        img.previousElementSibling.style.display = 'none';
                                                    }
                                                })
                                                ();
                                            </script>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <a class="text-dark nav-link px-0 pb-0" style="text-decoration: none;"
                                            href="{{ route('product.details', $item?->product?->slug) }}"
                                            title="{{ $item?->product?->name }}">{{ Str::limit($item?->product?->name, 30, '...') }}</a>
                                        {{-- Star ratings removed for cleaner UI --}}
                                    </td>
                                    <td class="align-middle">
                                        @if ($item?->product?->stock > 0)
                                            <span class="badge p-2 rounded"
                                                style="background-color: color-mix(in srgb, #198754 15%, white); color: #198754; font-weight: 600;">In
                                                Stock</span>
                                        @else
                                            <span class="badge p-2 rounded"
                                                style="background-color: color-mix(in srgb, #dc3545 15%, white); color: #dc3545; font-weight: 600;">Out
                                                of Stock</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <button
                                            onclick="window.location.href = '{{ route('product.details', $item->product?->slug) }}';"
                                            class="btn btn-sm btn-primary rounded theme-shadow fw-bold px-3">
                                            Add to Cart
                                        </button>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('wishlist.remove', $item->id) }}"
                                            class="btn btn-sm shadow-sm deleteBtn"
                                            style="background-color: color-mix(in srgb, red 12%, white); color: red; border: none;">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
                <div class="mt-3">
                    {{ $wishlists->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->
    @include('web.components.product_slider', [
        'products' => $recentProducts,
        'title' => 'Recently Viewed Products',
    ])
@endsection

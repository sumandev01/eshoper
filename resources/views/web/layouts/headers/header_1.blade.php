@php
    $categories = App\Models\Category::has('products')
        ->with([
            'subCategories' => function ($q) {
                $q->has('details');
            },
        ])
        ->latest('id')
        ->get();
    $user = auth('web')->user();
    $wishlistIds = $user ? App\Models\Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray() : [];
    $cartProducts = $user?->cartItems;
    $wishlistProducts = $user?->wishlists;
    $megaOffer1 = App\Models\Banner::where('position', 1)->where('status', 1)->first();
    $megaOffer2 = App\Models\Banner::where('position', 2)->where('status', 1)->first();
@endphp

@include('web.layouts.headers.header_1_partials.styles')

<header class="site-header martX-header-1" id="siteHeader">

    @include('web.layouts.headers.header_1_partials.topbar')

    <!-- MAIN HEADER (desktop) -->
    <div class="main-header">
        <div class="container">
            <div class="main-header-inner">
                @include('web.layouts.headers.header_1_partials.search_bar')
                @include('web.layouts.headers.header_1_partials.user_actions')
            </div>
        </div>
    </div>

    @include('web.layouts.headers.header_1_partials.navigation')

    @include('web.layouts.headers.header_1_partials.mobile_bar')

</header>

<div class="martX-header-1">
    @include('web.layouts.headers.header_1_partials.mobile_elements')
</div>

@include('web.layouts.headers.header_1_partials.scripts')

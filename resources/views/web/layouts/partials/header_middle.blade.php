<div class="row align-items-center py-sm-3 pt-3 pb-0">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="{{ route('root') }}" class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold">
                    <img src="{{ $siteSettings->site_logo ?? null }}" class="img-fluid"
                        style="width: auto; max-height: 80px;" alt="Logo" loading="lazy">
                </h1>
            </a>
        </div>

        <div class="col-lg-6 col-sm-6 text-start">
            <div class="input-group search-wrapper position-relative">
                <input id="header-search" type="text" class="form-control" placeholder="Search for products"
                    autocomplete="off">
                <button class="btn btn-primary px-4" type="button">
                    <i class="fa fa-search"></i>
                </button>
                <div id="search-suggestions"
                    style="
                    position: absolute;
                    top: calc(100% + 4px);
                    left: 0;
                    right: 0;
                    background: white;
                    border: 1px solid #e0e0e0;
                    border-radius: 8px;
                    z-index: 9999;
                    display: none;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
                    overflow: hidden;
                    max-height: 380px;
                    overflow-y: auto;
                ">
                </div>
            </div>
        </div>
        @php
            $cartProducts = $user?->cartItems;
            $wishlistProducts = $user?->wishlists;
        @endphp

        <div class="col-lg-3 col-sm-6 text-sm-end text-start pt-sm-0 pt-3">
            <a href="{{ route('wishlist') }}" class="btn border">
                <i class="fas fa-heart text-primary"></i>
                <span class="badge text-dark" id="wishlistCount">{{ $wishlistProducts?->count() ?? 0 }}</span>
            </a>
            <a href="{{ route('cart') }}" class="btn border">
                <i class="fas fa-shopping-cart text-primary"></i>
                <span class="badge text-dark" id="cartCount">{{ $cartProducts?->count() ?? 0 }}</span>
            </a>
        </div>
    </div>
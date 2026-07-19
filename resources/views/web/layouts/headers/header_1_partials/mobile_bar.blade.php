<!-- MOBILE BAR -->
<div class="mobile-bar d-block d-lg-none">
    <div class="container d-flex align-items-center justify-content-between py-4">
        <a href="{{ route('root') }}" class="logo brandmark" style="text-align: center;">
            @if (!empty($siteSettings->site_mobile_logo))
                <img src="{{ $siteSettings->site_mobile_logo }}"
                    style="max-height: 50px; max-width: 180px; object-fit: contain;" alt="Logo">
            @elseif (!empty($siteSettings->site_logo))
                <img src="{{ $siteSettings->site_logo }}" style="max-height: 50px; max-width: 180px; object-fit: contain;"
                    alt="Logo">
            @else
                {{ $siteSettings?->site_title }}
            @endif
        </a>
        <div class="header-actions">
            <button class="icon-btn" id="openSearch" aria-label="Open Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </button>
            <div class="wishlist-wrap" style="position:relative;">
                <a href="{{ route('wishlist') }}" class="icon-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="m20.8 8.6-8.1 8.6a1 1 0 0 1-1.4 0l-8.1-8.6a4.6 4.6 0 0 1 6.7-6.3l1.1 1.1 1.1-1.1a4.6 4.6 0 0 1 6.7 6.3Z" />
                    </svg>
                    <span class="cart-count global-cart-count">{{ $wishlistProducts?->count() ?? 0 }}</span>
                </a>
            </div>
            <div class="cart-wrap" style="position:relative;">
                <a href="{{ route('cart') }}" class="icon-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1.4" />
                        <circle cx="18" cy="21" r="1.4" />
                        <path d="M2.5 3h2l2.7 12.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7.5H6" />
                    </svg>
                    <span class="cart-count global-cart-count">{{ $cartProducts?->count() ?? 0 }}</span>
                </a>
            </div>
            <button class="hamburger" id="openOffcanvas" aria-label="Open Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
</div>

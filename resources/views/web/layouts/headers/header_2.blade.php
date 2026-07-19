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
<header class="site-header martX-header-2" id="siteHeader">
    <div class="container mt-4">
        <div class="header-capsule d-flex align-items-center justify-content-between shadow-lg px-2 px-md-4 py-2 py-md-3 position-relative">
            
            <!-- Left: Logo -->
            <div class="header-logo ps-0 ps-md-1">
                <a href="{{ route('root') }}" class="logo brandmark d-flex align-items-center" style="text-decoration: none;">
                    @if (!empty($siteSettings->site_logo))
                        <img src="{{ $siteSettings->site_logo }}" style="max-height: 45px; max-width: 130px; object-fit: contain;" alt="Logo">
                    @else
                        <h2 class="mb-0 text-brand" style="font-weight: 800; letter-spacing: -0.5px; color: var(--primary); font-size: 22px;">Mart<span style="color: var(--ink);">X</span></h2>
                    @endif
                </a>
            </div>

            <!-- Center: Navigation & Search -->
            <div class="header-center d-flex align-items-center flex-grow-1 mx-2 mx-md-4 gap-4">
                
                <!-- Navigation -->
                <nav class="d-none d-lg-block">
                    <ul class="d-flex align-items-center list-unstyled mb-0 gap-2 nav-list">
                        @php
                            $headerMenu = \App\Models\Menu::with(['items' => function ($q) { $q->orderBy('order'); }])
                                ->where('location', 'header_main')->first();
                            
                            $categoriesInserted = false;
                        @endphp
                        
                        @if ($headerMenu)
                            @foreach ($headerMenu->items as $index => $item)
                                @php
                                    $linkUrl = '#';
                                    if ($item->type == 'custom') { $linkUrl = $item->url; }
                                    elseif ($item->type == 'system') { $linkUrl = $item->reference_id == 'root' ? route('root') : route($item->reference_id); }
                                    elseif ($item->type == 'page') { $page = \App\Models\Page::find($item->reference_id); $linkUrl = $page ? route('page', $page->slug) : '#'; }
                                    elseif ($item->type == 'category') { $cat = \App\Models\Category::find($item->reference_id); $linkUrl = $cat ? route('category.products', $cat->slug) : '#'; }
                                @endphp
                                
                                <li class="nav-item"><a href="{{ $linkUrl }}" class="nav-link">{{ $item->title }}</a></li>
                                
                                @if ($index == 1 && !$categoriesInserted)
                                    <!-- Inject Categories Mega Menu after 2nd item -->
                                    <li class="nav-item has-mega">
                                        <a href="#" class="nav-link categories d-flex align-items-center gap-1">
                                            Categories
                                            <svg class="chev" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m6 9 6 6 6-6" /></svg>
                                        </a>
                                        @include('web.layouts.headers.header_2_partials.mega_menu')
                                    </li>
                                    @php $categoriesInserted = true; @endphp
                                @endif
                            @endforeach
                            
                            @if (!$categoriesInserted)
                                <li class="nav-item has-mega">
                                    <a href="#" class="nav-link categories d-flex align-items-center gap-1">
                                        Categories
                                        <svg class="chev" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m6 9 6 6 6-6" /></svg>
                                    </a>
                                    @include('web.layouts.headers.header_2_partials.mega_menu')
                                </li>
                            @endif
                        @endif
                    </ul>
                </nav>

            </div>

            <!-- Right: Actions -->
            <div class="header-actions d-flex align-items-center gap-2 gap-md-3 pe-0 pe-md-2">
                
                <!-- Search Icon & Dropdown -->
                <div class="search-dropdown-wrapper position-relative d-none d-lg-block">
                    <button class="action-btn text-dark" id="desktopSearchToggle" style="border: none;">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    </button>
                    <!-- Mega Search Dropdown -->
                    <div class="desktop-search-dropdown shadow-lg position-absolute bg-white" style="display: none; z-index: 1000; border-radius: 16px; top: calc(100% + 15px); right: 0; width: 450px; padding: 20px; border: 1px solid var(--line);">
                        <form action="{{ route('shop') }}" method="GET" class="search-form d-flex align-items-center rounded-pill position-relative" style="background: rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); padding: 5px; z-index: 10;">
                            
                            <!-- Custom Category Dropdown -->
                            <div class="custom-category-wrapper position-relative">
                                <input type="hidden" name="category_id" id="searchCategoryInput" value="all">
                                <button type="button" class="custom-cat-btn border-0 bg-transparent text-muted px-3 d-flex align-items-center gap-2" style="font-size: 14px; font-weight: 500; outline: none; cursor: pointer; white-space: nowrap;">
                                    <span id="searchCategoryLabel">All Categories</span>
                                    <svg class="cat-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; color: #999; transition: transform 0.2s;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>
                                <!-- The Dropdown Menu -->
                                <div class="custom-cat-dropdown shadow-sm position-absolute bg-white" style="display: none; top: calc(100% + 16px); left: 0px; min-width: 180px; max-height: 250px; overflow-y: auto; border-radius: 12px; border: 1px solid var(--line); padding: 8px 0; z-index: 1050;">
                                    <div class="custom-cat-option is-active" data-value="all">All Categories</div>
                                    @foreach ($categories as $cat)
                                        <div class="custom-cat-option" data-value="{{ $cat->id }}">{{ $cat->name }}</div>
                                    @endforeach
                                </div>
                            </div>

                            <div style="width: 1px; height: 24px; background: rgba(0,0,0,0.08); margin: 0 10px;"></div>
                            
                            <input type="text" name="search" id="searchInput" class="search-input form-control border-0 p-0 shadow-none px-2" placeholder="Search products..." autocomplete="off" style="background: transparent; font-size: 14.5px;">
                            <button type="submit" class="search-btn btn p-0 d-flex align-items-center justify-content-center text-white" style="background: var(--primary); border: none; width: 36px; height: 36px; border-radius: 50%; margin-left: 10px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(242,92,39,0.25);">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                            </button>
                        </form>
                        <!-- Search Results Dropdown -->
                        <div class="search-results-dropdown mt-3" style="display: none; max-height: 300px; overflow-y: auto;">
                            <!-- Results injected via JS -->
                        </div>
                    </div>
                </div>

                <a href="{{ route('user.dashboard') }}" class="action-btn text-dark text-decoration-none d-none d-lg-flex align-items-center justify-content-center position-relative">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M20 21c0-3.9-3.6-7-8-7s-8 3.1-8 7"/><circle cx="12" cy="7" r="4"/></svg>
                </a>
                
                <a href="{{ route('wishlist') }}" class="action-btn text-dark text-decoration-none d-flex align-items-center justify-content-center position-relative">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    <span class="badge rounded-pill position-absolute global-wishlist-count" style="top: -2px; right: -2px; background: var(--primary); font-size: 10px; padding: 3px 5px;">{{ $wishlistProducts?->count() ?? count((array) session('wishlist')) }}</span>
                </a>

                <a href="{{ route('cart') }}" class="action-btn text-dark text-decoration-none d-flex align-items-center justify-content-center position-relative">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    <span class="badge rounded-pill position-absolute global-cart-count" style="top: -2px; right: -2px; background: var(--primary); font-size: 10px; padding: 3px 5px;">{{ $cartProducts?->count() ?? count((array) session('cart')) }}</span>
                </a>

                <!-- Mobile Hamburger & Search Icon -->
                <button class="icon-btn d-md-none btn p-1 ms-1" id="openSearch2" aria-label="Open Search" style="border: none; background: transparent;">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                </button>
                <button class="hamburger d-lg-none btn p-1 ms-1" id="openOffcanvas2" style="border: none; background: transparent;">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
            
        </div>
    </div>
</header>

@include('web.layouts.headers.header_2_partials.mobile_elements')
@include('web.layouts.headers.header_2_partials.styles')
@include('web.layouts.headers.header_2_partials.scripts')

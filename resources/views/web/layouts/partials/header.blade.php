@php
    $categories = App\Models\Category::with('subCategories')->latest('id')->get();
    $user = auth('web')->user();
    $wishlistIds = $user ? App\Models\Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray() : [];
@endphp
<div class="container bg-white d-sm-block d-none">
    <div class="row py-2">
        <div class="col-md-6 d-none d-md-block">
            <div class="d-inline-flex align-items-center">
                @php
                    $headerTopMenu = \App\Models\Menu::with([
                        'items' => function ($q) {
                            $q->orderBy('order');
                        },
                    ])
                        ->where('location', 'header_top')
                        ->first();
                @endphp

                @if ($headerTopMenu)
                    @foreach ($headerTopMenu->items as $index => $item)
                        @php
                            $linkUrl = '#';
                            if ($item->type == 'custom') {
                                $linkUrl = $item->url;
                            } elseif ($item->type == 'system') {
                                $linkUrl = $item->reference_id == 'root' ? route('root') : route($item->reference_id);
                            } elseif ($item->type == 'page') {
                                $page = \App\Models\Page::find($item->reference_id);
                                $linkUrl = $page ? route('page', $page->slug) : '#';
                            } elseif ($item->type == 'category') {
                                $cat = \App\Models\Category::find($item->reference_id);
                                $linkUrl = $cat ? route('category.products', $cat->slug) : '#';
                            }
                        @endphp
                        <a class="text-dark text-decoration-none" href="{{ $linkUrl }}">{{ $item->title }}</a>
                        @if (!$loop->last)
                            <span class="text-muted px-2">|</span>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-md-6 text-center text-md-end">
            <div class="d-inline-flex align-items-center">
                @if (!empty($siteSettings->social_facebook) && $siteSettings->social_facebook != '#')
                    <a class="text-dark px-2" href="{{ $siteSettings->social_facebook }}" target="_blank"><i
                            class="fab fa-facebook-f"></i></a>
                @endif
                @if (!empty($siteSettings->social_twitter) && $siteSettings->social_twitter != '#')
                    <a class="text-dark px-2" href="{{ $siteSettings->social_twitter }}" target="_blank"><i
                            class="fab fa-twitter"></i></a>
                @endif
                @if (!empty($siteSettings->social_linkedin) && $siteSettings->social_linkedin != '#')
                    <a class="text-dark px-2" href="{{ $siteSettings->social_linkedin }}" target="_blank"><i
                            class="fab fa-linkedin-in"></i></a>
                @endif
                @if (!empty($siteSettings->social_instagram) && $siteSettings->social_instagram != '#')
                    <a class="text-dark px-2" href="{{ $siteSettings->social_instagram }}" target="_blank"><i
                            class="fab fa-instagram"></i></a>
                @endif
                @if (!empty($siteSettings->social_youtube) && $siteSettings->social_youtube != '#')
                    <a class="text-dark ps-2" href="{{ $siteSettings->social_youtube }}" target="_blank"><i
                            class="fab fa-youtube"></i></a>
                @endif
            </div>
        </div>
    </div>

    @include('web.layouts.partials.header_middle')
</div>
<!-- Topbar End -->

<!-- Navbar Start -->
<div class="custom-navbar-bg mb-2 {{ request()->routeIs('root') ? '' : 'navbarShadow' }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 position-relative d-none d-lg-block"
                id="{{ request()->routeIs('root') ? '' : 'navbar-vertical-wrapper' }}">
                <a class="btn btn-primary shadow-none d-flex align-items-center justify-content-between w-100 categories-main-btn rounded-0"
                    data-bs-toggle="collapse" href="#navbar-vertical"
                    style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0 transition-colors" style="color: inherit;">Categories</h6>
                    <i class="fa fa-angle-down transition-colors" style="color: inherit;"></i>
                </a>
                <!-- Category Menu Start -->
                @if (!request()->routeIs('root'))
                    @include('web.layouts.partials.category_menu')
                @endif
                <!-- Category Menu End -->
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg navbar-light py-3 py-lg-0 px-0">
                    <a href="{{ route('root') }}" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold">
                            @if (!empty($siteSettings->site_mobile_logo))
                                <img src="{{ $siteSettings->site_mobile_logo }}" class="img-fluid"
                                    style="width: auto; max-height: 50px;" alt="Logo" loading="lazy">
                            @elseif(!empty($siteSettings->site_logo))
                                <img src="{{ $siteSettings->site_logo }}" class="img-fluid"
                                    style="width: auto; max-height: 50px;" alt="Logo" loading="lazy">
                            @else
                                <span class="text-primary fw-bold border px-3 me-1">E</span>Shopper
                            @endif
                        </h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav me-auto py-0">
                            @php
                                $headerMenu = \App\Models\Menu::with([
                                    'items' => function ($q) {
                                        $q->orderBy('order');
                                    },
                                ])
                                    ->where('location', 'header_main')
                                    ->first();
                            @endphp

                            @if ($headerMenu)
                                @foreach ($headerMenu->items as $item)
                                    @php
                                        $linkUrl = '#';
                                        if ($item->type == 'custom') {
                                            $linkUrl = $item->url;
                                        } elseif ($item->type == 'system') {
                                            $linkUrl =
                                                $item->reference_id == 'root'
                                                    ? route('root')
                                                    : route($item->reference_id);
                                        } elseif ($item->type == 'page') {
                                            $page = \App\Models\Page::find($item->reference_id);
                                            $linkUrl = $page ? route('page', $page->slug) : '#';
                                        } elseif ($item->type == 'category') {
                                            $cat = \App\Models\Category::find($item->reference_id);
                                            $linkUrl = $cat ? route('category.products', $cat->slug) : '#';
                                        }

                                        $isActive = request()->url() == $linkUrl;
                                    @endphp
                                    <a href="{{ $linkUrl }}"
                                        class="nav-item nav-link {{ $isActive ? 'active' : '' }}">{{ $item->title }}</a>
                                @endforeach
                            @endif
                        </div>
                        <div class="navbar-nav ms-auto py-0">
                            @if ($user)
                                <div class="nav-item dropdown user-dropdown d-flex align-items-center">
                                    @if ($user->media_id > 0)
                                        <img src="{{ $user?->profile }}"
                                            style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; object-fit: cover; margin-right: 2px;"
                                            alt="{{ $user?->name }}" loading="lazy">
                                    @else
                                        <img src="{{ asset('user.png') }}"
                                            style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; object-fit: cover; margin-right: 2px;"
                                            alt="{{ $user?->name }}" loading="lazy">
                                    @endif
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                                        data-bs-display="static" style="padding-left: 5px; padding-right: 0;">
                                        <span
                                            class="text-dark font-weight-medium">{{ explode(' ', trim($user?->name))[0] }}</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end position-absolute rounded border-0 shadow m-0"
                                        style="min-width: 200px; top: 100%; right: 0;">
                                        <a href="{{ route('user.dashboard') }}" class="dropdown-item py-2">
                                            <i class="fas fa-tachometer-alt text-primary me-2"
                                                style="width: 16px; text-align: center;"></i> Dashboard
                                        </a>
                                        <a href="{{ route('user.orders') }}" class="dropdown-item py-2">
                                            <i class="fas fa-shopping-bag text-primary me-2"
                                                style="width: 16px; text-align: center;"></i> My Orders
                                        </a>
                                        <a href="{{ route('user.profile') }}" class="dropdown-item py-2">
                                            <i class="fas fa-user text-primary me-2"
                                                style="width: 16px; text-align: center;"></i> Profile
                                        </a>
                                        <div class="dropdown-divider m-0"></div>
                                        <a href="{{ route('logout') }}" class="dropdown-item py-2 text-danger">
                                            <i class="fas fa-sign-out-alt me-2"
                                                style="width: 16px; text-align: center;"></i> Logout
                                        </a>
                                    </div>
                                </div>
                            @else
                                <a href="javascript:void(0)" class="nav-item nav-link" data-bs-toggle="modal"
                                    data-bs-target="#loginModal">Login</a>
                                {{-- <a href="{{ route('login') }}" class="nav-item nav-link">Login</a> --}}
                                <a href="{{ route('register') }}" class="nav-item nav-link">Register</a>
                            @endif
                        </div>
                        <div class="d-sm-none d-block">
                            @include('web.layouts.partials.header_middle')
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
@if (Request::is('/'))
    <div class="container">
        <div class="row">
            <div class="col-lg-3 d-lg-block d-none">
                <!-- Category Menu Start -->
                @if (Request::is('/'))
                    @include('web.layouts.partials.category_menu')
                @endif
                <!-- Category Menu End -->
            </div>
            <div class="col-lg-9">
                <!-- Carousel Start -->
                @include('web.components.slider')
                <!-- Carousel End -->
            </div>
        </div>
    </div>
@endif
<!-- Navbar End -->

@push('scripts')
    <script>
        $(document).ready(function() {

            // =====================
            // Category Dropdown Hover
            // =====================
            $('.nav-item.dropdown').not('.user-dropdown').hover(function() {
                var $this = $(this);
                $this.addClass('show');
                $this.find('> .dropdown-menu').addClass('show');
                $this.find('i.fa').removeClass('fa-angle-down').addClass('fa-angle-right');
            }, function() {
                var $this = $(this);
                $this.removeClass('show');
                $this.find('> .dropdown-menu').removeClass('show');
                $this.find('i.fa').removeClass('fa-angle-right').addClass('fa-angle-down');
            });

            // =====================
            // User Dropdown Hover
            // =====================
            $('.user-dropdown').hover(function() {
                var $this = $(this);
                $this.addClass('show');
                $this.find('> .dropdown-menu').addClass('show');
            }, function() {
                var $this = $(this);
                $this.removeClass('show');
                $this.find('> .dropdown-menu').removeClass('show');
            });

            // =====================
            // Vertical Navbar Hover
            // =====================
            $('#navbar-vertical-wrapper').hover(function() {
                $('#navbar-vertical').addClass('show position-absolute').css({
                    width: 'calc(100% - 30px)',
                    zIndex: 999,
                    background: 'rgba(255, 255, 255, 1)'
                });
            }, function() {
                $('#navbar-vertical').removeClass('show position-absolute').removeAttr('style');
            });

            // =====================
            // Search Suggestions
            // =====================
            $('#header-search').on('keyup', function() {
                let search = $(this).val().trim();

                if (search.length < 2) {
                    $('#search-suggestions').hide();
                    return;
                }

                $.ajax({
                    url: "{{ route('search.suggestions') }}",
                    method: "GET",
                    data: {
                        search: search
                    },
                    success: function(products) {
                        if (products.length === 0) {
                            $('#search-suggestions').hide();
                            return;
                        }

                        let html = '';

                        products.forEach(function(product) {
                            // Prefetch images for suggestions
                            const prefetchImg = new Image();
                            prefetchImg.src = product.thumbnail;

                            html += `
                                <a href="/product/${product.slug}" style="
                                    display: flex;
                                    align-items: center;
                                    gap: 12px;
                                    padding: 10px 14px;
                                    text-decoration: none;
                                    color: #333;
                                    border-bottom: 1px solid #f0f0f0;
                                    transition: background 0.2s;
                                "
                                onmouseover="this.style.background='#f8f8f8'"
                                onmouseout="this.style.background='white'">
                                    <div class="img-wrapper" style="width: 50px; height: 50px; flex-shrink: 0; border-radius: 6px; border: 1px solid #eee;">
                                        <div class="img-spinner" style="width: 20px; height: 20px; border-width: 2px;"></div>
                                        <img src="${product.thumbnail}" style="
                                            width: 100%;
                                            height: 100%;
                                            object-fit: cover;
                                            opacity: 0;
                                            transition: opacity 0.3s;
                                        " alt="${product.name}"
                                        onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                                        onerror="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="
                                            font-size: 14px;
                                            font-weight: 500;
                                            white-space: nowrap;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        ">${product.name}</div>
                                        <div style="margin-top: 3px;">
                                            ${product.discount > 0 ? `
                                                                <span style="color: #000; font-weight: 600; font-size: 13px;">${siteCurrency}${product.discount}</span>
                                                                <span style="color: #e74c3c; font-size: 12px; text-decoration: line-through; margin-left: 5px;">${siteCurrency}${product.price}</span>
                                                            ` : `
                                                                <span style="color: #000; font-weight: 600; font-size: 13px;">${siteCurrency}${product.price}</span>
                                                            `}
                                        </div>
                                    </div>
                                </a>
                            `;
                        });

                        html += `
                            <a href="{{ route('shop') }}?search=${search}" style="
                                display: block;
                                padding: 10px 14px;
                                text-align: center;
                                color: #fff;
                                font-size: 13px;
                                font-weight: 600;
                                text-decoration: none;
                                background: var(--primary);
                            "
                            onmouseover="this.style.background='var(--primary-dark)'"
                            onmouseout="this.style.background='var(--primary)'">
                                <i class="fa fa-search" style="margin-right: 5px;"></i>
                                View all results for "${search}"
                            </a>
                        `;

                        $('#search-suggestions').html(html).show();
                    }
                });
            });

            // =====================
            // Enter Key - Go to Products Page
            // =====================
            $('#header-search').on('keydown', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    let search = $(this).val().trim();
                    if (search.length > 0) {
                        window.location.href = "{{ route('shop') }}?search=" + encodeURIComponent(
                            search);
                    }
                }
            });

            // =====================
            // Search Icon Click
            // =====================
            $('.input-group-text').on('click', function() {
                let search = $('#header-search').val().trim();
                if (search.length > 0) {
                    window.location.href = "{{ route('shop') }}?search=" + encodeURIComponent(search);
                }
            });

            // =====================
            // Click Outside - Hide Suggestions
            // =====================
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-wrapper').length) {
                    $('#search-suggestions').hide();
                }
            });

        });
    </script>
@endpush

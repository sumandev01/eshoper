@php
    $categories = App\Models\Category::with('subCategories')->latest('id')->get();
    $user = auth('web')->user();
    $wishlistIds = $user
        ? App\Models\Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray()
        : [];
@endphp
<div class="container-fluid">
    <div class="row bg-secondary py-2 px-xl-5">
        <div class="col-lg-6 d-none d-lg-block">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark" href="">FAQs</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="">Help</a>
                <span class="text-muted px-2">|</span>
                <a class="text-dark" href="">Support</a>
            </div>
        </div>
        <div class="col-lg-6 text-center text-lg-right">
            <div class="d-inline-flex align-items-center">
                <a class="text-dark px-2" href=""><i class="fab fa-facebook-f"></i></a>
                <a class="text-dark px-2" href=""><i class="fab fa-twitter"></i></a>
                <a class="text-dark px-2" href=""><i class="fab fa-linkedin-in"></i></a>
                <a class="text-dark px-2" href=""><i class="fab fa-instagram"></i></a>
                <a class="text-dark pl-2" href=""><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <div class="row align-items-center py-3 px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a href="{{ route('root') }}" class="text-decoration-none">
                <h1 class="m-0 display-5 font-weight-semi-bold">
                    <span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper
                </h1>
            </a>
        </div>

        <div class="col-lg-6 col-6 text-left">
            <div class="input-group search-wrapper position-relative">
                <input
                    id="header-search"
                    type="text"
                    class="form-control"
                    placeholder="Search for products"
                    autocomplete="off"
                >
                <div class="input-group-append">
                    <span class="input-group-text bg-transparent text-primary" style="cursor: pointer;">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
                <div id="search-suggestions" style="
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
                "></div>
            </div>
        </div>
        @php
            $cartProducts = $user?->cartItems;
            $wishlistProducts = $user?->wishlists;
        @endphp

        <div class="col-lg-3 col-6 text-right">
            <a href="{{ route('wishlist') }}" class="btn border">
                <i class="fas fa-heart text-primary"></i>
                <span class="badge" id="wishlistCount">{{ $wishlistProducts?->count() ?? 0 }}</span>
            </a>
            <a href="{{ route('cart') }}" class="btn border">
                <i class="fas fa-shopping-cart text-primary"></i>
                <span class="badge" id="cartCount">{{ $cartProducts?->count() ?? 0 }}</span>
            </a>
        </div>
    </div>
</div>
<!-- Topbar End -->

<!-- Navbar Start -->
<div class="container-fluid mb-4 {{ request()->routeIs('root') ? '' : 'navbarShadow' }}">
    <div class="row border-top px-xl-5">
        <div class="col-lg-3 d-none d-lg-block" id="{{ request()->routeIs('root') ? '' : 'navbar-vertical-wrapper' }}">
            <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100"
                data-toggle="collapse"
                href="#navbar-vertical"
                style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0">Categories</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="collapse {{ Request::is('/') ? 'show' : 'position-absolute' }} navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0"
                id="navbar-vertical"
                style="{{ Request::is('/') ? '' : 'width: calc(100% - 30px); z-index: 999; background: rgba(255, 255, 255, 1);' }}">
                <div class="navbar-nav w-100" style="{{ request()->routeIs('root') ? 'height: 410px' : '' }}">
                    @foreach ($categories as $category)
                        <div class="nav-item dropdown">
                            <a href="{{ route('categoryProducts', $category?->slug) }}" class="nav-link">
                                {{ $category->name }}
                                @if ($category->subCategories->count() > 0)
                                    <i class="fa fa-angle-down float-right mt-1"></i>
                                @endif
                            </a>
                            @if ($category->subCategories->count() > 0)
                                <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0"
                                    style="left: calc(100% + 2px); top: 0;">
                                    @foreach ($category->subCategories as $subCategory)
                                        <a href="{{ route('subcategoryProducts', $subCategory?->slug) }}" class="nav-link">
                                            {{ $subCategory->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </nav>
        </div>

        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                <a href="" class="text-decoration-none d-block d-lg-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold">
                        <span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper
                    </h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <a href="{{ route('root') }}"
                            class="nav-item nav-link {{ request()->routeIs('root') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('products') }}"
                            class="nav-item nav-link {{ request()->routeIs('products') ? 'active' : '' }}">Product</a>
                        <a href="{{ route('about') }}"
                            class="nav-item nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                        <a href="{{ route('contact') }}"
                            class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                    </div>
                    <div class="navbar-nav ml-auto py-0">
                        @if ($user)
                            <div class="d-flex justify-items-center align-items-center">
                                @if ($user->media_id > 0)
                                    <img src="{{ $user->profile }}"
                                        style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; padding: 2px"
                                        alt="">
                                @else
                                    <img src="{{ asset('user.png') }}"
                                        style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; padding: 2px"
                                        alt="">
                                @endif
                                <a href="" class="nav-item nav-link">{{ $user->name }}</a>
                            </div>
                            <a href="{{ route('logout') }}" class="nav-item nav-link">Logout</a>
                        @else
                            <a href="{{ route('login') }}" class="nav-item nav-link">Login</a>
                            <a href="{{ route('register') }}" class="nav-item nav-link">Register</a>
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Carousel Start -->
            @if (Request::is('/'))
                @include('web.layouts.partial.slider')
            @endif
            <!-- Carousel End -->
        </div>
    </div>
</div>
<!-- Navbar End -->

@push('script')
    <script>
        $(document).ready(function () {

            // =====================
            // Category Dropdown Hover
            // =====================
            $('.nav-item.dropdown').hover(function () {
                var $this = $(this);
                $this.addClass('show bg-secondary');
                $this.find('> .dropdown-menu').addClass('show');
                $this.find('i.fa').removeClass('fa-angle-down').addClass('fa-angle-right');
            }, function () {
                var $this = $(this);
                $this.removeClass('show bg-secondary');
                $this.find('> .dropdown-menu').removeClass('show');
                $this.find('i.fa').removeClass('fa-angle-right').addClass('fa-angle-down');
            });

            // =====================
            // Vertical Navbar Hover
            // =====================
            $('#navbar-vertical-wrapper').hover(function () {
                $('#navbar-vertical').addClass('show position-absolute').css({
                    width: 'calc(100% - 30px)',
                    zIndex: 999,
                    background: 'rgba(255, 255, 255, 1)'
                });
            }, function () {
                $('#navbar-vertical').removeClass('show position-absolute').removeAttr('style');
            });

            // =====================
            // Search Suggestions
            // =====================
            $('#header-search').on('keyup', function () {
                let search = $(this).val().trim();

                if (search.length < 2) {
                    $('#search-suggestions').hide();
                    return;
                }

                $.ajax({
                    url: "{{ route('searchSuggestions') }}",
                    method: "GET",
                    data: { search: search },
                    success: function (products) {
                        if (products.length === 0) {
                            $('#search-suggestions').hide();
                            return;
                        }

                        let html = '';

                        products.forEach(function (product) {
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
                                    <img src="${product.thumbnail}" style="
                                        width: 50px;
                                        height: 50px;
                                        object-fit: cover;
                                        border-radius: 6px;
                                        border: 1px solid #eee;
                                        flex-shrink: 0;
                                    ">
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
                                                <span style="color: #e74c3c; font-weight: 600; font-size: 13px;">৳${product.discount}</span>
                                                <span style="color: #aaa; font-size: 12px; text-decoration: line-through; margin-left: 5px;">৳${product.price}</span>
                                            ` : `
                                                <span style="color: #e74c3c; font-weight: 600; font-size: 13px;">৳${product.price}</span>
                                            `}
                                        </div>
                                    </div>
                                </a>
                            `;
                        });

                        html += `
                            <a href="{{ route('products') }}?search=${search}" style="
                                display: block;
                                padding: 10px 14px;
                                text-align: center;
                                color: #007bff;
                                font-size: 13px;
                                font-weight: 600;
                                text-decoration: none;
                                background: #f8f9ff;
                            "
                            onmouseover="this.style.background='#eef0ff'"
                            onmouseout="this.style.background='#f8f9ff'">
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
            $('#header-search').on('keydown', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    let search = $(this).val().trim();
                    if (search.length > 0) {
                        window.location.href = "{{ route('products') }}?search=" + encodeURIComponent(search);
                    }
                }
            });

            // =====================
            // Search Icon Click
            // =====================
            $('.input-group-text').on('click', function () {
                let search = $('#header-search').val().trim();
                if (search.length > 0) {
                    window.location.href = "{{ route('products') }}?search=" + encodeURIComponent(search);
                }
            });

            // =====================
            // Click Outside - Hide Suggestions
            // =====================
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.search-wrapper').length) {
                    $('#search-suggestions').hide();
                }
            });

        });
    </script>
@endpush
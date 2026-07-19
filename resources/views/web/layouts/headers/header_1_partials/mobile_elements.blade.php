<!-- FULLSCREEN SEARCH OVERLAY -->
<div class="search-overlay" id="searchOverlay">
    <div class="search-overlay-top">
        <form action="{{ route('shop') }}" method="GET" class="search-form" role="search">
            <label for="searchMobile" class="visually-hidden">Search Products</label>
            <input id="searchMobile" name="search" type="text" placeholder="What are you looking for?">
            <button type="submit" class="search-submit" aria-label="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </button>
        </form>
        <button class="close-overlay" id="closeSearch" aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- OFFCANVAS MENU -->
<div class="offcanvas-scrim" id="scrim"></div>
<aside class="martX-header-1-offcanvas" id="offcanvas" aria-hidden="true">
    <div class="offcanvas-head">
        <div class="avatar">{{ $user ? mb_substr($user->name, 0, 1) : 'U' }}</div>
        <div>
            @if ($user)
                <p>{{ Str::limit($user->name, 15, '...') }}</p>
                <span>Welcome back!</span>
            @else
                <p>Welcome!</p>
                <span><a class="text-primary" href="{{ route('login') }}">Login</a> or <a class="text-primary"
                        href="{{ route('register') }}">register</a></span>
            @endif
        </div>
        <button class="offcanvas-close" id="closeOffcanvas" aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="offcanvas-body">
        <p style="padding:6px 14px;font-size:12px;font-weight:700;color:var(--ink-soft);letter-spacing:.04em;">
            CATEGORIES</p>

        <div class="accordion" id="accordion">
            @foreach ($categories as $category)
                <div class="accordion-item">
                    @if($category->subCategories->isEmpty())
                        <a href="{{ route('category.products', $category->slug) }}" class="accordion-link">
                            {{ $category->name }}
                        </a>
                    @else
                        <button class="accordion-trigger" aria-expanded="false">
                            {{ $category->name }} <span class="arrow"></span>
                        </button>
                        <div class="accordion-panel">
                            <a href="{{ route('category.products', $category->slug) }}" style="font-weight: 700; color: var(--primary);">View All {{ $category->name }}</a>
                            @foreach ($category->subCategories as $subCat)
                                <a href="{{ route('subcategory.products', $subCat?->slug) }}">{{ $subCat->name }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($user)
            <a href="{{ route('user.dashboard') }}" class="oc-link text-primary rounded-0">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <path d="M20 21c0-3.9-3.6-7-8-7s-8 3.1-8 7" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                My Account
            </a>
            <a href="{{ route('logout') }}" class="oc-link rounded-0" style="color: #dc3545;">Logout</a>
        @else
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#loginModal" class="oc-link text-primary rounded-0">Login</a>
            <a href="{{ route('register') }}" class="oc-link text-primary rounded-0">Register</a>
        @endif

    </div>
</aside>

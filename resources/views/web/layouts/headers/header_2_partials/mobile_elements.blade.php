<!-- FULLSCREEN SEARCH OVERLAY -->
<div class="search-overlay" id="searchOverlay2">
    <div class="search-overlay-top">
        <div class="d-flex justify-content-end w-100">
            <button class="close-overlay" id="closeSearch2" aria-label="Close" style="background: rgba(0,0,0,0.04); border-radius: 50%; padding: 8px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('shop') }}" method="GET" class="search-form w-100" role="search">
            <!-- Custom Category Dropdown -->
            <div class="custom-category-wrapper position-relative">
                <input type="hidden" name="category_id" value="all">
                <button type="button" class="custom-cat-btn border-0 bg-transparent text-muted px-3 d-flex align-items-center gap-2" style="font-size: 14px; font-weight: 500; outline: none; cursor: pointer; white-space: nowrap;">
                    <span>All</span>
                    <svg class="cat-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; color: #999; transition: transform 0.2s;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <!-- The Dropdown Menu -->
                <div class="custom-cat-dropdown shadow-sm position-absolute bg-white" style="display: none; top: calc(100% + 12px); left: 0px; min-width: 180px; max-height: 250px; overflow-y: auto; border-radius: 12px; border: 1px solid var(--line); padding: 8px 0; z-index: 1050;">
                    <div class="custom-cat-option is-active" data-value="all">All Categories</div>
                    @foreach ($categories as $cat)
                        <div class="custom-cat-option" data-value="{{ $cat->id }}">{{ $cat->name }}</div>
                    @endforeach
                </div>
            </div>
            <div style="width: 1px; height: 20px; background: rgba(0,0,0,0.1); margin: 0 8px;"></div>
            <input id="searchMobile2" name="search" type="text" placeholder="Search..." style="flex:1;">
            <button type="submit" class="search-submit" aria-label="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </button>
        </form>
    </div>
</div>

<!-- OFFCANVAS MENU (Reusing identical logic from Header 1) -->
<div class="offcanvas-scrim" id="scrim2"></div>
<aside class="martX-header-2-offcanvas" id="offcanvas2" aria-hidden="true">
    <div class="offcanvas-head flex-column align-items-stretch">
        <div class="bottom-sheet-handle"></div>
        <div class="d-flex align-items-center w-100">
            <div class="avatar">{{ $user ? mb_substr($user->name, 0, 1) : 'U' }}</div>
            <div class="ms-3">
                @if ($user)
                    <p class="mb-0">{{ Str::limit($user->name, 15, '...') }}</p>
                    <span style="font-size: 12px; color: var(--ink-soft);">Welcome back!</span>
                @else
                    <p class="mb-0">Welcome!</p>
                    <span style="font-size: 12px; color: var(--ink-soft);"><a class="text-primary text-decoration-none" href="{{ route('login') }}">Login</a> or <a class="text-primary text-decoration-none"
                            href="{{ route('register') }}">register</a></span>
                @endif
            </div>
            <button class="offcanvas-close ms-auto" id="closeOffcanvas2" aria-label="Close" style="background: rgba(0,0,0,0.04); border-radius: 50%; padding: 8px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div class="offcanvas-body">
        <p style="padding:6px 14px 0 14px;font-size:12px;font-weight:700;color:var(--ink-soft);letter-spacing:.04em; margin-bottom: 8px;">
            MAIN MENU</p>
        <ul class="mobile-main-menu list-unstyled mb-4" style="padding: 0 14px;">
            @if(isset($headerMenu) && $headerMenu)
                @foreach($headerMenu->items as $item)
                    @php
                        $linkUrl = '#';
                        if ($item->type == 'custom') { $linkUrl = $item->url ?? $item->reference_id; }
                        elseif ($item->type == 'system') { $linkUrl = $item->reference_id == 'root' ? route('root') : route($item->reference_id); }
                        elseif ($item->type == 'page') { $page = \App\Models\Page::find($item->reference_id); $linkUrl = $page ? route('page', $page->slug) : '#'; }
                        elseif ($item->type == 'category') { $cat = \App\Models\Category::find($item->reference_id); $linkUrl = $cat ? route('category.products', $cat->slug) : '#'; }
                    @endphp
                    <li style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                        <a href="{{ $linkUrl }}" class="d-block py-2 text-decoration-none" style="font-weight: 600; color: var(--ink); font-size: 14.5px;">
                            {{ $item->title }}
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>

        <p style="padding:6px 14px 0 14px;font-size:12px;font-weight:700;color:var(--ink-soft);letter-spacing:.04em; margin-bottom: 8px;">
            CATEGORIES</p>

        <div class="accordion" id="accordion2">
            @foreach ($categories as $category)
                <div class="accordion-item">
                    @if($category->subCategories->isEmpty())
                        <a href="{{ route('category.products', $category->slug) }}" class="accordion-link">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" style="width: 24px; height: 24px; object-fit: cover; border-radius: 50%;">
                                {{ $category->name }}
                            </div>
                        </a>
                    @else
                        <button class="accordion-trigger" aria-expanded="false">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" style="width: 24px; height: 24px; object-fit: cover; border-radius: 50%;">
                                {{ $category->name }}
                            </div>
                            <i class="fa fa-chevron-down arrow-icon"></i>
                        </button>
                        <div class="accordion-panel subcat-panel">
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

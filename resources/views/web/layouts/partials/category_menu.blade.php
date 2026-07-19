<!-- Category Menu Start -->
<nav class="collapse {{ Request::is('/') ? 'show' : 'position-absolute' }} navbar navbar-vertical navbar-light align-items-start p-0 bg-white shadow-sm border rounded-bottom"
    id="navbar-vertical"
    style="{{ Request::is('/') ? '' : 'width: calc(100% - 30px); z-index: 999;' }}">
    <div class="navbar-nav w-100 custom-sidebar-nav">
        @php
            $isHome = Request::is('/');
            $limit = 10;
        @endphp
        @foreach ($categories as $index => $category)
            @if($isHome && $index == $limit)
                <div class="nav-item dropdown">
                    <a href="{{ route('categories.index') }}" class="nav-link custom-nav-link border-bottom-0 fw-bold text-primary text-center">
                        View All Categories <i class="fa fa-angle-double-right mt-1 ms-1"></i>
                    </a>
                </div>
                @break
            @endif
            <div class="nav-item dropdown category-item-wrapper">
                <a href="{{ route('category.products', $category?->slug) }}"
                    class="nav-link custom-nav-link border-bottom-0 d-flex justify-content-between align-items-center px-4 py-3">
                    <span class="fw-medium text-dark">{{ $category->name }}</span>
                    @if ($category->subCategories->count() > 0)
                        <i class="fa fa-angle-right text-muted"></i>
                    @endif
                </a>
                @if ($category->subCategories->count() > 0)
                    <!-- Premium Popout Menu -->
                    <div class="dropdown-menu position-absolute bg-white border-0 shadow-lg rounded m-0 p-3 mega-menu-popout"
                        style="left: 100%; top: 0; min-width: 250px; display: none;">
                        <h6 class="text-uppercase text-muted mb-3 pb-2 border-bottom" style="font-size: 12px; letter-spacing: 1px;">{{ $category->name }}</h6>
                        <div class="row">
                            <div class="col-12">
                                @foreach ($category->subCategories as $subCategory)
                                    <a href="{{ route('subcategory.products', $subCategory?->slug) }}"
                                        class="nav-link text-dark py-2 px-3 rounded custom-sub-link hover-bg-light transition-colors">
                                        <i class="fa fa-caret-right text-primary me-2 opacity-50"></i>{{ $subCategory->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</nav>

<style>
    .category-item-wrapper:hover .mega-menu-popout {
        display: block !important;
        animation: slideRight 0.2s ease-out;
    }
    .hover-bg-light:hover {
        background-color: #f8f9fa !important;
        padding-left: 20px !important;
    }
    @keyframes slideRight {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>
<!-- Category Menu End -->
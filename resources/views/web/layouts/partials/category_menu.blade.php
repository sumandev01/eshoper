<!-- Category Menu Start -->
<nav class="collapse {{ Request::is('/') ? 'show' : 'position-absolute' }} navbar navbar-vertical navbar-light align-items-start p-0 {{ Request::is('/') ? '' : 'custom-navbar-bg' }} rounded-0"
    id="navbar-vertical"
    style="{{ Request::is('/') ? '' : 'width: calc(100% - 30px); z-index: 999; background: rgba(255, 255, 255, 1);' }}">
    <div class="navbar-nav w-100 custom-sidebar-nav">
        @php
            $isHome = Request::is('/');
            $limit = 10;
        @endphp
        @foreach ($categories as $index => $category)
            @if($isHome && $index == $limit)
                <div class="nav-item dropdown">
                    <a href="{{ route('categories.index') }}" class="nav-link custom-nav-link border-bottom-0">
                        View All Categories <i class="fa fa-angle-double-right float-end mt-1"></i>
                    </a>
                </div>
                @break
            @endif
            <div class="nav-item dropdown">
                <a href="{{ route('category.products', $category?->slug) }}"
                    class="nav-link custom-nav-link border-bottom-0">
                    {{ $category->name }}
                    @if ($category->subCategories->count() > 0)
                        <i class="fa fa-angle-right float-end mt-1"></i>
                    @endif
                </a>
                @if ($category->subCategories->count() > 0)
                    <div class="dropdown-menu position-absolute bg-white border-0 rounded-0 w-100 m-0 shadow"
                        style="left: calc(100% + 2px); top: 0;">
                        @foreach ($category->subCategories as $subCategory)
                            <a href="{{ route('subcategory.products', $subCategory?->slug) }}"
                                class="nav-link text-dark custom-sub-link">
                                {{ $subCategory->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</nav>
<!-- Category Menu End -->
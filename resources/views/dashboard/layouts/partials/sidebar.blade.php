<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ auth()?->user()?->profile }}" alt="profile" />
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold text-capitalize mb-2">{{ auth()?->user()?->name }}</span>
                    <span class="text-secondary text-small">{{ auth()?->user()?->getRoleNames()[0] }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        @can(App\Enums\Permission\MediaPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.media*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#media_menu"
                    aria-expanded="{{ request()->routeIs('admin.media*') ? 'true' : 'false' }}" aria-controls="media_menu">
                    <span class="menu-title">Media</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-image-multiple menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.media*') ? 'show' : '' }}" id="media_menu">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\MediaPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.media') ? 'active' : '' }}"
                                    href="{{ route('admin.media') }}">All Media</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\MediaPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.media.add') ? 'active' : '' }}"
                                    href="{{ route('admin.media.add') }}">Add Media</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can([App\Enums\Permission\CategoryPermission::VIEW->value,
            App\Enums\Permission\SubCategoryPermission::VIEW->value, App\Enums\Permission\BrandPermission::VIEW->value,
            App\Enums\Permission\SizePermission::VIEW->value, App\Enums\Permission\ColorPermission::VIEW->value,
            App\Enums\Permission\TagPermission::VIEW->value])
            <li
                class="nav-item {{ request()->routeIs('category*', 'sub-category*', 'brand*', 'size*', 'color*', 'tag*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-catManagement"
                    aria-expanded="{{ request()->routeIs('category*', 'sub-category*', 'brand*', 'size*', 'color*', 'tag*') ? 'true' : 'false' }}"
                    aria-controls="ui-catManagement">
                    <span class="menu-title">Catalog Management</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('category*', 'sub-category*', 'brand*', 'size*', 'color*', 'tag*') ? 'show' : '' }}"
                    id="ui-catManagement">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\CategoryPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}"
                                    href="{{ route('category.index') }}">Categories</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\SubCategoryPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sub-category.index') ? 'active' : '' }}"
                                    href="{{ route('sub-category.index') }}">Sub Categories</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\BrandPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('brand.index') ? 'active' : '' }}"
                                    href="{{ route('brand.index') }}">Brands</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\SizePermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('size.index') ? 'active' : '' }}"
                                    href="{{ route('size.index') }}">Sizes</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\ColorPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('color.index') ? 'active' : '' }}"
                                    href="{{ route('color.index') }}">Colors</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\TagPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tag.index') ? 'active' : '' }}"
                                    href="{{ route('tag.index') }}">Tags</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\ProductPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('product*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#products"
                    aria-expanded="{{ request()->routeIs('product*') ? 'true' : 'false' }}" aria-controls="ui-products">
                    <span class="menu-title">Products</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-package-variant-closed menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('product*') ? 'show' : '' }}" id="products">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\ProductPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}"
                                    href="{{ route('product.index') }}">All Products</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\ProductPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('product.add') ? 'active' : '' }}"
                                    href="{{ route('product.add') }}">Add Product</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\CouponPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('coupon*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#coupon"
                    aria-expanded="{{ request()->routeIs('coupon*') ? 'true' : 'false' }}" aria-controls="coupon">
                    <span class="menu-title">Coupons</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-ticket menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('coupon*') ? 'show' : '' }}" id="coupon">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\CouponPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('coupon.index') ? 'active' : '' }}"
                                    href="{{ route('coupon.index') }}">All Coupons</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\CouponPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('coupon.add') ? 'active' : '' }}"
                                    href="{{ route('coupon.add') }}">Add Coupon</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\SliderPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('slider*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#slider"
                    aria-expanded="{{ request()->routeIs('slider*') ? 'true' : 'false' }}" aria-controls="slider">
                    <span class="menu-title">Sliders</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-view-carousel menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('slider*') ? 'show' : '' }}" id="slider">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\SliderPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('slider.index') ? 'active' : '' }}"
                                    href="{{ route('slider.index') }}">All Sliders</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\SliderPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('slider.add') ? 'active' : '' }}"
                                    href="{{ route('slider.add') }}">Add Slider</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#forms" aria-expanded="false"
                aria-controls="forms">
                <span class="menu-title">Forms</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
            <div class="collapse" id="forms">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/forms/basic_elements.html">Form Elements</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false"
                aria-controls="charts">
                <span class="menu-title">Charts</span>
                <i class="mdi mdi-chart-bar menu-icon"></i>
            </a>
            <div class="collapse" id="charts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false"
                aria-controls="tables">
                <span class="menu-title">Tables</span>
                <i class="mdi mdi-table-large menu-icon"></i>
            </a>
            <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a>
                    </li>
                </ul>
            </div>
        </li>
        @can(App\Enums\Permission\UserPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#user" aria-expanded="false"
                    aria-controls="user">
                    <span class="menu-title">Users</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-account menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.user*') ? 'show' : '' }}" id="user">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\UserPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.user.index') ? 'active' : '' }}"
                                    href="{{ route('admin.user.index') }}">All Users</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\UserPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.user.add') ? 'active' : '' }}"
                                    href="{{ route('admin.user.add') }}">Add User</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can(\App\Enums\Permission\UserRolePermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.role*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#role" aria-expanded="false"
                    aria-controls="role">
                    <span class="menu-title">Roles</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-shield menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.role*') ? 'show' : '' }}" id="role">
                    <ul class="nav flex-column sub-menu">
                        @can(\App\Enums\Permission\UserRolePermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.role.index') ? 'active' : '' }}"
                                    href="{{ route('admin.role.index') }}">All Roles</a>
                            </li>
                        @endcan
                        @can(\App\Enums\Permission\UserRolePermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.role.add') ? 'active' : '' }}"
                                    href="{{ route('admin.role.add') }}">Add Role</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        <li class="nav-item">
            <a class="nav-link" href="docs/documentation.html" target="_blank">
                <span class="menu-title">Documentation</span>
                <i class="mdi mdi-file-document-box menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>

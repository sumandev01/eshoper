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
        <li class="nav-item">
            <a class="nav-link" target="_blank" href="{{ route('root') }}">
                <span class="menu-title">Website</span>
                <i class="mdi mdi-web menu-icon"></i>
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
        <li class="nav-item {{ request()->routeIs('admin.settings.offers') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.settings.offers') }}">
                <span class="menu-title">Offers</span>
                <i class="mdi mdi-gift menu-icon"></i>
            </a>
        </li>
        @can(App\Enums\Permission\OrderPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('order*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#order"
                    aria-expanded="{{ request()->routeIs('order*') ? 'true' : 'false' }}" aria-controls="order">
                    <span class="menu-title">Orders</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-cart menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('order*') ? 'show' : '' }}" id="order">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\OrderPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('order.index') ? 'active' : '' }}"
                                    href="{{ route('order.index') }}">All Orders</a>
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
        @php
            use App\Models\ProductReview;
            $countComments = ProductReview::where('status', 0)->count();
        @endphp
        @can(App\Enums\Permission\CommentPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.comment*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#comments"
                    aria-expanded="{{ request()->routeIs('admin.comment*') ? 'true' : 'false' }}"
                    aria-controls="comments">
                    <span class="menu-title">Comments</span>
                    @if ($countComments > 0)
                        <span class="badge badge-warning badge-pill me-2">{{ $countComments }}</span>
                    @endif
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-comment menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.comment*') ? 'show' : '' }}" id="comments">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.comment.index') ? 'active' : '' }}"
                                href="{{ route('admin.comment.index') }}">All Comments</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\ContactMessagePermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.contact-message*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#contact-message"
                    aria-expanded="{{ request()->routeIs('admin.comment*') ? 'true' : 'false' }}"
                    aria-controls="contact-message">
                    <span class="menu-title">Contact Us</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-email menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.contact-message*') ? 'show' : '' }}"
                    id="contact-message">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.contact-message.index') ? 'active' : '' }}"
                                href="{{ route('admin.contact-message.index') }}">All Contact</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\AboutPagePermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.about-page.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.about-page.index') }}">
                    <span class="menu-title">About Page</span>
                    <i class="mdi mdi-file-document menu-icon"></i>
                </a>
            </li>
        @endcan
        @can(App\Enums\Permission\FaqPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.faq*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.faq.index') }}">
                    <span class="menu-title">FAQ</span>
                    <i class="mdi mdi-comment-question-outline menu-icon"></i>
                </a>
            </li>
        @endcan
        @can(App\Enums\Permission\TeamMemberPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.team-member*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.team-member.index') }}">
                    <span class="menu-title">Team Member</span>
                    <i class="mdi mdi-account-group menu-icon"></i>
                </a>
            </li>
        @endcan
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
        @can(\App\Enums\Permission\LocationPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.location*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#location" aria-expanded="false"
                    aria-controls="location">
                    <span class="menu-title">Locations</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-map-marker menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.location*') ? 'show' : '' }}" id="location">
                    <ul class="nav flex-column sub-menu">
                        @can(\App\Enums\Permission\LocationPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.location.index') ? 'active' : '' }}"
                                    href="{{ route('admin.location.index') }}">All Locations</a>
                            </li>
                        @endcan
                        @can(\App\Enums\Permission\LocationPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.location.create') ? 'active' : '' }}"
                                    href="{{ route('admin.location.create') }}">Add Location</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can(\App\Enums\Permission\SettingPermission::SettingAccess->value)
            <li class="nav-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.settings.index') }}">
                    <span class="menu-title">Settings</span>
                    <i class="mdi mdi-cog menu-icon"></i>
                </a>
            </li>
        @endcan
    </ul>
</nav>
<style>
    .sidebar .nav.sub-menu {
        margin-bottom: 0 !important;
    }
</style>

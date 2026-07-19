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
                class="nav-item {{ request()->routeIs('admin.category*', 'admin.sub-category*', 'admin.brand*', 'admin.size*', 'admin.color*', 'admin.tag*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-catManagement"
                    aria-expanded="{{ request()->routeIs('admin.category*', 'admin.sub-category*', 'admin.brand*', 'admin.size*', 'admin.color*', 'admin.tag*') ? 'true' : 'false' }}"
                    aria-controls="ui-catManagement">
                    <span class="menu-title">Catalog Management</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-crosshairs-gps menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.category*', 'admin.sub-category*', 'admin.brand*', 'admin.size*', 'admin.color*', 'admin.tag*') ? 'show' : '' }}"
                    id="ui-catManagement">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\CategoryPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.category.index') ? 'active' : '' }}"
                                    href="{{ route('admin.category.index') }}">Categories</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\SubCategoryPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.sub-category.index') ? 'active' : '' }}"
                                    href="{{ route('admin.sub-category.index') }}">Sub Categories</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\BrandPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.brand.index') ? 'active' : '' }}"
                                    href="{{ route('admin.brand.index') }}">Brands</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\SizePermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.size.index') ? 'active' : '' }}"
                                    href="{{ route('admin.size.index') }}">Sizes</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\ColorPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('color.index') ? 'active' : '' }}"
                                    href="{{ route('admin.color.index') }}">Colors</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\TagPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tag.index') ? 'active' : '' }}"
                                    href="{{ route('admin.tag.index') }}">Tags</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\ProductPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.product*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#products"
                    aria-expanded="{{ request()->routeIs('admin.product*') ? 'true' : 'false' }}" aria-controls="ui-products">
                    <span class="menu-title">Products</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-package-variant-closed menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.product*') ? 'show' : '' }}" id="products">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\ProductPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.product.index') ? 'active' : '' }}"
                                    href="{{ route('admin.product.index') }}">All Products</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\ProductPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.product.add') ? 'active' : '' }}"
                                    href="{{ route('admin.product.add') }}">Add Product</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        <li class="nav-item {{ request()->routeIs('admin.settings.offers') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.settings.offers') }}">
                <span class="menu-title">Banner Offers</span>
                <i class="mdi mdi-gift menu-icon"></i>
            </a>
        </li>
        @can(App\Enums\Permission\OrderPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.order*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#order"
                    aria-expanded="{{ request()->routeIs('admin.order*') ? 'true' : 'false' }}" aria-controls="order">
                    <span class="menu-title">Orders</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-cart menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.order*') ? 'show' : '' }}" id="order">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\OrderPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.order.index') ? 'active' : '' }}"
                                    href="{{ route('admin.order.index') }}">All Orders</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\OrderPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.couriers*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#courier"
                    aria-expanded="{{ request()->routeIs('admin.couriers*') ? 'true' : 'false' }}" aria-controls="courier">
                    <span class="menu-title">Couriers</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-truck-delivery menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.couriers*') ? 'show' : '' }}" id="courier">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.couriers.index') ? 'active' : '' }}"
                                href="{{ route('admin.couriers.index') }}">All Couriers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.couriers.create') ? 'active' : '' }}"
                                href="{{ route('admin.couriers.create') }}">Add Courier</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\CouponPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.coupon*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#coupon"
                    aria-expanded="{{ request()->routeIs('admin.coupon*') ? 'true' : 'false' }}" aria-controls="coupon">
                    <span class="menu-title">Coupons</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-ticket menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.coupon*') ? 'show' : '' }}" id="coupon">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\CouponPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.coupon.index') ? 'active' : '' }}"
                                    href="{{ route('admin.coupon.index') }}">All Coupons</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\CouponPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.coupon.add') ? 'active' : '' }}"
                                    href="{{ route('admin.coupon.add') }}">Add Coupon</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @can(App\Enums\Permission\SliderPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.slider*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#slider"
                    aria-expanded="{{ request()->routeIs('admin.slider*') ? 'true' : 'false' }}" aria-controls="slider">
                    <span class="menu-title">Sliders</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-view-carousel menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.slider*') ? 'show' : '' }}" id="slider">
                    <ul class="nav flex-column sub-menu">
                        @can(App\Enums\Permission\SliderPermission::VIEW->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.slider.index') ? 'active' : '' }}"
                                    href="{{ route('admin.slider.index') }}">All Sliders</a>
                            </li>
                        @endcan
                        @can(App\Enums\Permission\SliderPermission::CREATE->value)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.slider.add') ? 'active' : '' }}"
                                    href="{{ route('admin.slider.add') }}">Add Slider</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcan
        @php
            use App\Models\ProductReview;
            $countReviews = ProductReview::where('status', 0)->count();
        @endphp
        @can(App\Enums\Permission\CommentPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.product-review*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#product-reviews"
                    aria-expanded="{{ request()->routeIs('admin.product-review*') ? 'true' : 'false' }}"
                    aria-controls="product-reviews">
                    <span class="menu-title">Product Reviews</span>
                    @if ($countReviews > 0)
                        <span class="badge badge-warning badge-pill me-2">{{ $countReviews }}</span>
                    @endif
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-comment-account-outline menu-icon"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.product-review*') ? 'show' : '' }}" id="product-reviews">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.product-review.index') ? 'active' : '' }}"
                                href="{{ route('admin.product-review.index') }}">All Reviews</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endcan

        <li class="nav-item {{ request()->routeIs('admin.newsletter*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.newsletter.index') }}">
                <span class="menu-title">Newsletters</span>
                <i class="mdi mdi-email-open menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.payment-methods*') || request()->routeIs('admin.settings.payment-gateways*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#payments" aria-expanded="{{ request()->routeIs('admin.payment-methods*') || request()->routeIs('admin.settings.payment-gateways*') ? 'true' : 'false' }}" aria-controls="payments">
                <span class="menu-title">Payments</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-credit-card menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.payment-methods*') || request()->routeIs('admin.settings.payment-gateways*') ? 'show' : '' }}" id="payments">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.payment-methods.index') ? 'active' : '' }}" href="{{ route('admin.payment-methods.index') }}">Payment Methods List</a>
                    </li>
                    @can(\App\Enums\Permission\SettingPermission::SettingAccess->value)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.settings.payment-gateways') ? 'active' : '' }}" href="{{ route('admin.settings.payment-gateways') }}">Payment Gateways Settings</a>
                    </li>
                    @endcan
                </ul>
            </div>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.store-features*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.store-features.index') }}">
                <span class="menu-title">Store Features</span>
                <i class="mdi mdi-star menu-icon"></i>
            </a>
        </li>

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
        @can(\App\Enums\Permission\LocationPermission::VIEW->value)
            <li class="nav-item {{ request()->routeIs('admin.shipping_cost*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.shipping_cost.index') }}">
                    <span class="menu-title">Shipping Costs</span>
                    <i class="mdi mdi-truck-delivery menu-icon"></i>
                </a>
            </li>
        @endcan

        <li class="nav-item {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.menus.index') }}">
                <span class="menu-title">Menu Builder</span>
                <i class="mdi mdi-menu menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.pages.index') }}">
                <span class="menu-title">Pages</span>
                <i class="mdi mdi-file-document-outline menu-icon"></i>
            </a>
        </li>


        <li class="nav-item {{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.blog_categories.*') || request()->routeIs('admin.blog-comments.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#blog" aria-expanded="{{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.blog_categories.*') || request()->routeIs('admin.blog-comments.*') ? 'true' : 'false' }}" aria-controls="blog">
                <span class="menu-title">Blog System</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-post-outline menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.blog_categories.*') || request()->routeIs('admin.blog-comments.*') ? 'show' : '' }}" id="blog">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.blog_categories.*') ? 'active' : '' }}" href="{{ route('admin.blog_categories.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">All Blogs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.blog-comments.*') ? 'active' : '' }}" href="{{ route('admin.blog-comments.index') }}">Blog Comments</a>
                    </li>
                </ul>
            </div>
        </li>

        @can(\App\Enums\Permission\SettingPermission::SettingAccess->value)
            <li class="nav-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.settings.index') }}">
                    <span class="menu-title">Settings</span>
                    <i class="mdi mdi-cog menu-icon"></i>
                </a>
            </li>
            
            <li class="nav-item {{ request()->routeIs('admin.settings.theme-layouts') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.settings.theme-layouts') }}">
                    <span class="menu-title">Theme Layouts</span>
                    <i class="mdi mdi-view-dashboard-variant menu-icon"></i>
                </a>
            </li>
        @endcan
    </ul>
</nav>
<style>
    .sidebar .nav.sub-menu {
        margin-bottom: 0 !important;
    }

    #sidebar ul.nav>li.nav-item:last-child {
        margin-bottom: 25px !important;
    }
</style>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('dashboard/assets/images/faces/face1.jpg') }}" alt="profile" />
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">David Grey. H</span>
                    <span class="text-secondary text-small">Project Manager</span>
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
        <li class="nav-item {{ request()->routeIs('admin.media*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#media_menu" aria-expanded="false"
                aria-controls="media_menu">
                <span class="menu-title">Media</span>
                <i class="menu-arrow"></i>
                <i class="fa fa-file-photo-o menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.media*') ? 'show' : '' }}" id="media_menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.media') ? 'active' : '' }}"
                            href="{{ route('admin.media') }}">All Media</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.media.add') ? 'active' : '' }}"
                            href="{{ route('admin.media.add') }}">Add Media</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ request()->routeIs('category*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-catManagement" aria-expanded="false"
                aria-controls="ui-catManagement">
                <span class="menu-title">Catalog Management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('category*', 'sub-category*', 'brand*', 'size*', 'color*', 'tag*') ? 'show' : '' }}"
                id="ui-catManagement">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}"
                            href="{{ route('category.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sub-category.index') ? 'active' : '' }}"
                            href="{{ route('sub-category.index') }}">Sub Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('brand.index') ? 'active' : '' }}"
                            href="{{ route('brand.index') }}">Brands</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('size.index') ? 'active' : '' }}"
                            href="{{ route('size.index') }}">Sizes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('color.index') ? 'active' : '' }}"
                            href="{{ route('color.index') }}">Colors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tag.index') ? 'active' : '' }}"
                            href="{{ route('tag.index') }}">Tags</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#products" aria-expanded="false" aria-controls="products">
                <span class="menu-title">Products</span>
                <i class="mdi mdi-contacts menu-icon"></i>
            </a>
            <div class="collapse" id="products">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/icons/font-awesome.html">All Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('product.add') }}">Add Product</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#forms" aria-expanded="false" aria-controls="forms">
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
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
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
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false"
                aria-controls="auth">
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-lock menu-icon"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/blank-page.html"> Blank Page </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/login.html"> Login </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/register.html"> Register </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/error-404.html"> 404 </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/samples/error-500.html"> 500 </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="docs/documentation.html" target="_blank">
                <span class="menu-title">Documentation</span>
                <i class="mdi mdi-file-document-box menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
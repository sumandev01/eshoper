<div class="col-12 col-lg-3 mb-5">
    <!-- User Welcome Block -->
    <div class="dash-card mb-4 text-center p-4">
        <div class="mb-3">
            <img src="{{ auth('web')->user()?->profile_image ? asset('storage/' . auth('web')->user()->profile_image) : asset('user.webp') }}" alt="Profile" class="rounded-circle shadow-sm" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #fff;">
        </div>
        <h5 class="mb-1 font-weight-semi-bold">Hello, {{ auth()->user()->name ?? 'User' }}!</h5>
        <p class="text-muted small mb-0">Welcome back to your dashboard</p>
    </div>

    <!-- Sidebar Menu -->
    <div class="dash-sidebar list-group">
        <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
        </a>
        <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.orders') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag fa-fw me-2"></i> My Orders
        </a>
        <a href="{{ route('user.order.products') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.order.products') ? 'active' : '' }}">
            <i class="fas fa-star fa-fw me-2"></i> Review Products
        </a>
        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            <i class="fas fa-user-edit fa-fw me-2"></i> Profile
        </a>
        <a href="{{ route('user.address') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.address') ? 'active' : '' }}">
            <i class="fas fa-map-marker-alt fa-fw me-2"></i> Address
        </a>
        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger">
            <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
        </a>
    </div>
</div>

@push('styles')
<style>
    /* Global Dashboard Background */
    body {
        background-color: #ffffff !important;
    }

    /* Dashboard Cards */
    .dash-card {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .dash-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
    }

    /* Dashboard Sidebar */
    .dash-sidebar {
        border-radius: 12px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
    }
    .dash-sidebar .list-group-item {
        border: none;
        background-color: transparent;
        padding: 12px 20px;
        font-weight: 500;
        color: color-mix(in srgb, var(--primary) 60%, #111);
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    .dash-sidebar .list-group-item i {
        color: #adb5bd;
        transition: color 0.3s ease;
    }
    .dash-sidebar .list-group-item:hover,
    .dash-sidebar .list-group-item:focus,
    .dash-sidebar .list-group-item:active {
        background-color: transparent !important;
        color: var(--primary) !important;
    }
    .dash-sidebar .list-group-item:hover i,
    .dash-sidebar .list-group-item:focus i,
    .dash-sidebar .list-group-item:active i {
        color: var(--primary) !important;
    }
    
    .dash-sidebar .list-group-item.active {
        background-color: transparent !important;
        color: var(--primary) !important;
        border-left: 3px solid var(--primary);
        border-radius: 0;
        z-index: 1;
    }
    .dash-sidebar .list-group-item.active i {
        color: var(--primary);
    }
    .dash-sidebar .list-group-item.text-danger:hover {
        color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.05);
    }
    .dash-sidebar .list-group-item.text-danger:hover i {
        color: #dc3545 !important;
    }

    /* Badge Pills */
    .dash-badge {
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .badge-soft-success { background-color: rgba(40, 167, 69, 0.1) !important; color: #28a745 !important; }
    .badge-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; color: #ffc107 !important; }
    .badge-soft-danger { background-color: rgba(220, 53, 69, 0.1) !important; color: #dc3545 !important; }
    .badge-soft-info { background-color: rgba(23, 162, 184, 0.1) !important; color: #17a2b8 !important; }
    
    /* Dashboard Tables */
    .dash-table, .dash-table tr, .dash-table tbody, .dash-table td, .dash-table th {
        background-color: transparent !important;
    }
    .dash-table th {
        border-top: none;
        border-bottom: 2px solid rgba(0,0,0,0.05) !important;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #6c757d;
        background: transparent;
    }
    .dash-table td {
        vertical-align: middle;
        border-top: 1px dashed rgba(0,0,0,0.05);
        border-bottom: none;
        transition: all 0.3s ease;
    }
    .table-hover.dash-table tbody tr:hover td {
        background-color: color-mix(in srgb, var(--primary) 6%, white) !important;
        color: color-mix(in srgb, var(--primary) 80%, #111);
    }
    .table-hover.dash-table tbody tr:hover td.text-muted {
        color: color-mix(in srgb, var(--primary) 60%, #111) !important;
    }
</style>
@endpush


<div class="col-md-3 mb-5">
    <div class="list-group">
        <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.orders') ? 'active' : '' }}">My Orders</a>
        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.profile') ? 'active' : '' }}">Profile</a>
        <a href="{{ route('user.address') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.address') ? 'active' : '' }}">Address</a>
        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action">Logout</a>
    </div>
</div>
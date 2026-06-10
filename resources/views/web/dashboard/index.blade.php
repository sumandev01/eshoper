@extends('web.layouts.app')
@section('title', 'User Dashboard' . ' - ' . $siteSettings?->site_title)
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li>User Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container-fluid pt-2">
        <div class="row px-xl-5">
            @include('web.dashboard.sidebar')
            <div class="col-lg-9 mb-5">
                {{-- <div class="text-center mb-4">
                    <h2 class="section-title px-5"><span class="px-2">Welcome to Your Dashboard</span></h2>
                </div> --}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="card stat-card bg-primary text-white p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Total Orders</h5>
                                    <h3>{{ $orders->count() }}</h3>
                                </div>
                                <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-warning text-dark p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Pending</h5>
                                    <h3>{{ $orders->where('order_status', 'pending')->count() }}</h3>
                                </div>
                                <i class="fas fa-clock fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-success text-white p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Completed</h5>
                                    <h3>{{ $orders->where('order_status', 'delivered')->count() }}</h3>
                                </div>
                                <i class="fas fa-check-circle fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-info text-white p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Wishlist</h5>
                                    <h3>{{ $wishlists->count() }}</h3>
                                </div>
                                <i class="fas fa-heart fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <div class="d-flex justify-content-between align-items-center gap-1 mb-3">
                        <h5 class="font-weight-semi-bold mb-2">Recent Orders</h5>
                        <a href="{{ route('user.orders') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-primary">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Payment Status</th>
                                <th>Delivery Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders ?? [] as $key => $order)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>{{ $order?->order_number }}</td>
                                    <td class="text-end">{{ $order?->created_at?->format('d-M-Y') }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge text-white p-2 rounded-pill bg-{{ $order?->payment_status?->color() }}">
                                            {{ ucfirst($order?->payment_status?->value) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge text-white p-2 rounded-pill bg-{{ $order?->order_status?->color() }}">
                                            {{ ucfirst($order?->order_status?->value) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            <span>{{ $siteSettings?->currency_symbol }}</span>
                                            <span>{{ formatBDT($order?->grand_total) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center"><a class="btn btn-sm btn-primary"
                                            href="{{ route('user.orderDetails', $order->id) }}">View</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Order Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->
@endsection

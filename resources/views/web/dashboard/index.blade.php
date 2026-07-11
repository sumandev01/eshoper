@extends('web.layouts.app')
@section('title', 'User Dashboard' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'User Dashboard', 'url' => '']
        ]
    ])
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container pt-1">
        <div class="row">
            @include('web.dashboard.sidebar')
            <div class="col-lg-9 mb-5">
                <div class="row">
                    <div class="col-md-3">
                        <div class="dash-card dash-stat-card p-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Total Orders</h6>
                                    <h3 class="mb-0 fw-bold text-dark">{{ $orders->count() }}</h3>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: color-mix(in srgb, var(--primary) 12%, white);">
                                    <i class="fas fa-shopping-cart fa-lg" style="color: var(--primary);"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dash-card dash-stat-card p-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Pending</h6>
                                    <h3 class="mb-0 fw-bold text-dark">{{ $orders->where('order_status', 'pending')->count() }}</h3>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(255, 193, 7, 0.12);">
                                    <i class="fas fa-clock fa-lg text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dash-card dash-stat-card p-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Delivered</h6>
                                    <h3 class="mb-0 fw-bold text-dark">{{ $orders->where('order_status', 'delivered')->count() }}</h3>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(40, 167, 69, 0.12);">
                                    <i class="fas fa-check-circle fa-lg text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="dash-card dash-stat-card p-4 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-1 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Wishlist</h6>
                                    <h3 class="mb-0 fw-bold text-dark">{{ $wishlists->count() }}</h3>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(23, 162, 184, 0.12);">
                                    <i class="fas fa-heart fa-lg text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dash-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="font-weight-semi-bold mb-0" style="color: color-mix(in srgb, var(--primary) 60%, #111); font-size: 1.2rem;">Recent Orders</h5>
                        <a href="{{ route('user.orders') }}" class="btn btn-primary theme-shadow transition-all hover-up px-4 py-2" style="border-radius: 8px; font-weight: 500;">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table dash-table table-hover mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Order Date</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders ?? [] as $key => $order)
                                    <tr>
                                        <td class="text-center text-muted">{{ $key + 1 }}</td>
                                        <td class="font-weight-medium">{{ $order?->order_number }}</td>
                                        <td class="text-center text-muted">{{ $order?->created_at?->format('d-M-Y') }}</td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill dash-badge badge-soft-{{ $order?->payment_status?->color() === 'success' ? 'success' : ($order?->payment_status?->color() === 'warning' ? 'warning' : 'info') }} px-3 py-2">
                                                {{ ucfirst($order?->payment_status?->value) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill dash-badge badge-soft-{{ $order?->order_status?->color() === 'success' ? 'success' : ($order?->order_status?->color() === 'warning' ? 'warning' : 'info') }} px-3 py-2">
                                                {{ ucfirst($order?->order_status?->value) }}
                                            </span>
                                        </td>
                                        <td class="text-center font-weight-semi-bold text-dark">
                                            <span>{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ formatBDT($order?->grand_total) }}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-light rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center" href="{{ route('user.order.details', $order->id) }}" title="View Order" style="width: 35px; height: 35px; padding: 0;">
                                                <i class="fas fa-eye text-primary"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-box-open fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">No Orders Found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->
@endsection




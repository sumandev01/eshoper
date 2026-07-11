@extends('web.layouts.app')
@section('title', 'Order' . ' - ' . ($siteSettings->site_title ?? null))
@section('title', 'Order')
@section('content')
    <!-- Page Header Start -->
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'User Dashboard', 'url' => route('user.dashboard')],
            ['name' => 'Orders', 'url' => '']
        ]
    ])
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container pt-1">
        <div class="row">
            @include('web.dashboard.sidebar')
            <div class="col-lg-9 mb-2">
                <div class="dash-card p-4">
                    <div class="table-responsive">
                        <h5 class="font-weight-semi-bold mb-4" style="color: color-mix(in srgb, var(--primary) 60%, #111); font-size: 1.2rem;">All Orders</h5>
                        <table class="table dash-table table-hover mb-0" id="orderTable">
                            <thead>
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
                                            <a class="btn btn-sm btn-light rounded-circle theme-shadow hover-up transition-all d-inline-flex align-items-center justify-content-center" href="{{ route('user.order.details', $order->id) }}" title="View Order" style="width: 35px; height: 35px; padding: 0;">
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
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#orderTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endpush


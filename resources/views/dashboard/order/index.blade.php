@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Order Management')

@section('content')
    <div class="container-fluid px-0">
        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="fw-bold mb-1">Order Management</h4>
                <p class="text-muted small mb-0">Track, filter, and manage customer purchases & order statuses.</p>
            </div>
        </div>

        {{-- Executive Metric Cards (4 Cards) --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block text-uppercase mb-1">Total Orders</span>
                            <h3 class="fw-bold mb-0">{{ number_format($totalOrdersCount) }}</h3>
                        </div>
                        <div class="avatar-shape bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-cart-outline fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block text-uppercase mb-1">Pending Orders</span>
                            <h3 class="fw-bold text-warning mb-0">{{ number_format($pendingOrdersCount) }}</h3>
                        </div>
                        <div class="avatar-shape bg-warning-subtle text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-clock-outline fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block text-uppercase mb-1">Processing Orders</span>
                            <h3 class="fw-bold text-info mb-0">{{ number_format($processingOrdersCount) }}</h3>
                        </div>
                        <div class="avatar-shape bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-progress-clock fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block text-uppercase mb-1">Total Paid Revenue</span>
                            <h3 class="fw-bold text-success mb-0">
                                {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($totalRevenue) }}
                            </h3>
                        </div>
                        <div class="avatar-shape bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-currency-usd fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Card --}}
        <div class="card border-0 shadow-sm">
            {{-- Card Header: Status Tabs --}}
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    {{-- Status Filter Tabs (Enum-Driven) --}}
                    <ul class="nav nav-pills card-header-pills gap-2 m-0">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('order_status') ? 'active' : '' }}" 
                               href="{{ route('admin.order.index', array_merge(request()->except(['order_status', 'page']))) }}">
                                All Orders <span class="badge rounded-pill bg-secondary ms-1">{{ $totalOrdersCount }}</span>
                            </a>
                        </li>
                        @foreach(\App\Enums\OrderStatusEnums::cases() as $statusEnum)
                            @php
                                $statusVal = $statusEnum->value;
                                $count = $statusCounts[$statusVal] ?? 0;
                                $isActive = request('order_status') === $statusVal;
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link {{ $isActive ? 'active' : '' }}" 
                                   href="{{ route('admin.order.index', array_merge(request()->except(['order_status', 'page']), ['order_status' => $statusVal])) }}">
                                    {{ ucfirst($statusVal) }}
                                    <span class="badge rounded-pill bg-{{ $statusEnum->color() }} ms-1">{{ $count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Card Body: Component Filter Form & Table --}}
            <div class="card-body p-4">
                @php
                    $paymentStatuses = collect(\App\Enums\PaymentStatusEnums::cases())
                        ->mapWithKeys(fn($case) => [$case->value => ucfirst($case->value)])
                        ->toArray();

                    $dateFilterOptions = [
                        'today'        => 'Today',
                        'yesterday'    => 'Yesterday',
                        'this_week'    => 'This Week',
                        'last_7_days' => 'Last 7 Days',
                        'this_month'   => 'This Month',
                        'last_month'   => 'Last Month',
                        'this_year'    => 'This Year',
                        'custom'       => 'Custom Range',
                    ];
                @endphp

                {{-- Component-Based Filter Form --}}
                <form action="{{ route('admin.order.index') }}" method="GET" class="mb-4">
                    @if(request('order_status'))
                        <input type="hidden" name="order_status" value="{{ request('order_status') }}">
                    @endif

                    <div class="row g-2 align-items-center">
                        {{-- Search Input Component --}}
                        <div class="col-lg-2 col-md-6 search-input">
                            <x-input 
                                name="search" 
                                placeholder="Search by order # or name" 
                                :value="request('search')" 
                                :required="false"
                            />
                        </div>

                        {{-- Date Preset Select Component --}}
                        <div class="col-lg-2 col-md-6">
                            <x-select 
                                name="date_filter" 
                                id="date_filter_select"
                                label="" 
                                :options="$dateFilterOptions" 
                                :value="$dateFilter" 
                                placeholder="All Time (Date Filter)"
                                :required="false" 
                            />
                        </div>

                        {{-- Custom Start Date Component --}}
                        <div class="col-lg-2 col-md-6 customDateRange {{ $dateFilter == 'custom' ? '' : 'd-none' }}">
                            <x-input 
                                type="date" 
                                name="start_date" 
                                :value="request('start_date', $startDate ? $startDate->format('Y-m-d') : '')" 
                                placeholder="Start Date"
                                :required="false"
                            />
                        </div>

                        {{-- Custom End Date Component --}}
                        <div class="col-lg-2 col-md-6 customDateRange {{ $dateFilter == 'custom' ? '' : 'd-none' }}">
                            <x-input 
                                type="date" 
                                name="end_date" 
                                :value="request('end_date', $endDate ? $endDate->format('Y-m-d') : '')" 
                                placeholder="End Date"
                                :required="false"
                            />
                        </div>

                        {{-- Payment Status Select Component --}}
                        <div class="col-lg-2 col-md-6">
                            <x-select 
                                name="payment_status" 
                                label="" 
                                :options="$paymentStatuses" 
                                :value="request('payment_status')" 
                                placeholder="All Payment Status"
                                :required="false" 
                            />
                        </div>

                        {{-- Filter Action Buttons --}}
                        <div class="col-auto d-flex align-items-center gap-2 mb-3">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm border-0" style="padding: 0.65rem 1.25rem;">
                                <i class="mdi mdi-filter me-1"></i> Apply
                            </button>
                            @if(request()->hasAny(['search', 'date_filter', 'payment_status', 'order_status', 'start_date', 'end_date']))
                                <a href="{{ route('admin.order.index') }}" class="btn btn-light px-3 border shadow-sm" style="padding: 0.65rem 1rem;" title="Reset all filters">
                                    <i class="mdi mdi-refresh me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                {{-- Table View --}}
                <div class="table-responsive">
                    <table id="productTable" class="table table-hover table-bordered table-centered align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">Sl</th>
                                <th>Order Number</th>
                                <th>User Name</th>
                                <th class="text-center">Product Item</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Payment Status</th>
                                <th class="text-center">Order Status</th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $key => $order)
                                <tr>
                                    <td class="text-center text-muted fw-semibold">{{ $orders->firstItem() + $key }}</td>
                                    <td class="fw-bold text-dark">{{ $order->order_number }}</td>
                                    <td>{{ $order->user_name ?? 'Guest User' }}</td>
                                    <td class="text-center">{{ $order->orderProducts->count() }}</td>
                                    <td class="text-center fw-semibold">
                                        {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($order->grand_total) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-{{ $order?->payment_status?->color() }}">
                                            {{ ucfirst($order?->payment_status?->value) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-{{ $order?->order_status?->color() }}">
                                            {{ ucfirst($order?->order_status?->value) }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted">{{ $order->created_at->format('d-M-Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-sm btn-info text-white shadow-sm"
                                                href="{{ route('admin.order.view', $order->id) }}" title="View Order">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary text-white shadow-sm"
                                                href="{{ route('admin.order.edit', $order->id) }}" title="Edit Order">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="mdi mdi-database-off fs-1"></i>
                                            <p class="mt-2 mb-0">No Orders Found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Footer --}}
            @if($orders->hasPages())
                <div class="card-footer bg-transparent border-top py-3 d-flex justify-content-end align-items-center">
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .form-group{
            margin-bottom: 16px !important;
        }    
    </style>    
@endpush
@push('scripts')
<script>
    function handleDateFilterChange(value) {
        const customDateRanges = document.querySelectorAll('.customDateRange');
        if (value === 'custom') {
            customDateRanges.forEach(el => el.classList.remove('d-none'));
        } else {
            customDateRanges.forEach(el => el.classList.add('d-none'));
        }
    }

    $(document).ready(function() {
        $('#date_filter_select, select[name="date_filter"]').on('change', function() {
            handleDateFilterChange($(this).val());
        });
    });
</script>
@endpush

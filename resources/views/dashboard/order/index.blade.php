@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Order List')
@section('content')
    <div class="container-fluid">
        <!-- Product Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-4">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">
                                <a class="btn btn-primary btn-md" href="{{ route('order.index') }}">All Order List</a>
                            </h4>
                            <form action="">
                                <div class="row justify-content-end">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="search"
                                                value="{{ request('search') }}" autocomplete="off" required>
                                            <div class="input-group-append ms-1">
                                                <button class="btn btn-primary" type="submit">
                                                    search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table id="productTable"
                                class="table table-hover table-bordered table-centered align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Sl</th>
                                        <th>Order Number</th>
                                        <th>User Name</th>
                                        <th>Product Item</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Payment Status</th>
                                        <th class="text-center">Order Status</th>
                                        <th class="text-center">Order Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $key => $order)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->user_name }}</td>
                                            <td>{{ $order->orderProducts->count() }}</td>
                                            <td class="text-end">
                                                {{ ($siteSettings->currency_symbol ?? null) }}
                                                {{ formatBDT($order->grand_total) }}
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
                                            <td class="text-end">{{ $order->created_at->format('d-M-Y') }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('order.view', $order->id) }}">View</a>
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('order.edit', $order->id) }}">Edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="mdi mdi-database-off fs-1"></i>
                                                    <p class="mt-2">No Orders Found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


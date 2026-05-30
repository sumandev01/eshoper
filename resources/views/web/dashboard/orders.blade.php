@extends('web.layouts.app')
@section('title', 'Order' . ' - ' . $siteSettings?->site_title)
@section('title', 'Order')
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li><a class="nav-link p-0" href="{{ route('user.dashboard') }}">User Dashboard</a></li>
                        <li>Order</li>
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
            <div class="col-lg-9 mb-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <h5 class="font-weight-semi-bold mb-4">All Orders</h5>
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order ID</th>
                                        <th>Order Date</th>
                                        <th>Order Status</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders ?? [] as $key => $order)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $order?->order_number }}</td>
                                            <td>{{ $order?->created_at }}</td>
                                            <td>{{ $order?->order_status }}</td>
                                            <td>
                                                <span>{{ $siteSettings?->currency_symbol }}</span>
                                                <span>{{ formatBDT($order?->grand_total) }}</span>
                                            </td>
                                            <td><a class="btn" href="{{ route('user.orderDetails', $order->id) }}">View</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No Order Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->
@endsection

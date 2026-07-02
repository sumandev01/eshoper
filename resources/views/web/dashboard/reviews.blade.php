@extends('web.layouts.app')
@section('title', 'Product Reviews' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li><a class="nav-link p-0" href="{{ route('user.dashboard') }}">User Dashboard</a></li>
                        <li>Product Reviews</li>
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
                            <h5 class="font-weight-semi-bold mb-4">All Orders Products</h5>
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order ID</th>
                                        <th>Product Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderProducts ?? [] as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->order_id }}</td>
                                            <td>{{ $product->product?->name }}</td>
                                            <td><a class="btn" href="">View</a></td>
                                        </tr>
                                    @endforeach
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



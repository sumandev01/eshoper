@extends('web.layouts.app')
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
                                    <h3>12</h3>
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
                                    <h3>02</h3>
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
                                    <h3>10</h3>
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
                                    <h3>05</h3>
                                </div>
                                <i class="fas fa-heart fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <h5 class="font-weight-semi-bold mb-2">Resent Orders</h5>
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Order Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#123</td>
                                <td>01 Jan 2045</td>
                                <td>Processing</td>
                                <td>$99</td>
                                <td><a class="btn" href="">View</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->
@endsection

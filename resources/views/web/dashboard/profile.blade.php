@extends('web.layouts.app')
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li><a class="nav-link p-0" href="{{ route('user.dashboard') }}">User Dashboard</a></li>
                        <li>Profile</li>
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
                <h2 class="section-title px-5"><span class="px-2">My Profile</span></h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <p class="text-muted">Update your profile information here.</p>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div class="mb-4">
                                    <h4 class="font-weight-semi-bold mb-4">Billing Address</h4>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>E-mail</label>
                                            <input class="form-control" type="text" placeholder="example@email.com">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Mobile No</label>
                                            <input class="form-control" type="text" placeholder="+123 456 789">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Address Line 1</label>
                                            <input class="form-control" type="text" placeholder="123 Street">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Address Line 2</label>
                                            <input class="form-control" type="text" placeholder="123 Street">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Country</label>
                                            <select class="custom-select">
                                                <option selected>United States</option>
                                                <option>Afghanistan</option>
                                                <option>Albania</option>
                                                <option>Algeria</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>City</label>
                                            <input class="form-control" type="text" placeholder="New York">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>State</label>
                                            <input class="form-control" type="text" placeholder="New York">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>ZIP Code</label>
                                            <input class="form-control" type="text" placeholder="123">
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="shipto">
                                                <label class="custom-control-label" for="shipto" data-toggle="collapse"
                                                    data-target="#shipping-address">Ship to different address</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="collapse mb-4" id="shipping-address">
                                    <h4 class="font-weight-semi-bold mb-4">Shipping Address</h4>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>First Name</label>
                                            <input class="form-control" type="text" placeholder="John">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" type="text" placeholder="Doe">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>E-mail</label>
                                            <input class="form-control" type="text" placeholder="example@email.com">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Mobile No</label>
                                            <input class="form-control" type="text" placeholder="+123 456 789">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Address Line 1</label>
                                            <input class="form-control" type="text" placeholder="123 Street">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Address Line 2</label>
                                            <input class="form-control" type="text" placeholder="123 Street">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Country</label>
                                            <select class="custom-select">
                                                <option selected>United States</option>
                                                <option>Afghanistan</option>
                                                <option>Albania</option>
                                                <option>Algeria</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>City</label>
                                            <input class="form-control" type="text" placeholder="New York">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>State</label>
                                            <input class="form-control" type="text" placeholder="New York">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>ZIP Code</label>
                                            <input class="form-control" type="text" placeholder="123">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->
@endsection
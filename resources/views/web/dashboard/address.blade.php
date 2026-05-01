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
                        <li>Address</li>
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
                <h3 class="section-title px-5"><span class="px-2">Edit My Address</span></h3>
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input label="Full Name" labelClass="custom-label" name="name" type="text"
                                            placeholder="Full Name" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input label="Mobile No" labelClass="custom-label" name="mobile" type="text"
                                            placeholder="+088 123 456 789" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-select label="Category" name="category_id" id="category_id"
                                        placeholder="Select Category"
                                        options="[
                                            {id: 1, name: 'Category 1'},
                                            {id: 2, name: 'Category 2'},
                                            {id: 3, name: 'Category 3'},
                                        ]"
                                        />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <x-input label="Address Line 1" labelClass="custom-label" name="address1"
                                            type="text" placeholder="123 Street" />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <x-input label="Address Line 2" labelClass="custom-label" name="address2"
                                            type="text" placeholder="123 Street" />
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .custom-label {
            font-weight: 400 !important;
        }
    </style>
@endpush

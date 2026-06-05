@extends('dashboard.layouts.app')
@section('title', $siteSettings?->site_title . ' - ' . 'Edit Order - ' . $order->order_number)
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col col-xs-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between py-4">
                        <h4 class="mb-0">Order - {{ $order->order_number }}</h4>
                        <button onclick="history.back()" class="btn btn-primary btn-border-0 btn-icon-text">
                            <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                            <span>Back</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('order.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <h5 class="mb-3">Payment Status </h5>
                                </div>
                                <div class="col-auto">
                                    :
                                </div>
                                <div class="col-md-7">
                                    <x-select id="type" class="text-capitalize" name="payment_status"
                                        :required='true'>
                                        <option disabled>Select Type</option>
                                        @foreach ($paymentStatusEnums ?? [] as $paymentStatus)
                                            <option class="text-capitalize" value="{{ $paymentStatus->value }}"
                                                {{ $order->payment_status->value == $paymentStatus->value ? 'selected' : '' }}>
                                                {{ $paymentStatus->value }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <h5 class="mb-3">Order Status </h5>
                                </div>
                                <div class="col-auto">
                                    :
                                </div>
                                <div class="col-md-7">
                                    <x-select id="type" class="text-capitalize" name="order_status"
                                        :required='true'>
                                        <option disabled>Select Type</option>
                                        @foreach ($orderStatusEnums ?? [] as $orderStatus)
                                            <option class="text-capitalize" value="{{ $orderStatus->value }}"
                                                {{ $order->order_status->value == $orderStatus->value ? 'selected' : '' }}>
                                                {{ $orderStatus->value }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 py-3">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

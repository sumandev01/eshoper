@extends('web.layouts.app')
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li>Cart</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @forelse ($carts ?? [] as $cart)
                        @php
                            $subTotal = $cart->cart_price * $cart->quantity;
                        @endphp
                            <tr>
                                <td class="text-left d-flex">
                                    <img src="{{ Storage::url($cart->cart_image) }}" alt="" style="width: 100px;">
                                    <div class="pl-3">
                                        <a class="text-dark nav-link px-0" style="text-decoration: none;"
                                            href="{{ route('productDetails', $cart?->product?->slug) }}"
                                            title="{{ $cart?->product?->name }}">{{ Str::limit($cart?->product?->name, 30, '...') }}</a>
                                        <div>
                                            @if ($cart->size_id)
                                                <span class="p-1 text-white bg-primary">Size: {{ $cart?->size?->name ?? '' }}</span>
                                            @endif
                                            @if ($cart->color_id)
                                                <span class="p-1 text-white bg-primary">Color: {{ $cart?->color?->name ?? '' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">৳{{ $cart?->cart_price }}</td>
                                <td class="align-middle">
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-minus">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="quantity" class="form-control form-control-sm bg-secondary text-center"
                                            value="{{ old('quantity', $cart?->quantity) }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-plus">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">৳{{ $subTotal }}</td>
                                <td class="align-middle"><button class="btn btn-sm btn-primary"><i
                                            class="fa fa-times"></i></button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Product added to Cart Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </div>
                </form>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium">৳{{ $subTotalPrice }}</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium">$10</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold">$160</h5>
                        </div>
                        <button class="btn btn-block btn-primary my-3 py-3">Proceed To Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->
@endsection

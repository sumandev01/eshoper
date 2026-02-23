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
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <h4 class="font-weight-semi-bold mb-2">Your Cart</h4>
                <p>There are {{ count($carts) }} products in this list</p>
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Image</th>
                            <th class="text-left">Products</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @forelse ($carts ?? [] as $cart)
                            @php
                                $price = $cart?->cart_price;
                                $subTotal = 0;
                                $subTotal = $price * $cart?->quantity;
                                $productStock = $cart?->product_stock;
                            @endphp
                            <tr data-product-stock="{{ $productStock }}" data-cart-id="{{ $cart?->id }}"
                                data-product-price="{{ $price }}">
                                <td style="max-width: 100px">
                                    <img src="{{ Storage::url($cart->cart_image) }}" alt="" style="width: 100px; aspect-ratio: 1/1; object-fit: cover;">
                                </td>
                                <td class="text-left">
                                    <a class="text-dark nav-link px-0" style="text-decoration: none;"
                                        href="{{ route('productDetails', $cart?->product?->slug) }}"
                                        title="{{ $cart?->product?->name }}">{{ Str::limit($cart?->product?->name, 30, '...') }}</a>
                                    <div>
                                        @if ($cart->size_id)
                                            <span class="p-1 text-white bg-primary">Size:
                                                {{ $cart?->size?->name ?? '' }}</span>
                                        @endif
                                        @if ($cart->color_id)
                                            <span class="p-1 text-white bg-primary">Color:
                                                {{ $cart?->color?->name ?? '' }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="text-primary mr-2">
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star"></small>
                                            <small class="fas fa-star-half-alt"></small>
                                            <small class="far fa-star"></small>
                                        </div>
                                        <small class="pt-1">(50 Reviews)</small>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if ($productStock > 0)
                                        <span class="badge badge-success p-2 rounded">{{ $productStock }}</span>
                                    @else
                                        <span class="badge badge-danger p-2 rounded">Out of Stock</span>
                                    @endif
                                </td>
                                <td class="align-middle">৳{{ formatBDT($price) }}</td>
                                <td class="align-middle">
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-minus quantity-btn">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="quantity"
                                            class="form-control form-control-sm bg-secondary text-center product-quantity"
                                            value="{{ old('quantity', $cart?->quantity) }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-plus quantity-btn">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle product-subtotal">৳{{ formatBDT($subTotal) }}</td>
                                <td class="align-middle">
                                    <button onclick="window.location.href = '{{ route('removeFromCart', $cart->id) }}';"
                                        class="btn btn-sm btn-primary btn-remove">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Product added to Cart Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <form id="coupon-form" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" id="couponCode" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button type="button" id="couponBtn" class="btn btn-primary">Apply Coupon</button>
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
                            <h6 class="font-weight-medium cart-subtotal">৳{{ formatBDT($subTotalPrice) }}</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Discount</h6>
                            <h6 class="font-weight-medium coupon-discount">0</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold grand-total">৳{{ formatBDT($subTotalPrice) }}</h5>
                        </div>
                        <form action="{{ route('checkout.index') }}" method="POST">
                            @csrf
                            <input type="hidden" name="couponId" id="CouponId">
                            <button type="submit" class="btn btn-block btn-primary my-3 py-3">Proceed To Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->
@endsection
@push('script')
    <script>
        //let CouponId = null;
        $(document).ready(function() {
            function updateCartSubTotal() {
                let cartStotal = 0;
                $('.product-subtotal').each(function() {
                    let subTotalText = $(this).text().replace(/[^0-9.-]+/g, '');
                    cartStotal += parseFloat(subTotalText) || 0;
                });
                $('.cart-subtotal').text('৳' + cartStotal.toLocaleString('en-IN'));
                $('.grand-total').text('৳' + cartStotal.toLocaleString('en-IN'));
            }

            $('.quantity-btn').on('click', function() {
                const $button = $(this);
                const $container = $button.closest('.quantity');
                const $row = $container.closest('tr');
                const $input = $container.find('.product-quantity');
                const $productStock = $row.data('product-stock');
                const $price = $row.data('product-price');
                const $cartId = $row.data('cart-id');

                let quantity = parseInt($input.val()) || 1;

                if (quantity <= 1) {
                    quantity = 1;
                    $input.val('1');
                }

                if (quantity >= $productStock) {
                    quantity = $productStock;
                    showToast("error", "Available stock: " + $productStock);
                }

                let subTotal = (quantity * $price).toLocaleString('en-IN');

                $row.find('.product-subtotal').text('৳' + subTotal);

                $input.val(quantity);

                updateCartSubTotal();

                $.ajax({
                    url: '{{ route('updateCart') }}',
                    type: 'POST',
                    data: {
                        cartId: $row.data('cart-id'),
                        quantity: quantity,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {},
                    error: function(error) {}
                });
            });

            $(document).on('input', '.product-quantity', function() {
                const $input = $(this);
                const $container = $input.closest('.quantity');
                const $row = $container.closest('tr');
                const $productStock = $row.data('product-stock');

                if ($input.val() > $productStock) {
                    $input.val($productStock);
                    showToast("error", "Available stock: " + $productStock);
                }
            });

            $('#couponBtn').on('click', function() {
                let couponCode = $('#couponCode').val();
                let cartSubTotal = parseFloat($('.cart-subtotal').text().replace(/[^0-9.-]+/g, ''));
                if (couponCode == null || couponCode == '' || couponCode == undefined || couponCode.length <
                    5) return;
                $.ajax({
                    url: '{{ route('applyCoupon') }}',
                    type: 'POST',
                    data: {
                        cartSubTotal: cartSubTotal,
                        couponCode: couponCode,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            showToast('success', response.message);
                            $('.grand-total').text('৳' + response.discountPrice.toLocaleString(
                                'en-IN'));
                            $('.coupon-discount').text('৳' + (cartSubTotal - response
                                .discountPrice).toLocaleString('en-IN'));
                            $('.quantity-btn').prop('disabled', true);
                            $('.btn-remove').prop('disabled', true);
                            $('#couponCode').val('');
                            $('#CouponId').val(response.couponId);
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function(error) {
                        showToast('error', error.responseJSON.message);
                    }
                });
            });
        })
    </script>
@endpush

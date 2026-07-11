@extends('web.layouts.app')
@section('title', ucfirst(Route::currentRouteName()) . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    @include('web.components.breadcrumb', ['title' => 'Cart'])
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container pt-1">
        <div class="row">
            <div class="col-12">
                <h4 class="font-weight-semi-bold mb-2">Your Cart</h4>
                <p>There are {{ count($carts) }} products in this list</p>
            </div>
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-borderless table-hover text-center mb-0 theme-shadow rounded" style="border-collapse: separate; border-spacing: 0;">
                    <thead class="bg-secondary text-primary">
                        <tr>
                            <th>Image</th>
                            <th class="text-start">Products</th>
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
                                <td style="width: 65px; padding-right: 15px;">
                                    <div class="img-wrapper" style="width: 65px; height: 65px; overflow: hidden;">
                                        <div class="img-spinner"></div>
                                        <img class="img-fluid w-100 h-100 optimized-image" style="object-fit: cover;" src="{{ Storage::url($cart?->cart_image) }}"
                                            alt="{{ $cart?->product?->name }}" loading="lazy" 
                                            onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                                            onerror="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                                        <script>
                                            (function() {
                                                let img = document.currentScript.previousElementSibling;
                                                if (img && img.complete) {
                                                    img.style.opacity = '1';
                                                    img.previousElementSibling.style.display = 'none';
                                                }
                                            })();
                                        </script>
                                    </div>
                                </td>
                                <td class="text-start">
                                    <a class="text-dark nav-link px-0" style="text-decoration: none;"
                                        href="{{ route('product.details', $cart?->product?->slug) }}"
                                        title="{{ $cart?->product?->name }}">{{ Str::limit($cart?->product?->name, 30, '...') }}</a>
                                    <div class="my-2 d-flex align-items-center">
                                        @if ($cart?->size_id)
                                            <span class="badge rounded-circle d-inline-flex justify-content-center align-items-center me-2 shadow-sm" 
                                                  style="width: 24px; height: 24px; font-size: 11px; background-color: var(--primary); color: #fff; font-weight: 600;" 
                                                  title="Size: {{ $cart?->size?->name ?? '' }}">
                                                {{ $cart?->size?->name ?? '' }}
                                            </span>
                                        @endif
                                        @if ($cart?->color_id)
                                            <span class="rounded-circle d-inline-block shadow-sm" 
                                                  style="width: 24px; height: 24px; background-color: {{ $cart?->color?->color_code ?? '#000' }}; border: 1px solid #e0e0e0; box-shadow: 0 0 2px rgba(0,0,0,0.2);" 
                                                  title="Color: {{ $cart?->color?->name ?? '' }}" data-bs-toggle="tooltip"></span>
                                        @endif
                                    </div>
                                    {{-- Star ratings removed for cleaner UI --}}
                                </td>
                                <td class="align-middle">
                                    @if ($productStock > 0)
                                        <span class="badge p-2 rounded" style="background-color: color-mix(in srgb, #198754 15%, white); color: #198754; font-weight: 600;">{{ $productStock }}</span>
                                    @else
                                        <span class="badge p-2 rounded" style="background-color: color-mix(in srgb, #dc3545 15%, white); color: #dc3545; font-weight: 600;">Out of Stock</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($price) }}</td>
                                <td class="align-middle">
                                    <div class="input-group quantity mx-auto theme-shadow rounded" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-minus quantity-btn bg-secondary text-primary border-0">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" name="quantity"
                                            class="form-control form-control-sm border-0 text-center product-quantity bg-light"
                                            value="{{ old('quantity', $cart?->quantity) }}" style="font-weight: 600;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-plus quantity-btn bg-secondary text-primary border-0">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle product-subtotal">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($subTotal) }}</td>
                                <td class="align-middle">
                                    <button onclick="window.location.href = '{{ route('cart.remove', $cart->id) }}';"
                                        class="btn btn-sm shadow-sm btn-remove" style="background-color: color-mix(in srgb, red 12%, white); color: red; border: none;">
                                        <i class="fas fa-trash"></i>
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
                    <div class="input-group theme-shadow rounded" style="overflow: hidden;">
                        <input type="text" class="form-control p-3 border-0 bg-light" id="couponCode" placeholder="Coupon Code" style="box-shadow: none;">
                        <button type="button" id="couponBtn" class="btn btn-primary fw-bold px-4 border-0">Apply Coupon</button>
                    </div>
                </form>
                <div class="card border-0 theme-shadow mb-5 rounded">
                    <div class="card-header border-0 rounded-top bg-secondary text-primary">
                        <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium cart-subtotal">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($subTotalPrice) }}</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Discount</h6>
                            <h6 class="font-weight-medium coupon-discount">0</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="fw-bold">Total</h5>
                            <h5 class="fw-bold grand-total">{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($subTotalPrice) }}</h5>
                        </div>
                        <form action="{{ route('checkout.index') }}" method="get">
                            <input type="hidden" name="couponId" id="CouponId">
                            <button type="submit" class="btn btn-block btn-primary my-3 py-2">Proceed To
                                Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->
@endsection
@push('scripts')
    <script>
        //let CouponId = null;
        $(document).ready(function() {
            function updateCartSubTotal() {
                let cartStotal = 0;
                $('.product-subtotal').each(function() {
                    let subTotalText = $(this).text().replace(/[^0-9.-]+/g, '');
                    cartStotal += parseFloat(subTotalText) || 0;
                });
                $('.cart-subtotal').text(siteCurrency + cartStotal.toLocaleString('en-IN'));
                $('.grand-total').text(siteCurrency + cartStotal.toLocaleString('en-IN'));
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

                $row.find('.product-subtotal').text(siteCurrency + subTotal);

                $input.val(quantity);

                updateCartSubTotal();

                $.ajax({
                    url: '{{ route('cart.update') }}',
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
                    url: '{{ route('cart.coupon.apply') }}',
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

            // Star rating script removed for cleaner UI
        })
    </script>
@endpush





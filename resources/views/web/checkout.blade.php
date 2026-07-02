@php
    $userEmail = auth('web')->user() ? auth('web')->user()->email : '';
@endphp
@extends('web.layouts.app')
@section('title', 'Checkout' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li><a class="nav-link p-0" href="{{ route('cart') }}">Cart</a></li>
                        <li>Checkout</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->
    <!-- Checkout Start -->
    <div class="container-fluid pt-2">
        <form action="{{ route('web.orders.store') }}" method="POST">
            @csrf
            <div class="row px-xl-5">
                <div class="col-lg-7">
                    <div class="mb-4">
                        <h4 class="font-weight-semi-bold mb-4">Billing Address</h4>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <x-input name="billing_name" label="Name" type="text" placeholder="John"
                                    :value="$billingAddress?->name" :required="true" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-input name="billing_mobile" label="Mobile No" type="text" placeholder="+123 456 789"
                                    :value="$billingAddress?->mobile" :required="true" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-input name="billing_email" label="E-mail" type="text" placeholder="example@email.com"
                                    :value="$userEmail" :required="true" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-select name="billing_division_id" label="Division" class="custom-select"
                                    id="billing_division_id" placeholder="Choose..." :options="$divisions" :value="old('billing_division_id', $billingAddress?->division_id)"
                                    :required="true" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-select name="billing_district_id" label="District" class="custom-select"
                                    id="billing_district_id" placeholder="Choose..." :options="[]" :value="old('billing_district_id', $billingAddress?->district_id)"
                                    :required="true" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-select name="billing_thana_id" label="Thana" class="custom-select" id="billing_thana_id"
                                    placeholder="Choose..." :options="[]" :value="old('billing_thana_id', $billingAddress?->thana_id)" :required="true" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-input label="Address" name="billing_address" :value="$billingAddress?->address" :required="true" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-input label="ZIP Code" name="billing_zip" :value="$billingAddress?->zip" :required="true" />
                            </div>
                            <div class="col-md-12 form-group">
                                <x-textarea label="Message" name="note" :value="old('note')" />
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="shipto" class="custom-control-input" id="shipto"
                                        value="1" {{ old('shipto') ? 'checked' : '' }}>
                                    <label class="custom-control-label {{ old('shipto') ? '' : 'collapsed' }}"
                                        for="shipto" data-toggle="collapse" data-target="#shipping-address"
                                        aria-expanded="{{ old('shipto') ? 'true' : 'false' }}">Ship to different
                                        address</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="collapse mb-4 {{ old('shipto') ? 'show' : '' }}" id="shipping-address">
                        <h4 class="font-weight-semi-bold mb-4">Shipping Address</h4>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <x-input label="Name" id="shipping_name" name="shipping_name" :value="$shippingAddress?->name" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-input label="Mobile No" type="text" id="shipping_mobile" name="shipping_mobile"
                                    :value="$shippingAddress?->mobile" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-input label="E-mail" type="email" id="shipping_email" name="shipping_email"
                                    :value="$userEmail" placeholder="example@email.com" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-select name="shipping_division_id" label="Division" class="custom-select"
                                    id="shipping_division_id" placeholder="Choose..." :options="$divisions"
                                    :value="old('shipping_division_id', $shippingAddress?->division_id)" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-select name="shipping_district_id" label="District" class="custom-select"
                                    id="shipping_district_id" placeholder="Choose..." :options="[]"
                                    :value="old('shipping_district_id', $shippingAddress?->district_id)" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-select name="shipping_thana_id" label="Thana" class="custom-select"
                                    id="shipping_thana_id" placeholder="Choose..." :options="[]"
                                    :value="old('shipping_thana_id', $shippingAddress?->thana_id)" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-input label="Address" id="shipping_address" name="shipping_address"
                                    :value="$shippingAddress?->address" />
                            </div>
                            <div class="col-md-6 form-group">
                                <x-input label="ZIP Code" id="shipping_zip" name="shipping_zip" :value="$shippingAddress?->zip" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card border-secondary mb-5">
                        <div class="card-header bg-secondary border-0">
                            <h4 class="font-weight-semi-bold m-0">Order Total</h4>
                        </div>
                        <div class="card-body">
                            <h5 class="font-weight-medium mb-3">Products</h5>
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th class="text-right" scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($validItems ?? [] as $item)
                                        <tr>
                                            <td>
                                                <p title="{{ $item?->product?->name }}">
                                                    {{ Str::limit($item?->product?->name, 20) }}</p>
                                                <input type="text" name="cart_ids[]" value="{{ $item?->id }}"
                                                    hidden>
                                            </td>
                                            <td class="d-flex">
                                                <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                                <span class="cart-item-price">{{ formatBDT($item?->cart_price) }}</span>
                                                <span class="text-muted"> x {{ $item?->quantity }}</span>
                                            </td>
                                            <td class="text-right">
                                                <span>{{ ($siteSettings->currency_symbol ?? null) }}{{ formatBDT($item?->cart_price * $item?->quantity) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr class="mt-0">
                            <div class="d-flex justify-content-between mb-3 pt-1">
                                <h6 class="font-weight-medium">Subtotal</h6>
                                <h6 class="font-weight-medium">
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                    <span class="cart-subtotal">{{ formatBDT($subTotalPrice) }}</span>
                                </h6>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <input type="text" name="coupon_code" value="{{ $coupon?->id }}" hidden>
                                <h6 class="font-weight-medium">Discount</h6>
                                <h6 class="font-weight-medium">
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                    <span class="coupon-discount">{{ formatBDT($couponDiscount) }}</span>
                                </h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="font-weight-medium">Shipping</h6>
                                <h6 class="font-weight-medium">
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                    <span class="shipping-charge"></span>
                                </h6>
                            </div>
                        </div>
                        <div class="card-footer border-secondary bg-transparent">
                            <div class="d-flex justify-content-between mt-2">
                                <h5 class="font-weight-bold">Total</h5>
                                <h5 class="font-weight-bold">
                                    <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                    <span class="total-price">{{ formatBDT($totalPrice) }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card border-secondary mb-5">
                        <div class="card-header bg-secondary border-0">
                            <h4 class="font-weight-semi-bold m-0">Shipping Cost</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                @foreach ($shippingCost ?? [] as $shipping)
                                    <div class="custom-control custom-radio d-flex justify-content-between">
                                        <div class="{{ !$loop->last ? 'mb-2' : '' }}">
                                            <input type="radio" class="custom-control-input shipping-input"
                                                name="shipping_charge" id="shipping_{{ $shipping->id }}"
                                                value="{{ $shipping->id }}" data-price="{{ $shipping->price }}" {{ $loop->first ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="shipping_{{ $shipping->id }}">{{ $shipping->location }}</label>
                                        </div>
                                        <div>
                                            <span class="currency">{{ ($siteSettings->currency_symbol ?? null) }}</span>
                                            <span class="shipping-cost">{{ $shipping->price }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                @error('shipping_charge')
                                    <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card border-secondary mb-5">
                        <div class="card-header bg-secondary border-0">
                            <h4 class="font-weight-semi-bold m-0">Payment</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment" value="paypal"
                                        id="paypal">
                                    <label class="custom-control-label" for="paypal">Paypal</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment" value="stripe"
                                        id="stripe">
                                    <label class="custom-control-label" for="stripe">Stripe</label>
                                </div>
                            </div>
                            <div class="">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment"
                                        value="cashOnDelivery" id="cashOnDelivery">
                                    <label class="custom-control-label" for="cashOnDelivery">Cash on Delivery</label>
                                </div>
                            </div>
                            @error('payment')
                                <span class="text-danger mt-2 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="card-footer border-secondary bg-transparent">
                            <button type="submit"
                                class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Place
                                Order</button>
                        </div>
                    </div> --}}

                    <div class="card border-secondary mb-5">
                        <div class="card-header bg-secondary border-0">
                            <h4 class="font-weight-semi-bold m-0">Payment</h4>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment" value="sslcommerz"
                                        id="sslcommerz">
                                    <label class="custom-control-label" for="sslcommerz">SSLCommerz (Mobile Banking /
                                        Cards)</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment" value="stripe"
                                        id="stripe">
                                    <label class="custom-control-label" for="stripe">Stripe</label>
                                </div>
                            </div>

                            <div class="">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" name="payment"
                                        value="cashOnDelivery" id="cashOnDelivery">
                                    <label class="custom-control-label" for="cashOnDelivery">Cash on Delivery</label>
                                </div>
                            </div>

                            @error('payment')
                                <span class="text-danger mt-2 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="card-footer border-secondary bg-transparent">
                            <button type="submit"
                                class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Place Order</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <!-- Checkout End -->
@endsection
@push('styles')
    <style>
        .form-group label {
            font-weight: 400 !important;
        }
    </style>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            const shippingData = @json($shippingAddress);
            const billingData = @json($billingAddress);

            $('#shipping_division_id').on('change', function() {
                let division_id = $(this).val();
                let district_select = $('#shipping_district_id');
                let thana_select = $('#shipping_thana_id');

                district_select.html('<option selected disabled>Loading...</option>');
                thana_select.html('<option selected disabled>Choose...</option>');

                if (division_id) {
                    $.ajax({
                        url: "{{ route('admin.location.district.view') }}",
                        type: "GET",
                        data: {
                            division_id: division_id
                        },
                        success: function(result) {
                            let html = '<option selected disabled>Choose...</option>';
                            $.each(result, function(key, value) {
                                let selected = (shippingData && shippingData
                                    .district_id == value
                                    .id) ? 'selected' : '';
                                html +=
                                    `<option value="${value.id}" ${selected}>${value.name}</option>`;
                            });
                            district_select.html(html);
                            district_select.trigger('change');
                        }
                    });
                }
            });

            $('#shipping_district_id').on('change', function() {
                let district_id = $(this).val();
                let thana_select = $('#shipping_thana_id');

                if (district_id && district_id !== 'Choose...') {
                    thana_select.html('<option selected disabled>Loading...</option>');
                    $.ajax({
                        url: "{{ route('admin.location.thana.view') }}",
                        type: "GET",
                        data: {
                            district_id: district_id
                        },
                        success: function(result) {
                            let html = '<option selected disabled>Choose...</option>';
                            $.each(result, function(key, value) {
                                let selected = (shippingData && shippingData.thana_id ==
                                        value.id) ?
                                    'selected' : '';
                                html +=
                                    `<option value="${value.id}" ${selected}>${value.name}</option>`;
                            });
                            thana_select.html(html);
                        }
                    });
                }
            });

            $('#billing_division_id').on('change', function() {
                let division_id = $(this).val();
                let district_select = $('#billing_district_id');
                let thana_select = $('#billing_thana_id');

                district_select.html('<option selected disabled>Loading...</option>');
                if (division_id) {
                    $.ajax({
                        url: "{{ route('admin.location.district.view') }}",
                        type: "GET",
                        data: {
                            division_id: division_id
                        },
                        success: function(result) {
                            let html = '<option selected disabled>Choose...</option>';
                            $.each(result, function(key, value) {
                                let selected = (billingData && billingData
                                    .district_id == value
                                    .id) ? 'selected' : '';
                                html +=
                                    `<option value="${value.id}" ${selected}>${value.name}</option>`;
                            });
                            district_select.html(html);
                            district_select.trigger('change');
                        }
                    });
                }
            });

            $('#billing_district_id').on('change', function() {
                let district_id = $(this).val();
                let thana_select = $('#billing_thana_id');

                if (district_id && district_id !== 'Choose...') {
                    thana_select.html('<option selected disabled>Loading...</option>');
                    $.ajax({
                        url: "{{ route('admin.location.thana.view') }}",
                        type: "GET",
                        data: {
                            district_id: district_id
                        },
                        success: function(result) {
                            let html = '<option selected disabled>Choose...</option>';
                            $.each(result, function(key, value) {
                                let selected = (billingData && billingData.thana_id ==
                                        value.id) ?
                                    'selected' : '';
                                html +=
                                    `<option value="${value.id}" ${selected}>${value.name}</option>`;
                            });
                            thana_select.html(html);
                        }
                    });
                }
            });

            if ($('#shipping_division_id').val()) {
                $('#shipping_division_id').trigger('change');
            }
            if ($('#billing_division_id').val()) {
                $('#billing_division_id').trigger('change');
            }

            updatePricing();

            $('.shipping-input').on('change', function() {
                updatePricing();
            });

            function updatePricing() {
                let shippingPrice = parseFloat($('.shipping-input:checked').data('price')) || 0;

                let baseTotal = parseFloat('{{ $totalPrice }}');

                $('.shipping-charge').text(shippingPrice);

                let finalTotal = baseTotal + shippingPrice;
                $('.total-price').text(finalTotal.toLocaleString('en-IN'));
            }

        });
    </script>
@endpush


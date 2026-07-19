@php
    $userEmail = auth('web')->user() ? auth('web')->user()->email : '';
@endphp
@extends('web.layouts.app')
@section('title', 'Checkout' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    @include('web.components.breadcrumb', ['title' => 'Checkout'])
    <!-- Page Header End -->
    <!-- Checkout Start -->
    <div class="container pt-1">
        <form action="{{ route('web.orders.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-xl-8 col-lg-12 address_section">
                    <div class="row">
                        <!-- Billing Address -->
                        <div class="col-md-12 mb-4">
                            <div class="card p-0 border-0 rounded checkout-shadow">
                                <div class="card-header text-dark rounded-top border-0 mb-2 py-3">
                                    <h5 class="font-weight-semi-bold mb-0">Billing Address</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-input name="billing_name" id="billing_name" label="Name" type="text"
                                                placeholder="John" :value="$billingAddress?->name" :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input name="billing_mobile" id="billing_mobile" label="Mobile No"
                                                type="text" placeholder="+123 456 789" :value="$billingAddress?->mobile"
                                                :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input name="billing_email" id="billing_email" label="E-mail" type="text"
                                                placeholder="example@email.com" :value="$userEmail" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-select name="billing_country_id" label="Country" class="custom-select"
                                                id="billing_country_id" placeholder="Choose..." :options="$countries"
                                                :value="old('billing_country_id', $billingAddress?->country_id)" :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-select name="billing_state_id" label="State" class="custom-select"
                                                id="billing_state_id" placeholder="Choose..." :options="[]"
                                                :value="old('billing_state_id', $billingAddress?->state_id)" :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input name="billing_city" id="billing_city" label="City" type="text"
                                                placeholder="e.g. Dhaka" :value="old('billing_city', $billingAddress?->city)" :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="ZIP Code" id="billing_zip" name="billing_zip"
                                                :value="$billingAddress?->zip" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="Address" id="billing_address" name="billing_address"
                                                :value="$billingAddress?->address" :required="true" />
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <x-textarea label="Message" id="note" name="note" :value="old('note')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="col-md-12 mb-4">
                            <div class="card p-0 border-0 rounded checkout-shadow">
                                <div class="card-header text-dark border-0 rounded-top mb-2 py-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="font-weight-semi-bold mb-0">Shipping Address</h5>
                                        <div>
                                            <input type="hidden" name="shipto" id="shipto"
                                                value="{{ old('shipto', '0') }}">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="same_as_billing"
                                                    {{ old('shipto', '0') == '0' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="same_as_billing">Same as</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row" id="shipping-form-wrapper">
                                        <div class="col-md-6 mb-3">
                                            <x-input label="Name" id="shipping_name" name="shipping_name"
                                                :value="$shippingAddress?->name" :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="Mobile No" type="text" id="shipping_mobile"
                                                name="shipping_mobile" :value="$shippingAddress?->mobile" :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="E-mail" type="email" id="shipping_email" name="shipping_email"
                                                :value="$userEmail" placeholder="example@email.com" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-select name="shipping_country_id" label="Country" class="custom-select"
                                                id="shipping_country_id" placeholder="Choose..." :options="$countries"
                                                :value="old('shipping_country_id', $shippingAddress?->country_id)" :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-select name="shipping_state_id" label="State" class="custom-select"
                                                id="shipping_state_id" placeholder="Choose..." :options="[]"
                                                :value="old('shipping_state_id', $shippingAddress?->state_id)" :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input name="shipping_city" id="shipping_city" label="City"
                                                type="text" placeholder="e.g. Dhaka" :value="old('shipping_city', $shippingAddress?->city)"
                                                :required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="ZIP Code" id="shipping_zip" name="shipping_zip"
                                                :value="$shippingAddress?->zip" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="Address" id="shipping_address" name="shipping_address"
                                                :value="$shippingAddress?->address" :required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="card border-0 checkout-shadow mb-5 rounded">
                        <div class="card-header border-0 rounded-top text-dark py-3">
                            <h5 class="font-weight-semi-bold m-0">Order Total</h5>
                        </div>
                        <div class="card-body">
                            <h5 class="font-weight-medium mb-3">Products</h5>
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th class="text-end" scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($validItems ?? [] as $item)
                                        <tr>
                                            <td>
                                                <p class="mb-0" title="{{ $item?->product?->name }}">
                                                    {{ Str::limit($item?->product?->name, 20) }}</p>
                                                <input type="text" name="cart_ids[]" value="{{ $item?->id }}"
                                                    hidden>
                                            </td>
                                            <td class="d-flex">
                                                <span class="currency">{{ $siteSettings->currency_symbol ?? null }}</span>
                                                <span class="cart-item-price">{{ formatBDT($item?->cart_price) }}</span>
                                                <span class="text-muted"> x {{ $item?->quantity }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span>{{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($item?->cart_price * $item?->quantity) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr class="mt-0">
                            <div class="d-flex justify-content-between mb-3 pt-1">
                                <h6 class="font-weight-medium">Subtotal</h6>
                                <h6 class="font-weight-medium">
                                    <span class="currency">{{ $siteSettings->currency_symbol ?? null }}</span>
                                    <span class="cart-subtotal">{{ formatBDT($subTotalPrice) }}</span>
                                </h6>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <input type="text" name="coupon_code" value="{{ $coupon?->id }}" hidden>
                                <h6 class="font-weight-medium">Discount</h6>
                                <h6 class="font-weight-medium">
                                    <span class="currency">{{ $siteSettings->currency_symbol ?? null }}</span>
                                    <span class="coupon-discount">{{ formatBDT($couponDiscount) }}</span>
                                </h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="font-weight-medium">Shipping</h6>
                                <h6 class="font-weight-medium">
                                    <span class="currency">{{ $siteSettings->currency_symbol ?? null }}</span>
                                    <span class="shipping-charge"></span>
                                </h6>
                            </div>
                            @error('shipping_charge')
                                <div class="text-danger small text-end mt-1">Please select a state to calculate shipping
                                    cost.</div>
                            @enderror
                        </div>
                        <div class="card-footer border-0 bg-transparent pt-3 pb-4">
                            <div class="d-flex justify-content-between mt-2">
                                <h5 class="fw-bold text-primary">Total</h5>
                                <h5 class="fw-bold text-primary">
                                    <span class="currency">{{ $siteSettings->currency_symbol ?? null }}</span>
                                    <span class="total-price">{{ formatBDT($totalPrice) }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="shipping_charge" id="hidden_shipping_charge" value="">

                    <div id="payment-timeout-warning" class="alert alert-warning d-none"
                        style="display: none; border-left: 4px solid #ffc107;">
                        <strong><i class="fas fa-exclamation-triangle mr-1"></i> Attention:</strong> Please complete your
                        payment within 30 minutes of placing the order. Otherwise, your order will be automatically
                        canceled.
                    </div>

                    <div class="card border-0 checkout-shadow mb-5 rounded">
                        <div class="card-header border-0 rounded-top text-dark py-3">
                            <h5 class="font-weight-semi-bold m-0">Payment</h5>
                        </div>
                        <div class="card-body">

                            @if (isset($siteSettings->payment_sslcommerz_status) && $siteSettings->payment_sslcommerz_status == '1')
                                <div class="mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="payment"
                                            value="sslcommerz" id="sslcommerz">
                                        <label class="custom-control-label" for="sslcommerz">SSLCommerz (Mobile Banking /
                                            Cards)</label>
                                    </div>
                                </div>
                            @endif

                            @if (isset($siteSettings->payment_stripe_status) && $siteSettings->payment_stripe_status == '1')
                                <div class="mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="payment" value="stripe"
                                            id="stripe">
                                        <label class="custom-control-label" for="stripe">Stripe</label>
                                    </div>
                                </div>
                            @endif

                            @if (isset($siteSettings->payment_cod_status) && $siteSettings->payment_cod_status == '1')
                                <div class="mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="payment"
                                            value="cashOnDelivery" id="cashOnDelivery">
                                        <label class="custom-control-label" for="cashOnDelivery">Cash on Delivery</label>
                                    </div>
                                </div>
                            @endif

                            @if (isset($siteSettings->payment_manual_status) && $siteSettings->payment_manual_status == '1')
                                <div class="mb-3">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="payment" value="manual"
                                            id="manualPayment">
                                        <label class="custom-control-label" for="manualPayment">Manual Payment</label>
                                    </div>
                                    <div class="manual-payment-info mt-3 p-3 bg-light border rounded"
                                        style="display: none;">
                                        <p class="mb-2 text-dark fw-bold">{!! nl2br(e($siteSettings->payment_manual_instruction ?? '')) !!}</p>

                                        <div class="mb-2">
                                            <input type="text" name="sender_number" id="sender_number"
                                                class="form-control"
                                                placeholder="Sender Account Number (e.g. 017XXXXXXXX)">
                                            @error('sender_number')
                                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <input type="text" name="transaction_id" id="transaction_id"
                                                class="form-control" placeholder="Enter Transaction ID (TrxID)">
                                            @error('transaction_id')
                                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @error('payment')
                                <span class="text-danger mt-2 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="card-footer border-0 bg-transparent pt-3 pb-4">
                            <button type="submit"
                                class="btn btn-md btn-block btn-primary checkout-shadow rounded my-3 py-3 w-100 fw-bold fs-5">Place
                                Order</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <!-- Checkout End -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const paymentRadios = document.querySelectorAll('input[name="payment"]');
                const timeoutWarning = document.getElementById('payment-timeout-warning');

                function toggleWarning() {
                    const selectedPayment = document.querySelector('input[name="payment"]:checked');
                    
                    if (selectedPayment && (selectedPayment.value === 'stripe' || selectedPayment.value === 'sslcommerz')) {
                        $(timeoutWarning).removeClass('d-none').slideDown(300);
                    } else {
                        $(timeoutWarning).slideUp(300, function() {
                            $(this).addClass('d-none');
                        });
                    }
                }

                paymentRadios.forEach(radio => {
                    radio.addEventListener('change', toggleWarning);
                });

                // Initial check on load
                toggleWarning();
            });
        </script>
    @endpush
@endsection
@push('styles')
    <style>
        /* Modern Input Styling for Checkout Forms */
        .card-body .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: color-mix(in srgb, var(--primary) 60%, #111);
            margin-bottom: 8px;
        }

        .card-body .form-control,
        .card-body .custom-select,
        .card-body .form-select {
            border-radius: 12px !important;
            padding: 12px 20px !important;
            border: 1px solid color-mix(in srgb, var(--primary) 20%, #ccc) !important;
            background-color: #fcfcfc !important;
            transition: all 0.3s ease;
        }

        .card-body .form-control:focus,
        .card-body .custom-select:focus,
        .card-body .form-select:focus {
            background-color: white !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 15%, transparent) !important;
            outline: none !important;
        }

        /* Custom shadow for checkout cards without hover effect */
        .checkout-shadow {
            border: none !important;
            box-shadow: 0 4px 15px color-mix(in srgb, var(--primary) 10%, transparent) !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            const shippingData = @json($shippingAddress);
            const billingData = @json($billingAddress);

            function toggleShippingFields(readOnly) {
                $('#shipping-form-wrapper input, #shipping-form-wrapper select').prop('readonly', readOnly);
                // For selects to be truly read-only without being disabled (disabled fields don't submit), 
                // we can just make them pointer-events: none.
                if (readOnly) {
                    $('#shipping-form-wrapper select').css('pointer-events', 'none');
                } else {
                    $('#shipping-form-wrapper select').css('pointer-events', 'auto');
                }
            }

            function syncBillingToShipping() {
                if ($('#same_as_billing').is(':checked')) {
                    $('#shipping_name').val($('#billing_name').val());
                    $('#shipping_mobile').val($('#billing_mobile').val());
                    $('#shipping_email').val($('#billing_email').val());
                    $('#shipping_country_id').val($('#billing_country_id').val());

                    // We need to copy state options as well if it was loaded
                    $('#shipping_state_id').html($('#billing_state_id').html());
                    $('#shipping_state_id').val($('#billing_state_id').val());

                    $('#shipping_city').val($('#billing_city').val());
                    $('#shipping_address').val($('#billing_address').val());
                    $('#shipping_zip').val($('#billing_zip').val());
                }
            }

            $('#same_as_billing').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#shipto').val('0');
                    toggleShippingFields(true);
                    syncBillingToShipping();
                } else {
                    $('#shipto').val('1');
                    toggleShippingFields(false);
                    // Clear the fields so user can type
                    $('#shipping-form-wrapper input').val('');
                    $('#shipping_state_id').html('<option selected disabled>Choose...</option>');
                    $('#shipping_country_id').val('');
                }
                updatePricing();
            });

            // If billing fields change while same_as_billing is checked, sync them
            $('#billing_name, #billing_mobile, #billing_email, #billing_city, #billing_address, #billing_zip').on(
                'input',
                function() {
                    syncBillingToShipping();
                });
            $('#billing_country_id, #billing_state_id').on('change', function() {
                syncBillingToShipping();
                updatePricing();
            });
            $('#shipping_state_id').on('change', function() {
                updatePricing();
            });

            function loadStates(country_id, state_select, preSelectedData, isBilling) {
                state_select.html('<option selected disabled>Loading...</option>');
                if (country_id) {
                    $.ajax({
                        url: "/locations/states/" + country_id,
                        type: "GET",
                        success: function(result) {
                            let html =
                                '<option selected disabled data-price="0" data-cost-id="">Choose...</option>';
                            $.each(result, function(key, value) {
                                let selected = (preSelectedData && preSelectedData.state_id ==
                                    value.id) ? 'selected' : '';
                                let price = value.shipping_cost ? value.shipping_cost.price : 0;
                                let cost_id = value.shipping_cost ? value.shipping_cost.id : '';
                                html +=
                                    `<option value="${value.id}" data-price="${price}" data-cost-id="${cost_id}" ${selected}>${value.name}</option>`;
                            });
                            state_select.html(html);

                            // If this was billing and checkbox is checked, sync to shipping immediately
                            if (isBilling && $('#same_as_billing').is(':checked')) {
                                syncBillingToShipping();
                            }
                            updatePricing();
                        }
                    });
                }
            }

            $('#billing_country_id').on('change', function() {
                loadStates($(this).val(), $('#billing_state_id'), billingData, true);
            });

            $('#shipping_country_id').on('change', function() {
                // If checkbox is checked, we shouldn't trigger independent shipping loads
                if (!$('#same_as_billing').is(':checked')) {
                    loadStates($(this).val(), $('#shipping_state_id'), shippingData, false);
                }
            });

            function updatePricing() {
                let targetSelect = $('#same_as_billing').is(':checked') ? $('#billing_state_id') : $(
                    '#shipping_state_id');
                let selectedOption = targetSelect.find('option:selected');

                let shippingPrice = parseFloat(selectedOption.attr('data-price')) || 0;
                let shippingCostId = selectedOption.attr('data-cost-id') || '';

                $('#hidden_shipping_charge').val(shippingCostId);

                let baseTotal = parseFloat('{{ $totalPrice }}');
                $('.shipping-charge').text(shippingPrice);

                let finalTotal = baseTotal + shippingPrice;
                $('.total-price').text(finalTotal.toLocaleString('en-IN'));
            }

            // Initialize
            if ($('#same_as_billing').is(':checked')) {
                $('#shipto').val('0');
                toggleShippingFields(true);
            } else {
                $('#shipto').val('1');
                toggleShippingFields(false);
            }

            if ($('#billing_country_id').val()) {
                loadStates($('#billing_country_id').val(), $('#billing_state_id'), billingData, true);
            }
            if ($('#shipping_country_id').val() && !$('#same_as_billing').is(':checked')) {
                loadStates($('#shipping_country_id').val(), $('#shipping_state_id'), shippingData, false);
            }

            // Manual Payment Toggle
            $('input[name="payment"]').on('change', function() {
                if ($(this).val() === 'manual') {
                    $('.manual-payment-info').slideDown();
                } else {
                    $('.manual-payment-info').slideUp();
                }
            });

            if ($('input[name="payment"]:checked').val() === 'manual') {
                $('.manual-payment-info').show();
            }

            updatePricing();
        });
    </script>
@endpush

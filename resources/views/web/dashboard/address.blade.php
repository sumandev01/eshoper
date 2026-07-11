@extends('web.layouts.app')
@section('title', 'Address' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'User Dashboard', 'url' => route('user.dashboard')],
            ['name' => 'Address', 'url' => '']
        ]
    ])
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container pt-1">
        <div class="row">
            @include('web.dashboard.sidebar')
            <div class="col-lg-9 mb-2">
                <div class="dash-card p-4">
                    <div class="card-header bg-transparent py-3 border-bottom-0 rounded-top" style="border-radius: 12px 12px 0 0;">
                        <h5 class="mb-0 font-weight-semi-bold" style="color: color-mix(in srgb, var(--primary) 60%, #111); font-size: 1.2rem;">Address Book</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.address.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">Shipping Address</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-input label="Name" name="shipping_name" :value="old('shipping_name') ?? $shippingAddress?->name" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="Mobile No" name="shipping_mobile" :value="old('shipping_mobile') ?? $shippingAddress?->mobile" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-select label="Country" name="shipping_country_id" id="shipping_country_id" class="custom-select" placeholder="Choose..." :options="$countries" :value="old('shipping_country_id') ?? $shippingAddress?->country_id" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-select label="State/Province" name="shipping_state_id" id="shipping_state_id" class="custom-select" placeholder="Choose..." :options="[]" :value="old('shipping_state_id') ?? $shippingAddress?->state_id" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="City" name="shipping_city" :value="old('shipping_city') ?? $shippingAddress?->city" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input label="ZIP Code" name="shipping_zip" :value="old('shipping_zip') ?? $shippingAddress?->zip" placeholder="123" />
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <x-input label="Address" name="shipping_address" :value="old('shipping_address') ?? $shippingAddress?->address" />
                                        </div>
                                        <div class="col-md-12 mb-3 mt-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" value="1"
                                                    name="billingToDifferent" id="billingToDifferent"
                                                    {{ $billingAddress?->address_default == '1' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="billingToDifferent"
                                                    data-bs-toggle="collapse" data-bs-target="#shipping-address">Billing to
                                                    different address</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="collapse" id="shipping-address">
                                        <hr class="my-4">
                                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">Billing Address</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <x-input label="Name" name="billing_name" :value="old('billing_name') ?? $billingAddress?->name" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <x-input label="Mobile No" name="billing_mobile" :value="old('billing_mobile') ?? $billingAddress?->mobile" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <x-select label="Country" name="billing_country_id" id="billing_country_id" class="custom-select" placeholder="Choose..." :options="$countries" :value="old('billing_country_id') ?? $billingAddress?->country_id" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <x-select label="State/Province" name="billing_state_id" id="billing_state_id" class="custom-select" placeholder="Choose..." :options="[]" :value="old('billing_state_id') ?? $billingAddress?->state_id" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <x-input label="City" name="billing_city" :value="old('billing_city') ?? $billingAddress?->city" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <x-input label="ZIP Code" name="billing_zip" :value="old('billing_zip') ?? $billingAddress?->zip" placeholder="123" />
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <x-input label="Address" name="billing_address" :value="old('billing_address') ?? $billingAddress?->address" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-primary theme-shadow transition-all hover-up px-4 py-2" style="border-radius: 8px; font-weight: 500;">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->
@endsection
@push('styles')
    <style>
        .mb-3 label {
            font-weight: 400 !important;
        }

        /* Modern Input Styling for Address Forms */
        .card-body .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: color-mix(in srgb, var(--primary) 60%, #111);
            margin-bottom: 8px;
        }

        .card-body .form-control, .card-body .custom-select, .card-body .form-select {
            border-radius: 12px !important;
            padding: 12px 20px !important;
            border: 1px solid color-mix(in srgb, var(--primary) 20%, #ccc) !important;
            background-color: #fcfcfc !important;
            transition: all 0.3s ease;
        }

        .card-body .form-control:focus, .card-body .custom-select:focus, .card-body .form-select:focus {
            background-color: white !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 15%, transparent) !important;
            outline: none !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        const shippingData = @json($shippingAddress);
        const billingData = @json($billingAddress);

        $('#shipping_country_id').on('change', function() {
            let country_id = $(this).val();
            let state_select = $('#shipping_state_id');

            state_select.html('<option selected disabled>Loading...</option>');

            if (country_id) {
                $.ajax({
                    url: "/locations/states/" + country_id,
                    type: "GET",
                    success: function(result) {
                        let html = '<option selected disabled>Choose...</option>';
                        $.each(result, function(key, value) {
                            let selected = (shippingData && shippingData.state_id == value.id) ? 'selected' : '';
                            html += `<option value="${value.id}" ${selected}>${value.name}</option>`;
                        });
                        state_select.html(html);
                    }
                });
            }
        });

        $('#billing_country_id').on('change', function() {
            let country_id = $(this).val();
            let state_select = $('#billing_state_id');

            state_select.html('<option selected disabled>Loading...</option>');

            if (country_id) {
                $.ajax({
                    url: "/locations/states/" + country_id,
                    type: "GET",
                    success: function(result) {
                        let html = '<option selected disabled>Choose...</option>';
                        $.each(result, function(key, value) {
                            let selected = (billingData && billingData.state_id == value.id) ? 'selected' : '';
                            html += `<option value="${value.id}" ${selected}>${value.name}</option>`;
                        });
                        state_select.html(html);
                    }
                });
            }
        });

        function toggleBillingAddress() {
            if ($('#billingToDifferent').is(':checked')) {
                $('#shipping-address').collapse('show');
            } else {
                $('#shipping-address').collapse('hide');
            }
        }

        $('#billingToDifferent').on('change', function() {
            toggleBillingAddress();
        });

        $(document).ready(function() {
            toggleBillingAddress();

            if ($('#shipping_country_id').val()) {
                $('#shipping_country_id').trigger('change');
            }
            if ($('#billing_country_id').val()) {
                $('#billing_country_id').trigger('change');
            }
        });
    </script>
@endpush




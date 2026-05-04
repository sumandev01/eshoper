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
            <div class="col-lg-9 mb-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('user.updateAddress') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="section-title px-5"><span class="px-2">Shipping Address</span></h4>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <x-input label="Name" name="shipping_name" :value="old('shipping_name') ?? $shippingAddress?->name" />
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <x-input label="Mobile No" name="shipping_mobile" :value="old('shipping_mobile') ?? $shippingAddress?->mobile" />
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Division</label>
                                            <select class="custom-select" name="shipping_division_id"
                                                id="shipping_division_id">
                                                <option selected disabled>Choose...</option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}"
                                                        {{ $shippingAddress?->division_id == $division->id ? 'selected' : '' }}>
                                                        {{ $division->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>District</label>
                                            <select class="custom-select" name="shipping_district_id"
                                                id="shipping_district_id">
                                                <option selected disabled>Choose...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Thana</label>
                                            <select class="custom-select" name="shipping_thana_id" id="shipping_thana_id">
                                                <option selected disabled>Choose...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <x-input label="Address" name="shipping_address" :value="old('shipping_address') ?? $shippingAddress?->address" />
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>ZIP Code</label>
                                            <input class="form-control" name="shipping_zip"
                                                value="{{ old('shipping_zip') ?? $shippingAddress?->zip }}" type="text"
                                                placeholder="123">
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" value="1"
                                                    name="billingToDifferent" id="billingToDifferent"
                                                    {{ $billingAddress?->address_default == '1' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="billingToDifferent"
                                                    data-toggle="collapse" data-target="#shipping-address">Billing to
                                                    different address</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="collapse" id="shipping-address">
                                        <h4 class="section-title px-5"><span class="px-2">Billing Address</span></h4>
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <x-input label="Name" name="billing_name" :value="old('billing_name') ?? $billingAddress?->name" />
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <x-input label="Mobile No" name="billing_mobile" :value="old('billing_mobile') ?? $billingAddress?->mobile" />
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>Division</label>
                                                <select class="custom-select" name="billing_division_id"
                                                    id="billing_division_id">
                                                    <option selected disabled>Choose...</option>
                                                    @foreach ($divisions as $division)
                                                        <option value="{{ $division->id }}"
                                                            {{ $billingAddress?->division_id == $division->id ? 'selected' : '' }}>
                                                            {{ $division->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>District</label>
                                                <select class="custom-select" name="billing_district_id"
                                                    id="billing_district_id">
                                                    <option selected disabled>Choose...</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>Thana</label>
                                                <select class="custom-select" name="billing_thana_id" id="billing_thana_id">
                                                    <option selected disabled>Choose...</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <x-input label="Address" name="billing_address" :value="old('billing_address') ?? $billingAddress?->address" />
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>ZIP Code</label>
                                                <input class="form-control" name="billing_zip"
                                                    value="{{ old('billing_zip') ?? $billingAddress?->zip }}"
                                                    type="text" placeholder="123">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary text-white btn-md">Save Changes</button>
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
        .form-group label {
            font-weight: 400 !important;
        }
    </style>
@endpush
@push('script')
    <script>
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
                            let selected = (shippingData && shippingData.district_id == value
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
                            let selected = (shippingData && shippingData.thana_id == value.id) ?
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
                            let selected = (billingData && billingData.district_id == value
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
                            let selected = (billingData && billingData.thana_id == value.id) ?
                                'selected' : '';
                            html +=
                                `<option value="${value.id}" ${selected}>${value.name}</option>`;
                        });
                        thana_select.html(html);
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

            if ($('#shipping_division_id').val()) {
                $('#shipping_division_id').trigger('change');
            }
            if ($('#billing_division_id').val()) {
                $('#billing_division_id').trigger('change');
            }
        });
    </script>
@endpush

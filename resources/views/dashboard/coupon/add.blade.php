@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1 fw-semibold">Add New Coupon</h5>
                                <p class="text-muted small mb-0">Fill in the details to create a new coupon</p>
                            </div>
                            <a href="{{ route('coupon.index') }}"
                                class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                <i class="mdi mdi-arrow-left"></i>
                                <span>Back to List</span>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('coupon.store') }}" method="POST">
                            @csrf
                            {{-- Basic Info --}}
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted fw-semibold small mb-3 border-bottom pb-2">
                                    <i class="mdi mdi-tag-outline me-1"></i> Basic Information
                                </h6>
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <x-input label="Coupon Code" name="code" type="text"
                                            placeholder="Coupon Code" :required='true' />
                                    </div>
                                    <div class="col-lg-6">
                                        <x-select label="Type" id="type" class="text-capitalize" name="type"
                                            :required='true'>
                                            <option disabled selected>Select Type</option>
                                            @foreach ($couponTypeEnums ?? [] as $couponType)
                                                <option class="text-capitalize" value="{{ $couponType }}">
                                                    {{ $couponType }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                </div>
                            </div>

                            {{-- Discount Info --}}
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted fw-semibold small mb-3 border-bottom pb-2">
                                    <i class="mdi mdi-percent-outline me-1"></i> Discount Details
                                </h6>
                                <div class="row g-3">
                                    <div class="col-lg-6 amount-div">
                                        <x-input label="Amount" name="amount" type="number" placeholder="00"
                                            :required='true' />
                                        <span class="text-muted small type_info"></span>
                                    </div>
                                    <div class="col-lg-6">
                                        <x-input label="Min Order Amount" name="min_order_amount" type="number"
                                            placeholder="00" :required='true' />
                                    </div>
                                    <div class="col-lg-6">
                                        <x-input label="Max Discount" id="max_discount" name="max_discount" type="number"
                                            placeholder="00" :required='true' />
                                    </div>
                                    <div class="col-lg-6">
                                        <x-input label="Coupon Limit" name="usage_limit" type="number"
                                            placeholder="0" :required='true' />
                                    </div>
                                </div>
                            </div>

                            {{-- Validity --}}
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted fw-semibold small mb-3 border-bottom pb-2">
                                    <i class="mdi mdi-calendar-outline me-1"></i> Validity Period
                                </h6>
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <x-input label="Start Date" name="start_date" type="datetime-local" :required='false' />
                                    </div>
                                    <div class="col-lg-6">
                                        <x-input label="Expire Date" name="expire_date" type="datetime-local" :required='false' />
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted fw-semibold small mb-3 border-bottom pb-2">
                                    <i class="mdi mdi-toggle-switch-outline me-1"></i> Status
                                </h6>
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <x-select label="Status" name="status" :required='true'>
                                            <option value="0">Inactive</option>
                                            <option value="1" selected>Active</option>
                                        </x-select>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-3 d-flex justify-content-end gap-2">
                                <a href="{{ route('coupon.index') }}" class="btn btn-light px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="mdi mdi-content-save me-1"></i> Create Coupon
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .amount-div .form-group{
            margin-bottom: 0 !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#type').on('change', function() {
                let selectedType = $(this).val();
                let typeInfo = $('.type_info');
                
                if (selectedType === 'percentage') {
                    $('#max_discount').prop('disabled', false);
                    $('#max_discount').prop('required', true);
                    typeInfo.text('Note: Amount should be in percentage only.');
                } else {
                    $('#max_discount').prop('disabled', true);
                    $('#max_discount').prop('required', false);
                    $('#max_discount').val('');
                    typeInfo.text('Note: Amount should be in taka only.');
                }
            });
        })
    </script>
@endpush

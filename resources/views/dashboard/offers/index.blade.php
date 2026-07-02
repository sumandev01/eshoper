@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? 'E-Shopper') . ' - ' . 'Offers Settings')
@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-header py-4">
                <div class="page-title-box">
                    <h3 class="mb-0">Promotional Offers</h3>
                    <p class="text-muted fw-bold mt-2 mb-0">Manage your homepage offer sections</p>
                </div>
            </div>
            <div class="card-body py-3 px-md-4 px-2">

                <form action="{{ route('admin.settings.offers.update') }}" method="POST">
                    @csrf

                    <div class="container mt-4">
                        <!-- ================= Offer 1 Section ================= -->
                        <h4 class="text-primary mb-4 border-bottom pb-2">Offer 1 Details</h4>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Offer Title</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="offer1_title" class="fs-6" type="text" :value="$siteSettings->offer1_title ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-5 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Image</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-media-thumbnail button_label="Select Image" input_name="offer1_image" :existing_image="$offer1Image" :existing_id="$siteSettings->offer1_image ?? null"
                                    :target_id="'offer1_image_id'" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Offer Subtitle</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="offer1_subtitle" class="fs-6" type="text" :value="$siteSettings->offer1_subtitle ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Button Link</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="offer1_link" class="fs-6" type="text" :value="$siteSettings->offer1_link ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Status</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select name="offer1_status" class="form-select form-control fs-6"
                                        style="height: auto; padding: 0.5rem 1rem;">
                                        <option value="1"
                                            {{ isset($siteSettings->offer1_status) && $siteSettings->offer1_status == '1' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0"
                                            {{ isset($siteSettings->offer1_status) && $siteSettings->offer1_status == '0' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- ================= Offer 2 Section ================= -->
                        <h4 class="text-primary mb-4 mt-5 border-bottom pb-2">Offer 2 Details</h4>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Offer Title</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="offer2_title" class="fs-6" type="text" :value="$siteSettings->offer2_title ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Image</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-media-thumbnail button_label="Select Image" input_name="offer2_image" :existing_image="$offer2Image" :existing_id="$siteSettings->offer2_image ?? null"
                                    :target_id="'offer2_image_id'" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Offer Subtitle</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="offer2_subtitle" class="fs-6" type="text" :value="$siteSettings->offer2_subtitle ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Button Link</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <x-input name="offer2_link" class="fs-6" type="text" :value="$siteSettings->offer2_link ?? null"
                                    :required="false" />
                            </div>
                        </div>

                        <div class="row align-items-center mb-4 gy-3">
                            <div class="col-sm-3 col-10 text-start">
                                <label class="form-label mb-0">Status</label>
                            </div>
                            <div class="col-auto px-0 fs-5 fw-bold">:</div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <select name="offer2_status" class="form-select form-control fs-6"
                                        style="height: auto; padding: 0.5rem 1rem;">
                                        <option value="1"
                                            {{ isset($siteSettings->offer2_status) && $siteSettings->offer2_status == '1' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0"
                                            {{ isset($siteSettings->offer2_status) && $siteSettings->offer2_status == '0' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary px-4 py-3 mb-2 mt-4">
                            <i class="mdi mdi-content-save me-1"></i> Save Offers
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-group {
            margin-bottom: 0 !important;
            width: 100%;
            margin-left: 10px;
        }

        [id^="media-preview-"] {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
        }

        .preview-image-wrapper,
        .imagePreviewSingle {
            width: 500px !important;
            aspect-ratio: 4/2 !important;
            padding-left: 0 !important;
            margin-bottom: 0 !important;
        }

        @media (max-width: 767px) {
            .form-group {
                margin-left: 0 !important;
            }
        }
    </style>
@endpush

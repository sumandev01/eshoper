@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? 'E-Shopper') . ' - ' . 'Banner Offers')
@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.settings.offers.update') }}" method="POST">
            @csrf
            <div class="card shadow-sm mb-2 border-0">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <div class="page-title-box">
                        <h4 class="mb-0">Banner Offers</h4>
                        <p class="text-muted fw-bold mb-0">Manage your homepage banner offer sections</p>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary px-4 py-3">
                            <i class="mdi mdi-content-save me-1"></i> Save Offers
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <!-- ================= Offer 1 Section ================= -->
                    <div class="card border mb-4 shadow-sm">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0 text-primary fw-bold"><i class="mdi mdi-gift-outline me-2"></i> Offer 1
                                Details</h5>
                        </div>

                        <div class="card-body px-4 py-4">

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-sm-3 col-10 text-start">
                                    <label class="form-label mb-0">Offer Title</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="offer1_title" class="fs-6 rounded" type="text" :value="$offer1->title ?? null"
                                        :required="false" />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-sm-3 col-10 text-start">
                                    <label class="form-label mb-0">Image</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    @php $offer1Image = $offer1->image ? \Illuminate\Support\Facades\Storage::url($offer1->image->src) : asset('default.webp'); @endphp
                                    <x-media-thumbnail button_label="Select Image" input_name="offer1_image"
                                        :existing_image="$offer1Image" :existing_id="$offer1->image_id ?? null" :target_id="'offer1_image_id'" />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-sm-3 col-10 text-start">
                                    <label class="form-label mb-0">Offer Subtitle</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="offer1_subtitle" class="fs-6 rounded" type="text" :value="$offer1->subtitle ?? null"
                                        :required="false" />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-sm-3 col-10 text-start">
                                    <label class="form-label mb-0">Button Link</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-link-selector prefix="offer1" :selectedType="$offer1->link_type ?? ''" :selectedRef="$offer1->link_ref_id ?? ''" />
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
                                                {{ isset($offer1->status) && $offer1->status == '1' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="0"
                                                {{ isset($offer1->status) && $offer1->status == '0' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- ================= Offer 2 Section ================= -->
                    <div class="card border mb-4 shadow-sm">
                        <div class="card-header bg-light py-3">
                            <h5 class="mb-0 text-primary fw-bold"><i class="mdi mdi-gift-outline me-2"></i> Offer 2
                                Details</h5>
                        </div>
                        <div class="card-body px-4 py-4">

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-sm-3 col-10 text-start">
                                    <label class="form-label mb-0">Offer Title</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="offer2_title" class="fs-6 rounded" type="text" :value="$offer2->title ?? null"
                                        :required="false" />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-sm-3 col-10 text-start">
                                    <label class="form-label mb-0">Image</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    @php $offer2Image = $offer2->image ? \Illuminate\Support\Facades\Storage::url($offer2->image->src) : asset('default.webp'); @endphp
                                    <x-media-thumbnail button_label="Select Image" input_name="offer2_image"
                                        :existing_image="$offer2Image" :existing_id="$offer2->image_id ?? null" :target_id="'offer2_image_id'" />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-sm-3 col-10 text-start">
                                    <label class="form-label mb-0">Offer Subtitle</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="offer2_subtitle" class="fs-6 rounded" type="text" :value="$offer2->subtitle ?? null"
                                        :required="false" />
                                </div>
                            </div>

                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-sm-3 col-10 text-start">
                                    <label class="form-label mb-0">Button Link</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-link-selector prefix="offer2" :selectedType="$offer2->link_type ?? ''" :selectedRef="$offer2->link_ref_id ?? ''" />
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
                                                {{ isset($offer2->status) && $offer2->status == '1' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="0"
                                                {{ isset($offer2->status) && $offer2->status == '0' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .form-group {
            margin-bottom: 0 !important;
            width: 100%;
        }

        [id^="media-preview-"] {
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
        }

        .preview-image-wrapper,
        .imagePreviewSingle {
            width: 100% !important;
            aspect-ratio: 6/2 !important;
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

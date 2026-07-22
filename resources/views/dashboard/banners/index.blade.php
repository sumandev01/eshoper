@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? 'E-Shopper') . ' - ' . 'Banner Offers')
@section('content')
    <div class="container-fluid">
        <form action="{{ route('admin.settings.offers.update') }}" method="POST">
            @csrf
            
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-11">

                    <!-- Tab Navigation -->
                    <div class="d-flex justify-content-center mb-4">
                        <ul class="nav nav-pills bg-white p-1 rounded-pill shadow-sm border" id="offerTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-medium px-4 py-2" id="offer1-tab" data-bs-toggle="tab" data-bs-target="#offer1" type="button" role="tab" aria-controls="offer1" aria-selected="true">
                                    <i class="mdi mdi-image-multiple me-1"></i> Banner Offer 1
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-medium px-4 py-2" id="offer2-tab" data-bs-toggle="tab" data-bs-target="#offer2" type="button" role="tab" aria-controls="offer2" aria-selected="false">
                                    <i class="mdi mdi-image-multiple me-1"></i> Banner Offer 2
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="offerTabsContent">
                        <!-- ================= Offer 1 Section ================= -->
                        <div class="tab-pane fade show active" id="offer1" role="tabpanel" aria-labelledby="offer1-tab">
                            <div class="card border-0 mb-4 shadow-sm rounded-4">
                                <div class="card-body p-4 p-md-5">
                                    <div class="row">
                                        <!-- Left Column: Inputs -->
                                        <div class="col-lg-7 mb-4 mb-lg-0">
                                            <div class="mb-4">
                                                <x-input label="Title" name="offer1_title" class="fs-6" type="text" :value="$offer1->title ?? null" :required="false" placeholder="e.g. Spring Collection" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-input label="Sub-Title" name="offer1_subtitle" class="fs-6" type="text" :value="$offer1->subtitle ?? null" :required="false" placeholder="e.g. 20% off all orders" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-link-selector label="Button Link" prefix="offer1" :selectedType="$offer1->link_type ?? ''" :selectedRef="$offer1->link_ref_id ?? ''" />
                                            </div>
                                            
                                            <x-select 
                                                name="offer1_status" 
                                                label="Status" 
                                                :options="['1' => 'Active', '0' => 'Inactive']" 
                                                :value="$offer1->status ?? '1'" 
                                                class="fs-6 rounded-3 bg-light border-0 py-2" 
                                                :required="false" 
                                            />
                                        </div>

                                        <!-- Right Column: Image -->
                                        <div class="col-lg-5 ps-lg-4">
                                            <label class="form-label fw-semibold text-dark mb-2">Banner Image</label>
                                            <div class="border rounded-4 p-2 bg-light text-center">
                                                @php $offer1Image = $offer1->image ? \Illuminate\Support\Facades\Storage::url($offer1->image->src) : asset('default.webp'); @endphp
                                                <x-media-thumbnail button_label="Change Image" input_name="offer1_image" :existing_image="$offer1Image" :existing_id="$offer1->image_id ?? null" :target_id="'offer1_image_id'" />
                                            </div>
                                            <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline"></i> Recommended ratio: 1:1 (e.g. 500x500px) & transparent background</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ================= Offer 2 Section ================= -->
                        <div class="tab-pane fade" id="offer2" role="tabpanel" aria-labelledby="offer2-tab">
                            <div class="card border-0 mb-4 shadow-sm rounded-4">
                                <div class="card-body p-4 p-md-5">
                                    <div class="row">
                                        <!-- Left Column: Inputs -->
                                        <div class="col-lg-7 mb-4 mb-lg-0">
                                            <div class="mb-4">
                                                <x-input label="Title" name="offer2_title" class="fs-6" type="text" :value="$offer2->title ?? null" :required="false" placeholder="e.g. Winter Collection" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-input label="Sub-Title" name="offer2_subtitle" class="fs-6" type="text" :value="$offer2->subtitle ?? null" :required="false" placeholder="e.g. 50% off selected items" />
                                            </div>
                                            
                                            <div class="mb-4">
                                                <x-link-selector label="Button Link" prefix="offer2" :selectedType="$offer2->link_type ?? ''" :selectedRef="$offer2->link_ref_id ?? ''" />
                                            </div>
                                            
                                            <x-select 
                                                name="offer2_status" 
                                                label="Status" 
                                                :options="['1' => 'Active', '0' => 'Inactive']" 
                                                :value="$offer2->status ?? '1'" 
                                                class="fs-6 rounded-3 bg-light border-0 py-2" 
                                                :required="false" 
                                            />
                                        </div>

                                        <!-- Right Column: Image -->
                                        <div class="col-lg-5 ps-lg-4">
                                            <label class="form-label fw-semibold text-dark mb-2">Banner Image</label>
                                            <div class="border rounded-4 p-2 bg-light text-center">
                                                @php $offer2Image = $offer2->image ? \Illuminate\Support\Facades\Storage::url($offer2->image->src) : asset('default.webp'); @endphp
                                                <x-media-thumbnail button_label="Change Image" input_name="offer2_image" :existing_image="$offer2Image" :existing_id="$offer2->image_id ?? null" :target_id="'offer2_image_id'" />
                                            </div>
                                            <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline"></i> Recommended ratio: 1:1 (e.g. 500x500px) & transparent background</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end mb-5">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill">
                            <i class="mdi mdi-content-save me-2"></i> Save All Changes
                        </button>
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
            aspect-ratio: 1/1 !important;
            object-fit: contain !important;
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

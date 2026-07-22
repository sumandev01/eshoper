@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Edit Slider - ' . $slider->title)
@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <form action="{{ route('admin.slider.update', $slider->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header py-4">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Edit Slider</h5>
                            <a href="{{ route('admin.slider.index') }}" class="btn btn-sm btn-primary">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-1"></i>
                                Back to Sliders
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <div class="row g-3 mb-2">
                            <div class="col-lg-6">
                                <x-input label="Title" name="title" type="text" :value="$slider->title" placeholder="Slider title"
                                    :required='false' />
                            </div>
                            <div class="col-lg-6">
                                <x-input label="Sub Title" name="subtitle" type="text" :value="$slider->subtitle" placeholder="Slider subtitle"
                                    :required='false' />
                            </div>
                            <div class="col-lg-6">
                                <x-input label="Button Text" name="button_text" type="text" :value="$slider->button_text" placeholder="Shop Now"
                                    :required='false' />
                            </div>
                            <div class="col-lg-6">
                                <x-select label="Status" name="is_active">
                                    <option value="1" {{ $slider->is_active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $slider->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                </x-select>
                            </div>
                        </div>
                        <x-link-selector label="Button Link" :selectedType="$slider->link_type" :selectedRef="$slider->link_ref_id" />
                        <x-media-thumbnail label="Image" class="slider_image" target_id="main-thumb" :existing_image="Storage::url($slider->media?->src) ?? null" :existing_id="$slider->media_id"
                            input_name="media_id" />
                        <small class="text-muted mb-3 d-block"><i class="mdi mdi-information-outline me-1"></i>Recommended image ratio is 21:9 (e.g., 1200x500 px) for the best e-commerce experience.</small>

                    </div>
                    <div class="card-footer text-end py-4">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="mdi mdi-update btn-icon-prepend me-2"></i>
                            <span>Update Slider</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .slider_image {
            width: 100% !important;
        }

        .slider_image .preview-image-wrapper,
        .slider_image .no-image-placeholder,
        .slider_image .preview-image-wrapper img {
            width: 100% !important;
            height: auto !important;
            aspect-ratio: 21 / 9 !important;
            object-fit: cover !important;
        }
        .link-selector-wrapper .form-label {
            font-size: 0.875rem;
            font-weight: 600;
        }
    </style>
@endpush



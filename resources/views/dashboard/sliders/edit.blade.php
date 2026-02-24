@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <form action="{{ route('slider.update', $slider->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header py-4">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Edit Slider</h4>
                            <a href="{{ route('slider.index') }}" class="btn btn-primary">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-1"></i>
                                Back to Sliders
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Title" name="title" type="text" :value="$slider->title" placeholder="Slider title"
                            :required='false' />
                        <x-input label="Sub Title" name="subtitle" type="text" :value="$slider->subtitle" placeholder="Slider subtitle"
                            :required='false' />
                        <x-input label="Button Text" name="button_text" type="text" :value="$slider->button_text" placeholder="Shop Now"
                            :required='false' />
                        <x-input label="Button Link" name="button_link" type="text" :value="$slider->button_link" placeholder="www.example.com"
                            :required='false' />
                        <x-media-thumbnail label="Image" class="slider_image" target_id="main-thumb" :existing_image="Storage::url($slider->media->src) ?? null" :existing_id="$slider->media_id"
                            input_name="media_id" />
                        <x-select label="Status" name="is_active">
                            <option value="1" {{ $slider->is_active == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $slider->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                        </x-select>
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
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
            aspect-ratio: 16 / 9 !important;
            object-fit: cover !important;
        }
    </style>
@endpush

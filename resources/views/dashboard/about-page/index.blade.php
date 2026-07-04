@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'About Page')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header py-3">
                    <h4>About Page</h4>
                    <p class="mb-0 text-muted">Update About Page</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.about-page.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="container">
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Header Title</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="top_header" class="fs-6" type="text" :value="$aboutPage?->top_header ?? ''"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Header Subtitle</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="top_sub_header" class="fs-6" type="text" :value="$aboutPage?->top_sub_header ?? ''"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Heading</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="heading" class="fs-6" type="text" :value="$aboutPage?->heading ?? ''"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Description</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-textarea name="description" :editor="true" :value="$aboutPage?->description ?? ''"
                                        placeholder="Write something..." maxlength="500" :wordcount="true" rows="5"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Image</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-media-thumbnail button_label="Select Image" class="about_image" input_name="image"
                                        :existing_image="$image ?? ''" :target_id="'media_id'" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Button Title</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="button_text" class="fs-6" type="text" :value="$aboutPage?->button_text ?? ''"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Button URL</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-input name="button_link" class="fs-6" type="text" :value="$aboutPage?->button_link ?? ''"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Our Mission</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-textarea name="our_mission" :editor="false" :value="$aboutPage?->our_mission ?? ''"
                                        placeholder="Write something..." maxlength="150" :wordcount="true" rows="4"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Our Vision</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-textarea name="our_vision" :editor="false" :value="$aboutPage?->our_vision ?? ''"
                                        placeholder="Write something..." maxlength="150" :wordcount="true" rows="4"
                                        :required="false" />
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 gy-3">
                                <div class="col-md-3 col-10 text-start">
                                    <label class="form-label mb-0">Our Values</label>
                                </div>
                                <div class="col-auto px-0 fs-5 fw-bold">:</div>
                                <div class="col-md-8">
                                    <x-textarea name="our_values" :editor="false" :value="$aboutPage?->our_values ?? ''"
                                        placeholder="Write something..." maxlength="150" :wordcount="true" rows="4"
                                        :required="false" />
                                </div>
                            </div>
                        </div>
                        @can(App\Enums\Permission\AboutPagePermission::UPDATE->value)
                        <button type="submit" class="btn btn-primary">Update</button>
                        @endcan
                    </form>
                </div>
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

        #media-preview-media_id {
            padding-left: 0 !important;
            margin-bottom: 10px !important;
        }

        #media-preview-main_thumb {
            padding: 0 !important;
            margin-bottom: 0 !important;
        }

        .preview-image-wrapper {
            padding-left: 0 !important;
            margin-bottom: 0 !important;
        }

        .imagePreviewSingle {
            width: 250px !important;
            margin-bottom: 0 !important;
            object-fit: contain !important;
        }

        @media (max-width: 767px) {
            .form-group {
                margin-left: 0 !important;
            }
        }

        #our_mission, #our_vision, #our_values {
            font-size: 16px !important;
            line-height: 1.5 !important;
        }
    </style>
@endpush


@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <form action="{{ route('brand.update', $brand?->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header pt-4">
                        <div class="card-title d-md-flex justify-content-between align-items-center">
                            <h4 class="">Edit Brand</h4>
                            <a href="{{ route('brand.index') }}" class="btn btn-primary mr-2 btn-icon-text">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" value="{{ $brand?->name }}"
                            placeholder="Enter brand name" />

                        <x-input label="Slug" name="slug" type="text" value="{{ $brand?->slug }}"
                            :required='false' placeholder="Enter brand slug" />

                        <x-media-thumbnail label="Image" input_name="media_id" :existing_image="$brand->thumbnail" :existing_id="$brand->media_id" />
                    </div>
                    <div class="card-footer pb-4 pt-3">
                        <button type="submit" class="btn btn-primary me-2 mt-2">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Save</span>
                        </button>
                        <a href="{{ route('brand.index') }}" class="btn btn-danger btn-icon-text mt-2">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Edit Sub Category - ' . $subCategory?->name)
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card">
                <form action="{{ route('admin.sub-category.update', $subCategory->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header py-4">
                        <div class="card-title d-md-flex justify-content-between align-items-center">
                            <h4 class="card-title">Edit Sub Category</h4>
                            <a href="{{ route('admin.sub-category.index') }}" class="btn btn-sm btn-primary mr-2 btn-icon-text">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" value="{{ $subCategory?->name }}"
                            placeholder="Enter sub category name" :required="true" />
                        <x-input label="Slug" name="slug" type="text" value="{{ $subCategory?->slug }}"
                            :required='false' placeholder="Enter sub category slug" />
                        <x-select label="Category" name="category_id" id="category_id" :options="$categories"
                            value="{{ $subCategory?->category_id ?? '' }}" placeholder="Select Category" :required="true" />
                        <x-media-thumbnail label="Image" input_name="media_id" :existing_image="$subCategory->thumbnail" 
                            :existing_id="$subCategory->media_id" :required="true" />
                    </div>
                    <div class="card-footer pb-4 pt-3 text-end">                        
                        <a href="{{ route('admin.sub-category.index') }}" class="btn btn-sm btn-secondary btn-icon-text mt-2">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Cancel</span>
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary ms-4 mt-2">
                            <i class="mdi mdi-update btn-icon-prepend me-2"></i>
                            <span>Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


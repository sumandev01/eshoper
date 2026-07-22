@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Edit Category - ' . $category?->name)
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card">
                <form action="{{ route('admin.category.update', $category?->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header py-4">
                        <div class="card-title d-md-flex justify-content-between align-items-center">
                            <h5 class="card-title">Edit Category</h5>
                            <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-primary mr-2 btn-icon-text">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" value="{{ $category?->name }}"
                            placeholder="Enter category name" />

                        <x-input label="Slug" name="slug" type="text" value="{{ $category?->slug }}"
                            :required='false' placeholder="Enter category slug" />

                        <x-media-thumbnail label="Image" input_name="media_id" :existing_image="$category->image" :existing_id="$category->media_id" />

                    </div>
                    <div class="card-footer pb-4 pt-3 text-end">                        
                        <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-secondary btn-icon-text mt-2">
                            <i class="mdi mdi-close btn-icon-prepend"></i>
                            <span>Cancel</span>
                        </a>
                        @can(\App\Enums\Permission\CategoryPermission::UPDATE->value)
                            <button type="submit" class="btn btn-sm btn-primary ms-4 mt-2">
                                <i class="mdi mdi-update btn-icon-prepend me-2"></i>
                                <span>Update</span>
                            </button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Edit Color - ' . $color?->name)
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card">
                <form action="{{ route('admin.color.update', $color?->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header py-4">
                        <div class="card-title d-md-flex justify-content-between align-items-center">
                            <h5 class="card-title">Edit Color</h5>
                            <a href="{{ route('admin.color.index') }}" class="btn btn-sm btn-primary mr-2 btn-icon-text">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" value="{{ $color?->name }}"
                            placeholder="Enter color name" :required="true"/>

                        <x-color-picker name="color_code" label="Color Code" value="{{ $color?->color_code }}" :required="true" />
                    </div>
                    <div class="card-footer pb-4 pt-3 text-end">                        
                        <a href="{{ route('admin.color.index') }}" class="btn btn-sm btn-secondary btn-icon-text mt-2">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Cancel</span>
                        </a>
                        @can(\App\Enums\Permission\ColorPermission::UPDATE->value)
                            <button type="submit" class="btn btn-sm btn-primary ms-4 mt-2">
                                <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                                <span>Update</span>
                            </button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


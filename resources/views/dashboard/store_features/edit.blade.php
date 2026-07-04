@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - Edit Store Feature')
@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <form action="{{ route('admin.store-features.update', $storeFeature->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-header pt-4">
                        <div class="card-title d-md-flex justify-content-between align-items-center">
                            <h4 class="">Edit Store Feature</h4>
                            <a href="{{ route('admin.store-features.index') }}" class="btn btn-primary mr-2 btn-icon-text">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Title" name="title" type="text" placeholder="Enter feature title" value="{{ $storeFeature->title }}" :required="true" />

                        <x-input label="FontAwesome Icon Class" name="icon" type="text" placeholder="Enter icon class (e.g. fa-check)" value="{{ $storeFeature->icon }}" :required="false" />
                        <small class="text-muted d-block mb-3">You can find icon classes at <a href="https://fontawesome.com/v5/search?m=free" target="_blank">FontAwesome 5</a>.</small>
                    </div>
                    <div class="card-footer pb-4 pt-3">
                        <button type="submit" class="btn btn-primary me-2 mt-2">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Update</span>
                        </button>
                        <a href="{{ route('admin.store-features.index') }}" class="btn btn-danger btn-icon-text mt-2">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

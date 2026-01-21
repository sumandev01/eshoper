@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header p-4 d-flex justify-content-between">
                    <h3 class="page-title"> Add New Media </h3>
                    <a class="btn btn-primary mr-2 btn-icon-text" href="{{ route('admin.media') }}">
                        <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Back to All Gallery
                    </a>
                </div>
                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4">
                        <h4 class="card-title">Upload Files</h4>
                        <p class="card-description"> You can upload multiple images at once. </p>
                        <input type="hidden" name="user_id" value="{{ auth()?->id() }}">
                        <div id="drop-area" class="border-dashed py-5 text-center"
                            style="cursor: pointer; background: #fafafa; border: 2px dashed #ccc;">
                            <i class="mdi mdi-cloud-upload text-primary" style="font-size: 40px;"></i>
                            <p>Click here</p>
                            <input type="file" name="files[]" id="file-input" multiple hidden accept="image/*">
                        </div>
                        <div id="error-container" class="mt-2"></div>
                        <div class="row mt-3" id="tabpanel-image-preview-container"></div>
                    </div>
                    <div class="card-footer text-end p-4">
                        <button type="submit" class="btn btn-primary me-4 btn-icon-text">
                            <i class="mdi mdi-file-check btn-icon-prepend me-2"></i>
                            <span>Upload All</span>
                        </button>
                        <a href="{{ route('admin.media') }}" class="btn btn-danger btn-icon-text">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
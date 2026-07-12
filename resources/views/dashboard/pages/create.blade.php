@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Create Page')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.pages.store') }}" method="post">
                    @csrf
                    <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Add New Page</h4>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Page Title" name="title" type="text" placeholder="Enter page title (e.g. Terms & Conditions)" :required='true' />
                        
                        <x-textarea label="Page Content" name="content" :editor="true" :rows="15" />

                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="status" name="status" checked value="1">
                            <label class="form-check-label" for="status">Active Status</label>
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Save Page</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Summernote init would go here if not already in app.blade.php -->
@endpush

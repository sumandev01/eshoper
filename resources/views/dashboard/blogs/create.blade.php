@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Create Blog')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.blogs.store') }}" method="post">
                    @csrf
                    <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Add New Blog</h4>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Blog Title" name="title" type="text" placeholder="Enter blog title"
                            :maxlength="255" :required='true' />

                        <div class="mb-3">
                            <x-select label="Category" name="blog_category_id" :options="$categories->pluck('name', 'id')" :required="true" />
                        </div>

                        <x-media-thumbnail label="Featured Image" input_name="media_id" />

                        <x-textarea label="Blog Content" name="content" :editor="true" :maxlength="2000" />

                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="text-uppercase text-muted fw-semibold small mb-3 border-bottom pb-2">SEO Settings
                                </h6>
                            </div>
                            <div class="col-lg-6">
                                <x-textarea label="Meta Title" name="meta_title" :maxlength="70" :rows="1" />
                            </div>
                            <div class="col-lg-6">
                                <x-textarea label="Meta Keyword" name="meta_keyword"
                                    placeholder="SEO Keywords (comma separated)" :maxlength="255" :rows="1" />
                            </div>
                            <div class="col-lg-12">
                                <x-textarea label="Meta Description" name="meta_description" placeholder="SEO Description"
                                    :maxlength="160" :rows="5" />
                            </div>
                        </div>

                        <div class="mb-3 form-check form-switch mt-3">
                            <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="status"
                                name="status" checked value="1">
                            <label class="form-check-label" for="status">Active Status</label>
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Save Blog</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        #media-preview-main_thumb {
            width: 300px !important;
        }
        .imagePreviewSingle {
            width: 100% !important;
        }
    </style>
@endpush

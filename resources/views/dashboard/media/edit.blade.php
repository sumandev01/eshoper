@extends('dashboard.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Edit Media Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <a class="btn btn-primary mr-2 btn-icon-text" href="{{ route('admin.media') }}">
                        <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Back to All Gallery
                    </a>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Media Preview</h4>
                        <div class="text-center bg-light p-3" style="border-radius: 10px; border: 1px solid #ebedf2;">
                            <img src="{{ asset($media->thumbnail) }}" alt="Static Image" class="img-fluid shadow-sm"
                                style="max-height: 300px; border-radius: 5px; object-fit: contain;">
                        </div>

                        <div class="mt-4 border-top pt-3">
                            <p class="mb-2"><strong>Name:</strong> <span class="text-muted">{{ $media->name }}</span>
                            </p>
                            <p class="mb-2"><strong>File Size:</strong> <span class="text-muted">
                                @if ($media->size < 1000000)
                                    {{ number_format($media->size / 1024, 2) }} KB
                                @else
                                    {{ number_format($media->size / (1024 * 1024), 2) }} MB
                                @endif
                            </span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Update Information</h4>
                        <p class="card-description"> Update the title and alternative text for this media. </p>

                        <form action="{{ route('admin.media.update', $media->id) }}" method="POST" class="forms-sample">
                            @csrf
                            @method('PUT')
                            <input type="hidden" class="d-none" name="id" value="{{ $media->id }}" hidden />
                            <x-input label="Name" name="name" type="text" value="{{ $media->name }}" placeholder="Enter media name" required="true" :maxlength="50" />

                            <x-input label="Alt Text" name="alt_text" type="text" value="{{ $media->alt_text }}" placeholder="Enter alternative text" required="true" :maxlength="50" />

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" id="description" rows="4"
                                    placeholder="Brief description of this media" maxlength="200">{{ $media->description }}</textarea>
                                @error('description')
                                    <span class="text-danger mt-2 d-block">{{ $errors->first('description') }}</span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary mr-2 btn-icon-text">
                                    <i class="mdi mdi-content-save btn-icon-prepend"></i> Update Media
                                </button>
                                <a href="{{ route('admin.media') }}" class="btn btn-danger mr-2 btn-icon-text">
                                    <i class="mdi mdi-cancel btn-icon-prepend"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

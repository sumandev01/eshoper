@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <form action="{{ route('color.update', $color?->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-header pt-4">
                        <div class="card-title d-md-flex justify-content-between align-items-center">
                            <h4 class="">Edit Color</h4>
                            <a href="{{ route('color.index') }}" class="btn btn-primary mr-2 btn-icon-text">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" value="{{ $color?->name }}"
                            placeholder="Enter color name" />

                        <x-color-picker name="color_code" label="Color Code" value="{{ $color?->color_code }}" />
                    </div>
                    <div class="card-footer pb-4 pt-3">
                        <button type="submit" class="btn btn-primary me-2 mt-2">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Save</span>
                        </button>
                        <a href="{{ route('color.index') }}" class="btn btn-danger btn-icon-text mt-2">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header pt-4">
                    <h5>All Categories</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="categoryTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th>Title</th>
                                <th>Sub Title</th>
                                <th class="text-center">Image</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sliders ?? [] as $key => $slider)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td> {{ $slider?->title }} </td>
                                    <td> {{ $slider?->subtitle }} </td>
                                    <td class="text-center">
                                        <img class="img-fluid"
                                            style=" border-radius: 0; object-fit: contain; aspect-ratio: 4 / 4; background-color: #fff; border: 1px solid #ccc;"
                                            src="{{ $slider?->image }}" alt="{{ $slider?->alt }}">
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('slider.edit', $slider?->id) }}" class="btn btn-info btn-sm">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        <a href="{{ route('slider.destroy', $slider?->id) }}"
                                            class="btn btn-danger btn-sm deleteBtn">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No Sliders Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <form action="{{ route('slider.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header pt-4">
                        <h4 class="card-title">Add New Slider</h4>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Title" name="title" type="text" placeholder="Slider title"
                            :required='false' />
                        <x-input label="Sub Title" name="subtitle" type="text" placeholder="Slider subtitle"
                            :required='false' />
                        <x-input label="Button Text" name="button_text" type="text"
                            placeholder="Shop Now" :required='false' />
                        <x-input label="Button Link" name="button_link" type="text"
                            placeholder="www.example.com" :required='false' />
                        <x-media-thumbnail label="Image" class="slider_image" target_id="main-thumb"
                            input_name="media_id" />
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Add Slider</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .slider_image {
            width: 100% !important;
        }
        .slider_image .preview-image-wrapper,
        .slider_image .no-image-placeholder,
        .slider_image .preview-image-wrapper img {
            width: 100% !important;
            height: auto !important;
            aspect-ratio: 16 / 9 !important;
            object-fit: cover !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#categoryTable').DataTable();
        })
    </script>
@endpush

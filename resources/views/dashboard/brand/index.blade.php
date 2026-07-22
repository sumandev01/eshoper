@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Brands')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header py-4">
                    <h5 class="card-title">All Brands</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="brandTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th class="text-center">Image</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($brands ?? [] as $key => $brand)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td> {{ $brand->name }} </td>
                                    <td> {{ $brand->slug }} </td>
                                    <td class="text-center">
                                        <img class="img-fluid"
                                            style=" border-radius: 0; object-fit: contain; aspect-ratio: 4 / 4; background-color: #fff; border: 1px solid #ccc;"
                                            src="{{ $brand->thumbnail }}" alt="{{ $brand->alt }}">
                                    </td>
                                    <td class="text-end">
                                        @can(\App\Enums\Permission\BrandPermission::UPDATE->value)
                                            <a href="{{ route('admin.brand.edit', $brand?->id) }}" class="btn btn-info btn-sm">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        @endcan
                                        @can(\App\Enums\Permission\BrandPermission::DELETE->value)
                                            <a href="{{ route('admin.brand.destroy', $brand?->id) }}"
                                                class="btn btn-danger btn-sm deleteBtn">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No Brands Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @can(\App\Enums\Permission\BrandPermission::CREATE->value)
            <div class="col-lg-5">
            <div class="card">
                <form action="{{ route('admin.brand.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header py-4">
                        <h5 class="card-title">Add New Brand</h5>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" placeholder="Enter brand name"
                            :required='true' />
                        <x-input label="Slug" name="slug" type="text" placeholder="Enter brand slug"
                            :required='false' />
                        <x-media-thumbnail label="Image" target_id="main-thumb" input_name="media_id" :required="true" />
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn btn-primary">
                            <i class="mdi mdi-plus-circle btn-icon-prepend me-2"></i>
                            <span>Add Brand</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endcan
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#brandTable').DataTable();
        })
    </script>
@endpush

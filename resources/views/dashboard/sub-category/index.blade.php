@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header pt-4">
                    <h5>All Sub Categories</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped mb-0" id="subCategoryTable"
                            style="width: 100%; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th class="text-start">Sl</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Slug</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sub_categories ?? [] as $key => $subCategory)
                                    <tr>
                                        <td class="text-start">{{ $key + 1 }}</td>
                                        <td style="word-break: break-all; white-space: normal;">{{ $subCategory->name }}
                                        </td>
                                        <td>{{ $subCategory->category->name }}</td>
                                        <td style="word-break: break-word; white-space: normal; vertical-align: middle;">
                                            {{ $subCategory->slug }}</td>
                                        <td class="text-center">
                                            <img class="img-fluid"
                                                style=" border-radius: 0; object-fit: contain; aspect-ratio: 4 / 4; background-color: #fff; border: 1px solid #ccc;"
                                                src="{{ $subCategory?->thumbnail }}" alt="{{ $subCategory?->alt }}">
                                        </td>

                                        <td class="text-end">
                                            <a href="{{ route('sub-category.edit', $subCategory?->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                            <a href="{{ route('sub-category.destroy', $subCategory?->id) }}"
                                                class="btn btn-danger btn-sm deleteBtn">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">No Sub Categories Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <form action="{{ route('sub-category.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header pt-4">
                        <h4 class="card-title">Add New Sub Category</h4>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text"
                            placeholder="Enter sub category name" :required='true' />
                        <x-input label="Slug" name="slug" type="text"
                            placeholder="Enter sub category slug" :required='false' />
                        <x-select label="Category" name="category_id" id="category_id" :options="$categories"
                            placeholder="Select Category" />
                        <x-media-thumbnail label="Image" target_id="main-thumb" input_name="media_id" />
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Add Sub Category</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#subCategoryTable').DataTable({
                // "autoWidth": false,
                "columnDefs": [{
                    "width": "60px",
                    "targets": 0
                }]
            });
        });
    </script>
@endpush

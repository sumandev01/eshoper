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
                                <th>Name</th>
                                <th>Slug</th>
                                <th class="text-center">Image</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories ?? [] as $key => $Category)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td> {{ $Category->name }} </td>
                                    <td> {{ $Category->slug }} </td>
                                    <td class="text-center">
                                        <img class="img-fluid"
                                            style=" border-radius: 0; object-fit: contain; aspect-ratio: 4 / 4; background-color: #fff; border: 1px solid #ccc;"
                                            src="{{ $Category->image }}" alt="{{ $Category->alt }}">
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('category.edit', $Category?->id) }}" class="btn btn-info btn-sm">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        <a href="{{ route('category.destroy', $Category?->id) }}"
                                            class="btn btn-danger btn-sm deleteBtn">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No Categories Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <form action="{{ route('category.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header pt-4">
                        <h4 class="card-title">Add New Category</h4>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" placeholder="Enter category name"
                            :required='true' />
                        <x-input label="Slug" name="slug" type="text" placeholder="Enter category slug"
                            :required='false' />
                        <x-media-thumbnail label="Image" target_id="main-thumb" input_name="media_id" />
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Add Category</span>
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
            $('#categoryTable').DataTable();
        })
    </script>
@endpush
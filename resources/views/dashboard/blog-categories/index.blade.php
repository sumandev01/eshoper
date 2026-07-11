@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Blog Categories')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header pt-4">
                    <h5>All Blog Categories</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="categoryTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories ?? [] as $key => $Category)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td> {{ $Category->name }} </td>
                                    <td> {{ $Category->slug }} </td>
                                    <td>
                                        <span class="badge {{ $Category->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $Category->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.blog_categories.destroy', $Category?->id) }}" class="btn btn-danger btn-sm deleteBtn"><i class="mdi mdi-delete"></i></a>
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
                <form action="{{ route('admin.blog_categories.store') }}" method="post">
                    @csrf
                    <div class="card-header pt-4">
                        <h4 class="card-title">Add New Blog Category</h4>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" placeholder="Enter category name"
                            :required='true' />
                        
                        <x-select label="Status" name="status" :required='true'>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </x-select>
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

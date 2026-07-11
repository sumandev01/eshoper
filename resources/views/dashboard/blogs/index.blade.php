@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Blogs')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                    <h5>All Blogs</h5>
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">Add New Blog</a>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="blogTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Views</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($blogs ?? [] as $key => $blog)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td> 
                                        <div class="d-flex align-items-center gap-2">
                                            @if($blog->thumbnail)
                                                <img src="{{ $blog->thumbnail }}" alt="thumbnail" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <span>{{ $blog->title }}</span>
                                        </div>
                                    </td>
                                    <td> {{ $blog->category?->name }} </td>
                                    <td> {{ $blog->views }} </td>
                                    <td>
                                        <span class="badge {{ $blog->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $blog->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-info btn-sm">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        <a href="{{ route('admin.blogs.destroy', $blog->id) }}" class="btn btn-danger btn-sm deleteBtn"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">No Blogs Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#blogTable').DataTable();
        })
    </script>
@endpush

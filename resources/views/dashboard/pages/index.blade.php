@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Pages')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                    <h5>All Pages</h5>
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">Add New Page</a>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="pageTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pages ?? [] as $key => $page)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td> {{ $page->title }} </td>
                                    <td> {{ $page->slug }} </td>
                                    <td>
                                        @if($page->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('page', $page->slug) }}" target="_blank" class="btn btn-success btn-sm">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-info btn-sm">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No Pages Found</td>
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
            $('#pageTable').DataTable();
        })
    </script>
@endpush

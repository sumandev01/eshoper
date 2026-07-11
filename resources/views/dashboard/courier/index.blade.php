@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Couriers')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-semibold">Couriers</h4>
                                <p class="text-muted small mb-0">Manage all your couriers</p>
                            </div>
                            <a href="{{ route('admin.couriers.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                <i class="mdi mdi-plus"></i>
                                <span>Add New Courier</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="courierTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Tracking URL</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($couriers as $index => $courier)
                                        <tr>
                                            <td class="text-muted small">{{ $couriers->firstItem() + $index }}</td>
                                            <td>
                                                <p class="fs-6 mb-0 fw-semibold">{{ $courier?->name }}</p>
                                            </td>
                                            <td>
                                                <a href="{{ $courier?->tracking_url }}" target="_blank" class="text-decoration-none">
                                                    {{ Str::limit($courier?->tracking_url, 40) }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge {{ $courier?->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $courier?->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{ route('admin.couriers.edit', $courier->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.couriers.destroy', $courier->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this courier?');">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                No couriers found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $couriers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Role Permission Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-4">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Role Permission List</h4>
                            <a href="{{ route('admin.role.add') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i>
                                <span>Add New Role</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table id="productTable"
                                class="table table-hover table-bordered table-centered align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Sl</th>
                                        <th>Role</th>
                                        <th>Permissions</th>
                                        <th class="text-center" style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles ?? [] as $key => $role)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $role?->name }}</td>
                                            <td style="white-space: normal;">
                                                @foreach ($role?->permissions as $permission)
                                                    <span class="badge bg-info text-white mb-1">{{ $permission?->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('admin.role.view', $role?->id) }}"
                                                        class="btn btn-sm btn-outline-secondary me-1" title="View">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.role.edit', $role?->id) }}"
                                                        class="btn btn-sm btn-outline-info me-1" title="Edit">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>
                                                    <a href="{{ route('admin.role.destroy', $role?->id) }}"
                                                        class="btn btn-danger btn-sm deleteBtn me-1">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="mdi mdi-database-off fs-1"></i>
                                                    <p class="mt-2">No Roles Found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
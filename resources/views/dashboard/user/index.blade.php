@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- User Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-4">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Users List</h4>
                            <a href="{{ route('admin.user.add') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i>
                                <span>Add New User</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <div class="md-table-responsive">
                            <div class="row justify-content-end">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <form action="">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Search by name or email"
                                                name="search" value="{{ request('search') }}">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-magnify"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <table id="productTable" class="table table-hover table-bordered table-centered align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Sl</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th class="text-center">Role</th>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users ?? [] as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td> {{ $user?->name }} </td>
                                            <td> {{ $user?->email }} </td>
                                            <td class="text-center"> <span class="badge bg-success">{{ $user?->getRoleNames()[0] }}</span> </td>
                                            <td class="text-center">
                                                <img class="img-fluid"
                                                    style=" border-radius: 100%; padding: 2px; object-fit: contain; aspect-ratio: 4 / 4; background-color: #fff; border: 1px solid #ccc;"
                                                    src="{{ $user?->profile }}" alt="{{ $user?->name }}">
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('admin.user.view', $user?->id) }}"
                                                        class="btn btn-sm btn-outline-secondary me-1" title="View">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.user.edit', $user?->id) }}"
                                                        class="btn btn-sm btn-outline-info me-1" title="Edit">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>
                                                    <a href="{{ route('admin.user.destroy', $user?->id) }}"
                                                        class="btn btn-danger btn-sm deleteBtn me-1">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="mdi mdi-database-off fs-1"></i>
                                                    <p class="mt-2">No Users Found</p>
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

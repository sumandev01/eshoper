@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col col-xs-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between py-4">
                        <h4 class="mb-0">Role Permission Details</h4>
                        <button onclick="history.back()" class="btn btn-primary btn-border-0 btn-icon-text">
                            <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                            <span>Back</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <h5>Role Name :</h5>
                            </div>
                            <div class="col-md-9">
                                <h5 class="mb-0 text-primary">{{ $role->name }}</h5>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <h4 class="mb-4">Permissions</h4>
                        
                        <div class="row mb-3">
                            @foreach ($adminAccess as $group => $permissions)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                    <h4>{{ $group }}</h4>
                                    <div class="ms-4">
                                        @foreach ($permissions as $permission)
                                            <div class="d-flex align-items-center mb-2">
                                                @if($role->hasPermissionTo($permission))
                                                    <i class="mdi mdi-check-circle text-primary me-2 fs-5"></i>
                                                @else
                                                    <i class="mdi mdi-close-circle text-muted me-2 fs-5 opacity-50"></i>
                                                @endif
                                                <span class="{{ $role->hasPermissionTo($permission) ? 'fw-bold text-dark' : 'text-muted opacity-75' }}">
                                                    {{ $permission->name }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            @foreach ($groups as $group => $permissions)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                    <h4>{{ $group }}</h4>
                                    <div class="ms-4">
                                        @foreach ($permissions as $permission)
                                            <div class="d-flex align-items-center mb-2">
                                                @if($role->hasPermissionTo($permission))
                                                    <i class="mdi mdi-check-circle text-primary me-2 fs-5"></i>
                                                @else
                                                    <i class="mdi mdi-close-circle text-muted me-2 fs-5 opacity-50"></i>
                                                @endif
                                                <span class="{{ $role->hasPermissionTo($permission) ? 'fw-bold text-dark' : 'text-muted opacity-75' }}">
                                                    {{ $permission->name }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

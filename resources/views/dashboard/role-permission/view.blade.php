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
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <h5 class="mb-3">Role Name </h5>
                            </div>
                            <div class="col-auto">
                                :
                            </div>
                            <div class="col-md-7">
                                <p>{{ $role->name }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <h5 class="mb-3">Permissions </h5>
                            </div>
                            <div class="col-auto">
                                :
                            </div>
                            <div class="col-md-7">
                                <p>
                                    @foreach ($role?->permissions as $permission)
                                        <span class="badge bg-info text-white mb-1">{{ $permission?->name }}</span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

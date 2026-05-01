@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- User Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-4">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Edit User</h4>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-primary">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-1"></i>
                                Back to User
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <x-input label="Name" name="name" type="text" placeholder="Enter user name" :value="old('name', $user->name)" />
                            <x-input label="Email" name="email" type="email" placeholder="Enter user email" :value="old('email', $user->email)" />
                            <x-select label="Role" name="role_id" :options="$roles" :value="old('role_id', $user->roles->first()->id ?? null)" placeholder="Select role" />
                            <x-media-thumbnail label="Image" class="user_image" target_id="main-thumb"
                                input_name="media_id" />
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                                <span>Update User</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

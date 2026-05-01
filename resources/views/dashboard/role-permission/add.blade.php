@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1 fw-semibold">Add New Role</h5>
                                <p class="text-muted small mb-0">Fill in the details to create a new role</p>
                            </div>
                            <a href="{{ route('admin.role.index') }}"
                                class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                <i class="mdi mdi-arrow-left"></i>
                                <span>Back to List</span>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('admin.role.store') }}" method="POST">
                            @csrf
                            {{-- Basic Info --}}
                            <div class="mb-4">
                                <div class="row g-3">
                                    <div class="col-lg-12">
                                        <x-input label="Role Name" name="name" type="text" placeholder="Role Name"
                                            :required='true' />
                                    </div>
                                </div>
                            </div>

                            {{-- Permissions --}}
                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted fw-semibold small mb-3 border-bottom pb-2">
                                    <i class="mdi mdi-shield-check me-1"></i> Permissions
                                </h6>
                                <div class="row g-3">
                                    @foreach ($groups as $group => $permissions)
                                        <div class="col-xl-3 col-lg-4 col-md-6 permission-group">
                                            <h4>{{ $group }}</h4>
                                            <div class="ms-4">
                                                @foreach ($permissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                                            name="permissions[]" value="{{ $permission->value }}"
                                                            id="{{ $permission->value }}" />
                                                        <label for="{{ $permission->value }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                <div class="form-check">
                                                    <input class="form-check-input group-checkbox" type="checkbox"
                                                        id="all-{{ Str::slug($group) }}" />
                                                    <label for="all-{{ Str::slug($group) }}">
                                                        All
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border-top pt-3 d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.role.index') }}" class="btn btn-light px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="mdi mdi-content-save me-1"></i> Create Role
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.group-checkbox').on('click', function() {
                $(this).closest('.permission-group').find('.permission-checkbox').prop('checked', this
                    .checked);
            });

            $('.permission-checkbox').on('click', function() {
                let group = $(this).closest('.permission-group');
                let allChecked = group.find('.permission-checkbox:checked').length === group.find(
                    '.permission-checkbox').length;
                group.find('.group-checkbox').prop('checked', allChecked);
            });
        });
    </script>
@endpush

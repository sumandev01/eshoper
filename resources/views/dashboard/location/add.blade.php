@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Add New Location')
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="mb-1 fw-semibold">Add Country</h5>
                    <p class="text-muted small mb-0">Create a new country</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.location.country.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Country Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Bangladesh" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Country Code (Optional for Flags)</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. BD">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Country</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="mb-1 fw-semibold">Add State (Manual)</h5>
                    <p class="text-muted small mb-0">Add a state manually to an existing country</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.location.state.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Select Country *</label>
                            <select name="country_id" class="form-select select2" required>
                                <option value="" selected disabled>-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">State Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Dhaka" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save State</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endpush

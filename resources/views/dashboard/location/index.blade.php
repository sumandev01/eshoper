@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'All Locations')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-semibold">All Countries & States</h5>
                            <p class="text-muted small mb-0">Manage your locations here</p>
                        </div>
                        <a href="{{ route('admin.location.create') }}"
                            class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                            <i class="mdi mdi-plus"></i>
                            <span>Add New Location</span>
                        </a>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="locationTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Country</th>
                                    <th>Code</th>
                                    <th>States</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @forelse ($countries ?? [] as $country)
                                    <tr>
                                        <td class="text-muted small">{{ $sl++ }}</td>
                                        <td>{{ $country?->name }}</td>
                                        <td>{{ $country?->code }}</td>
                                        <td style="max-width: 200px;">
                                            @if ($country->states->isNotEmpty())
                                                @foreach ($country->states->take(2) as $state)
                                                    <span class="badge bg-primary me-1">{{ $state->name }}
                                                        @can(\App\Enums\Permission\LocationPermission::DELETE->value)
                                                            <a href="{{ route('admin.location.state.destroy', $state->id) }}" class="btn-close btn-close-white ms-1 deleteBtn" style="font-size: 8px; display: inline-block;"></a>
                                                        @endcan
                                                    </span>
                                                @endforeach
                                                @if ($country->states->count() > 2)
                                                    <span class="badge bg-secondary small">+ {{ $country->states->count() - 2 }} more</span>
                                                @endif
                                            @else
                                                <span class="text-muted italic small">No States</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @can(\App\Enums\Permission\LocationPermission::DELETE->value)
                                                <a href="{{ route('admin.location.country.destroy', $country->id) }}" class="btn btn-sm btn-soft-danger btn-icon deleteBtn" title="Delete Country">
                                                    <i class="mdi mdi-trash-can-outline"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No locations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="mb-1 fw-semibold">Sync States from Online</h5>
                    <p class="text-muted small mb-0">1-Click sync all states for a specific country.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.location.states.sync') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Select Country</label>
                            <select name="country_id" class="form-select select2" required>
                                <option value="" selected disabled>-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info w-100 text-white">
                            <i class="mdi mdi-cloud-download"></i> Sync States
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#locationTable').DataTable({
                "pageLength": 10,
                "lengthChange": false,
                "info": false,
            });
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endpush

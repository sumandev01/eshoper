@extends('dashboard.layouts.app')
@section('title', $siteSettings?->site_title . ' - ' . 'All Locations')
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-semibold">All Locations</h5>
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
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>Thana</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp

                                @forelse ($locations ?? [] as $division)
                                    @if ($division->districts->isNotEmpty())
                                        @foreach ($division->districts as $district)
                                            <tr>
                                                <td class="text-muted small">{{ $sl++ }}</td>
                                                <td>{{ $division?->name }}</td>
                                                <td>{{ $district?->name }}</td>
                                                <td style="max-width: 200px;">
                                                    @if ($district->thanas->isNotEmpty())
                                                        @foreach ($district->thanas->take(2) as $thana)
                                                            <span class="badge bg-primary me-1">{{ $thana->name }}</span>
                                                        @endforeach
                                                        @if ($district->thanas->count() > 2)
                                                            <span class="badge bg-secondary small">+
                                                                {{ $district->thanas->count() - 2 }} more</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted italic small">No Thana Found</span>
                                                    @endif

                                                </td>
                                                <td class="text-center">
                                                    @can(\App\Enums\Permission\LocationPermission::VIEW->value)
                                                        @php
                                                            $allThana = $district->thanas->pluck('name')->toArray();
                                                            $thanaNames = implode(', ', $allThana);
                                                        @endphp
                                                        <button data-id="{{ $division->id }}" data-name="{{ $division->name }}"
                                                            data-districts="{{ $district->name }}"
                                                            data-thanas="{{ $thanaNames }}" class="btn btn-sm btn-info"
                                                            data-bs-toggle="modal" data-bs-target="#viewDivisionModal">
                                                            <i class="mdi mdi-view-list"></i>
                                                        </button>
                                                    @endcan
                                                    @can(\App\Enums\Permission\LocationPermission::DELETE->value)
                                                        <a href="{{ route('admin.location.destroy', $division->id) }}"
                                                            class="btn btn-sm btn-danger">
                                                            <i class="mdi mdi-delete"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-muted small">{{ $sl++ }}</td>
                                            <td>{{ $division?->name }}</td>
                                            <td class="text-danger small italic">No District Found</td>
                                            <td class="text-danger small italic">No Thana Found</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.location.destroy', $division->id) }}"
                                                    class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No location data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-semibold">Division Overview</h5>
                            <p class="text-muted small mb-0">Overview of your division data</p>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="divisionTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Division Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @forelse ($locations ?? [] as $division)
                                    <tr>
                                        <td class="text-muted small">{{ $sl++ }}</td>
                                        <td>{{ $division?->name }}</td>
                                        <td class="text-center">
                                            @can(\App\Enums\Permission\LocationPermission::UPDATE->value)
                                                <button data-id="{{ $division->id }}" data-name="{{ $division->name }}"
                                                    class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editDivisionModal">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </button>
                                            @endcan
                                            @can(\App\Enums\Permission\LocationPermission::DELETE->value)
                                                <a href="{{ route('admin.location.division.destroy', $division->id) }}"
                                                    class="btn btn-danger btn-sm deleteBtn">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No division data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-semibold">District Overview</h5>
                            <p class="text-muted small mb-0">Overview of your district data</p>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="districtTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>District Name</th>
                                    <th>Division Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @forelse ($districts ?? [] as $district)
                                    <tr>
                                        <td class="text-muted small">{{ $sl++ }}</td>
                                        <td>{{ $district?->name }}</td>
                                        <td>{{ $district?->division?->name }}</td>
                                        <td class="text-center">
                                            @can(\App\Enums\Permission\LocationPermission::UPDATE->value)
                                                <button data-id="{{ $district->id }}" data-name="{{ $district->name }}"
                                                    data-division_id="{{ $district->division_id }}" class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#editDistrictModal">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </button>
                                            @endcan
                                            @can(\App\Enums\Permission\LocationPermission::DELETE->value)
                                                <a href="{{ route('admin.location.district.destroy', $district->id) }}"
                                                    class="btn btn-danger btn-sm deleteBtn">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No district data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-semibold">Thana Overview</h5>
                            <p class="text-muted small mb-0">Overview of your thana data</p>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="thanaTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Thana Name</th>
                                    <th>District Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @forelse ($thanas ?? [] as $thana)
                                    <tr>
                                        <td class="text-muted small">{{ $sl++ }}</td>
                                        <td>{{ $thana?->name }}</td>
                                        <td>{{ $thana?->district?->name }}</td>
                                        <td class="text-center">
                                            @can(\App\Enums\Permission\LocationPermission::UPDATE->value)
                                                <button data-id="{{ $thana->id }}" data-name="{{ $thana->name }}"
                                                    data-district_id="{{ $thana->district_id }}" class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#editThanaModal">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </button>
                                            @endcan
                                            @can(\App\Enums\Permission\LocationPermission::DELETE->value)
                                                <a href="{{ route('admin.location.thana.destroy', $thana->id) }}"
                                                    class="btn btn-danger btn-sm deleteBtn">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No thana data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Location View Modal --}}
    <div class="modal fade" id="viewDivisionModal" tabindex="-1" aria-labelledby="viewDivisionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDivisionModalLabel"><span id="divisionName"></span> Division 1 District Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4 col-8">
                            <h5>District Name</h5>
                        </div>
                        <div class="col-auto">:</div>
                        <div class="col-md-6">
                            <div id="districtList"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-8">
                            <h5>Thana Name</h5>
                        </div>
                        <div class="col-auto">:</div>
                        <div class="col-md-6">
                            <div id="thanaList"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Division Modal Edit --}}
    <div class="modal fade" id="editDivisionModal" tabindex="-1" aria-labelledby="editDivisionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDivisionModalLabel">Edit Division</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDivisionForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="divisionName" class="form-label">Division Name</label>
                            <input type="text" class="form-control" id="divisionName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- District Modal --}}
    <div class="modal fade" id="editDistrictModal" tabindex="-1" aria-labelledby="editDistrictModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDistrictModalLabel">Edit District</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDistrictForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="districtName" class="form-label">District Name</label>
                            <input type="text" class="form-control" id="districtName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="divisionSelect" class="form-label">Select Division</label>
                            <select class="form-select" id="divisionSelect" name="division_id" required></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Thana Modal --}}
    <div class="modal fade" id="editThanaModal" tabindex="-1" aria-labelledby="editThanaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editThanaModalLabel">Edit Thana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editThanaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="thanaName" class="form-label">Thana Name</label>
                            <input type="text" class="form-control" id="thanaName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="districtSelect" class="form-label">Select District</label>
                            <select class="form-select" id="districtSelect" name="district_id" required></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#locationTable').DataTable();
            $('#divisionTable').DataTable();
            $('#districtTable').DataTable();
            $('#thanaTable').DataTable();

            $('#viewDivisionModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let divisionId = button.data('id');
                let divisionName = button.data('name');
                let districtName = button.data('districts');
                let thanaString = button.data('thanas');

                let modal = $(this);
                modal.find('#divisionName').text(divisionName);
                modal.find('#districtList').html('');
                modal.find('#thanaList').html('')
                if (districtName) {
                    modal.find('#districtList').append('<span>' + districtName + '</span>');
                }
                if (thanaString) {
                    let thanaArray = thanaString.split(', ');
                    thanaArray.forEach(function(thana, index) {
                        let separator = (index < thanaArray.length - 1) ? ', ' : '';
                        modal.find('#thanaList').append('<span>' + thana + separator + '</span>');
                    });
                } else {
                    modal.find('#thanaList').append('<span class="text-muted">No Thana Found</span>');
                }
            });

            $('#editDivisionModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let divisionId = button.data('id');
                let divisionName = button.data('name');
                let modal = $(this);
                let url = "{{ route('admin.location.division.update', ':id') }}";
                url = url.replace(':id', divisionId);
                modal.find('#divisionName').val(divisionName);
                modal.find('#editDivisionForm').attr('action', url);
            });

            $('#editDistrictModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let districtId = button.data('id');
                let districtName = button.data('name');
                let divisionId = button.data('division_id');
                let modal = $(this);
                modal.find('#districtName').val(districtName);
                modal.find('#divisionSelect').val(divisionId);
                let url = "{{ route('admin.location.district.update', ':id') }}";
                url = url.replace(':id', districtId);
                modal.find('#editDistrictForm').attr('action', url);
            });

            // Populate division dropdown in district modal
            let divisions = @json($locations);
            let divisionSelect = $('#divisionSelect');
            divisions.forEach(function(division) {
                divisionSelect.append(new Option(division.name, division.id));
            });

            $('#editThanaModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let thanaId = button.data('id');
                let thanaName = button.data('name');
                let districtId = button.data('district_id');
                let modal = $(this);
                modal.find('#thanaName').val(thanaName);
                modal.find('#districtSelect').val(districtId);
                let url = "{{ route('admin.location.thana.update', ':id') }}";
                url = url.replace(':id', thanaId);
                modal.find('#editThanaForm').attr('action', url);
            });

            // Populate district dropdown in thana modal
            let districts = @json($districts);
            let districtSelect = $('#districtSelect');
            districts.forEach(function(district) {
                districtSelect.append(new Option(district.name, district.id));
            });
        });
    </script>
@endpush

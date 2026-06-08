@extends('dashboard.layouts.app')
@section('title', $siteSettings?->site_title . ' - ' . 'Team Members')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Team Member List start -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header py-3">
                        <h4>Team Member List</h4>
                        <p class="mb-0 text-muted">Team Member List</p>
                    </div>
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="teamMemberTable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Position</th>
                                        <th scope="col" class="text-center">Image</th>
                                        <th scope="col" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="teamMemberSortable">
                                    @forelse ($teamMembers ?? [] as $key => $teamMember)
                                        <tr data-id="{{ $teamMember->id }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $teamMember->name }}</td>
                                            <td>{{ $teamMember->position }}</td>
                                            <td class="text-center">
                                                <img src="{{ $teamMember->media ? Storage::url($teamMember->media->src) : asset('default.webp') }}"
                                                    class="img-fluid" alt="Image">
                                            </td>
                                            <td class="text-end">
                                                @can(App\Enums\Permission\TeamMemberPermission::UPDATE->value)
                                                    <a href="{{ route('admin.team-member.edit', $teamMember->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can(App\Enums\Permission\TeamMemberPermission::DELETE->value)
                                                    <a href="{{ route('admin.team-member.destroy', $teamMember->id) }}"
                                                        class="btn btn-danger btn-sm deleteBtn">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Team Members Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <span class="text-danger" style="font-size: 14px;"><b>Note: </b>Drag and drop to change
                                order</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Team Member List end -->
            <!-- Add Team Member start -->
            @can(APP\Enums\Permission\TeamMemberPermission::CREATE->value)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header py-3">
                            <h4>Add Team Member</h4>
                            <p class="mb-0 text-muted">Add New Team Member</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.team-member.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-input name="name" label="Name" :value="old('name')" class="fs-6" type="text"
                                            :required="true" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-input name="position" label="Position" :value="old('position')" class="fs-6"
                                            type="text" :required="true" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-media-thumbnail label="Profile Image" button_label="Select Image" class="about_image"
                                            :required="true" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
            <!-- Add Team Member end -->
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#teamMemberTable').DataTable();
        });
        // Initialize Sortable on the table body
        Sortable.create(document.getElementById('teamMemberSortable'), {
            animation: 150,
            onEnd: function(evt) {
                let order = [];
                $('#teamMemberSortable tr').each(function(index) {
                    order.push($(this).data('id'));
                });
                // Send the new order to the server via AJAX
                $.ajax({
                    url: '{{ route('admin.team-member.reorder') }}',
                    method: 'POST',
                    data: {
                        order: order,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Order updated successfully');
                        $('#teamMemberSortable tr').each(function(index) {
                            $(this).find('td:first').text(index + 1);
                        });
                    },
                    error: function(xhr) {
                        console.error('Error updating order');
                    }
                });
            }
        })
    </script>
@endpush

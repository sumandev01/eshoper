@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header pt-4">
                    <h5>All Tags</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="tagTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th>Name</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tags ?? [] as $key => $tag)
                                <tr>
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td> {{ $tag->name }} </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-info btn-sm editBtn"
                                            data-id="{{ $tag->id }}" data-name="{{ $tag->name }}"
                                            data-bs-toggle="modal" data-bs-target="#editTagModal">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </button>
                                        <a href="{{ route('tag.destroy', $tag?->id) }}"
                                            class="btn btn-danger btn-sm deleteBtn">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No Tags Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <form action="{{ route('tag.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header pt-4">
                        <h4 class="card-title">Add New Tag</h4>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" placeholder="Enter tag name"
                            :required='true' />
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Add Tag</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Tag Modal -->
    <div class="modal fade" id="editTagModal" tabindex="-1" aria-labelledby="editTagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editTagForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTagModalLabel">Edit Tag</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name" class="form-label">Tag Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer text-start">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Save</span>
                        </button>
                        <button type="button" class="btn btn-danger btn-icon-text" data-bs-dismiss="modal">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Close</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tagTable').DataTable();

            // Edit button click event handler
            $('.editBtn').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');

                // Input field value update
                $('#edit_name').val(name);

                // Form action update
                var url = "{{ route('tag.update', ':id') }}";
                url = url.replace(':id', id);
                $('#editTagForm').attr('action', url);
            });
        });
    </script>
@endpush
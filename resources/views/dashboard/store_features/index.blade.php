@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Store Features')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header py-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Store Features</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="storeFeatureTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Icon</th>
                                <th>Title</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="sortable">
                            @forelse ($storeFeatures ?? [] as $key => $feature)
                                <tr data-id="{{ $feature->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <i
                                            class="{{ str_starts_with($feature->icon, 'fas ') || str_starts_with($feature->icon, 'fab ') ? $feature->icon : 'fas ' . $feature->icon }} text-primary m-0 mr-3"></i>
                                    </td>
                                    <td>{{ $feature->title }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.store-features.edit', $feature->id) }}"
                                            class="btn btn-sm btn-info border-0">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.store-features.destroy', $feature->id) }}"
                                            class="btn btn-danger btn-sm deleteBtn">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Features Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <p class="text-muted small mt-3">
                        <i class="mdi mdi-information-outline"></i>
                        <em>
                            <span class="fw-bold">Note:</span> You can drag and drop rows to reorder the features.
                        </em>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <form action="{{ route('admin.store-features.store') }}" method="post">
                    @csrf
                    <div class="card-header py-4">
                        <h5 class="mb-0">Add Store Feature</h5>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Title" name="title" type="text"
                            placeholder="Enter feature title (e.g. Free Shipping)" :required="true" />

                        <x-input label="FontAwesome Icon Class" name="icon" type="text"
                            placeholder="Enter icon class (e.g. fa-shipping-fast)" :required="false" />
                        <small class="text-muted d-block mb-3">You can find icon classes at <a
                                href="https://fontawesome.com/v5/search?m=free" target="_blank">FontAwesome 5</a>.</small>
                    </div>
                    <div class="card-footer pb-4 pt-3">
                        <button type="submit" class="btn btn-primary me-2 mt-2">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Save</span>
                        </button>
                        <a href="{{ route('admin.store-features.index') }}" class="btn btn-danger btn-icon-text mt-2">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#sortable").sortable({
                update: function(event, ui) {
                    var order = [];
                    $('#sortable tr').each(function(index, element) {
                        order.push($(this).data('id'));
                    });

                    $.ajax({
                        url: '{{ route('admin.store-features.reorder') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order: order
                        },
                        success: function(response) {
                            toastr.success('Order updated successfully.');
                        },
                        error: function(response) {
                            toastr.error('Failed to update order.');
                        }
                    });
                }
            });
        });
    </script>
@endpush

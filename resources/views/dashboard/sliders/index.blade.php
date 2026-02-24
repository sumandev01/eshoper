@extends('dashboard.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header py-4">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">All Sliders</h4>
                    <a href="{{ route('slider.add') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus btn-icon-prepend me-1"></i>
                        Add New Slider
                    </a>
                    </div>
                </div>
                <div class="card-body p-4 table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="categoryTable">
                        <thead>
                            <tr>
                                <th class="text-start" style="width: 60px">Sl</th>
                                <th class="text-center" style="max-width: 400px">Image</th>
                                <th>Title</th>
                                <th>Sub Title</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="sliderSortable">
                            @forelse ($sliders ?? [] as $key => $slider)
                                <tr data-id="{{ $slider?->id }}">
                                    <td class="text-start">{{ $key + 1 }}</td>
                                    <td class="text-center slider_image">
                                        <img class="img-fluid rounded-0" style=" border-radius: 0; object-fit: contain; aspect-ratio: 16 / 9; background-color: #fff; border: 1px solid #ccc;" src="{{ Storage::url($slider?->media?->src) }}">
                                    </td>
                                    <td> {{ $slider?->title }} </td>
                                    <td> {{ $slider?->subtitle }} </td>
                                    <td class="text-center">
                                        @if ($slider?->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('slider.edit', $slider?->id) }}" class="btn btn-info btn-sm">
                                            <i class="mdi mdi-square-edit-outline"></i>
                                        </a>
                                        <a href="{{ route('slider.destroy', $slider?->id) }}"
                                            class="btn btn-danger btn-sm deleteBtn">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No Sliders Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .slider_image {
            width: 200px !important;
            height: auto !important;
        }
        .slider_image img {
            width: 100% !important;
            height: auto !important;
            aspect-ratio: 16 / 9 !important;
            object-fit: cover !important;
        }
    </style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#categoryTable').DataTable();
        });
        // Initialize SortableJS on the table body
        Sortable.create(document.getElementById('sliderSortable'), {
            animation: 150,
            onEnd: function (evt) {
                let order = [];
                $('#sliderSortable tr').each(function(index) {
                    order.push($(this).data('id'));
                });
                // Send the new order to the server via AJAX
                $.ajax({
                    url: '{{ route("slider.reorder") }}',
                    method: 'POST',
                    data: {
                        order: order,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        //console.log('Order updated successfully');
                        $('#sliderSortable tr').each(function(index) {
                            $(this).find('td:first').text(index + 1);
                        });
                    },
                    error: function(xhr) {
                        //console.error('Error updating order');
                    }
                });
            }
        });
    </script>
@endpush

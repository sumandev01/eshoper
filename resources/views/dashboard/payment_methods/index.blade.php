@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Payment Methods')
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header py-4">
                    <h5 class="mb-0">All Payment Methods</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="paymentMethodTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="sortable">
                            @forelse ($paymentMethods ?? [] as $key => $paymentMethod)
                                <tr data-id="{{ $paymentMethod->id }}">
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        @if ($paymentMethod->media)
                                            <img src="{{ Storage::url($paymentMethod->media->src) }}" alt="Logo"
                                                style="border-radius: 0;">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $paymentMethod->name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.payment-methods.edit', $paymentMethod->id) }}"
                                            class="btn btn-sm btn-info border-0">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.payment-methods.destroy', $paymentMethod->id) }}"
                                            class="btn btn-danger btn-sm deleteBtn">
                                            <i class="mdi mdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Payment Method Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <p class="text-muted small italic mt-3">
                        <i class="mdi mdi-information-outline"></i>
                        <em>
                            <span class="fw-bold">Note:</span> You can drag and drop rows to reorder the payment methods.
                        </em>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <form action="{{ route('admin.payment-methods.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header py-4">
                            <h5 class="mb-0">Add Payment Method</h5>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <x-input label="Name" name="name" type="text" placeholder="Enter payment method name (e.g. bKash)" :required="false" />

                        <x-media-thumbnail label="Logo Image" input_name="" target_id="media_id" :existing_image="null" :existing_id="null" />
                    </div>
                    <div class="card-footer pb-4 pt-3">
                        <button type="submit" class="btn btn-primary me-2 mt-2">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Save</span>
                        </button>
                        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-danger btn-icon-text mt-2">
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
                        url: '{{ route('admin.payment-methods.reorder') }}',
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

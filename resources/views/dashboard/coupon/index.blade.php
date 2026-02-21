@extends('dashboard.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-semibold">Coupons</h4>
                                <p class="text-muted small mb-0">Manage all your coupons</p>
                            </div>
                            <a href="{{ route('coupon.add') }}"
                                class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                <i class="mdi mdi-plus"></i>
                                <span>Add New Coupon</span>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="couponTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Amount</th>
                                        <th>Min Order Amount</th>
                                        <th>Max Discount</th>
                                        <th>Used Count / <br> Usage Limit</th>
                                        <th>Start Date / <br> Expire Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($coupons as $index => $coupon)
                                        <tr>
                                            <td class="text-muted small">{{ $index + 1 }}</td>
                                            <td>
                                                <p class="fs-6 mb-2 fw-semibold">{{ $coupon?->code }}</p>
                                                <p class="text-muted small mb-0"> Type:
                                                    <span
                                                        class="badge {{ $coupon?->type === 'percentage' ? 'bg-info' : 'bg-warning text-dark' }} text-capitalize">{{ $coupon?->type }}</span>
                                                </p>
                                            </td>
                                            <td>
                                                @if ($coupon->type === 'percentage')
                                                    {{ $coupon?->amount }}%
                                                @else
                                                    ৳{{ formatBDT($coupon?->amount, 2) }}
                                                @endif
                                            </td>
                                            <td>৳{{ formatBDT($coupon?->min_order_amount, 2) }}</td>
                                            <td>
                                                @if ($coupon->type === 'percentage')
                                                    ৳{{ formatBDT($coupon?->max_discount, 2) }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $coupon?->used_count }}</span>
                                                /
                                                <span>{{ $coupon?->usage_limit ?? '∞' }}</span>
                                            </td>
                                            <td class="small">
                                                <p class="mb-2">
                                                    {{ $coupon?->start_date?->format('d M Y, h:i A') ?? 'N/A' }}</p>
                                                <p class="mb-0">
                                                    {{ $coupon?->expire_date?->format('d M Y, h:i A') ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                @if ($coupon?->expire_date > now())
                                                    @if ($coupon?->status === 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                                @else
                                                    <span class="badge bg-secondary">Expired</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('coupon.edit', $coupon?->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>
                                                    <a href="{{ route('coupon.destroy', $coupon?->id) }}"
                                                        class="btn btn-danger btn-sm deleteBtn">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-3">
                                                No coupons found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#couponTable').DataTable();
        });
    </script>
@endpush

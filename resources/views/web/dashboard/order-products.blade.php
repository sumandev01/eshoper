@extends('web.layouts.app')
@section('title', 'Address' . ' - ' . ($siteSettings->site_title ?? null))
@section('content')
    <!-- Page Header Start -->
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'User Dashboard', 'url' => route('user.dashboard')],
            ['name' => 'Product Reviews', 'url' => '']
        ]
    ])
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container pt-1">
        <div class="row">
            @include('web.dashboard.sidebar')
            <div class="col-lg-9 mb-2">
                <div class="dash-card">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <h5 class="font-weight-semi-bold mb-4" style="color: color-mix(in srgb, var(--primary) 60%, #111); font-size: 1.2rem;">All Orders Products</h5>
                            <table class="table dash-table table-hover mb-0">
                                <thead>
                                    <tr class="text-center align-middle">
                                        <th>#</th>
                                        <th class="text-start">Product Name</th>
                                        <th>Order Date</th>
                                        <th class="text-center">Delivery status</th>
                                        <th>Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderProducts ?? [] as $key => $product)
                                        <tr class="align-middle">
                                            <td class="text-center text-muted">{{ $key + 1 }}</td>
                                            <td style="max-width: 350px;">
                                                <a class="text-decoration-none text-dark font-weight-semi-bold"
                                                    href="{{ route('product.details', $product->product?->slug) }}">
                                                    {{ $product->product?->name }}
                                                </a>
                                                <div class="mt-2">
                                                    @if (isset($productReviews[$product->product_id]))
                                                        <div class="review-rating-box bg-light p-2 rounded">
                                                            <div class="star-group mb-1">
                                                                <input type="hidden" class="rating-value-active"
                                                                    value="{{ $productReviews[$product->product_id]->rating ?? 0 }}">
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="1"
                                                                    style="background: none; border: none; font-size: 13px; color: #ffc107; cursor: default; pointer-events: none; padding: 0;"></button>
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="2"
                                                                    style="background: none; border: none; font-size: 13px; color: #ffc107; cursor: default; pointer-events: none; padding: 0;"></button>
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="3"
                                                                    style="background: none; border: none; font-size: 13px; color: #ffc107; cursor: default; pointer-events: none; padding: 0;"></button>
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="4"
                                                                    style="background: none; border: none; font-size: 13px; color: #ffc107; cursor: default; pointer-events: none; padding: 0;"></button>
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="5"
                                                                    style="background: none; border: none; font-size: 13px; color: #ffc107; cursor: default; pointer-events: none; padding: 0;"></button>
                                                            </div>
                                                            <span class="text-muted small d-block" style="line-height: 1.4;">{{ $productReviews[$product->product_id]->review_text }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted small"><i class="fas fa-info-circle me-1"></i>No review submitted yet</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center text-muted">{{ $product->order->created_at->format('d-M-Y') }}</td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill dash-badge badge-soft-{{ $product?->order?->order_status?->color() === 'success' ? 'success' : ($product?->order?->order_status?->color() === 'warning' ? 'warning' : 'info') }} px-3 py-2">
                                                    {{ ucfirst($product?->order?->order_status?->value) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($product->order->order_status->value === \App\Enums\OrderStatusEnums::DELIVERED->value)
                                                    @if (isset($productReviews[$product->product_id]))
                                                        <span class="badge badge-soft-success rounded-pill px-2 py-1"><i class="fas fa-check me-1"></i>Submitted</span>
                                                    @else
                                                        <button class="btn btn-primary theme-shadow transition-all hover-up editBtn px-3 py-1"
                                                            style="border-radius: 8px; font-weight: 500;"
                                                            data-product_id="{{ $product->product_id }}"
                                                            data-name="{{ $product->product?->name }}" data-bs-toggle="modal"
                                                            data-bs-target="#editTagModal">
                                                            <i class="fas fa-star me-1"></i> Review
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">Not Available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $orderProducts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dashboard End -->

    <!-- Product Review Modal -->
    <div class="modal fade" id="editTagModal" tabindex="-1" data-backdrop="static" aria-labelledby="editTagModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="reviewForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTagModalLabel">Product Review</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 mb-3">
                            <input type="hidden" name="product_id" id="product_id" value="">
                            <label for="rating_value" class="form-label">Your Rating</label>
                            <div class="rating-container">
                                <button type="button" class="star-btn outline-none far fa-star" data-value="1"
                                    style="background: none; border: none; font-size: 24px; color: #ffc107; cursor: pointer;"></button>
                                <button type="button" class="star-btn outline-none far fa-star" data-value="2"
                                    style="background: none; border: none; font-size: 24px; color: #ffc107; cursor: pointer;"></button>
                                <button type="button" class="star-btn outline-none far fa-star" data-value="3"
                                    style="background: none; border: none; font-size: 24px; color: #ffc107; cursor: pointer;"></button>
                                <button type="button" class="star-btn outline-none far fa-star" data-value="4"
                                    style="background: none; border: none; font-size: 24px; color: #ffc107; cursor: pointer;"></button>
                                <button type="button" class="star-btn outline-none far fa-star" data-value="5"
                                    style="background: none; border: none; font-size: 24px; color: #ffc107; cursor: pointer;"></button>

                                <input type="hidden" name="rating" id="rating_value" value="" required>
                            </div>
                            @error('rating')
                                <span class="text-danger mt-2">{{ $message }}</span>
                            @enderror
                            <div class="mt-3">
                                <label for="review" class="form-label">Your Review</label>
                                <textarea class="form-control" id="review" name="review" rows="4" placeholder="Write your review here..."
                                    maxlength="1000" required></textarea>
                                @error('review')
                                    <span class="text-danger mt-2">{{ $message }}</span>
                                @enderror
                            </div>
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
@push('styles')
    <style>
        .star-btn:focus {
            outline: 0 !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {

            $('.editBtn').on('click', function() {
                let id = $(this).data('product_id');
                console.log('Product ID for review:', id);

                $('#reviewForm').find('input[name=product_id]').val(id);

                let url = "{{ route('review.store') }}";
                $('#reviewForm').attr('action', url);
            });

            $(document).on('click', '.star-btn', function() {
                let value = $(this).data('value');

                $('#rating_value').val(value);

                $('.star-btn').removeClass('fas').addClass('far');

                $('.star-btn').each(function(index) {
                    if (index < value) {
                        $(this).removeClass('far').addClass('fas');
                    }
                });
            });

            $('.star-group').each(function() {
                let $group = $(this);
                let dbValue = parseInt($group.find('.rating-value-active').val());
                if (dbValue > 0) {
                    $group.find('.star-btn-active').each(function(index) {
                        if (index < dbValue) {
                            $(this).removeClass('far').addClass('fas');
                        }
                    });
                }
            });
        });
    </script>
@endpush





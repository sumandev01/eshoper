@extends('web.layouts.app')
@section('title', 'Address' . ' - ' . $siteSettings?->site_title)
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li><a class="nav-link p-0" href="{{ route('user.dashboard') }}">User Dashboard</a></li>
                        <li>Product Reviews</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Dashboard Start -->
    <div class="container-fluid pt-2">
        <div class="row px-xl-5">
            @include('web.dashboard.sidebar')
            <div class="col-lg-9 mb-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <h5 class="font-weight-semi-bold mb-4">All Orders Products</h5>
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-primary">
                                    <tr class="text-center align-middle">
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Order Date</th>
                                        <th class="text-center">Delivery status</th>
                                        <th>Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderProducts ?? [] as $key => $product)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td style="max-width: 350px;">
                                                <a class="text-decoration-none text-dark font-weight-bold"
                                                    href="{{ route('productDetails', $product->product->slug) }}">
                                                    {{ $product->product?->name }}
                                                </a>
                                                <div>
                                                    @if (isset($productReviews[$product->product_id]))
                                                        <div class="review-rating-box">
                                                            <div class="star-group">
                                                                <input type="hidden" class="rating-value-active"
                                                                    value="{{ $productReviews[$product->product_id]->rating ?? 0 }}">
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="1"
                                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="2"
                                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="3"
                                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="4"
                                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                                <button type="button" class="star-btn-active far fa-star"
                                                                    data-value="5"
                                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                            </div>
                                                            <span class="text-muted">{{ $productReviews[$product->product_id]->review_text }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No review submitted yet</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-right">{{ $product->order->created_at->format('d-M-Y') }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge rounded-pill px-3 py-2 text-white bg-{{ $product?->order?->order_status?->color() }}">
                                                    {{ ucfirst($product?->order?->order_status?->value) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($product->order->order_status->value === \App\Enums\OrderStatusEnums::DELIVERED->value)
                                                    @if (isset($productReviews[$product->product_id]))
                                                        <span class="text-success">Submitted</span>
                                                    @else<button class="btn btn-transparent editBtn"
                                                            data-product_id="{{ $product->product_id }}"
                                                            data-name="{{ $product->product?->name }}" data-toggle="modal"
                                                            data-target="#editTagModal">
                                                            <i class="fas fa-comment-dots"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Not Available</span>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
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
                        <button type="button" class="btn btn-danger btn-icon-text" data-dismiss="modal">
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
@push('script')
    <script>
        $(document).ready(function() {

            $('.editBtn').on('click', function() {
                let id = $(this).data('product_id');
                console.log('Product ID for review:', id);

                $('#reviewForm').find('input[name=product_id]').val(id);

                let url = "{{ route('user.addReview') }}";
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

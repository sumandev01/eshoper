@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Product Reviews Management')

@section('content')
    <div class="container-fluid px-0">
        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold mb-1">Product Reviews</h4>
                <p class="text-muted small mb-0">Manage customer ratings, feedback, and moderation statuses.</p>
            </div>
        </div>

        {{-- Summary Widgets (4 Cards) --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block text-uppercase mb-1">Total Reviews</span>
                            <h3 class="fw-bold mb-0">{{ number_format($totalCount) }}</h3>
                        </div>
                        <div class="avatar-shape bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-comment-text-multiple-outline fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block text-uppercase mb-1">Pending Approval</span>
                            <h3 class="fw-bold text-warning mb-0">{{ number_format($pendingCount) }}</h3>
                        </div>
                        <div class="avatar-shape bg-warning-subtle text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-clock-outline fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block text-uppercase mb-1">Approved Reviews</span>
                            <h3 class="fw-bold text-success mb-0">{{ number_format($approvedCount) }}</h3>
                        </div>
                        <div class="avatar-shape bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-check-circle-outline fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block text-uppercase mb-1">Average Rating</span>
                            <h3 class="fw-bold text-primary mb-0">⭐ {{ $avgRating }} <span class="fs-6 text-muted font-normal">/ 5.0</span></h3>
                        </div>
                        <div class="avatar-shape bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="mdi mdi-star-outline fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Card --}}
        <div class="card border-0 shadow-sm">
            {{-- Navigation Tabs & Filter Bar --}}
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    {{-- Status Filter Tabs --}}
                    <ul class="nav nav-pills card-header-pills gap-2">
                        <li class="nav-item">
                            <a class="nav-link {{ request('status', 'all') === 'all' ? 'active' : '' }}" 
                               href="{{ route('admin.product-review.index', array_merge(request()->except('page'), ['status' => 'all'])) }}">
                                All Reviews <span class="badge rounded-pill bg-secondary ms-1">{{ $totalCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') === '0' ? 'active' : '' }}" 
                               href="{{ route('admin.product-review.index', array_merge(request()->except('page'), ['status' => '0'])) }}">
                                Pending <span class="badge rounded-pill bg-warning text-dark ms-1">{{ $pendingCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') === '1' ? 'active' : '' }}" 
                               href="{{ route('admin.product-review.index', array_merge(request()->except('page'), ['status' => '1'])) }}">
                                Approved <span class="badge rounded-pill bg-success ms-1">{{ $approvedCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') === '2' ? 'active' : '' }}" 
                               href="{{ route('admin.product-review.index', array_merge(request()->except('page'), ['status' => '2'])) }}">
                                Rejected <span class="badge rounded-pill bg-danger ms-1">{{ $rejectedCount }}</span>
                            </a>
                        </li>
                    </ul>

                    {{-- Rating Filter Dropdown --}}
                    <form action="{{ route('admin.product-review.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        @if(request('status') !== null)
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <select name="rating" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 160px;">
                            <option value="">All Ratings</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Stars)</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Stars)</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 Stars)</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 Stars)</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ (1 Star)</option>
                        </select>
                        @if(request('rating'))
                            <a href="{{ route('admin.product-review.index', ['status' => request('status')]) }}" class="btn btn-sm btn-light" title="Reset rating filter">
                                <i class="mdi mdi-refresh"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Table View --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="ps-3">#</th>
                                <th style="min-width: 250px;">Product & Review</th>
                                <th>User</th>
                                <th>Rating</th>
                                <th>Date</th>
                                <th class="text-center" style="width: 120px;">Status</th>
                                <th class="text-end pe-3" style="min-width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $review)
                                <tr>
                                    <td class="ps-3 fw-semibold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark text-truncate mb-1" style="max-width: 320px;" title="{{ $review->product?->name }}">
                                                <i class="mdi mdi-package-variant-closed me-1 text-primary"></i>
                                                {{ $review->product?->name ?? 'Unknown Product' }}
                                            </span>
                                            <p class="text-muted small mb-0 text-truncate" style="max-width: 350px;" title="{{ $review->review_text }}">
                                                "{{ $review->review_text }}"
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar-title rounded-circle bg-secondary-subtle text-secondary fw-bold px-2 py-1 small">
                                                {{ strtoupper(substr($review->user?->name ?? 'U', 0, 1)) }}
                                            </span>
                                            <span class="fw-semibold small">{{ $review->user?->name ?? 'Guest User' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= ($review->rating ?? 0) ? 'text-warning' : 'text-secondary opacity-25' }}" style="font-size: 13px;"></i>
                                            @endfor
                                            <span class="ms-1 small fw-bold text-muted">({{ $review->rating ?? 0 }})</span>
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $review->created_at ? $review->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($review->status === 1)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                                <i class="mdi mdi-check-circle me-1"></i>Approved
                                            </span>
                                        @elseif ($review->status === 0)
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded-pill">
                                                <i class="mdi mdi-clock-outline me-1"></i>Pending
                                            </span>
                                        @elseif ($review->status === 2)
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                                <i class="mdi mdi-close-circle me-1"></i>Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            {{-- Inline Approve Action --}}
                                            @if($review->status !== 1)
                                                @can(App\Enums\Permission\CommentPermission::UPDATE->value)
                                                    <form action="{{ route('admin.product-review.update', $review->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="1">
                                                        <button type="submit" class="btn btn-sm btn-outline-success p-1 px-2" title="Approve Review">
                                                            <i class="mdi mdi-check"></i> Approve
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif

                                            {{-- Inline Reject Action --}}
                                            @if($review->status !== 2)
                                                @can(App\Enums\Permission\CommentPermission::UPDATE->value)
                                                    <form action="{{ route('admin.product-review.update', $review->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="2">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2" title="Reject Review">
                                                            <i class="mdi mdi-close"></i> Reject
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif

                                            {{-- View Details Modal Button --}}
                                            <button type="button" 
                                                class="btn btn-sm btn-light btn-icon text-primary ms-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewReviewModal"
                                                data-product="{{ $review->product?->name ?? 'N/A' }}"
                                                data-user="{{ $review->user?->name ?? 'Guest' }}"
                                                data-rating="{{ $review->rating ?? 0 }}"
                                                data-review="{{ $review->review_text }}"
                                                data-date="{{ $review->created_at ? $review->created_at->format('M d, Y') : '' }}"
                                                title="View Review Details">
                                                <i class="mdi mdi-eye"></i>
                                            </button>

                                            {{-- Delete Button --}}
                                            @can(App\Enums\Permission\CommentPermission::DELETE->value)
                                                <form action="{{ route('admin.product-review.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light btn-icon text-danger" title="Delete Review">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="mdi mdi-comment-remove-outline fs-1 d-block mb-2 text-secondary"></i>
                                        <h6>No Product Reviews Found</h6>
                                        <p class="small mb-0">Try changing your filter options or selecting another status tab.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Footer --}}
            @if($reviews->hasPages())
                <div class="card-footer bg-transparent border-top py-3">
                        {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- View Review Details Modal --}}
    <div class="modal fade" id="viewReviewModal" tabindex="-1" aria-labelledby="viewReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold" id="viewReviewModalLabel">
                        <i class="mdi mdi-comment-text-outline text-primary me-2"></i>Review Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <span class="text-muted small d-block">Product</span>
                        <h6 class="fw-bold text-dark mb-0" id="modalProductName"></h6>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <span class="text-muted small d-block">Submitted By</span>
                            <span class="fw-semibold text-dark" id="modalUserName"></span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small d-block">Date</span>
                            <span class="text-muted small" id="modalDate"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Rating</span>
                        <div id="modalRatingStars" class="d-flex align-items-center gap-1"></div>
                    </div>
                    <div>
                        <span class="text-muted small d-block mb-1">Customer Review</span>
                        <div class="p-3 bg-light rounded text-dark" id="modalReviewText" style="white-space: pre-line; min-height: 80px;"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Modal details population & stars rendering
            $('#viewReviewModal').on('show.bs.modal', function(e) {
                let button = $(e.relatedTarget);
                let product = button.data('product');
                let user = button.data('user');
                let rating = parseInt(button.data('rating')) || 0;
                let review = button.data('review');
                let date = button.data('date');

                $('#modalProductName').text(product);
                $('#modalUserName').text(user);
                $('#modalReviewText').text(review);
                $('#modalDate').text(date);

                // Scoped modal stars rendering
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '<i class="fa fa-star text-warning" style="font-size: 16px;"></i>';
                    } else {
                        starsHtml += '<i class="fa fa-star text-secondary opacity-25" style="font-size: 16px;"></i>';
                    }
                }
                starsHtml += '<span class="ms-2 fw-bold text-muted">(' + rating + '/5)</span>';
                $('#modalRatingStars').html(starsHtml);
            });
        });
    </script>
@endpush

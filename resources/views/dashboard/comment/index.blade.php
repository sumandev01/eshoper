@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Comments')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header py-3">
                    <h3>All Comments</h3>
                    <p class="mb-0 text-muted">List of all comments</p>
                </div>
                <div class="card-body pt-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="commentTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Comment</th>
                                    <th class="text-center">User</th>
                                    <th>Date</th>
                                    <th class="text-center" style="width: 100px">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <h5 title="{{ $review->product?->name }}">
                                                {{ Str::limit($review->product?->name, 20) }}</h5>
                                            <div class="star-group">
                                                <input type="hidden" class="rating-value-active"
                                                    value="{{ $review->rating ?? 0 }}">

                                                <button type="button" class="star-btn-active fa fa-star-o" data-value="1"
                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                <button type="button" class="star-btn-active fa fa-star-o" data-value="2"
                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                <button type="button" class="star-btn-active fa fa-star-o" data-value="3"
                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                <button type="button" class="star-btn-active fa fa-star-o" data-value="4"
                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                <button type="button" class="star-btn-active fa fa-star-o" data-value="5"
                                                    style="background: none; border: none; font-size: 15px; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                            </div>
                                            <span
                                                title="{{ $review->review_text }}">{{ Str::limit($review->review_text, 50) }}</span>
                                        </td>
                                        <td class="text-center">{{ $review->user?->name }}</td>
                                        <td>{{ $review->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            @if ($review->status === 1)
                                                <span class="badge bg-success">Approved</span>
                                            @elseif ($review->status === 0)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($review->status === 2)
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button
                                                data-review='{{ json_encode($review->only(['id', 'review_text', 'rating', 'status'])) }}'
                                                data-product="{{ $review?->product?->name }}"
                                                data-user="{{ $review?->user?->name }}" data-bs-toggle="modal"
                                                data-bs-target="#viewCommentModal" class="btn btn-sm btn-primary">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                            @can(App\Enums\Permission\CommentPermission::UPDATE->value)
                                                <button data-id="{{ $review->id }}" data-status="{{ $review->status }}"
                                                    data-bs-toggle="modal" data-bs-target="#editCommentModal"
                                                    class="btn btn-sm btn-secondary">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- View Comment Modal --}}
    <div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="viewCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content p-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewCommentModalLabel">View Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="modalProductName"></h5>
                    <p><strong>User:</strong> <span id="modalUserName"></span></p>
                    <div class="star-group mb-2">
                        <button type="button" class="star-btn-active fa fa-star" data-value="1"
                            style="background: none; border: none; font-size: 20px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                        <button type="button" class="star-btn-active fa fa-star" data-value="2"
                            style="background: none; border: none; font-size: 20px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                        <button type="button" class="star-btn-active fa fa-star" data-value="3"
                            style="background: none; border: none; font-size: 20px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                        <button type="button" class="star-btn-active fa fa-star" data-value="4"
                            style="background: none; border: none; font-size: 20px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                        <button type="button" class="star-btn-active fa fa-star" data-value="5"
                            style="background: none; border: none; font-size: 20px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                    </div>
                    <p id="modalReviewText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Comment Modal --}}
    <div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content p-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editCommentModalLabel">Edit Comment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCommentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="editCommentId" name="comment_id">
                        <div class="mb-3">
                            <label for="commentStatus" class="form-label">Status</label>
                            <select class="form-select" id="commentStatus" name="status" required>
                                <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>Approved</option>
                                <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>Pending</option>
                                <option value="2" {{ $review->status == 2 ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#commentTable').DataTable();

            $('.star-group').each(function() {
                let $group = $(this);
                let dbValue = parseInt($group.find('.rating-value-active').val());

                if (dbValue > 0) {
                    $group.find('.star-btn-active').each(function(index) {
                        if (index < dbValue) {
                            $(this).removeClass('fa-star-o').addClass('fa-star');
                        }
                    });
                }
            });

            $('#viewCommentModal').on('show.bs.modal', function(e) {
                let data = $(e.relatedTarget).data('review');
                $('#modalProductName').text($(e.relatedTarget).data('product'));
                $('#modalUserName').text($(e.relatedTarget).data('user'));
                $('#modalReviewText').text(data.review_text);
                $('#rating_value_active').val(data.rating);
                $('.star-btn-active').each(function(index) {
                    if (index < data.rating) {
                        $(this).removeClass('far').addClass('fas');
                    }
                });
            });

            $('#editCommentModal').on('show.bs.modal', function(e) {
                let commentId = $(e.relatedTarget).data('id');
                let status = $(e.relatedTarget).data('status');
                $('#editCommentId').val(commentId);
                $('#commentStatus').val(status);
                let url = "{{ route('admin.comment.update', ':id') }}";
                url = url.replace(':id', commentId);
                $('#editCommentForm').attr('action', url);
            });
        });
    </script>
@endpush



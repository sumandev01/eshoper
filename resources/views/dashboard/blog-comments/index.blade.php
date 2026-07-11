@extends('dashboard.layouts.app')
@section('title', 'Blog Comments')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                    <h5>Blog Comments Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.blog-comments.update-settings') }}" method="POST">
                        @csrf
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="blog_comment_auto_approve"
                                id="blog_comment_auto_approve" value="1"
                                {{ ($siteSettings->blog_comment_auto_approve ?? '0') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-bold" for="blog_comment_auto_approve">Enable Auto
                                Approve for New Comments</label>
                            <small class="d-block text-muted">If enabled, new blog comments will be published immediately
                                without waiting for admin approval.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                    <h5>All Comments</h5>
                    <div>
                        <select id="statusFilter" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-4">
                    <table class="table table-bordered table-hover table-striped" id="commentTable">
                        <thead>
                            <tr>
                                <th style="width: 50px">Sl</th>
                                <th>Blog</th>
                                <th>User/Name</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comments as $key => $comment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a href="{{ route('web.blogs.show', $comment->blog->slug) }}"
                                            target="_blank">{{ Str::limit($comment->blog->title, 30) }}</a>
                                    </td>
                                    <td>
                                        {{ $comment->user ? $comment->user->name : $comment->name }}<br>
                                        <small class="text-muted">{{ $comment->email ?? $comment->user?->email }}</small>
                                    </td>
                                    <td>
                                        <p class="mb-1" style="max-width: 300px; white-space: normal;">
                                            {{ Str::limit($comment->comment, 80) }}</p>
                                        @if ($comment->parent_id)
                                            <span class="badge bg-info">Reply</span>
                                        @endif
                                        <small
                                            class="text-muted d-block">{{ $comment->created_at->format('d M, Y h:i A') }}</small>
                                    </td>
                                    <td data-search="{{ $comment->status == 0 ? 'Pending' : ($comment->status == 1 ? 'Approved' : 'Rejected') }}">
                                        <form action="{{ route('admin.blog-comments.status', $comment->id) }}"
                                            method="POST">
                                            @csrf
                                            <select name="status"
                                                class="form-select form-select-sm {{ $comment->status == 0 ? 'border-warning text-warning' : ($comment->status == 1 ? 'border-success text-success' : 'border-danger text-danger') }}"
                                                onchange="this.form.submit()">
                                                <option value="0" {{ $comment->status == 0 ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="1" {{ $comment->status == 1 ? 'selected' : '' }}>
                                                    Approved</option>
                                                <option value="2" {{ $comment->status == 2 ? 'selected' : '' }}>
                                                    Rejected</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-info btn-sm view-comment-btn" 
                                            data-name="{{ $comment->user ? $comment->user->name : $comment->name }}"
                                            data-email="{{ $comment->email ?? $comment->user?->email }}"
                                            data-blog="{{ $comment->blog->title }}"
                                            data-date="{{ $comment->created_at->format('d M, Y h:i A') }}"
                                            data-comment="{{ $comment->comment }}"
                                            data-bs-toggle="modal" data-bs-target="#viewCommentModal">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.blog-comments.destroy', $comment->id) }}"
                                            class="btn btn-danger btn-sm deleteBtn"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">No Comments Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Comment Modal -->
    <div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="viewCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCommentModalLabel">Comment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Blog:</strong> <span id="modalBlogTitle"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Name:</strong> <span id="modalName"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> <span id="modalEmail"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Date:</strong> <span id="modalDate"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Comment:</strong>
                        <div id="modalCommentText" class="mt-2 p-3 bg-light rounded" style="white-space: pre-wrap;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .form-switch .form-check-input {
            margin-left: 0;
            margin-right: 10px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#commentTable').DataTable({
                "paging": false,
                "info": false
            });

            $('#statusFilter').on('change', function() {
                table.column(4).search(this.value).draw();
            });

            $('.view-comment-btn').on('click', function() {
                var name = $(this).data('name');
                var email = $(this).data('email');
                var blog = $(this).data('blog');
                var date = $(this).data('date');
                var comment = $(this).data('comment');

                $('#modalName').text(name);
                $('#modalEmail').text(email);
                $('#modalBlogTitle').text(blog);
                $('#modalDate').text(date);
                $('#modalCommentText').text(comment);
            });
        });
    </script>
@endpush

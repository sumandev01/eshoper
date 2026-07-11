@extends('web.layouts.app')
@section('title', $blog->meta_title ?? $blog->title)
@section('meta_description', $blog->meta_description)
@section('meta_keyword', $blog->meta_keyword)

@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'Blogs', 'url' => route('web.blogs.index')],
            ['name' => $blog->title, 'url' => '']
        ]
    ])

    <!-- Blog Detail Start -->
    <div class="container-fluid pt-1 pb-5">
        <div class="row px-xl-5">
            <!-- Blog Content -->
            <div class="col-lg-9 col-md-12 mb-5">
                <div class="bg-light p-5">
                    @if($blog->thumbnail)
                        <img src="{{ $blog->thumbnail }}" class="img-fluid rounded mb-4" alt="{{ $blog->title }}" style="width: 100%; max-height: 500px; object-fit: cover;">
                    @endif
                    <h1 class="font-weight-semi-bold mb-3">{{ $blog->title }}</h1>
                    <div class="d-flex mb-4">
                        <small class="text-muted me-3"><i class="fa fa-folder text-primary me-2"></i>{{ $blog->category?->name }}</small>
                        <small class="text-muted me-3"><i class="fa fa-calendar-alt text-primary me-2"></i>{{ $blog->created_at->format('d M, Y') }}</small>
                        <small class="text-muted"><i class="fa fa-eye text-primary me-2"></i>{{ $blog->views }} Views</small>
                    </div>
                    
                    <div class="blog-content">
                        {!! $blog->content !!}
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-5">
                        <h4 class="mb-4">{{ $comments->count() }} Comments</h4>
                        
                        @foreach($comments as $comment)
                        <div class="media mb-4 p-3 bg-white shadow-sm">
                            <div class="media-body">
                                <h6>{{ $comment->name }} <small><i>{{ $comment->created_at->format('d M Y') }}</i></small></h6>
                                <p>{{ $comment->comment }}</p>
                                <button class="btn btn-sm btn-outline-primary reply-btn" data-id="{{ $comment->id }}">Reply</button>

                                @foreach($comment->replies as $reply)
                                <div class="media mt-4 p-3 bg-light">
                                    <div class="media-body">
                                        <h6>{{ $reply->name }} <small><i>{{ $reply->created_at->format('d M Y') }}</i></small></h6>
                                        <p>{{ $reply->comment }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Leave a Comment -->
                    <div class="bg-white p-4 mt-5 shadow-sm">
                        <h4 class="mb-4">Leave a comment</h4>
                        <form action="{{ route('web.blogs.comment', $blog->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="parent_id" id="parent_id" value="">
                            
                            @if(!auth()->check())
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Name *</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="message">Message *</label>
                                <textarea id="message" name="comment" cols="30" rows="5" class="form-control" maxlength="500" required></textarea>
                                <small class="text-muted d-block mt-1 text-end"><span id="charCount">0</span>/500 characters</small>
                            </div>
                            <div class="mb-3 mb-0">
                                <button type="submit" class="btn btn-primary px-3">Leave Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right Side) -->
            @include('web.blog.sidebar')
        </div>
    </div>
    <!-- Blog Detail End -->
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.reply-btn').click(function() {
            var commentId = $(this).data('id');
            $('#parent_id').val(commentId);
            $('#message').focus();
            
            // Show a small indicator that we are replying
            if($('#reply-indicator').length === 0) {
                $('<div id="reply-indicator" class="alert alert-info mt-2">Replying to comment. <a href="#" id="cancel-reply">Cancel</a></div>').insertAfter('h4:contains("Leave a comment")');
            }
        });

        $(document).on('click', '#cancel-reply', function(e) {
            e.preventDefault();
            $('#parent_id').val('');
            $('#reply-indicator').remove();
            $('#message').focus();
        });

        $('#message').on('input', function() {
            var currentLength = $(this).val().length;
            $('#charCount').text(currentLength);
            
            if (currentLength >= 500) {
                $('#charCount').addClass('text-danger');
            } else {
                $('#charCount').removeClass('text-danger');
            }
        });
    });
</script>
@endpush



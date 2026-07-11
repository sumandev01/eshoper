@extends('web.layouts.app')
@section('title', 'Blog & Articles')
@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => 'Blogs', 'url' => '']
        ]
    ])

    <!-- Blog List Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <!-- Blog List (Left Side) -->
            <div class="col-lg-9 col-md-12">
                <div class="row">
                    @forelse ($blogs as $blog)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="bg-white theme-shadow transition-all hover-up h-100 d-flex flex-column" style="border-radius: 12px;">
                                <a href="{{ route('web.blogs.show', $blog->slug) }}" class="overflow-hidden" style="border-radius: 12px 12px 0 0;">
                                    <img class="img-fluid w-100" src="{{ $blog->thumbnail }}" alt="{{ $blog->title }}" style="height: 220px; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                </a>
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <h5 class="font-weight-semi-bold mb-3" style="line-height: 1.4;">
                                        <a href="{{ route('web.blogs.show', $blog->slug) }}" class="text-decoration-none transition-all" style="color: color-mix(in srgb, var(--primary) 60%, #111);" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='color-mix(in srgb, var(--primary) 60%, #111)'">{{ $blog->title }}</a>
                                    </h5>
                                    <div class="d-flex mb-3">
                                        <small class="text-muted me-3"><i class="fa fa-folder text-primary me-2"></i>{{ $blog->category?->name }}</small>
                                        <small class="text-muted"><i class="fa fa-calendar-alt text-primary me-2"></i>{{ $blog->created_at->format('d M, Y') }}</small>
                                    </div>
                                    <p class="text-muted mb-4">{{ Str::limit(strip_tags($blog->content), 100) }}</p>
                                    <div class="mt-auto">
                                        <a href="{{ route('web.blogs.show', $blog->slug) }}" class="btn btn-primary theme-shadow transition-all hover-up px-4 py-2" style="border-radius: 8px; font-weight: 500;">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <h5>No Articles Found</h5>
                        </div>
                    @endforelse
                    
                    <div class="col-12 mt-4">
                        {{ $blogs->withQueryString()->links() }}
                    </div>
                </div>
            </div>

            <!-- Sidebar (Right Side) -->
            @include('web.blog.sidebar')
        </div>
    </div>
    <!-- Blog List End -->
@endsection



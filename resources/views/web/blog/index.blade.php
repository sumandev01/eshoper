@extends('web.layouts.app')
@section('title', 'Blog & Articles')
@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [['name' => 'Blogs', 'url' => '']],
    ])

    <!-- Blog List Start -->
    <div class="container pt-1 pb-5">
        <div class="row">
            <!-- Blog List (Left Side) -->
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    @forelse ($blogs as $blog)
                        <div class="col-md-6 mb-4">
                            @include('web.components.blog_card', ['blog' => $blog])
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

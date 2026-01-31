@extends('dashboard.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="page-header flex-wrap">
            <div class="header-left">
                <h3 class="page-title"> Media Gallery </h3>
            </div>
            <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
                <a href="{{ route('admin.media.add') }}" class="btn btn-primary mt-2 mt-sm-0 btn-icon-text">
                    <i class="mdi mdi-plus-circle"></i> Add New Media
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <p class="card-description m-0"> Showing all uploaded files </p>
                        <div class="view-icons">
                            <button id="grid-view-btn" class="btn btn-outline-secondary btn-sm active">
                                <i class="mdi mdi-grid"></i>
                            </button>
                            <button id="list-view-btn" class="btn btn-outline-secondary btn-sm">
                                <i class="mdi mdi-format-list-bulleted"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row grid-view" id="media-wrapper">
            @foreach ($medias as $media)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 grid-margin stretch-card media-item">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-2 text-center">
                            <div class="media-thumb position-relative"
                                style="height: 180px; overflow: hidden; background: #f8f9fa; border-radius: 8px;">
                                <img src="{{ asset($media->thumbnail) }}" alt="media" class="img-fluid w-100 h-100"
                                    style="object-fit: contain;">

                                <div class="media-actions position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
                                    style="top:0; left:0; background: rgba(0,0,0,0.5); opacity:0; transition: 0.3s;">
                                    <button class="btn btn-primary btn-sm mx-1" onclick="window.location.href='{{ route('admin.media.edit', $media->id) }}'"><i class="fa fa-edit"></i></button>
                                    <a href="{{ route('admin.media.destroy', $media->id) }}" class="btn btn-danger deleteBtn btn-sm mx-1">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="mt-2 text-left px-2">
                                <h6 class="text-truncate mb-1" title="Sample-image-name.jpg">{{ $media->file_name }}</h6>
                                <small class="text-muted">
                                    @if ($media->size < 1000000)
                                        {{-- 1024 * 1024 Bytes = 1 MB --}}
                                        {{ number_format($media->size / 1024, 2) }} KB
                                    @else
                                        {{ number_format($media->size / (1024 * 1024), 2) }} MB
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $medias->links() }}
        </div>
    </div>

    <style>
        /* Hover effect styling */
        .media-thumb:hover .media-actions {
            opacity: 1 !important;
        }

        .media-thumb img {
            transition: transform 0.5s ease;
        }

        .media-thumb:hover img {
            transform: scale(1.1);
        }
    </style>
    <script>
        const gridBtn = document.getElementById('grid-view-btn');
        const listBtn = document.getElementById('list-view-btn');
        const wrapper = document.getElementById('media-wrapper');

        listBtn.addEventListener('click', function() {
            wrapper.classList.remove('row', 'grid-view');
            wrapper.classList.add('list-view');

            listBtn.classList.add('active');
            gridBtn.classList.remove('active');

            document.querySelectorAll('.media-item').forEach(item => {
                item.classList.remove('col-md-3');
                item.classList.add('col-12', 'mb-2');
            });
        });

        gridBtn.addEventListener('click', function() {
            wrapper.classList.add('row', 'grid-view');
            wrapper.classList.remove('list-view');

            gridBtn.classList.add('active');
            listBtn.classList.remove('active');

            document.querySelectorAll('.media-item').forEach(item => {
                item.classList.add('col-md-3');
                item.classList.remove('col-12', 'mb-2');
            });
        });
    </script>
@endsection

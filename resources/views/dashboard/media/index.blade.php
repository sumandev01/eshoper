{{-- resources/views/admin/media/index.php --}}

@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'Media Gallery')
@section('content')
    <div class="page-header flex-wrap">
        <div class="header-left">
            <h3 class="page-title"> Media Gallery </h3>
        </div>
        <div class="header-right d-flex flex-wrap mt-2 mt-sm-0">
            @can(\App\Enums\Permission\MediaPermission::CREATE->value)
                <a href="{{ route('admin.media.add') }}" class="btn btn-primary mt-2 mt-sm-0 btn-icon-text">
                    <i class="mdi mdi-plus-circle"></i> Add New Media
                </a>
            @endcan
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3 m-0">
                            <input class="form-check-input ms-0 select-all-checkbox" type="checkbox" id="selectAllMedia">
                            <label class="form-check-label mb-0" for="selectAllMedia" style="cursor: pointer;">
                                Select All
                            </label>
                        </div>
                        @can(\App\Enums\Permission\MediaPermission::DELETE->value)
                            <x-bulk-action url="{{ route('admin.media.bulk-delete') }}" param="media_ids[]" />
                        @endcan
                    </div>
                    <div class="view-icons">
                        {{-- Buttons with IDs for JS selection --}}
                        <button id="grid-view-btn" class="btn btn-outline-secondary btn-sm" title="Grid View">
                            <i class="mdi mdi-grid"></i>
                        </button>
                        <button id="list-view-btn" class="btn btn-outline-secondary btn-sm" title="List View">
                            <i class="mdi mdi-format-list-bulleted"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Set initial class based on URL query param 'view' --}}
    @php
        $currentView = request()->query('view', 'grid'); // Default to 'grid' if no param
        $wrapperClass = $currentView === 'list' ? 'list-view' : 'grid-view';
    @endphp

    <div class="row {{ $wrapperClass }}" id="media-wrapper">
        @foreach ($medias as $media)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 grid-margin stretch-card media-item">
                <div class="card shadow-sm border-0 w-100">
                    <div class="form-check position-absolute mt-0 mb-0 delete-checkbox" style="top: 8px; left: 30px; z-index: 10;">
                        <input class="form-check-input media-checkbox bulk-item-checkbox" type="checkbox" value="{{ $media->id }}"
                            id="media_{{ $media->id }}"
                            style="width: 18px; height: 18px; cursor: pointer; border: 1px solid rgba(0,0,0,0.5); box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    </div>
                    <div class="card-body p-2 text-center">
                        <div class="media-thumb position-relative"
                            style="height: 180px; overflow: hidden; background: #f8f9fa; border-radius: 8px;">

                            <img src="{{ asset($media->thumbnail) }}" alt="media" class="img-fluid w-100 h-100"
                                style="object-fit: contain;">
                            {{-- Grid view specific actions overlay --}}
                            <div class="media-actions grid-actions position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
                                style="top:0; left:0; background: rgba(0,0,0,0.5); opacity:0; transition: 0.3s;">
                                @can(\App\Enums\Permission\MediaPermission::UPDATE->value)
                                    {{-- UPDATED: Call JS function to preserve view state --}}
                                    <button class="btn btn-primary btn-sm mx-1"
                                        onclick="redirectToEdit('{{ route('admin.media.edit', $media->id) }}')" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                @endcan
                                @can(\App\Enums\Permission\MediaPermission::DELETE->value)
                                    <a href="{{ route('admin.media.destroy', $media->id) }}"
                                        class="btn btn-danger deleteBtn btn-sm mx-1" title="Delete">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>

                        {{-- Details shown below image in grid view, hidden in list view --}}
                        <div class="media-content mt-2 text-left px-2">
                            <h6 class="text-truncate mb-1" title="{{ $media->file_name }}">{{ $media->file_name }}</h6>
                            <small class="text-muted">
                                @if ($media->size < 1000000)
                                    {{ number_format($media->size / 1024, 2) }} KB
                                @else
                                    {{ number_format($media->size / (1024 * 1024), 2) }} MB
                                @endif
                            </small>
                        </div>

                        {{-- --- NEW: Action buttons for List View (visible on hover) --- --}}
                        {{-- These buttons also need the redirectToEdit function --}}
                        <div class="media-actions list-actions">
                            @can(\App\Enums\Permission\MediaPermission::UPDATE->value)
                                <button class="btn btn-outline-primary btn-sm mx-1"
                                    onclick="redirectToEdit('{{ route('admin.media.edit', $media->id) }}')" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                            @endcan
                            @can(\App\Enums\Permission\MediaPermission::DELETE->value)
                                <a href="{{ route('admin.media.destroy', $media->id) }}"
                                    class="btn btn-outline-danger deleteBtn btn-sm mx-1" title="Delete">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-12 mt-3">
            {{ $medias->links() }}
        </div>
    </div>

    <style>
        /* Hover effect styling for Grid */
        .media-thumb:hover .media-actions {
            opacity: 1 !important;
        }

        .media-thumb img {
            transition: transform 0.5s ease;
        }

        .media-thumb:hover img {
            transform: scale(1.1);
        }

        /* Helper class to hide list actions in grid view */
        .grid-view .list-actions {
            display: none !important;
        }

        /* --- NEW: List View CSS overrides to make your original design work in a list format --- */
        .list-view .media-item {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 10px;
        }

        .list-view .card {
            flex-direction: row;
            align-items: center;
            padding: 10px;
        }

        .list-view .media-thumb {
            height: 60px !important;
            width: 60px !important;
            flex-shrink: 0;
        }

        .list-view .card-body {
            padding: 0 !important;
            padding-left: 50px !important;
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            width: 100%;
        }

        .list-view .delete-checkbox{
            left: 50px !important;
            transform: translate(0, 100%) !important;
        }

        /* Style for content wrapper in list view */
        .list-view .media-content {
            margin-top: 0 !important;
            padding-left: 15px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: flex-start;
            flex-direction: column;
            flex-grow: 1 !important;
        }

        .list-view .media-content h6 {
            margin-bottom: 0 !important;
            font-weight: bold;
        }

        .list-view .media-content small {
            margin-left: 0 !important;
        }

        /* Hide grid specific elements in list view */
        .list-view .grid-actions {
            display: none !important;
        }

        .list-view .media-content small.text-muted {
            /* Original size text is hidden by css below */
        }

        /* Show list specific actions in list view on row hover */
        .list-view .media-item:hover .list-actions {
            display: flex !important;
            opacity: 1 !important;
        }

        .list-view .list-actions {
            display: none !important;
            /* Hidden by default, shown on hover */
            opacity: 0;
            transition: opacity 0.2s;
            justify-content: flex-end;
            flex-shrink: 0;
            margin-left: 15px;
        }

        /* Hide original size text in list view to make space for actions */
        .list-view .media-content small {
            visibility: hidden;
            /* Keeps layout */
        }

        /* Active state styling for view buttons */
        .view-icons .btn.active {
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            background-color: #e6e7e8;
            border-color: #adadad;
        }
    </style>
    <script>
        const gridBtn = document.getElementById('grid-view-btn');
        const listBtn = document.getElementById('list-view-btn');
        const wrapper = document.getElementById('media-wrapper');

        // Function to get the current URL parameter
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Function to set the URL parameter and reload the page
        function setViewParam(viewType) {
            const url = new URL(window.location.href);
            url.searchParams.set('view', viewType);
            window.location.href = url.toString(); // Page reload ensures state persists
        }

        // Set initial active state on page load
        const currentView = getQueryParam('view') || 'grid'; // Default to grid
        if (currentView === 'list') {
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        } else {
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        }

        // Add event listeners for button clicks
        listBtn.addEventListener('click', function() {
            if (currentView !== 'list') {
                setViewParam('list');
            }
        });

        gridBtn.addEventListener('click', function() {
            if (currentView !== 'grid') {
                setViewParam('grid');
            }
        });

        // --- NEW: Function to redirect to edit page while preserving the current view state ---
        function redirectToEdit(editUrl) {
            const url = new URL(editUrl);
            const currentView = getQueryParam('view'); // Get 'view' from current URL
            if (currentView) {
                url.searchParams.set('view', currentView); // Append to edit URL
            }
            window.location.href = url.toString();
        }
        // --------------------------------------------------------------------------------------

        // Bulk Delete Logic handled by global script
    </script>
@endsection

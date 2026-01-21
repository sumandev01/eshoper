<div class="row gallery-container-wrapper" style="height: 400px; overflow-y:auto;">
    @forelse ($allMedia as $media)
        <div class="col-xl-3 col-md-4 col-6 mb-3 media-item media-col">
            <div class="media-item select-this-media border rounded p-1 text-center bg-white"
                style="cursor: pointer; border: 1px solid #dee2e6; aspect-ratio: 4 / 4;" 
                data-id="{{ $media->id }}"
                data-src="{{ $media->thumbnail }}">
                <img src="{{ $media->thumbnail }}" class="img-fluid rounded"
                    style="height: 100%; width: 100%; object-fit: contain;">
            </div>
            <h6 class="text-truncate mb-1" title="{{ $media->name }}">{{ $media->name }}</h6>
        </div>
    @empty
        <div class="col-12 text-center p-5">
            <p class="text-muted">No media found.</p>
        </div>
    @endforelse
</div>

<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center pt-2">
        <div class="small text-muted">
            Showing {{ $allMedia->firstItem() }} to {{ $allMedia->lastItem() }} of {{ $allMedia->total() }}
        </div>
        <div class="ajax-pagination d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous Button --}}
                    @if ($allMedia->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    @else
                        <li class="page-item">
                            <button class="page-link load-page"
                                data-url="{{ $allMedia->previousPageUrl() }}">Previous</button>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($allMedia->getUrlRange(max(1, $allMedia->currentPage() - 2), min($allMedia->lastPage(), $allMedia->currentPage() + 2)) as $page => $url)
                        <li class="page-item {{ $page == $allMedia->currentPage() ? 'active' : '' }}">
                            <button class="page-link load-page"
                                data-url="{{ $url }}">{{ $page }}</button>
                        </li>
                    @endforeach

                    {{-- Next Button --}}
                    @if ($allMedia->hasMorePages())
                        <li class="page-item">
                            <button class="page-link load-page" data-url="{{ $allMedia->nextPageUrl() }}">Next</button>
                        </li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Function to update grid based on selection mode
        function adjustGridSystem() {
            if (typeof isMultiple !== 'undefined' && isMultiple === true) {
                $('.media-col').removeClass('col-xl-3').addClass('col-xl-2');
            } else {
                $('.media-col').removeClass('col-xl-2').addClass('col-xl-3');
            }
        }

        // Apply grid adjustment immediately after content load
        adjustGridSystem();

        // Single handle for pagination to avoid multiple bindings
        $(document).off('click', '.load-page').on('click', '.load-page', function(e) {
            e.preventDefault();
            let url = $(this).data('url');

            if (url) {
                $('#ajax-media-container').animate({ opacity: 0.5 }, 200);

                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(data) {
                        $('#ajax-media-container').html(data).animate({ opacity: 1 }, 200);
                        // Re-run adjustment and selection logic
                        if (typeof reapplySelection === "function") reapplySelection();
                    },
                    error: function() {
                        $('#ajax-media-container').html('<p class="text-danger text-center">Error loading media.</p>').css('opacity', 1);
                    }
                });
            }
        });
    });
</script>
@props([
    'label' => '',
    'target_id' => 'gallery_2',
    'input_name' => 'product_galleries',
    'existing_media' => collect(),
    'thumbnail_source' => null,
    'limit' => 5, // Just added this limit prop
    'required' => false,
    'button_label' => 'Select Images',
    'button_class' => '',
])

<div class="form-group">
    @if($label)
        <label class="form-label font-weight-bold">{{ $label }}@if($required) <span class="text-danger">*</span> @endif</label>
    @endif

    {{-- <div id="media-preview-{{ $target_id }}" class="row mb-2 d-flex flex-wrap p-2 bg-white rounded border border-light"
        style="min-height: 50px; gap: 5px;"> --}}
    <div id="media-preview-{{ $target_id }}" class="row">
        @php
            // Get existing media IDs as comma separated string
            $existingIds = $existing_media->pluck('id')->implode(',');
        @endphp

        @forelse($existing_media as $media)
            <div class="col-md-3 col-sm-4 col-6 position-relative gallery-item" data-id="{{ $media->id }}"
                id="item-{{ $target_id }}-{{ $media->id }}">
                <span class="remove-oldImage-btn"
                    onclick="removeMediaFromGallery('{{ $media->id }}', '{{ $target_id }}')">&times;</span>
                <img src="{{ Storage::url($media->src) }}" class="rounded w-100 galleryItemImg">
            </div>
        @empty
            {{-- Default placeholder when no data exists --}}
            <div class="col-md-3 col-sm-4 col-6 no-image-placeholder">
            <div class=" border rounded d-flex align-items-center justify-content-center bg-white imagePreviewSingle">
                <div class="text-center text-muted">
                    <i class="mdi mdi-image-multiple-outline fs-2"></i>
                    <div style="font-size: 10px;">No Gallery</div>
                </div>
            </div>
            </div>
        @endforelse
    </div>

    {{-- Input for existing media IDs --}}
    <input type="hidden" name="{{ $input_name }}_existing" id="media-input-{{ $target_id }}-existing"
        value="{{ $existingIds }}">

    {{-- Input for deleted media IDs --}}
    <input type="hidden" name="{{ $input_name }}_deleted" id="media-input-{{ $target_id }}-deleted"
        value="">

    <button type="button" class="btn btn-info btn-sm open-media-picker {{ $button_class }}" data-target-id="{{ $target_id }}"
        data-multiple="true" data-limit="{{ $limit }}"> {{-- Added data-limit --}}
        <i class="mdi mdi-image-multiple"></i> {{ $button_label }}
    </button>
</div>

@push('scripts')
    <script>
        /**
         * Logic to check limit and filter selection
         */
        $(document).on('click', '.open-media-picker[data-target-id="{{ $target_id }}"]', function(e) {
            const limit = parseInt($(this).data('limit'));
            const currentCount = $('#media-preview-{{ $target_id }} .gallery-item').length;

            if (currentCount >= limit) {
                e.preventDefault();
                e.stopImmediatePropagation();
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Limit Reached',
                    text: 'You cannot add more than ' + limit + ' images.',
                });
                return false;
            }
        });

        // Global function to handle image insertion with limit check
        // This function should be called from your main media picker modal
        window.processMediaSelection_{{ str_replace('-', '_', $target_id) }} = function(selectedImages) {
            const limit = {{ $limit }};
            const currentCount = $('#media-preview-{{ $target_id }} .gallery-item').length;
            const availableSlots = limit - currentCount;

            if (selectedImages.length > availableSlots) {
                Swal.fire({
                    icon: 'info',
                    title: 'Selection Adjusted',
                    text: `You can only add ${availableSlots} more image(s). Only the first ${availableSlots} selected images were added.`,
                });
                // Slice the array to only allow available slots
                return selectedImages.slice(0, availableSlots);
            }
            return selectedImages;
        };

        function removeMediaFromGallery(mediaId, targetId) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#item-${targetId}-${mediaId}`).remove();

                    let existingInput = $(`#media-input-${targetId}-existing`);
                    let currentExistingIds = existingInput.val().split(',').filter(id => id != mediaId && id != "");
                    existingInput.val(currentExistingIds.join(','));

                    let deletedInput = $(`#media-input-${targetId}-deleted`);
                    let deletedIds = deletedInput.val() ? deletedInput.val().split(',') : [];
                    if (!deletedIds.includes(mediaId.toString())) {
                        deletedIds.push(mediaId);
                        deletedInput.val(deletedIds.join(','));
                    }

                    if ($(`#media-preview-${targetId}`).find('.gallery-item').length === 0) {
                        $(`#media-preview-${targetId}`).html(`
                            <div class="no-image-placeholder border rounded d-flex align-items-center justify-content-center bg-white imagePreviewSingle">
                                <div class="text-center text-muted">
                                    <i class="mdi mdi-image-multiple-outline fs-2"></i>
                                    <div style="font-size: 10px;">No Gallery</div>
                                </div>
                            </div>
                        `);
                    }
                }
            });
        }
    </script>
@endpush
@props([
    'label' => '',
    'target_id' => 'gallerys',
    'input_name' => 'product_galleries',
    'existing_media' => collect(),
    'thumbnail_source' => null,
    'limit' => 5,
    'required' => false,
    'button_label' => 'Select Images',
    'button_class' => '',
])

<div class="form-group">
    @if($label)
        <label class="form-label font-weight-bold">{{ $label }}@if($required) <span class="text-danger">*</span> @endif</label>
    @endif

    <div id="media-preview-{{ $target_id }}" class="row">
        {{-- Hidden inputs container for form request --}}
        <div id="hidden-inputs-{{ $target_id }}">
            @foreach($existing_media as $media)
                <input type="hidden" name="{{ $input_name }}[]" value="{{ $media->id }}" id="input-{{ $target_id }}-{{ $media->id }}">
            @endforeach
        </div>

        {{-- Image preview section --}}
        @forelse($existing_media as $media)
            <div class="col-md-3 col-sm-4 col-6 position-relative gallery-item" data-id="{{ $media->id }}"
                id="item-{{ $target_id }}-{{ $media->id }}">
                <span class="remove-oldImage-btn"
                    onclick="removeMediaFromGallery('{{ $media->id }}', '{{ $target_id }}')">&times;</span>
                <img src="{{ Storage::url($media->src) }}" class="rounded w-100 galleryItemImg">
            </div>
        @empty
            <div class="col-md-3 col-sm-4 col-6 no-image-placeholder">
                <div class="border rounded d-flex align-items-center justify-content-center bg-white imagePreviewSingle">
                    <div class="text-center text-muted">
                        <i class="mdi mdi-image-multiple-outline fs-2"></i>
                        <div style="font-size: 10px;">No Gallery</div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <button type="button" class="btn btn-info btn-sm open-media-picker {{ $button_class }}" data-target-id="{{ $target_id }}"
        data-multiple="true" data-limit="{{ $limit }}">
        <i class="mdi mdi-image-multiple"></i> {{ $button_label }}
    </button>
</div>

@push('scripts')
    <script>
        /**
         * Function to remove media from gallery
         */
        function removeMediaFromGallery(mediaId, targetId) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to remove this image?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Remove preview element and hidden input
                    $(`#item-${targetId}-${mediaId}`).remove();
                    $(`#input-${targetId}-${mediaId}`).remove();

                    // Show placeholder if no images left
                    if ($(`#media-preview-${targetId}`).find('.gallery-item').length === 0) {
                        const placeholder = `
                            <div class="col-md-3 col-sm-4 col-6 no-image-placeholder-${targetId}">
                                <div class="border rounded d-flex align-items-center justify-content-center bg-white imagePreviewSingle">
                                    <div class="text-center text-muted">
                                        <i class="mdi mdi-image-multiple-outline fs-2"></i>
                                        <div style="font-size: 10px;">No Gallery</div>
                                    </div>
                                </div>
                            </div>`;
                        $(`#media-preview-${targetId}`).append(placeholder);
                    }
                }
            });
        }

        /**
         * Logic to check image limit
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

        /**
         * Function to process media selection from picker
         * selectedImages: Array of objects [{id: 1, url: '...'}, {id: 2, url: '...'}]
         */
        window.processMediaSelection_{{ str_replace('-', '_', $target_id) }} = function(selectedImages) {
            const limit = {{ $limit }};
            const inputContainer = $('#hidden-inputs-{{ $target_id }}');
            const previewContainer = $('#media-preview-{{ $target_id }}');
            const inputName = '{{ $input_name }}';

            // 1. Check existing images to prevent duplicates
            let currentIds = [];
            $(`#hidden-inputs-{{ $target_id }} input`).each(function() {
                currentIds.push($(this).val().toString());
            });

            // 2. Filter out already selected images
            let newUniqueImages = selectedImages.filter(image => {
                return !currentIds.includes(image.id.toString());
            });

            if (newUniqueImages.length === 0 && selectedImages.length > 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Already Added',
                    text: 'Selected image(s) are already in the gallery.',
                });
                return;
            }

            // 3. Check for available slots based on limit
            const currentCount = $('#media-preview-{{ $target_id }} .gallery-item').length;
            const availableSlots = limit - currentCount;

            let imagesToAdd = newUniqueImages;
            if (newUniqueImages.length > availableSlots) {
                Swal.fire({
                    icon: 'info',
                    title: 'Selection Adjusted',
                    text: `You can only add ${availableSlots} more image(s).`,
                });
                imagesToAdd = newUniqueImages.slice(0, availableSlots);
            }

            // Remove placeholder if exists
            $(`.no-image-placeholder-{{ $target_id }}`).remove();

            // 4. Add selected images to preview and hidden inputs
            imagesToAdd.forEach(image => {
                const imageId = image.id.toString();
                
                // Add hidden input
                inputContainer.append(`<input type="hidden" name="${inputName}[]" value="${imageId}" id="input-{{ $target_id }}-${imageId}">`);
                
                // Add preview card
                const html = `
                    <div class="col-md-3 col-sm-4 col-6 position-relative gallery-item" data-id="${imageId}" id="item-{{ $target_id }}-${imageId}">
                        <span class="remove-oldImage-btn" onclick="removeMediaFromGallery('${imageId}', '{{ $target_id }}')">&times;</span>
                        <img src="${image.url}" class="rounded w-100 galleryItemImg">
                    </div>`;
                previewContainer.append(html);
            });
        };
    </script>
@endpush
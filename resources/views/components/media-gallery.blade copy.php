@props([
    'label' => 'Gallery Images',
    'target_id' => 'gallery_2',
    'input_name' => 'product_gallery_id_2',
    'existing_media' => collect(),
    'thumbnail_source' => null,
])

<div class="form-group">
    <label class="form-label font-weight-bold">{{ $label }}</label>

    <div id="media-preview-{{ $target_id }}" class="mb-2 d-flex flex-wrap p-2 bg-white rounded border border-light"
        style="min-height: 50px; gap: 5px;">

        @php
            // Get existing media IDs as comma separated string
            $existingIds = $existing_media->pluck('id')->implode(',');
        @endphp

        @forelse($existing_media as $media)
            <div class="preview-image-wrapper position-relative gallery-item" data-id="{{ $media->id }}"
                id="item-{{ $target_id }}-{{ $media->id }}">
                <span class="remove-oldImage-btn"
                    onclick="removeMediaFromGallery('{{ $media->id }}', '{{ $target_id }}')">&times;</span>
                <img src="{{ Storage::url($media->src) }}" class="rounded bg-white imagePreviewMultiple">
            </div>
        @empty
            {{-- Default placeholder when no data exists --}}
            <div class="no-image-placeholder border rounded d-flex align-items-center justify-content-center bg-white imagePreviewSingle">
                <div class="text-center text-muted">
                    <i class="mdi mdi-image-multiple-outline fs-2"></i>
                    <div style="font-size: 10px;">No Gallery</div>
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

<button type="button" class="btn btn-info btn-sm open-media-picker" 
    data-target-id="{{ $target_id }}"
    data-multiple="true" 
    data-limit="{{ $limit }}"> {{-- এই লাইনটি খুব গুরুত্বপূর্ণ --}}
    <i class="mdi mdi-image-multiple"></i> Select {{ $label }}
</button>
</div>

@push('scripts')
    <script>
        /**
         * Function to remove media using SweetAlert2
         */
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
                    // 1. Remove the element from preview
                    $(`#item-${targetId}-${mediaId}`).remove();

                    // 2. Update existing IDs input
                    let existingInput = $(`#media-input-${targetId}-existing`);
                    let currentExistingIds = existingInput.val().split(',').filter(id => id != mediaId && id != "");
                    existingInput.val(currentExistingIds.join(','));

                    // 3. Update deleted IDs input
                    let deletedInput = $(`#media-input-${targetId}-deleted`);
                    let deletedIds = deletedInput.val() ? deletedInput.val().split(',') : [];
                    if (!deletedIds.includes(mediaId.toString())) {
                        deletedIds.push(mediaId);
                        deletedInput.val(deletedIds.join(','));
                    }

                    // 4. Show placeholder if gallery is empty
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
@props([
    'label' => '',
    'target_id' => 'main_thumb',
    'input_name' => 'media_id', // Set this as 'media_id' by default
    'existing_image' => null, // This gets the URL from your Accessor
    'existing_id' => null, // This is the actual ID from your DB
    'button_label' => 'Select Image',
    'required' => false,
    'disabled' => false,
    'class' => '',
    'id' => '',
    'image_preview_class' => '',
    'fit_content' => 'fit-content;',
    'width' => '100px',
    'height' => '100px',
])

<div class="form-group">
    @if ($label)
        <label for="{{ $id ?? $name }}" class="form-label fw-bold">
            {{ $label }} @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div id="media-preview-{{ $target_id }}" class="mb-2 rounded p-2 bg-white {{ $class }} {{ $image_preview_class }}"
        style="min-height: 100px; width: {{ $fit_content ?? '' }};">

        {{-- English: Check if the image is not the default placeholder --}}
        @if ($existing_image && !str_contains($existing_image, 'default.webp'))
            <div class="preview-image-wrapper position-relative gallery-item" id="item-{{ $target_id }}-preview">
                <img src="{{ $existing_image }}" class="rounded bg-white imagePreviewSingle">
            </div>
        @else
            {{-- English: Default design when no image is selected --}}
            <div class="no-image-placeholder border rounded d-flex align-items-center justify-content-center bg-white imagePreviewSingle">
                <div class="text-center text-muted defaultImagePlaceholder">
                    <i class="mdi mdi-image-outline fs-2"></i>
                    <div class="noImagesSelected" style="font-size: 10px;">No image selected</div>
                </div>
            </div>
        @endif
    </div>

    {{-- This input will now carry the 'media_id' or whatever you pass as input_name --}}
    <input type="hidden" name="{{ $input_name }}" id="media-input-{{ $target_id }}" value="{{ $existing_id }}">

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary btn-sm open-media-picker" data-target-id="{{ $target_id }}"
            data-multiple="false">
            <i class="mdi mdi-image"></i> {{ $button_label }}
        </button>

        {{-- Remove button: only visible when there's an actual image --}}
        {{-- <button type="button" class="btn btn-danger btn-sm" id="remove-btn-{{ $target_id }}"
            style="{{ ($existing_image && !str_contains($existing_image, 'default.webp')) ? '' : 'display:none;' }}"
            onclick="handleSingleRemove('{{ $target_id }}')">
            <i class="mdi mdi-delete"></i> Remove
        </button> --}}
    </div>

    @error($input_name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@push('scripts')
    <script>
        /**
         * English: Handle image removal with SweetAlert2 
         * It clears the specific hidden input and resets the preview.
         */
        function handleSingleRemove(targetId) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, remove it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // 1. Clear the hidden input
                    $(`#media-input-${targetId}`).val('');

                    // 2. Restore placeholder design
                    $(`#media-preview-${targetId}`).html(`
                        <div class="no-image-placeholder border rounded d-flex align-items-center justify-content-center bg-white imagePreviewSingle" style="width: 100px; height: 100px;">
                            <div class="text-center text-muted">
                                <i class="mdi mdi-image-multiple-outline fs-2"></i>
                                <div style="font-size: 10px;">No image selected</div>
                            </div>
                        </div>
                    `);

                    // 3. Hide the remove button
                    $(`#remove-btn-${targetId}`).hide();
                }
            });
        }
    </script>
@endpush
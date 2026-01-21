<div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Media Manager</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="gallery-tab" data-bs-toggle="pill"
                            data-bs-target="#modal-gallery-pane" type="button">Gallery</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="upload-tab" data-bs-toggle="pill"
                            data-bs-target="#modal-upload-pane" type="button">Upload New</button>
                    </li>
                </ul>

                <div class="tab-content border-top pt-3">
                    <div class="tab-pane fade show active" id="modal-gallery-pane">
                        <div class="row">
                            <div id="modal-image-col" class="col-md-9 border-right">
                                <div id="ajax-media-container"
                                    style="height: 450px; overflow-y: auto; overflow-x: hidden;">
                                </div>
                                <div id="multiple-confirm-wrapper" class="mt-3 d-none">
                                    <button type="button" class="btn btn-success w-100 confirm-selection-btn">Insert
                                        Selected Media</button>
                                </div>
                            </div>

                            <div id="modal-preview-col" class="col-md-3">
                                <h6 class="font-weight-bold">Selection Preview</h6>
                                <div class="border rounded bg-light mb-3 d-flex align-items-center justify-content-center"
                                    style="height: 200px; overflow: hidden;">
                                    <img id="modal-selection-preview" src=""
                                        style="max-height: 100%; display:none;">
                                    <span id="no-preview-text" class="text-muted">No image selected</span>
                                </div>
                                <button type="button"
                                    class="btn btn-success btn-block w-100 confirm-selection-btn">Insert Media</button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="modal-upload-pane">
                        <div class="row">
                            <div class="col-12" style="height: 450px; overflow-y: auto; overflow-x: hidden;">
                                <form id="ajax-upload-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ auth()?->id() }}">
                                    <div id="drop-area" class="border-dashed py-5 text-center"
                                        style="cursor: pointer; background: #fafafa; border: 2px dashed #ccc;">
                                        <i class="mdi mdi-cloud-upload text-primary" style="font-size: 40px;"></i>
                                        <p>Click here</p>
                                        <input type="file" name="files[]" id="file-input" multiple hidden
                                            accept="image/*">
                                    </div>
                                    <div id="error-container" class="mt-2"></div>
                                    <div class="row mt-3" id="tabpanel-image-preview-container"></div>
                                    <div class="text-right mt-3 pb-3">
                                        <button type="submit" id="upload-all-btn" class="btn btn-primary">Upload
                                            All</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .selected-media-border {
        border: 4px solid #b66dff !important;
    }

    .preview-image-wrapper {
        position: relative;
        display: inline-block;
        margin: 5px;
    }

    .remove-image-btn,
    .remove-oldImage-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: red;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 18px;
        cursor: pointer;
        font-weight: bold;
        z-index: 10;
    }

    .border-dashed:hover {
        border-color: #b66dff !important;
        background: #f3eaff !important;
    }
</style>

@push('scripts')
    <script>
        let currentTargetId = null;
        let isMultiple = false;
        let tempSelectedMedia = [];
        let uploadFilesContainer = new DataTransfer(); // Container to store files for upload

        function adjustGridSystem() {
            if (isMultiple === true) {
                $('.media-col').removeClass('col-xl-3').addClass('col-xl-2');
            } else {
                $('.media-col').removeClass('col-xl-2').addClass('col-xl-3');
            }
        }

        function refreshGallery(url = "{{ route('admin.media.getGalleryAjax') }}") {
            $('#ajax-media-container').html(
                '<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>');

            $.get(url, function(data) {
                $('#ajax-media-container').html(data);
                adjustGridSystem();
            });
        }

        $(document).ready(function() {
            // 1. Open Modal
            $(document).on('click', '.open-media-picker', function(e) {
                e.preventDefault();
                currentTargetId = $(this).data('target-id');
                
                // Check limit before opening modal and stop if exceeded
                const limit = parseInt($(this).data('limit')) || 5; 
                const currentImagesCount = $(`#media-preview-${currentTargetId}`).find('.gallery-item').length;

                if (currentImagesCount >= limit) {
                    e.stopImmediatePropagation(); 
                    Swal.fire({
                        icon: 'warning',
                        title: 'Limit Reached',
                        text: `You already have ${currentImagesCount} images. Please remove some to add new ones.`,
                    });
                    return false;
                }

                isMultiple = $(this).data('multiple') === true || $(this).data('multiple') == "true";
                tempSelectedMedia = [];

                if (isMultiple) {
                    $('#modal-image-col').removeClass('col-md-9 border-right').addClass('col-12');
                    $('#modal-preview-col').addClass('d-none');
                    $('#multiple-confirm-wrapper').removeClass('d-none');
                } else {
                    $('#modal-image-col').removeClass('col-12').addClass('col-md-9 border-right');
                    $('#modal-preview-col').removeClass('d-none');
                    $('#multiple-confirm-wrapper').addClass('d-none');
                }

                var myModal = new bootstrap.Modal(document.getElementById('mediaPickerModal'));
                myModal.show();
                refreshGallery();
            });

            // 2. Upload Section: Drop Area Click
            $(document).on('click', '#drop-area', function(e) {
                if (e.target !== document.getElementById('file-input')) {
                    $('#file-input').click();
                }
            });

            // 3. Upload Section: File Selection and Preview
            $('#file-input').on('change', function() {
                const files = this.files;
                const previewZone = $('#tabpanel-image-preview-container');
                const errorContainer = $('#error-container');

                errorContainer.empty();

                Array.from(files).forEach((file, index) => {
                    if (file.size > 2 * 1024 * 1024) {
                        const $errorAlert = $(`
                            <div class="alert alert-danger p-2 mb-2 error-alert-item">
                                <i class="mdi mdi-alert-circle mr-2"></i>
                                <strong>${file.name}</strong> is over 2MB.
                            </div>
                        `);

                        errorContainer.append($errorAlert);

                        setTimeout(function() {
                            $errorAlert.fadeOut(500, function() {
                                $(this).remove();
                            });
                        }, 3000 + (index * 500)); 

                        return;
                    }

                    uploadFilesContainer.items.add(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewZone.append(`
                            <div class="col-md-3 mb-3 upload-preview-item" data-filename="${file.name}">
                                <div class="card p-1 shadow-sm">
                                    <div class="position-absolute" style="top: 5px; right: 5px; z-index: 10;">
                                        <button type="button" class="btn btn-danger btn-xs p-0 rounded-circle d-flex align-items-center justify-content-center remove-upload-img" style="width: 25px; height: 25px;">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                    <img src="${e.target.result}" style="height:100px; object-fit:contain;">
                                    <small class="text-truncate d-block mt-1 px-1">${file.name}</small>
                                </div>
                            </div>
                        `);
                    }
                    reader.readAsDataURL(file);
                });
                this.files = uploadFilesContainer.files;
            });
            

            // 4. Upload Section: Remove file from preview
            $(document).on('click', '.remove-upload-img', function() {
                const parent = $(this).closest('.upload-preview-item');
                const fileName = parent.data('filename');
                const newContainer = new DataTransfer();

                Array.from(uploadFilesContainer.files).forEach(file => {
                    if (file.name !== fileName) newContainer.items.add(file);
                });

                uploadFilesContainer = newContainer;
                document.getElementById('file-input').files = uploadFilesContainer.files;
                parent.remove();
            });

            // 5. Upload files via AJAX
            $('#ajax-upload-form').on('submit', function(e) {
                e.preventDefault();
                if (uploadFilesContainer.files.length === 0) return alert('Select files first!');

                const btn = $('#upload-all-btn');
                const formData = new FormData(this);

                formData.delete('files[]');
                Array.from(uploadFilesContainer.files).forEach(file => formData.append('files[]', file));

                btn.prop('disabled', true).text('Uploading...');

                $.ajax({
                    url: "{{ route('admin.media.ajaxStore') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#ajax-upload-form')[0].reset();
                        $('#tabpanel-image-preview-container').empty();
                        uploadFilesContainer = new DataTransfer();
                        btn.prop('disabled', false).text('Upload All');

                        bootstrap.Tab.getInstance(document.querySelector('#gallery-tab'))
                    .show();
                        refreshGallery();
                    },
                    error: function() {
                        alert('Upload failed!');
                        btn.prop('disabled', false).text('Upload All');
                    }
                });
            });

            // 6. Logic to select image from Gallery
            $(document).on('click', '.select-this-media', function() {
                const mediaId = $(this).data('id');
                const mediaSrc = $(this).data('src');
                
                const limit = parseInt($(`.open-media-picker[data-target-id="${currentTargetId}"]`).data('limit')) || 5;
                const currentInPreviewCount = $(`#media-preview-${currentTargetId} .gallery-item`).length;

                if (!isMultiple) {
                    $('.select-this-media').removeClass('selected-media-border');
                    $(this).addClass('selected-media-border');
                    tempSelectedMedia = [{ id: mediaId, src: mediaSrc }];
                    $('#no-preview-text').hide();
                    $('#modal-selection-preview').attr('src', mediaSrc).show();
                } else {
                    const index = tempSelectedMedia.findIndex(x => x.id === mediaId);
                    if (index > -1) {
                        tempSelectedMedia.splice(index, 1);
                        $(this).removeClass('selected-media-border');
                    } else {
                        // Check limit before adding new selection and stop if exceeded
                        if ((currentInPreviewCount + tempSelectedMedia.length) >= limit) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Limit Reached',
                                text: `You can only select a total of ${limit} images.`,
                            });
                            return false;
                        }
                        
                        tempSelectedMedia.push({ id: mediaId, src: mediaSrc });
                        $(this).addClass('selected-media-border');
                    }
                }
            });

            // 7. Confirm Selection (Display preview on main page)
            $('.confirm-selection-btn').on('click', function() {
                if (tempSelectedMedia.length === 0) return alert('Please select an image!');

                let previewContainer = $(`#media-preview-${currentTargetId}`);
                let baseInputName = currentTargetId.replace(/-/g, '_');
                const limit = parseInt($(`.open-media-picker[data-target-id="${currentTargetId}"]`).data('limit')) || 5;

                if (!isMultiple) {
                    const media = tempSelectedMedia[0];
                    previewContainer.html(`
                        <div class="preview-image-wrapper position-relative gallery-item" data-media-id="${media.id}" data-target="${currentTargetId}">
                            <img src="${media.src}" class="img-thumbnail imagePreviewSingle">
                            <input type="hidden" name="${baseInputName}" value="${media.id}">
                        </div>
                    `);
                } else {
                    previewContainer.find('.text-muted, .no-data-msg, .no-image-placeholder').remove();

                    tempSelectedMedia.forEach(media => {
                        let currentInGallery = previewContainer.find('.gallery-item').length;
                        
                        if (currentInGallery < limit) {
                            if (previewContainer.find(`[data-media-id="${media.id}"]`).length === 0) {
                                previewContainer.append(`
                                <div class="col-md-3 col-sm-4 col-6 position-relative gallery-item p-0" data-media-id="${media.id}" data-target="${currentTargetId}">
                                    <span class="remove-image-btn bg-danger text-white rounded-circle position-absolute" style="top:5px; right:5px; cursor:pointer; width:20px; height:20px; text-align:center; line-height:18px;">&times;</span>
                                    <img src="${media.src}" class="rounded w-100 galleryItemImg" >
                                    <input type="hidden" name="${baseInputName}[]" value="${media.id}">
                                </div>
                                `);
                            }
                        }
                    });
                }
                bootstrap.Modal.getInstance(document.getElementById('mediaPickerModal')).hide();
            });

            // 8. Remove image from main page preview
            $(document).on('click', '.remove-image-btn', function() {
                const wrapper = $(this).closest('.preview-image-wrapper, .gallery-item');
                const targetId = wrapper.data('target');
                const mediaId = wrapper.data('media-id');

                let existingInput = $(`#media-input-${targetId}-existing`);
                if (existingInput.length > 0) {
                    let existingIds = existingInput.val().split(',').filter(id => id != mediaId && id != "");
                    existingInput.val(existingIds.join(','));
                }

                let deletedInput = $(`#media-input-${targetId}-deleted`);
                if (deletedInput.length > 0) {
                    let deletedIds = deletedInput.val() ? deletedInput.val().split(',') : [];
                    if (!deletedIds.includes(mediaId.toString())) {
                        deletedIds.push(mediaId);
                        deletedInput.val(deletedIds.join(','));
                    }
                }

                wrapper.remove();

                if ($(`#media-preview-${targetId}`).children().length === 0) {
                    $(`#media-preview-${targetId}`).html(
                        `<div class="no-image-placeholder border rounded d-flex align-items-center justify-content-center bg-white imagePreviewSingle">
                            <div class="text-center text-muted">
                                <i class="mdi mdi-image-multiple-outline fs-2"></i>
                                <div style="font-size: 10px;">No Gallery</div>
                            </div>
                        </div>`
                    );
                }
            });
        });
    </script>
@endpush
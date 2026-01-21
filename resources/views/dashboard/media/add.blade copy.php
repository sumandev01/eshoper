@extends('dashboard.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Add New Media </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="btn btn-primary mr-2 btn-icon-text" href="{{ route('admin.media') }}">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Back to All Gallery
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Upload Files</h4>
                        <p class="card-description"> You can upload multiple images at once. </p>

                        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="forms-sample">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ auth()?->user()?->id }}">
                            <div class="form-group">
                                <label>File upload (Drag & Drop)</label>
                                <div id="drop-area"
                                    class="border-dashed d-flex flex-column align-items-center justify-content-center py-5"
                                    style="border: 2px dashed #ebedf2; border-radius: 10px; background: #fafafa; cursor: pointer;">
                                    <i class="mdi mdi-cloud-upload text-primary" style="font-size: 50px;"></i>
                                    <p class="mb-0 text-muted">Click here or drag files to upload</p>
                                    <input type="file" name="files[]" id="file-input" multiple hidden>
                                </div>
                            </div>
                            <div id="error-container" class="mb-3"></div>
                            <div class="row" id="image-preview-container">
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary mr-2 btn-icon-text">
                                    <i class="mdi mdi-file-check btn-icon-prepend"></i> Upload All
                                </button>
                                <a href="{{ route('admin.media') }}" class="btn btn-danger mr-2 btn-icon-text">
                                    <i class="mdi mdi-close btn-icon-prepend"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('image-preview-container');

        // Click to select files
        dropArea.addEventListener('click', () => fileInput.click());

        // Handle file selection
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        // Drag and Drop events
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.style.background = "#f0f0f0";
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.style.background = "#fafafa";
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.style.background = "#fafafa";
            const files = e.dataTransfer.files;
            handleFiles(files);
        });

        // Function to preview images
        function handleFiles(files) {
            const previewContainer = document.getElementById('image-preview-container');
            const errorContainer = document.getElementById('error-container');

            // Only the error container will be cleared each time a new file is selected
            // so that new errors can be shown after a new check
            errorContainer.innerHTML = "";

            Array.from(files).forEach(file => {
                const fileSizeMB = file.size / (1024 * 1024); // mb

                if (fileSizeMB > 2) {
                    // If it is more than 2 MB, an error message will be created and added to the error-container.
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'alert alert-danger p-2 mb-1 shadow-sm';
                    errorMsg.style.fontSize = '13px';
                    errorMsg.innerHTML =
                        `<i class="mdi mdi-alert-circle mr-2"></i> <strong>${file.name}</strong> - This image is over 2 MB in size, so it has been removed.`;
                    errorContainer.appendChild(errorMsg);

                    // This large file will be skipped, it will not be previewed
                    return;
                }

                // If the file is an image and under 2 MB, it will be added to the preview
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const col = document.createElement('div');
                        col.className = 'col-md-2 mt-3 position-relative media-item';
                        col.innerHTML = `
                            <div class="card border shadow-sm h-100">
                                <div class="position-absolute" style="top: 5px; right: 5px; z-index: 10;">
                                    <button type="button" class="btn btn-danger btn-circle btn-xs p-1" onclick="this.parentElement.parentElement.parentElement.remove()" style="border-radius: 50%; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center;">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </div>
                                <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: contain; border-radius: 5px 5px 0 0;">
                                <div class="card-footer p-1 text-center bg-white">
                                    <small class="text-truncate d-block px-1" title="${file.name}">${file.name}</small>
                                </div>
                            </div>
                        `;
                        // Here 'appendChild' is used instead of 'innerHTML = '
                        // so that previous previews remain
                        previewContainer.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
    <script>
        // Click to select files
dropArea.addEventListener('click', (e) => {
    // এটি ডাবল ক্লিক বা বাবলিং সমস্যা প্রতিরোধ করবে
    if (e.target !== fileInput) {
        fileInput.click();
    }
});
    </script>
    <style>
        .border-dashed:hover {
            border-color: #b66dff !important;
            /* Theme Purple Color */
            background: #f3eaff !important;
        }
    </style>
@endsection

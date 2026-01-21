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

                        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data"
                            class="forms-sample">
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
        const errorContainer = document.getElementById('error-container');

        // Array to track all selected files
        let allSelectedFiles = [];

        // Trigger file input click when drop area is clicked
        dropArea.addEventListener('click', (e) => {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });

        // Handle file selection via input
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        // Drag and Drop event listeners
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
            handleFiles(e.dataTransfer.files);
        });

        // Process files and filter by size/type
        function handleFiles(files) {
            errorContainer.innerHTML = ""; // Clear previous errors
            const newFiles = Array.from(files);

            newFiles.forEach(file => {
                const fileSizeMB = file.size / (1024 * 1024);

                // Skip files larger than 2MB
                if (fileSizeMB > 2) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'alert alert-danger p-2 mb-1 shadow-sm';
                    errorMsg.style.fontSize = '13px';
                    errorMsg.innerHTML =
                        `<i class="mdi mdi-alert-circle mr-2"></i> <strong>${file.name}</strong> is over 2 MB and was skipped.`;
                    errorContainer.appendChild(errorMsg);
                    return;
                }

                // Only add images to the selection
                if (file.type.startsWith('image/')) {
                    allSelectedFiles.push(file);
                }
            });

            updateUI();
        }

        // Refresh preview and sync the actual file input
        function updateUI() {
            previewContainer.innerHTML = ""; // Clear existing previews
            const dt = new DataTransfer(); // Object to update the file input value

            allSelectedFiles.forEach((file, index) => {
                dt.items.add(file); // Add file to the DataTransfer object

                const reader = new FileReader();
                reader.onload = (e) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-2 mt-3 position-relative media-item';
                    col.innerHTML = `
                    <div class="card border shadow-sm h-100">
                        <div class="position-absolute" style="top: 5px; right: 5px; z-index: 10;">
                            <button type="button" class="btn btn-danger btn-circle btn-xs p-1" onclick="removeImage(${index})" style="border-radius: 50%; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center;">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>
                        <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: contain; border-radius: 5px 5px 0 0;">
                        <div class="card-footer p-1 text-center bg-white">
                            <small class="text-truncate d-block px-1" title="${file.name}">${file.name}</small>
                        </div>
                    </div>
                `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            });

            // Sync the hidden file input with our processed file list
            fileInput.files = dt.files;
        }

        // Remove file from array and refresh UI
        function removeImage(index) {
            allSelectedFiles.splice(index, 1);
            updateUI();
        }
    </script>

    <style>
        /* Styling for the drop area hover state */
        .border-dashed:hover {
            border-color: #b66dff !important;
            background: #f3eaff !important;
        }
    </style>
@endsection
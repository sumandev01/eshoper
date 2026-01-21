@extends('dashboard.layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add New Product</h4>
                        <p class="card-description"> Fill in the details below </p>

                        <form class="forms-sample" action="{{ route('admin.media.productStore') }}" method="POST">
                            @csrf
                            <!--------------------------- Gallery Images Tab Using Media Picker -------------------------
                            1. Media Picker Button: data-target-id="newName"
                            2. Media Input: id="media-input-newName"
                            3. Media Preview: id="media-preview-newName"
                            3. Multiple Selection: data-multiple="true/false"
                            4. Hidden Input Name: name="newName"
                            5. Preview Container: Use flex-wrap for multiple images
                            Note: Replace "newName" with your desired identifier.
                            ---------------------------------------------------------------------------------------------->
                            <div class="form-group">
                                <label for="productName">Product Name</label>
                                <input type="text" class="form-control" id="productName" name="name"
                                    placeholder="Enter product name" required>
                            </div>

                            <div class="form-group border p-3 bg-white rounded shadow-sm mb-4">
                                <label class="font-weight-bold">Featured Thumbnail</label>
                                <div id="media-preview-main-thumb" class="mb-2 border rounded p-2 bg-light"
                                    style="min-height: 100px; width: fit-content;">
                                    <span class="text-muted">No image selected</span>
                                </div>
                                <input type="hidden" name="thumbnail_id" id="media-input-main-thumb">

                                <button type="button" class="btn btn-primary btn-sm open-media-picker"
                                    data-target-id="main-thumb" data-multiple="false">
                                    <i class="mdi mdi-image"></i> Select Thumbnail
                                </button>
                            </div>

                            <div class="form-group border p-3 mb-4 bg-white rounded shadow-sm">
                                <label class="font-weight-bold">Product Gallery Images</label>
                                <div id="media-preview-product-gallery"
                                    class="mb-2 d-flex flex-wrap border rounded p-2 bg-light" style="min-height: 50px;">
                                    <span class="text-muted">No images selected</span>
                                </div>
                                <input type="hidden" name="product_gallery_ids[]" id="media-input-product-gallery">

                                <button type="button" class="btn btn-info btn-sm open-media-picker"
                                    data-target-id="product-gallery" data-multiple="true">
                                    <i class="mdi mdi-image-multiple"></i> Select Gallery Images
                                </button>
                            </div>

                            <div class="form-group border p-3 mb-4 bg-white rounded shadow-sm">
                                <label class="font-weight-bold">Product Gallery 2 Images</label>
                                <div id="media-preview-product-gallery_2"
                                    class="mb-2 d-flex flex-wrap border rounded p-2 bg-light" style="min-height: 50px;">
                                    <span class="text-muted">No images selected</span>
                                </div>
                                <input type="hidden" name="product_gallery_id_2[]" id="media-input-product-gallery_2">

                                <button type="button" class="btn btn-info btn-sm open-media-picker"
                                    data-target-id="product-gallery_2" data-multiple="true">
                                    <i class="mdi mdi-image-multiple"></i> Select Gallery Images
                                </button>
                            </div>

                            <div class="form-group border p-3 mb-4 bg-white rounded shadow-sm">
                                <label class="font-weight-bold">Office Photos</label>
                                <div id="media-preview-office-photos"
                                    class="mb-2 d-flex flex-wrap border rounded p-2 bg-light" style="min-height: 50px;">
                                    <span class="text-muted">No images selected</span>
                                </div>
                                <input type="hidden" name="office_photos_ids[]" id="media-input-office-photos">

                                <button type="button" class="btn btn-info btn-sm open-media-picker"
                                    data-target-id="office-photos" data-multiple="true">
                                    <i class="mdi mdi-image-multiple"></i> Select Office Photos
                                </button>
                            </div>

                            <button type="submit" class="btn btn-gradient-primary me-2">Save Product</button>
                            <button type="button" class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

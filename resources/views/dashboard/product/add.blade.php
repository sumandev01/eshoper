@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-4">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Add New Product</h4>
                            <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm">
                                <i class="mdi mdi-arrow-left me-1"></i>
                                <span>Back to List</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <p class="text-muted fw-bold">After adding the product, you can proceed to add product variants.</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mt-3">
                <!-- Left Side: Basic Info & Description -->
                <div class="col-lg-8">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header pt-3">
                            <h5 class="card-title">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <x-input name="name" label="Product Name" type="text" placeholder="Enter product name"
                                :required="false" :maxlength="100" :value="$item->name ?? ''" />

                            <x-input name="slug" type="hidden" placeholder="enter-product-slug"
                                :required="false" :maxlength="100" :value="$item->slug ?? ''" />

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-input name="sku" type="text" placeholder="12345" label="Product SKU"
                                        :value="$item->sku ?? ''" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input name="quantity" type="number" placeholder="0" label="Product Quantity"
                                        :value="$item->quantity ?? ''" />
                                </div>
                            </div>

                            <x-textarea name="short_description" label="Short Description" :editor="false"
                                :value="old('short_description')" placeholder="Write something..." maxlength="500" :wordcount="true"
                                rows="5" :required="false" />

                            <x-textarea name="description" label="Description" :editor="true" :value="old('description')"
                                placeholder="Write something..." maxlength="1500" :wordcount="true" rows="5"
                                :required="false" />

                            <x-textarea name="specifications" label="Specifications" :editor="true" :value="old('specifications')"
                                placeholder="Write something..." maxlength="1500" :wordcount="true" rows="5"
                                :required="false" />
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header pt-3">
                            <h5 class="card-title">Pricing</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-input name="sale_price" type="number" placeholder="0.00" :inputGroup="true"
                                        inputGroupText="৳" label="Sale Price" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input name="discount" type="number" placeholder="0.00" :inputGroup="true"
                                        inputGroupText="৳" label="Discount Price" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input name="buy_price" type="number" placeholder="0.00" :inputGroup="true"
                                        inputGroupText="৳" label="Buy Price" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input name="tax" type="number" placeholder="0.00" :inputGroup="true"
                                        inputGroupText="%" label="Tax" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header pt-3">
                            <h5 class="card-title">Gallery</h5>
                        </div>
                        <div class="card-body">
                            <x-media-gallery target_id="product_galleries" name="galleries" :required="false"
                                limit="10" button_class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Right Side: Category, Brand & Image -->
                <div class="col-lg-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header pt-3">
                            <h5 class="card-title">Organization</h5>
                        </div>
                        <div class="card-body">
                            <x-select name="category_id" id="category_id" label="Category" :options="$categories"
                                placeholder="Select Category" :required="false" />
                            <x-select name="sub_category_id" id="sub_category_id" label="Subcategory" :options="$subCategories ?? []"
                                placeholder="Select Subcategory" />

                            <x-select name="brand_id" id="brand_id" label="Brand" :options="$brands ?? []"
                                placeholder="Select Brand" />

                            <x-select name="status" label="Status">
                                <option value="1">Active</option>
                                <option value="0" selected>Inactive</option>
                            </x-select>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header pt-3">
                            <h5 class="card-title">Tags</h5>
                        </div>
                        <div class="card-body">
                            <x-select name="tag_id_select" id="tag_id" :options="$tags ?? []" placeholder="Select Tag" />
                            <div id="hidden-tags-inputs"></div>
                            <div id="selected-tags-container" class="mt-3">
                                <div id="tag-badges" class="d-flex flex-wrap gap-2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header pt-3">
                            <h5 class="card-title">Product Image</h5>
                        </div>
                        <div class="card-body text-center">
                            <x-media-thumbnail name="image" image_preview_class="product_thumbnail" fit_content="100%"
                                :required="false" />
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="mdi mdi-content-save me-1"></i> Add Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('styles')
    <style>
        .product_thumbnail {
            /* border: 2px solid #dee2e6 !important; */
            padding: 10px !important;
            /* margin-bottom: 25px !important; */
            padding: 0 !important;
        }

        .product_thumbnail .preview-image-wrapper {
            display: block !important;
        }

        .product_thumbnail img.imagePreviewSingle,
        .product_thumbnail .no-image-placeholder {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            margin-bottom: 30px !important;
            border: 2px solid #dee2e6 !important;
            aspect-ratio: 4/3 !important;
            padding: 0 !important;
        }

        .product_thumbnail .defaultImagePlaceholder i {
            font-size: 50px !important;
        }

        .product_thumbnail .noImagesSelected {
            font-size: 14px !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        /**
         * Variable for Category/Subcategory caching
         */
        let cachedSubCategories = null;

        /**
         * Global function for Subcategory filtering
         */
        async function handleCategoryChange(parentId, targetId, savedValue = null) {
            const targetSelect = document.getElementById(targetId);
            if (!targetSelect) return;

            // Start loading: Disable dropdown and change placeholder
            targetSelect.disabled = true;
            targetSelect.innerHTML = '<option value="" selected disabled>Loading subcategories...</option>';

            try {
                // 1. Fetch data if not cached
                if (!cachedSubCategories) {
                    const response = await fetch("{{ route('getAllSubCategory') }}");
                    cachedSubCategories = await response.json();
                }

                // 2. Filter data by category_id
                const filtered = cachedSubCategories.filter(item => String(item.category_id) === String(parentId));

                // 3. Update dropdown UI
                targetSelect.innerHTML = '<option value="" selected disabled>Select Subcategory</option>';

                if (filtered.length === 0) {
                    targetSelect.innerHTML = '<option value="" selected disabled>No Subcategory Found</option>';
                } else {
                    filtered.forEach(item => {
                        let option = document.createElement('option');
                        option.value = item.id;
                        option.text = item.name;
                        // Auto-select value for Edit Mode
                        if (savedValue && String(item.id) === String(savedValue)) {
                            option.selected = true;
                        }
                        targetSelect.add(option);
                    });
                }

                // 4. Finished: Enable dropdown
                targetSelect.disabled = false;

            } catch (error) {
                console.error("Error fetching data:", error);
                targetSelect.innerHTML = '<option value="" selected disabled>Error Loading Data</option>';
                targetSelect.disabled = false;
            }
        }

        /**
         * DOM Content Loaded Initialization
         */
        document.addEventListener("DOMContentLoaded", function() {

            // --- 1. Image Preview Logic ---
            $('#imageInput').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#previewImage').attr('src', e.target.result);
                }
                if (this.files[0]) {
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // UI Fix: Adjust margin for product thumbnail
            $('.product_thumbnail').closest('.form-group').addClass('mb-0');


            // --- 2. Dynamic Tag Selection Logic ---
            const tagSelect = document.getElementById('tag_id');
            const badgeContainer = document.getElementById('tag-badges');
            const hiddenContainer = document.getElementById('hidden-tags-inputs');
            let selectedTagIds = [];

            if (tagSelect && badgeContainer) {
                tagSelect.addEventListener('change', function() {
                    const id = this.value;
                    const name = this.options[this.selectedIndex].text;

                    if (id && !selectedTagIds.includes(id)) {
                        selectedTagIds.push(id);

                        // Create Badge UI
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-primary p-2 me-2 mb-2 d-inline-flex align-items-center';
                        badge.setAttribute('data-id', id);
                        badge.innerHTML = `
                        ${name} 
                        <span class="ms-2 btn-close btn-close-white remove-tag" style="cursor:pointer; font-size: 10px;"></span>
                    `;
                        badgeContainer.appendChild(badge);

                        // Create Hidden Input for Form Submission
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'tag_id[]';
                        hiddenInput.value = id;
                        hiddenInput.setAttribute('id', 'input-tag-' + id);
                        hiddenContainer.appendChild(hiddenInput);
                    }
                    this.value = "";
                });

                // Remove Badge and Input functionality
                badgeContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-tag')) {
                        const badge = e.target.parentElement;
                        const idToRemove = badge.getAttribute('data-id');

                        selectedTagIds = selectedTagIds.filter(id => id !== idToRemove);
                        badge.remove();

                        const inputToRemove = document.getElementById('input-tag-' + idToRemove);
                        if (inputToRemove) inputToRemove.remove();
                    }
                });
            }


            // --- 3. Category & Subcategory Logic ---
            const categorySelect = document.getElementById('category_id');
            const subCategorySelect = document.getElementById('sub_category_id');

            if (categorySelect && subCategorySelect) {
                const initialCat = categorySelect.value;
                const initialSub = subCategorySelect.getAttribute('data-selected-value');

                // Handle Page Load / Edit Mode
                if (initialCat) {
                    handleCategoryChange(initialCat, 'sub_category_id', initialSub);
                } else {
                    subCategorySelect.disabled = true;
                }

                // Handle Category Change
                categorySelect.addEventListener('change', function() {
                    handleCategoryChange(this.value, 'sub_category_id', null);
                });
            }

        });
    </script>
@endpush
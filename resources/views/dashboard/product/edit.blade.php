@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-products-center justify-content-between">
                    <h4 class="mb-0">Add New Product</h4>
                    <a href="{{ route('product.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>
            </div>
        </div>

        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row mt-3">
                <!-- Left Side: Basic Info & Description -->
                <div class="col-lg-8">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white pt-3">
                            <h5 class="card-title">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <x-input name="name" label="Product Name" type="text" placeholder="Enter product name"
                                :required="false" :maxlength="100" :value="$product->name ?? ''" />

                            <x-input name="slug" label="Product Slug" type="text" placeholder="enter-product-slug"
                                :required="false" :maxlength="100" :value="$product->slug ?? ''" />

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-input name="sku" type="text" placeholder="SKU-12345" label="Product SKU"
                                        :value="$product->sku ?? ''" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input name="quantity" type="number" placeholder="0" label="Product Quantity"
                                        :value="$product->stock ?? ''" />
                                </div>
                            </div>

                            <x-textarea name="short_description" label="Short Description" :editor="false"
                                :value="$product->details->shortDescription ?? ''" placeholder="Write something..." maxlength="500" :wordcount="true"
                                rows="5" :required="false" />

                            <x-textarea name="description" label="Description" :editor="true" :value="$product->details->description ?? ''"
                                placeholder="Write something..." maxlength="1500" :wordcount="true" rows="5"
                                :required="false" />

                            <x-textarea name="specifications" label="Specifications" :editor="true" :value="$product->details->information ?? ''"
                                placeholder="Write something..." maxlength="1500" :wordcount="true" rows="5"
                                :required="false" />
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white pt-3">
                            <h5 class="card-title">Pricing</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-input name="sale_price" type="number" placeholder="0.00" :inputGroup="true"
                                        inputGroupText="৳" label="Sale Price" :value="$product->price ?? ''" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input name="discount" type="number" placeholder="0.00" :inputGroup="true"
                                        inputGroupText="৳" label="Discount Price" :value="$product->discount ?? ''" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input name="buy_price" type="number" placeholder="0.00" :inputGroup="true"
                                        inputGroupText="৳" label="Buy Price" :value="$product->buy_price ?? ''" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input name="tax" type="number" placeholder="0.00" :inputGroup="true"
                                        inputGroupText="%" label="Tax" :value="$product->tax ?? ''" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white pt-3">
                            <h5 class="card-title">Gallery</h5>
                        </div>
                        <div class="card-body">
                            <x-media-gallery target_id="product_galleries" :existing_media="$product->galleries" :existing_id="$product->details->media_id"
                                name="galleries" :required="false" limit="10" button_class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Right Side: Category, Brand & Image -->
                <div class="col-lg-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white pt-3">
                            <h5 class="card-title">Organization</h5>
                        </div>
                        <div class="card-body">
                            <x-select name="category_id" id="category_id" label="Category" :options="$categories"
                                placeholder="Select Category" :required="false" :value="$product->details->category_id ?? ''" />
                            <x-select name="sub_category_id" id="sub_category_id" label="Subcategory" :options="$subCategories ?? []"
                                placeholder="Select Subcategory" :value="$product->details->sub_category_id ?? ''" />

                            <x-select name="brand_id" id="brand_id" label="Brand" :options="$brands ?? []"
                                placeholder="Select Brand" :value="$product->details->brand_id ?? ''" />

                            <x-select name="status" label="Status" :value="$product->status ?? 0">
                                <option value="1" {{ isset($product) && $product->status == 1 ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0" {{ isset($product) && $product->status == 0 ? 'selected' : '' }}>
                                    Inactive</option>
                            </x-select>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white pt-3">
                            <h5 class="card-title">Tags</h5>
                        </div>
                        <div class="card-body">
                            {{-- Tag Selection --}}
                            <x-select name="tag_id_select" id="tag_id" :options="$tags ?? []" placeholder="Select Tag" />

                            {{-- Hidden inputs container for tag IDs --}}
                            <div id="hidden-tags-inputs">
                                @if (isset($product) && $product->tags)
                                    @foreach ($product->tags as $tag)
                                        <input type="hidden" name="tag_id[]" value="{{ $tag->id }}"
                                            id="input-tag-{{ $tag->id }}">
                                    @endforeach
                                @endif
                            </div>

                            {{-- Displaying selected tags as badges --}}
                            <div id="selected-tags-container" class="mt-3">
                                <div id="tag-badges" class="d-flex flex-wrap gap-2">
                                    @if (isset($product) && $product->tags)
                                        @foreach ($product->tags as $tag)
                                            <span class="badge bg-primary p-2 d-inline-flex align-items-center"
                                                data-id="{{ $tag->id }}">
                                                {{ $tag->name }}
                                                <span class="ms-2 btn-close btn-close-white remove-tag"
                                                    style="cursor:pointer; font-size: 10px;"></span>
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white pt-3">
                            <h5 class="card-title">Product Image</h5>
                        </div>
                        <div class="card-body text-center">
                            <x-media-thumbnail name="image" image_preview_class="product_thumbnail" fit_content="100%"
                                :required="false" :existing_image="$product->thumbnail ?? ''" :existing_id="$product->media_id" />
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="mdi mdi-content-save me-1"></i> Save Product
                            </button>
                            <button type="reset" class="btn btn-outline-danger w-100">
                                Reset Form
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
        document.addEventListener("DOMContentLoaded", function() {
            // Image Preview Functionality for Single Image Upload (Input ID: imageInput, Preview ID: previewImage)
            $('#imageInput').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            // Adjust margin for product thumbnail form group
            $('.product_thumbnail').closest('.form-group').addClass('mb-0');

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('tag_id');
            const badgeContainer = document.getElementById('tag-badges');
            const hiddenContainer = document.getElementById('hidden-tags-inputs');

            // Initializing selectedIds with existing tags from the database
            let selectedIds = @json(isset($product) ? $product->tags->pluck('id')->map(fn($id) => (string) $id)->toArray() : []);

            // Handling new tag selection
            selectElement.addEventListener('change', function() {
                const id = this.value;
                const name = this.options[this.selectedIndex].text;

                if (id && !selectedIds.includes(id)) {
                    selectedIds.push(id);

                    // 1. Create and append badge
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-primary p-2 d-inline-flex align-items-center';
                    badge.setAttribute('data-id', id);
                    badge.innerHTML = `
                    ${name} 
                    <span class="ms-2 btn-close btn-close-white remove-tag" style="cursor:pointer; font-size: 10px;"></span>
                `;
                    badgeContainer.appendChild(badge);

                    // 2. Create and append hidden input for request
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'tag_id[]';
                    hiddenInput.value = id;
                    hiddenInput.setAttribute('id', 'input-tag-' + id);
                    hiddenContainer.appendChild(hiddenInput);
                }
                this.value = ""; // Reset select dropdown
            });

            // Event delegation for removing tags (both old and new)
            badgeContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-tag')) {
                    const badge = e.target.parentElement;
                    const idToRemove = badge.getAttribute('data-id');

                    // 1. Remove from selectedIds tracking array
                    selectedIds = selectedIds.filter(id => id !== idToRemove);

                    // 2. Remove the badge element from UI
                    badge.remove();

                    // 3. Remove the hidden input so it won't be sent in the request
                    const inputToRemove = document.getElementById('input-tag-' + idToRemove);
                    if (inputToRemove) {
                        inputToRemove.remove();
                    }
                }
            });
        });
    </script>
@endpush
@push('scripts')
<script>
    /**
     * Variable to store data in memory (Caching)
     * This prevents multiple API calls for the same data
     */
    let cachedSubCategories = null;

    /**
     * Function to handle subcategory loading based on category selection
     */
    async function handleCategoryChange(parentId, targetId, savedValue = null) {
        const targetSelect = document.getElementById(targetId);
        if (!targetSelect) return;

        // Start loading: Disable dropdown and change placeholder text
        targetSelect.disabled = true;
        targetSelect.innerHTML = '<option value="" selected disabled>Loading subcategories...</option>';

        try {
            // 1. Fetch all subcategories from API only once (Caching)
            if (!cachedSubCategories) {
                const response = await fetch("{{ route('getAllSubCategory') }}");
                cachedSubCategories = await response.json();
            }

            // 2. Filter data by category_id
            const filtered = cachedSubCategories.filter(item => String(item.category_id) === String(parentId));

            // 3. Update dropdown options
            targetSelect.innerHTML = '<option value="" selected disabled>Select Subcategory</option>';
            
            if (filtered.length === 0) {
                targetSelect.innerHTML = '<option value="" selected disabled>No Subcategory Found</option>';
            } else {
                filtered.forEach(item => {
                    let option = document.createElement('option');
                    option.value = item.id;
                    option.text = item.name;
                    
                    // Set selected option for Edit Mode (if savedValue matches)
                    if (savedValue && String(item.id) === String(savedValue)) {
                        option.selected = true;
                    }
                    targetSelect.add(option);
                });
            }

            // 4. Loading finished: Enable the dropdown
            targetSelect.disabled = false;

        } catch (error) {
            console.error("Error fetching data:", error);
            targetSelect.innerHTML = '<option value="" selected disabled>Error Loading Data</option>';
            targetSelect.disabled = false;
        }
    }

    /**
     * Initialize event listeners on DOM Content Loaded
     */
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const subCategorySelect = document.getElementById('sub_category_id');

        if (categorySelect && subCategorySelect) {
            // Check for initial value on page load (useful for Edit Mode)
            const initialCat = categorySelect.value;
            const initialSub = subCategorySelect.getAttribute('data-selected-value');

            if (initialCat) {
                // If category is already selected, load its subcategories
                handleCategoryChange(initialCat, 'sub_category_id', initialSub);
            } else {
                // Keep disabled initially if no category is selected
                subCategorySelect.disabled = true; 
            }

            // Trigger logic whenever the Category dropdown is changed
            categorySelect.addEventListener('change', function() {
                handleCategoryChange(this.value, 'sub_category_id', null);
            });
        }
    });
</script>
@endpush
@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Add New Product</h4>
                <a href="{{ route('product.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
            </div>
        </div>
    </div>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-3">
            <!-- Left Side: Basic Info & Description -->
            <div class="col-lg-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white pt-3">
                        <h5 class="card-title">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <x-input name="name" label="Product Name" type="text" placeholder="Enter product name" :required="true" :maxlength="50" :value="$item->name ?? ''" />

                        <x-input name="slug" label="Product Slug" type="text" placeholder="enter-product-slug" :required="true" :maxlength="50" :value="$item->slug ?? ''" />

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input name="sku" type="text" placeholder="SKU-12345" label="Product SKU" :value="$item->sku ?? ''" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input name="quantity" type="number" placeholder="0" label="Product Quantity" :value="$item->quantity ?? ''" />
                            </div>
                        </div>

                        <x-textarea  name="short_description"  label="Short Description" :editor="false" :value="$item->short_description ?? ''" placeholder="Write something..." maxlength="200" :wordcount="true" rows="5" :required="true"/>

                        <x-textarea  name="description"  label="Description" :editor="true" :value="$item->description ?? ''" placeholder="Write something..." maxlength="200" :wordcount="true" rows="5" :required="true"/>

                        <div class="mb-3">
                            <label class="form-label">Long Description</label>
                            <textarea name="long_description" id="editor" class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white pt-3">
                        <h5 class="card-title">Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Regular Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="price" class="form-control" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="sale_price" class="form-control" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white pt-3">
                        <h5 class="card-title">Gallery</h5>
                    </div>
                    <div class="card-body">
                        <x-media-gallery name="galleries" :required="false" limit="10"/>
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
                        <x-select name="category_id" id="category_id" label="Category" :options="$categories" placeholder="Select Category" :required="true" />
                        <x-select name="subcategory_id" id="sub_category_id" label="Subcategory" :options="$subCategories ??[]" placeholder="Select Subcategory" />
                        <x-select name="brand_id" id="brand_id" label="Brand" :options="$brands ??[]" placeholder="Select Brand" />
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white pt-3">
                        <h5 class="card-title">Product Image</h5>
                    </div>
                    <div class="card-body text-center">
                        <x-media-thumbnail name="image" image_preview_class="product_thumbnail" fit_content="100%" :required="true"/>
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
        .product_thumbnail{
            border: 2px solid #dee2e6 !important;
            padding: 10px !important;
            margin-bottom: 15px !important;
        }
        .product_thumbnail .preview-image-wrapper{
            display: block !important;
        }
        .product_thumbnail img.imagePreviewSingle,
        .product_thumbnail .no-image-placeholder {
            width: 100% !important;
            height: 300px !important;
            object-fit: contain !important;
            margin: 0 auto !important;
            border: 0 !important;
        }
        .product_thumbnail .defaultImagePlaceholder i{
            font-size: 50px !important;
        }
        .product_thumbnail .noImagesSelected{
            font-size: 14px !important;
        }
    </style>
@endpush
@push('scripts')
<script>
    // ইমেজ সিলেক্ট করলে সাথে সাথে প্রিভিউ দেখার জন্য
    $('#imageInput').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => { 
            $('#previewImage').attr('src', e.target.result); 
        }
        reader.readAsDataURL(this.files[0]); 
    });
</script>
@endpush
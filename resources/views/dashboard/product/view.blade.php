@extends('dashboard.layouts.app')
@section('title', ($siteSettings->site_title ?? null) . ' - ' . 'View Product - ' . $product?->name)
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-0 py-4">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Product Details</h5>
                            <div>
                                @can(\App\Enums\Permission\ProductPermission::VIEW->value)
                                    <a href="{{ route('admin.product.index') }}" class="btn btn-secondary btn-sm me-2">
                                        <i class="mdi mdi-arrow-left me-1"></i> Back to List
                                    </a>
                                @endcan
                                @can(\App\Enums\Permission\ProductPermission::UPDATE->value)
                                    <a href="{{ route('admin.product.edit', $product?->id) }}" class="btn btn-primary btn-sm">
                                        <i class="mdi mdi-pencil me-1"></i> Edit Product
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Left Side: Basic Info, Descriptions & Pricing -->
            <div class="col-lg-8">
                <!-- General Info section -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header py-3 border-bottom">
                        <h5 class="card-title mb-0">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="text-muted small fw-bold">Product Name</label>
                                <p class="fs-5 fw-medium">{{ $product?->name }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="text-muted small fw-bold">Slug</label>
                                <p class="text-secondary">{{ $product?->slug }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small fw-bold">SKU</label>
                                <p class="fw-bold">{{ $product?->sku ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small fw-bold">Quantity</label>
                                <p><span class="badge bg-soft-info text-info fs-6">{{ $product?->stock ?? 0 }} Units</span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="text-muted small fw-bold">Short Description</label>
                            <p class="text-dark">
                                {{ $product?->details?->shortDescription ?? 'No short description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pricing section -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header py-3 border-bottom">
                        <h5 class="card-title">Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-success bg-opacity-10 rounded">
                                    <label class="text-success small fw-bold d-block mb-1">Price</label>
                                    <h4 class="text-success mb-0">
                                        {{ $siteSettings->currency_symbol ?? null }}{{ number_format($product?->price ?? 0, 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-danger bg-opacity-10 rounded">
                                    <label class="text-danger small fw-bold d-block mb-1">Discount Price</label>
                                    <h4 class="text-danger mb-0">
                                        {{ $siteSettings->currency_symbol ?? null }}{{ number_format($product?->discount ?? 0, 2) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Inventory & Variations -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0">Inventory & Variations</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0 text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Image</th>
                                        <th class="text-end pe-3">Price</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($product?->inventories ?? [] as $inv)
                                        <tr>
                                            <td>
                                                <span class="fw-medium">{{ $inv?->size?->name ?? 'Default' }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span class="color-dot"
                                                        style="background-color: {{ $inv?->color?->color_code ?? '#eee' }};"
                                                        title="{{ $inv?->color?->name ?? '' }}"
                                                        data-bs-toggle="tooltip"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <img src="{{ $inv?->thumbnail }}" alt="Variant Image"
                                                    class="img-fluid rounded border"
                                                    style="width: 45px; height: 45px; object-fit: cover;">
                                            </td>
                                            <td class="text-end pe-3">
                                                @php
                                                    $displayDiscount =
                                                        $inv?->use_main_discount == 1
                                                            ? $inv?->product?->discount
                                                            : $inv?->discount;
                                                    $displayPrice =
                                                        $inv?->use_main_price == 1
                                                            ? $inv?->product?->price
                                                            : $inv?->price;
                                                @endphp

                                                @if (!is_null($displayDiscount) && $displayDiscount > 0)
                                                    <span class="text-muted text-decoration-line-through small me-1">
                                                        {{ $siteSettings->currency_symbol ?? null }}{{ $displayPrice }}
                                                    </span>
                                                    <span class="fw-bold text-danger">
                                                        {{ $siteSettings->currency_symbol ?? null }}{{ $displayDiscount }}
                                                    </span>
                                                @else
                                                    <span class="fw-bold">
                                                        {{ $siteSettings->currency_symbol ?? null }}{{ $displayPrice }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($inv?->stock > 10)
                                                    <span class="badge bg-success">{{ $inv?->stock }} In Stock</span>
                                                @elseif($inv?->stock > 0)
                                                    <span class="badge bg-warning text-dark">{{ $inv?->stock }} Low
                                                        Stock</span>
                                                @else
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                No variations available for this product.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Content & Specifications section -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title">Content & Specifications</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#description" role="tab">
                                    Full Description
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#specifications" role="tab">
                                    Specifications
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content text-muted p-2">
                            <div class="tab-pane active" id="description" role="tabpanel">
                                <div>
                                    {!! $product?->details?->description ?? '<span class="text-muted">No description available.</span>' !!}
                                </div>
                            </div>
                            <div class="tab-pane" id="specifications" role="tabpanel">
                                <div>
                                    {!! $product?->details?->information ?? '<span class="text-muted">No specifications provided.</span>' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery section -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header py-3 border-bottom">
                        <h5 class="card-title mb-0">Product Gallery</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @forelse($product?->galleries ?? [] as $gallery)
                                <div class="col-md-3 col-sm-4 col-6">
                                    <img src="{{ Storage::url($gallery?->src) }}" class="img-fluid rounded border w-100"
                                        style="object-fit: cover; aspect-ratio: 1/1" alt="gallery">
                                </div>
                            @empty
                                <div class="col-md-12 text-center text-muted py-3">
                                    No gallery images available.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Organization, Image & Tags -->
            <div class="col-lg-4">
                <!-- Thumbnail Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header py-3 border-bottom text-center">
                        <h5 class="card-title">Main Thumbnail</h5>
                    </div>
                    <div class="card-body bg-light text-center">
                        <img src="{{ asset($product?->thumbnail) }}" alt="Thumbnail"
                            class="img-fluid rounded shadow-sm border bg-white"
                            style="max-height: 250px; width: 100%; object-fit: cover;">
                    </div>
                </div>

                <!-- Organization Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header py-3 border-bottom">
                        <h5 class="card-title">Organization</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Category:</span>
                            <span class="fw-bold text-dark">{{ $product?->details?->category?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Subcategory:</span>
                            <span class="fw-bold text-dark">{{ $product?->details?->subCategory?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Brand:</span>
                            <span class="fw-bold text-dark">{{ $product?->details?->brand?->name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between pt-1">
                            <span class="text-muted">Status:</span>
                            @if ($product?->status == 1)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tags Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header py-3 border-bottom">
                        <h5 class="card-title">Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($product?->tags ?? [] as $tag)
                                <span class="badge bg-light text-dark border p-2">
                                    <i class="mdi mdi-tag-outline me-1"></i>{{ $tag?->name }}
                                </span>
                            @empty
                                <span class="text-muted italic">No tags selected.</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- SEO Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header py-3 border-bottom">
                        <h5 class="card-title">SEO</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small  fw-bold">Meta Title</label>
                            <p class="text-dark">{{ $product?->details?->meta_title ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small  fw-bold">Meta Keyword</label>
                            <p class="text-dark">{{ $product?->details?->meta_keyword ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-muted small  fw-bold">Meta Description</label>
                            <p class="text-dark">{{ $product?->details?->meta_description ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Audit Info -->
                <div class="card shadow-sm border-0 bg-light">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <p class="mb-1 small text-muted"><strong>Created:</strong>
                            {{ $product?->created_at?->format('M d, Y h:i A') }}</p>
                        <p class="mb-0 small text-muted"><strong>Last Update:</strong>
                            {{ $product?->updated_at?->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .nav-tabs-custom .nav-link.active {
            color: #556ee6;
            border-bottom: 2px solid #556ee6;
            background-color: transparent;
        }

        .bg-soft-info {
            background-color: rgba(80, 165, 241, 0.1);
        }

        .color-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1px solid #ddd;
            display: inline-block;
        }

        .badge {
            font-weight: 500;
        }
    </style>
@endpush

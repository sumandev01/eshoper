@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-0">Product Details</h4>
                        {{-- <p class="text-muted mb-0">Full information for: <strong>{{ $product->name }}</strong></p> --}}
                    </div>
                    <div>
                        <a href="{{ route('product.index') }}" class="btn btn-secondary btn-sm me-2">
                            <i class="mdi mdi-arrow-left me-1"></i> Back to List
                        </a>
                        <a href="{{ route('product.edit', $product?->id) }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-pencil me-1"></i> Edit Product
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Left Side: Basic Info, Descriptions & Pricing -->
            <div class="col-lg-8">
                <!-- General Info Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="text-muted small text-uppercase fw-bold">Product Name</label>
                                <p class="fs-5 fw-medium">{{ $product?->name }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="text-muted small text-uppercase fw-bold">Slug</label>
                                <p class="text-secondary">{{ $product?->slug }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small text-uppercase fw-bold">SKU</label>
                                <p class="fw-bold">{{ $product?->sku ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small text-uppercase fw-bold">Quantity</label>
                                <p><span class="badge bg-soft-info text-info fs-6">{{ $product?->stock ?? 0 }} Units</span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="text-muted small text-uppercase fw-bold">Short Description</label>
                            <p class="text-dark">
                                {{ $product?->details?->shortDescription ?? 'No short description provided.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content & Specs Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Content & Specifications</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
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

                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="description" role="tabpanel">
                                <div class="mt-3">
                                    {!! $product?->details?->description ?? 'No description available.' !!}
                                </div>
                            </div>
                            <div class="tab-pane" id="specifications" role="tabpanel">
                                <div class="mt-3">
                                    {!! $product?->details?->information ?? 'No specifications provided.' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Pricing & Tax</h5>
                    </div>
                    <div class="card-body bg-light-subtle">
                        <div class="row text-center">
                            <div class="col-md-3 border-end">
                                <label class="text-muted small d-block">Sale Price</label>
                                <h4 class="text-primary mb-0">৳{{ number_format($product?->price ?? 0, 2) }}</h4>
                            </div>
                            <div class="col-md-3 border-end">
                                <label class="text-muted small d-block">Discount Price</label>
                                <h4 class="text-danger mb-0">৳{{ number_format($product?->discount ?? 0, 2) }}</h4>
                            </div>
                            <div class="col-md-3 border-end">
                                <label class="text-muted small d-block">Buy Price</label>
                                <h4 class="text-dark mb-0">৳{{ number_format($product?->buy_price ?? 0, 2) }}</h4>
                            </div>
                            <div class="col-md-3">
                                <label class="text-muted small d-block">Tax</label>
                                <h4 class="text-secondary mb-0">{{ $product?->tax ?? 0 }}%</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Product Gallery</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @forelse($product?->galleries ?? [] as $gallery)
                                <div class="col-md-3">
                                    <img src="{{ Storage::url($gallery?->src) }}" class="img-fluid rounded border"
                                        alt="gallery">
                                </div>
                            @empty
                                <div class="col-md-12 text-center">
                                    <span class="text-muted">No gallery images.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- SEO Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">SEO</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="text-muted small text-uppercase fw-bold">Meta Title</label>
                                <p class="text-dark">
                                    {{ $product?->details?->meta_title ?? 'No meta title provided.' }}
                                </p>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small text-uppercase fw-bold">Meta Description</label>
                                <p class="text-dark">
                                    {{ $product?->details?->meta_description ?? 'No meta description provided.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Inventory Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Inventory & Variations</h5>
                        <a href="{{ route('inventory.index', $product?->id) }}" class="btn btn-sm btn-soft-primary">
                            <i class="mdi mdi-cog-outline me-1"></i> Manage Inventory
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-bordered table-hover table-nowrap mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Size</th>
                                        <th class="text-center">Color</th>
                                        <th class="text-center">Image</th>
                                        <th>Price</th>
                                        <th class="text-center">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($product?->inventories ?? [] as $inv)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!-- Size Name -->
                                                    <span class="fw-medium">{{ $inv?->size?->name ?? 'Default' }}</span>
                                                </div>
                                            </td>
                                            <td class="d-flex justify-content-center align-items-center">
                                                <div class="me-2"
                                                        style="background-color: {{ $inv?->color?->color_code ?? '#eee' }}; border: 1px solid #ddd; width: 20px; height: 20px; border-radius: 50%;"
                                                        title="{{ $inv?->color?->name ?? '' }}"></div>
                                            </td>
                                            <td class="text-center">
                                                <img src="{{ $inv?->thumbnail }}" alt="Variant Image"
                                                    class="img-fluid rounded" style="max-width: 50px; max-height: 50px; object-fit: cover; border: 1px solid #ccc; padding: 2px;">
                                            </td>
                                            <td class="fw-bold text-primary">৳{{ number_format($inv?->price, 2) }}</td>
                                            <td class="text-center">
                                                @if ($inv?->stock > 10)
                                                    <span class="badge bg-success-subtle text-success">{{ $inv?->stock }}
                                                        In Stock</span>
                                                @elseif($inv?->stock > 0)
                                                    <span class="badge bg-warning-subtle text-warning">{{ $inv?->stock }}
                                                        Low Stock</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger">Out of Stock</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-3 text-muted">No variants available
                                                for this product.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Organization, Image & Tags -->
            <div class="col-lg-4">
                <!-- Thumbnail Card -->
                <div class="card mb-4 shadow-sm border-0 overflow-hidden">
                    <div class="card-header bg-white py-3 text-center">
                        <h5 class="card-title mb-0">Main Thumbnail</h5>
                    </div>
                    <div class="card-body text-center bg-light">
                        <img src="{{ asset($product?->thumbnail) }}" alt="Thumbnail"
                            class="img-fluid rounded shadow-sm border"
                            style="max-height: 250px; width: 100%; object-fit: cover;">
                    </div>
                </div>

                <!-- Organization Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">Organization</h5>
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
                        <div class="d-flex justify-content-between">
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
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0">Tags</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($product?->tags ?? [] as $tag)
                                <span class="badge bg-primary-subtle text-primary p-2 fs-6 border border-primary-subtle">
                                    <i class="mdi mdi-tag-outline me-1"></i>{{ $tag?->name }}
                                </span>
                            @empty
                                <span class="text-muted italic">No tags selected.</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Audit Info -->
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

        .badge {
            font-weight: 500;
        }

        label {
            letter-spacing: 0.5px;
        }
    </style>
@endpush
@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-0">Product List</h4>
                    <a href="{{ route('product.add') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i> Add New Product
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Table Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="productTable" class="table table-hover table-bordered table-centered align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Sl</th>
                                        <th>Product</th>
                                        <th>Category/Brand</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products ?? [] as $key => $product)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($product->thumbnail) }}" alt="product-img"
                                                        title="product-img" class="rounded me-3 border" height="45"
                                                        width="45" style="object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-0 fs-14">{{ Str::limit($product->name, 30) }}</h6>
                                                        <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-0 fw-medium">{{ $product->details->category->name ?? 'N/A' }}</p>
                                                <small class="text-muted">{{ $product->details->brand->name ?? 'No Brand' }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">৳{{ number_format($product->discount, 2) }}
                                                </div>
                                                @if ($product->discount > 0)
                                                    <del class="text-muted small">৳{{ number_format($product->price, 2) }}</del>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->stock > 10)
                                                    <span class="text-success fw-bold">{{ $product->stock }}</span>
                                                @elseif($product->stock > 0)
                                                    <span class="text-warning fw-bold">{{ $product->stock }}
                                                        (Low)</span>
                                                @else
                                                    <span class="text-danger fw-bold">Out of Stock</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->status == 1)
                                                    <span
                                                        class="badge bg-soft-success text-success border border-success-subtle">Active</span>
                                                @else
                                                    <span
                                                        class="badge bg-soft-danger text-danger border border-danger-subtle">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('product.view', $product->id) }}"
                                                        class="btn btn-sm btn-outline-secondary" title="View">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('product.edit', $product->id) }}"
                                                        class="btn btn-sm btn-outline-info" title="Edit">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>
                                                    <form action="{{ route('product.destroy', $product->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger deleteBtn" title="Delete">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="mdi mdi-database-off fs-1"></i>
                                                    <p class="mt-2">No Products Found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-nowrap {
            white-space: nowrap;
        }

        .bg-soft-success {
            background-color: rgba(52, 195, 143, 0.1);
        }

        .bg-soft-danger {
            background-color: rgba(244, 106, 106, 0.1);
        }

        .table-centered td,
        .table-centered th {
            vertical-align: middle !important;
        }

        .btn-group .btn {
            margin: 0 2px;
            border-radius: 4px !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#productTable').DataTable({
                responsive: true,
                language: {
                    searchPlaceholder: "Search products...",
                    sSearch: "",
                }
            });
        });
    </script>
@endpush
@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Product Inventory Management</h2>
                <a href="{{ route('product.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                    Back to Products
                </a>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Product: {{ $product->name }}</h5>
                    <p class="card-text">Manage the inventory for this product by adding different size and color variants along with their prices, stock quantities, and images.</p>
                </div>
            </div>
        </div>
        <!-- Left Side Inventory Table  -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header py-4">
                    <h5 class="card-title mb-0">Inventory List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle table-striped" id="inventoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>SL</th>
                                    <th>Size</th>
                                    <th class="text-center">Color</th>
                                    <th>Price</th>
                                    <th class="text-center">Image</th>
                                    <th>Stock</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventories ?? [] as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->size->name ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <div class="mx-auto d-block" style="background-color: {{ $item->color->color_code }}; border: 2px solid #ccc; width: 30px; height: 30px; border-radius: 50%;">
                                            </div>
                                        </td>
                                        <td class="fw-bold text-primary">৳{{ number_format($item->price, 2) }}</td>
                                        <td class="text-center">
                                            <img class="img-fluid"
                                                style=" border-radius: 0; object-fit: contain; aspect-ratio: 4 / 4; background-color: #fff; border: 1px solid #ccc;"
                                                src="{{ $item->thumbnail }}">
                                        </td>
                                        <td>{{ $item->stock }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-info btn-sm">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                            <a href="{{ route('inventory.destroy', $item->id) }}"
                                                class="btn btn-danger btn-sm deleteBtn">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No variants found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side Add Form Submition -->
        <div class="col-lg-4">
            <div class="card">
                <form action="{{ route('inventory.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header pt-4">
                        <h4 class="card-title">Add New Inventory</h4>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <x-select label="Size" name="size_id" :options="$sizes" option_label="name" option_value="id" placeholder="Select Size" :required="false" />
                        <x-select label="Color" name="color_id" :options="$colors" option_label="name" option_value="id" placeholder="Select color" :required="false" />
                        <x-input label="Price" name="price" type="number" step="0.01" placeholder="Enter price" :required='false' />
                        <x-input label="Stock Quantity" name="stock" type="number" placeholder="Enter stock quantity" :required='false' />
                        <x-media-thumbnail label="Image" target_id="main_thumb" input_name="media_id" />
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Add Inventory</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#inventoryTable').DataTable({
            "pageLength": 10,
            "language": {
                "searchPlaceholder": "Search inventory...",
                "sSearch": ""
            }
        });
    });
</script>
@endpush
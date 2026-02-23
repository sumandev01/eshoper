@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Product Inventory Management</h4>
                            <a href="{{ route('product.index') }}" class="btn btn-primary">
                                <i class="mdi mdi-arrow-left btn-icon-prepend me-2"></i>
                                Back to Products
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Product: {{ $product?->name }}</h5>
                        <p class="card-text">Manage the inventory for this product by adding different size and color
                            variants along with their prices, stock quantities, and images.</p>
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
                                        <th class="text-end">Price / <br> Discount</th>
                                        <th class="text-center">Image</th>
                                        <th class="text-end stock-col">Stock</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inventories ?? [] as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item?->size?->name ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <div class="mx-auto d-block"
                                                    style="background-color: {{ $item?->color?->color_code }}; border: 2px solid #ccc; width: 30px; height: 30px; border-radius: 50%;">
                                                </div>
                                            </td>
                                            <td class="fw-bold text-primary text-end">
                                                <div class="price-container">
                                                    @php
                                                        $displayDiscount =
                                                            $item?->use_main_discount == 1
                                                                ? $item?->product?->discount
                                                                : $item?->discount;
                                                        $displayPrice =
                                                            $item?->use_main_price == 1
                                                                ? $item?->product?->price
                                                                : $item?->price;
                                                    @endphp

                                                    @if (!is_null($displayDiscount) && $displayDiscount > 0)
                                                        <span
                                                            style="color: gray; text-decoration: line-through;">৳{{ $displayPrice }}</span>
                                                        <br>
                                                        <span
                                                            style="font-weight: bold; color: red;">৳{{ $displayDiscount }}</span>
                                                    @else
                                                        <span style="font-weight: bold;">&#2547;{{ $displayPrice }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <img class="img-fluid"
                                                    style=" border-radius: 0; object-fit: contain; aspect-ratio: 4 / 4; background-color: #fff; border: 1px solid #ccc;"
                                                    src="{{ $item?->thumbnail }}">
                                            </td>
                                            <td class="text-end">
                                                @if ($item?->stock > 0)
                                                    <span>{{ $item?->stock }}</span>
                                                @else
                                                    <span class="badge bg-danger fw-bold rounded-pill">Out of Stock</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editInventoryModal{{ $item?->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </button>
                                                <a href="{{ route('inventory.destroy', $item?->id) }}"
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
                            <input type="hidden" name="product_id" value="{{ $product?->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-select label="Size" name="size_id" :options="$sizes" option_label="name"
                                        option_value="id" placeholder="Select Size" :required="true" />
                                </div>
                                <div class="col-md-6">
                                    <x-select label="Color" name="color_id" :options="$colors" option_label="name"
                                        option_value="id" placeholder="Select color" :required="true" />
                                </div>
                                <div class="col-md-12 checkBox mb-4">
                                    <x-input id="variant_price" label="Price" name="price" type="number" step="0.01"
                                        placeholder="Enter price" :required='true' />
                                    <div class="check-box mt-2">
                                        <input class="form-check-input" id="use_main_price" name="use_main_price"
                                            type="checkbox">
                                        <label for="use_main_price" style="cursor: pointer;">Default price</label>
                                    </div>
                                </div>
                                <div class="col-md-12 checkBox mb-4">
                                    <x-input id="variant_discount" label="Discount Price" name="discount" type="number"
                                        step="0.01" placeholder="Enter discount" :required='true' />
                                    <div class="check-box mt-2">
                                        <input class="form-check-input" id="use_main_discount" name="use_main_discount"
                                            type="checkbox">
                                        <label for="use_main_discount" style="cursor: pointer;">Default discount
                                            price</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <x-input label="Quantity" name="stock" type="number"
                                        placeholder="Enter stock quantity" :required='true' />
                                </div>
                            </div>
                            <x-media-thumbnail label="Image" target_id="main_thumb" input_name="media_id"
                                required="true" />
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
    @include('dashboard.product.inventory.edit-modal')
@endsection
@push('styles')
    <style>
        .checkBox .form-group {
            margin-bottom: 0 !important;
        }
        .stock-col {
            width: 60px;
            white-space: nowrap;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            function togglePriceInput() {
                const priceInput = $('#variant_price');
                const priceCheckBox = $('#use_main_price');

                if (priceCheckBox.is(':checked')) {
                    priceInput.val('');
                    priceInput.prop('readonly', true);
                    priceInput.prop('required', false);
                    priceInput.css('background-color', '#e9ecef');
                } else {
                    priceInput.prop('readonly', false);
                    priceInput.prop('required', true);
                    priceInput.css('background-color', '#fff');
                }
            }

            function toggleDiscountInput() {
                const discountInput = $('#variant_discount');
                const discountCheckBox = $('#use_main_discount');

                if (discountCheckBox.is(':checked')) {
                    discountInput.val('');
                    discountInput.prop('readonly', true);
                    discountInput.prop('required', false);
                    discountInput.css('background-color', '#e9ecef');
                } else {
                    discountInput.prop('readonly', false);
                    discountInput.prop('required', true);
                    discountInput.css('background-color', '#fff');
                }
            }

            $('#use_main_price').on('change', togglePriceInput);
            $('#use_main_discount').on('change', toggleDiscountInput);

            togglePriceInput();
            toggleDiscountInput();

            
            $('#inventoryTable').DataTable({
                "pageLength": 10,
                "language": {
                    "searchPlaceholder": "Search inventory...",
                    "sSearch": ""
                }
            });

            function handleEditToggle(checkbox, inputId) {
                const input = document.getElementById(inputId);
                if (checkbox.checked) {
                    input.value = '';
                    input.readOnly = true;
                    input.style.backgroundColor = "#e9ecef";
                } else {
                    input.readOnly = false;
                    input.style.backgroundColor = "#fff";
                }
            }

            $('.edit-price-check').each(function() {
                const id = $(this).data('id');
                handleEditToggle(this, 'edit_price_' + id);
            });

            $('.edit-discount-check').each(function() {
                const id = $(this).data('id');
                handleEditToggle(this, 'edit_discount_' + id);
            });

            $(document).on('change', '.edit-price-check', function() {
                const id = $(this).data('id');
                handleEditToggle(this, 'edit_price_' + id);
            });

            $(document).on('change', '.edit-discount-check', function() {
                const id = $(this).data('id');
                handleEditToggle(this, 'edit_discount_' + id);
            });
        });
    </script>
@endpush

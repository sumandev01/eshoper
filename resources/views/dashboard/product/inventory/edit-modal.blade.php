<!-- Edit Modal -->
@foreach ($inventories ?? [] as $inv)
    <div class="modal fade" id="editInventoryModal{{ $inv->id }}" tabindex="-1"
        aria-labelledby="editInventoryModalLabel{{ $inv->id }}" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInventoryModalLabel{{ $inv->id }}">Edit Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="ajax-inventory-form" action="{{ route('inventory.update', $inv->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <x-select label="Size" name="size_id" :options="$sizes" option_label="name"
                                    option_value="{{ $inv->size->id }}" :selected="$inv->size_id" value="{{ $inv->size_id }}"
                                    placeholder="Select Size" :required="false" :disabled="true" />
                                <x-select label="Color" name="color_id" :options="$colors" option_label="name"
                                    option_value="{{ $inv->color->id }}" :selected="$inv->color_id" value="{{ $inv->color_id }}"
                                    placeholder="Select color" :required="false" :disabled="true" />
                                <div class="mb-3 checkBox">
                                    <x-input id="edit_price_{{ $inv->id }}" label="Price" name="price"
                                        type="number" step="0.01" :value="$inv->price" placeholder="Enter price" />
                                    <div class="form-check mt-1">
                                        <input class="form-check-input edit-price-check" type="checkbox"
                                            name="use_main_price" id="edit_use_main_price_{{ $inv->id }}"
                                            value="1" data-id="{{ $inv->id }}" @checked($inv->use_main_price == 1)>
                                        <label for="edit_use_main_price_{{ $inv->id }}">Default price</label>
                                    </div>
                                </div>

                                <div class="mb-3 checkBox">
                                    <x-input id="edit_discount_{{ $inv->id }}" label="Discount Price"
                                        name="discount" type="number" step="0.01" :value="$inv->discount"
                                        placeholder="Enter discount price" />
                                    <div class="form-check mt-1">
                                        <input class="form-check-input edit-discount-check" type="checkbox"
                                            name="use_main_discount" id="edit_use_main_discount_{{ $inv->id }}"
                                            value="1" data-id="{{ $inv->id }}" @checked($inv->use_main_discount == 1)>
                                        <label for="edit_use_main_discount_{{ $inv->id }}">Default
                                            discount</label>
                                    </div>
                                </div>
                                <x-input label="Stock Quantity" name="stock" type="number" :value="$inv->stock"
                                    placeholder="Enter stock quantity" :required='true' />
                            </div>
                            <div class="col-md-5">
                                <x-media-thumbnail label="Image" target_id="inventory_media_{{ $inv->id }}"
                                    input_name="media_id" :existing_image="$inv->thumbnail" :existing_id="$inv->media_id" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save btn-icon-prepend me-2"></i>
                            <span>Update</span>
                        </button>
                        <button type="button" class="btn btn-danger btn-icon-text" data-bs-dismiss="modal">
                            <i class="mdi mdi-close btn-icon-prepend me-2"></i>
                            <span>Close</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
<!-- End Edit Modal -->

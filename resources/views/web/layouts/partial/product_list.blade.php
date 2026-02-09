<div class="row pb-3">
    @forelse ($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-12 pb-1 product-card">
            <div class="card product-item border-0 mb-4 product-card position-relative"
                data-product-id="{{ $product->id }}" data-main-price="{{ $product->price }}"
                data-discount-price="{{ $product->discount }}">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <div class="save-amount-box text-center position-absolute p-0" style="top: 0; right: 0; z-index: 99;">
                        @if ($product->discount > 0 && $product->discount < $product->price)
                            <p class="save-amount text-success p-2 bg-primary" style="font-size: 13px;">
                                Save ৳{{ $product->price - $product->discount }}
                            </p>
                        @else
                            <p class="save-amount d-none p-2 bg-primary" style="font-size: 13px;"></p>
                        @endif
                    </div>
                    <img class="img-fluid w-100" src="{{ $product->thumbnail }}"
                        style="aspect-ratio: 1/1; object-fit: contain;" alt="{{ $product->name }}">
                    @if ($product->inventories->count() > 0)
                        <div class="varient-product position-absolute d-flex justify-content-between bg-transparent"
                            style="bottom: 0; left: 0; width: 100%;">
                            {{-- Size dropdown --}}
                            <select class="form-control form-control-md shop-size-selector" style="width: 100px">
                                <option value="" disabled>Size</option>
                                @foreach ($product->inventories->unique('size_id')->sortBy('size.name') as $index => $inv)
                                    <option value="{{ $inv->size_id }}" {{ $index == 0 ? 'selected' : '' }}>
                                        {{ $inv->size->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Color dropdown --}}
                            <select class="form-control form-control-md shop-color-selector" style="width: 100px">
                                <option value="">Color</option>
                            </select>
                        </div>
                    @endif
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3" title="{{ $product->name }}">
                        {{ Str::limit($product->name, 20, '...') }}</h6>
                    <div class="d-flex justify-content-center">
                        @if ($product->discount > 0 && $product->discount != $product->price)
                            <h6 class="variant-price">৳{{ $product->discount }}</h6>
                            <h6 class="text-muted ml-2"><del class="main-price">৳{{ $product->price }}</del></h6>
                        @else
                            <h6 class="variant-price">৳{{ $product->price }}</h6>
                            <h6 class="text-muted ml-2"><del class="main-price d-none"></del></h6>
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="{{ route('productDetails', $product->slug) }}" class="btn btn-sm text-dark p-0"><i
                            class="fas fa-eye text-primary mr-1"></i>View
                        Detail</a>
                    <a href="" class="btn btn-sm text-dark p-0 shop-add-to-cart"
                        data-product-id="{{ $product->id }}"><i
                            class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-12">
            <h3 class="text-center mt-5">No Product Found</h3>
        </div>
    @endforelse
    <div class="col-12 pb-1">
        <div class="d-flex align-items-center mb-4" style="position: relative;">
            <div class="text-left">
                <p class="mb-0">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                    {{ $products->total() }} results</p>
            </div>
            <div class="mx-auto" id="pagination-links" aria-label="Page navigation">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
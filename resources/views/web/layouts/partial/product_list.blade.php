<div class="row pb-3">
    @forelse ($products as $product)
        <div class="col-lg-4 col-md-6 col-sm-12 pb-1 product-card"
            data-variants="{{ json_encode($product->formatted_variants) }}">
            <div class="card product-item border-0 mb-4 product-card position-relative"
                data-product-id="{{ $product->id }}" data-main-price="{{ $product->price }}"
                data-discount-price="{{ $product->discount }}">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <div class="position-absolute" style="top: 8px; left: 8px; z-index: 99;">
                        <button class="btn btn-sm bg-white rounded-circle shadow-sm wishlist-btn p-1"
                            data-product-id="{{ $product->id }}" style="width: 32px; height: 32px; line-height: 1;">
                            <i class="fas fa-heart"
                                style="font-size: 13px; color: {{ in_array($product->id, $wishlistIds ?? []) ? '#e74c3c' : '#ccc' }};"></i>
                        </button>
                    </div>

                    <div class="save-amount-box @if ($product->discount > 0 && $product->discount < $product->price) d-block @else d-none @endif text-center position-absolute p-0"
                        style="top: 0; right: 0; z-index: 99;">
                        @if ($product->discount > 0 && $product->discount < $product->price)
                            <p class="save-amount text-dark p-2 bg-primary" style="font-size: 13px;">
                                Save {{ $siteSettings?->currency_symbol }}{{ formatBDT($product->price - $product->discount) }}
                            </p>
                        @else
                            <p class="save-amount p-2 bg-primary text-dark" style="font-size: 13px;"></p>
                        @endif
                    </div>
                    <div class="img-wrapper">
                        <div class="img-spinner"></div>
                        <img class="img-fluid w-100 product-main-image optimized-image" src="{{ $product->thumbnail }}"
                            alt="{{ $product->name }} - {{ $siteSettings?->site_title }}" loading="lazy" 
                            onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                            onerror="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                        <script>
                            // Handle cached images immediately
                            (function() {
                                let img = document.currentScript.previousElementSibling;
                                if (img && img.complete) {
                                    img.style.opacity = '1';
                                    img.previousElementSibling.style.display = 'none';
                                }
                            })();
                        </script>
                    </div>
                    @if ($product->inventories->count() > 0)
                        <div class="varient-product position-absolute d-flex justify-content-between bg-transparent"
                            style="bottom: 0; left: 0; width: 100%; z-index: 5;">
                            {{-- Size dropdown --}}
                            <select class="form-control form-control-md shop-size-selector" style="width: 100px">
                                <option value="" disabled>Size</option>
                                @foreach ($product->inventories->unique('size_id')->sortBy('size.name') as $index => $inv)
                                    <option value="{{ $inv->size_id }}" {{ $index == 0 ? 'selected' : '' }}>
                                        {{ $inv->size->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Color dropdown (Pre-populated for the first size) --}}
                            @php
                                $firstSizeId = $product->inventories->unique('size_id')->sortBy('size.name')->first()?->size_id;
                                $firstSizeColors = $product->inventories->where('size_id', $firstSizeId)->unique('color_id');
                            @endphp
                            <select class="form-control form-control-md shop-color-selector" style="width: 100px">
                                <option value="" disabled>Color</option>
                                @foreach ($firstSizeColors as $index => $inv)
                                    <option value="{{ $inv->color_id }}" {{ $index == 0 ? 'selected' : '' }}>
                                        {{ $inv->color->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3" title="{{ $product->name }}">
                        {{ Str::limit($product->name, 20, '...') }}</h6>
                    <div class="d-flex justify-content-center">
                        @if ($product->discount > 0 && $product->discount != $product->price)
                            <h6 class="variant-price">{{ $siteSettings?->currency_symbol }}{{ formatBDT($product->discount) }}
                            </h6>
                            <h6 class="text-muted ml-2"><del
                                    class="main-price">{{ $siteSettings?->currency_symbol }}{{ formatBDT($product->price) }}</del>
                            </h6>
                        @else
                            <h6 class="variant-price">{{ $siteSettings?->currency_symbol }}{{ formatBDT($product->price) }}</h6>
                            <h6 class="text-muted ml-2"><del class="main-price d-none"></del></h6>
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="{{ route('productDetails', $product->slug) }}" class="btn btn-sm text-dark p-0"><i
                            class="fas fa-eye text-primary mr-1"></i>View
                        Detail</a>
                    <a href="" class="btn btn-sm text-dark p-0 shop-add-to-cart"
                        data-product-id="{{ $product->id }}"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add
                        To Cart</a>
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

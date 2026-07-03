@php
    $firstVariant = $product->formattedVariants->first();
    $displayPrice = $firstVariant ? $firstVariant['price'] : $product->price;
    $displayDiscount = $firstVariant ? $firstVariant['discount_price'] : $product->discount;
    $displayStock = $firstVariant ? $firstVariant['stock'] : $product->stock;
@endphp
<div class="card product-item border-0 mb-4 product-card position-relative" data-product-id="{{ $product->id }}"
    data-variants="{{ json_encode($product->formatted_variants) }}">
    <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
        <div class="position-absolute" style="top: 8px; left: 8px; z-index: 99;">
            <button class="btn btn-sm bg-white rounded-circle shadow-lg wishlist-btn p-1"
                data-product-id="{{ $product->id }}" style="width: 32px; height: 32px; line-height: 1;">
                <i class="fas fa-heart"
                    style="font-size: 13px; color: {{ in_array($product->id, $wishlistIds ?? []) ? '#e74c3c' : '#ccc' }};"></i>
            </button>
        </div>

        <div class="save-amount-box @if ($displayDiscount > 0 && $displayDiscount < $displayPrice) d-block @else d-none @endif text-center position-absolute p-0"
            style="top: 0; right: 0; z-index: 99;">
            @if ($displayDiscount > 0 && $displayDiscount < $displayPrice)
                <p class="save-amount text-dark p-2 bg-primary" style="font-size: 13px;">
                    Save {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($displayPrice - $displayDiscount) }}
                </p>
            @else
                <p class="save-amount p-2 bg-primary text-dark" style="font-size: 13px;"></p>
            @endif
        </div>
        <div class="img-wrapper position-relative">
            <div class="img-spinner"></div>
            <img class="img-fluid w-100 product-main-image optimized-image" src="{{ $product->thumbnail }}"
                alt="{{ $product->name }} - {{ $siteSettings->site_title ?? (null ?? '') }}" loading="lazy"
                onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                onerror="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
            <script>
                (function() {
                    let img = document.currentScript.previousElementSibling;
                    if (img && img.complete) {
                        img.style.opacity = '1';
                        img.previousElementSibling.style.display = 'none';
                    }
                })();
            </script>
            @if ($product->inventories->count() > 0)
                @php
                    $firstSizeId = $product->inventories->unique('size_id')->sortBy('size.name')->first()?->size_id;
                    $firstSizeColors = $product->inventories->where('size_id', $firstSizeId)->unique('color_id');
                @endphp
                {{-- Dynamic size --}}
                <div class="position-absolute shop-size-container"
                    style="top: 50%; left: -40px; transform: translateY(-50%); z-index: 999;">
                    @foreach ($product->inventories->unique('size_id')->sortBy('size.name') as $index => $inv)
                        <div>
                            <span class="size shop-size-selector {{ $index == 0 ? 'active' : '' }}"
                                data-value="{{ $inv->size_id }}" style="cursor: pointer;"
                                title="{{ $inv->size?->name ?? 'N/A' }}">
                                {{ $inv->size?->name ?? 'N/A' }}
                            </span>
                        </div>
                    @endforeach
                </div>
                {{-- Dynamic color --}}
                <div class="position-absolute shop-color-container"
                    style="top: 50%; right: -40px; transform: translateY(-50%); z-index: 999;">
                    @foreach ($firstSizeColors as $index => $inv)
                        <div>
                            <span class="color shop-color-selector {{ $index == 0 ? 'active' : '' }}"
                                data-value="{{ $inv->color_id }}"
                                style="background-color: {{ $inv->color?->color_code ?? '#000' }}; cursor: pointer;"
                                title="{{ $inv->color?->name ?? 'N/A' }}"></span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="card-body border-left border-right border-bottom p-3 position-relative">
        <h6 class="text-primary mb-2" style="font-size: 13px;">
            {{ $product->details?->category?->name ?? '' }}
        </h6>
        <h6 class="text-truncate mb-2" title="{{ $product->name }}">
            <a href="{{ route('productDetails', $product->slug) }}" class="nav-link text-dark p-0">
                {{ Str::limit($product->name, 20, '...') }}
            </a>
        </h6>
        <div class="d-flex align-items-center justify-content-start mb-2">
            @if ($displayDiscount > 0 && $displayDiscount != $displayPrice)
                <h6 class="variant-price mb-0 text-primary">
                    {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($displayDiscount) }}
                </h6>
                <h6 class="ml-2 mb-0 text-primary" style="opacity: 0.6;"><del
                        class="main-price">{{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($displayPrice) }}</del>
                </h6>
            @else
                <h6 class="variant-price mb-0 text-primary">
                    {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($displayPrice) }}
                </h6>
                <h6 class="ml-2 mb-0 text-primary" style="opacity: 0.6;"><del class="main-price d-none"></del></h6>
            @endif
        </div>
        @if ($displayStock <= 0)
            <a href="javascript:void(0);"
                class="btn btn-primary py-2 rounded shadow-sm disabled d-flex justify-content-center align-items-center shop-add-to-cart"
                style="pointer-events: none;" data-product-id="{{ $product->id }}">
                <i class="fas fa-shopping-cart mr-2"></i>
                out of stock
            </a>
        @else
            <a href=""
                class="btn btn-primary py-2 rounded shadow-sm shop-add-to-cart d-flex justify-content-center align-items-center"
                data-product-id="{{ $product->id }}">
                <i class="fas fa-shopping-cart mr-2"></i>
                add to cart
            </a>
        @endif
    </div>
</div>
@push('styles')
    <style>
        .shop-size-container,
        .shop-color-container {
            transition: 0.4s;
        }

        .product-item:hover .shop-size-container {
            left: 8px !important;
            transition: 0.4s;
        }

        .product-item:hover .shop-color-container {
            right: 8px !important;
            transition: 0.4s;
        }

        span.size {
            background: var(--primary) !important;
            color: white;
            border: 2px solid #fff;
            width: 32px;
            height: 32px;
            display: flex;
            margin-bottom: 10px;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            border-radius: 50%;
        }

        span.size.active {
            width: 34px;
            height: 34px;
            background: #fff !important;
            color: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        span.color {
            width: 32px;
            height: 32px;
            display: block;
            margin-bottom: 10px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        span.color.active {
            width: 34px;
            height: 34px;
            border-color: var(--primary);
        }
    </style>
@endpush

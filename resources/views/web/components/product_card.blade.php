@php
    $firstVariant = $product->formattedVariants->first();
    $displayPrice = $firstVariant ? $firstVariant['price'] : $product->price;
    $displayDiscount = $firstVariant ? $firstVariant['discount_price'] : $product->discount;
    $displayStock = $firstVariant ? $firstVariant['stock'] : $product->stock;
@endphp
<div class="card product-item theme-shadow mb-4 product-card position-relative" data-product-id="{{ $product->id }}"
    data-variants="{{ json_encode($product->formatted_variants) }}">
    <div class="card-header product-img position-relative overflow-hidden bg-transparent border-0 p-0">
        <div class="position-absolute" style="top: 8px; left: 8px; z-index: 99;">
            <button class="btn btn-sm bg-white rounded-circle shadow-lg wishlist-btn p-1"
                data-product-id="{{ $product->id }}" style="width: 32px; height: 32px; line-height: 1;">
                <i class="fas fa-heart"
                    style="font-size: 13px; color: {{ in_array($product->id, $wishlistIds ?? []) ? '#e74c3c' : '#ccc' }};"></i>
            </button>
        </div>

        <div class="save-amount-box @if ($displayDiscount > 0 && $displayDiscount < $displayPrice) d-block @else d-none @endif text-center position-absolute p-0"
            style="top: 10px; right: 10px; z-index: 99;">
            @if ($displayDiscount > 0 && $displayDiscount < $displayPrice)
                @php
                    $discountPercentage = round((($displayPrice - $displayDiscount) / $displayPrice) * 100);
                @endphp
                <p class="save-amount px-2 py-1 mb-0 fw-bold rounded-pill shadow-sm" style="font-size: 13px; background-color: color-mix(in srgb, var(--primary) 12%, white); color: var(--primary);">
                    -{{ $discountPercentage }}%
                </p>
            @else
                <p class="save-amount px-2 py-1 mb-0 fw-bold rounded-pill shadow-sm" style="font-size: 13px; background-color: color-mix(in srgb, var(--primary) 12%, white); color: var(--primary);"></p>
            @endif
        </div>
        <div class="img-wrapper position-relative">
            <div class="img-spinner"></div>
            <img onclick="window.location.href='{{ route('product.details', $product->slug) }}'" style="cursor: pointer;"
                class="img-fluid w-100 product-main-image optimized-image" src="{{ $product->thumbnail }}"
                alt="{{ $product->name }} - {{ $siteSettings->site_title ?? (null ?? '') }}" 
                @if(isset($isFirst) && $isFirst) loading="eager" fetchpriority="high" @else loading="lazy" @endif
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
                    style="bottom: 8px; left: -50px; display: flex; flex-direction: column; gap: 5px; z-index: 999;">
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
                    style="bottom: 8px; right: -50px; display: flex; flex-direction: column; gap: 5px; z-index: 999;">
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
    <div class="card-body border-0 p-3 position-relative">
        <h6 class="text-primary mb-2" style="font-size: 13px;">
            {{ $product->details?->category?->name ?? '' }}
        </h6>
        <h6 class="text-truncate mb-2" title="{{ $product->name }}">
            <a href="{{ route('product.details', $product->slug) }}" class="nav-link text-dark p-0">
                {{ Str::limit($product->name, 20, '...') }}
            </a>
        </h6>
        <div class="d-flex align-items-center justify-content-start mb-2">
            @if ($displayDiscount > 0 && $displayDiscount != $displayPrice)
                <h6 class="variant-price mb-0 text-primary">
                    {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($displayDiscount) }}
                </h6>
                <h6 class="ms-2 mb-0 text-primary" style="opacity: 0.6;"><del
                        class="main-price">{{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($displayPrice) }}</del>
                </h6>
            @else
                <h6 class="variant-price mb-0 text-primary">
                    {{ $siteSettings->currency_symbol ?? null }}{{ formatBDT($displayPrice) }}
                </h6>
                <h6 class="ms-2 mb-0 text-primary" style="opacity: 0.6;"><del class="main-price d-none"></del></h6>
            @endif
        </div>

        {{-- Review Stars --}}
        <div class="d-flex align-items-center justify-content-start mb-3">
            <div class="text-primary" style="font-size: 12px;">
                @php
                    $rating = $product->reviews_avg_rating ?? 0;
                    $fullStars = floor($rating);
                    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - ($fullStars + $halfStar);
                @endphp
                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star"></i>
                @endfor
                @if ($halfStar)
                    <i class="fas fa-star-half-alt"></i>
                @endif
                @for ($i = 0; $i < $emptyStars; $i++)
                    <i class="far fa-star"></i>
                @endfor
            </div>
        </div>

        @if ($displayStock <= 0)
            <a href="javascript:void(0);"
                class="btn btn-outline-secondary py-2 rounded disabled d-flex justify-content-center align-items-center shop-add-to-cart"
                style="pointer-events: none; border-width: 2px;" data-product-id="{{ $product->id }}">
                <i class="fas fa-shopping-cart me-2"></i>
                Out of Stock
            </a>
        @else
            <a href="javascript:void(0);"
                class="btn btn-outline-primary py-2 rounded shop-add-to-cart d-flex justify-content-center align-items-center product-add-btn"
                style="border-width: 2px; font-weight: 500; transition: all 0.3s;" data-product-id="{{ $product->id }}">
                <i class="fas fa-shopping-cart me-2"></i>
                Add to Cart
            </a>
        @endif
    </div>
</div>
@push('styles')
    <style>
        .shop-size-container,
        .shop-color-container {
            transition: 0.4s;
            opacity: 0;
        }

        .product-item:hover .shop-size-container,
        .product-item:hover .shop-color-container {
            opacity: 1;
            transition: 0.4s;
        }

        .product-item:hover .shop-size-container{
            left: 8px !important;
        }
        .product-item:hover .shop-color-container {
            right: 8px !important;
        }

        span.size {
            background: var(--primary) !important;
            color: white;
            border: 2px solid #fff;
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            border-radius: 50%;
        }

        span.size.active {
            width: 32px;
            height: 32px;
            background: #fff !important;
            color: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        span.color {
            width: 32px;
            height: 32px;
            display: block;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        span.color.active {
            width: 32px;
            height: 32px;
            border-color: var(--primary);
        }

        .product-add-btn:hover {
            background-color: var(--primary) !important;
            color: #fff !important;
            border-color: var(--primary) !important;
        }
    </style>
@endpush

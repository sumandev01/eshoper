@extends('web.layouts.app')
@section('title', ($product?->details?->meta_title ?? $product?->name) . ' - ' . ($siteSettings->site_title ?? null))
@section('meta_description', $product?->details?->meta_description ??
    Str::limit(strip_tags($product?->details?->description ?? ''), 160))
@section('meta_keywords', $product?->details?->meta_keyword ?? ($siteSettings->site_keywords ?? null))
@section('og_image', url($product?->thumbnail))
@section('og_url', route('product.details', $product?->slug))
@section('content')
    @include('web.components.breadcrumb', [
        'breadcrumbs' => [
            ['name' => $product?->details?->category?->name ?? 'Category', 'url' => $product?->details?->category?->slug ? route('category.products', $product->details->category->slug) : '#'],
            ['name' => $product?->name, 'url' => '']
        ]
    ])
    <div class="container pt-1 pb-5">
        <div class="row">
            <!-- Product Gallery Start -->
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner border" id="carousel-images-container">
                        <div class="carousel-item active">
                            <div class="img-wrapper">
                                <div class="img-spinner"></div>
                                <img id="main-image-preview" class="w-100 h-100 optimized-image" src="{{ $product?->media?->medium_url ?? asset('default.webp') }}"
                                    alt="Image" loading="lazy" 
                                    onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                                    onerror="this.style.opacity='1'; this.previousElementSibling.style.display='none';">
                                <script>
                                    (function() {
                                        let img = document.getElementById('main-image-preview');
                                        if (img && img.complete) {
                                            img.style.opacity = '1';
                                            img.previousElementSibling.style.display = 'none';
                                        }
                                    })();
                                </script>
                            </div>
                        </div>
                        @foreach ($product?->galleries ?? [] as $gallery)
                            <div class="carousel-item">
                                <div class="img-wrapper">
                                    <div class="img-spinner"></div>
                                    <img class="w-100 h-100 optimized-image" src="{{ $gallery?->large_url }}"
                                        alt="Image" loading="lazy" 
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                        <i class="fa fa-2x fa-angle-left"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                        <i class="fa fa-2x fa-angle-right"></i>
                    </a>
                </div>
            </div>
            <!-- Product Gallery End -->
            <!-- Product Detail Start -->
            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold">{{ $product?->name }}</h3>
                <div class="d-flex">
                    <div class="star-group">
                        <input type="hidden" class="rating-value-active" value="{{ $finalRating }}">
                        <button type="button" class="star-btn-active far fa-star" data-value="1"
                            style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                        <button type="button" class="star-btn-active far fa-star" data-value="2"
                            style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                        <button type="button" class="star-btn-active far fa-star" data-value="3"
                            style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                        <button type="button" class="star-btn-active far fa-star" data-value="4"
                            style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                        <button type="button" class="star-btn-active far fa-star" data-value="5"
                            style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                    </div>
                    <small class="pt-1 ms-2">({{ $productReview?->count() ?? 0 }} Reviews)</small>
                </div>
                <div class="d-flex align-items-end" id="price-container">
                    @php
                        $mainPrice = $product?->price;
                        $discountPrice = $product?->discount;

                        if (isset($productInventory)) {
                            $mainPrice =
                                $productInventory?->use_main_price == 1 ? $product?->price : $productInventory?->price;
                            $discountPrice =
                                $productInventory?->use_main_discount == 1
                                    ? $product?->discount
                                    : $productInventory?->discount;
                        }

                    @endphp

                    @if (($discountPrice ?? 0) > 0)
                        <h3 class="font-weight-semi-bold mb-0 product_main_price"><span>{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ $discountPrice }}</h3>
                        @if (($mainPrice ?? 0) > 0)
                            <h4 class="font-weight-semi-bold text-muted mb-0 ms-2"><del><span>{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ $mainPrice }}</del></h4>
                        @endif
                    @elseif(($mainPrice ?? 0) > 0)
                        <h3 class="font-weight-semi-bold mb-0 product_main_price"><span>{{ ($siteSettings->currency_symbol ?? null) }}</span>{{ $mainPrice }}</h3>
                    @endif
                </div>

                <p class="mb-3">{{ $product?->details?->shortDescription }}</p>

                @if ($sizes && $sizes->count() > 0)
                    <div class="d-flex mb-3 align-items-center">
                        <p class="text-dark font-weight-medium mb-0 me-3">Sizes:</p>
                        <div id="main-size-form" class="d-flex flex-wrap">
                            @foreach ($sizes->sortByDesc('name') ?? [] as $index => $size)
                                <span class="size variable-size-input {{ $index == 0 ? 'active' : '' }}"
                                    data-value="{{ $size?->id }}" style="cursor: pointer; margin-right: 10px; margin-bottom: 0;"
                                    title="{{ $size?->name }}">
                                    {{ $size?->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($colors && $colors->count() > 0)
                    <div class="d-flex mb-3 align-items-center">
                        <p class="text-dark font-weight-medium mb-0 me-3">Colors:</p>
                        <div id="main-color-form" class="d-flex flex-wrap align-items-center">
                            <span id="color-selection-message" class="text-muted small">Please select a size</span>
                            <div id="dynamic-colors-container" class="d-flex flex-wrap"></div>
                        </div>
                    </div>
                @endif

                <div class="d-flex mb-3">
                    <p class="text-dark font-weight-medium mb-0 me-2">Quantity:</p>
                    <span id="variant-stock-display">
                        @if ($product?->stock > 0)
                            <span class="text-primary fw-bold product_stock">{{ $product?->stock }} In Stock (Total)</span>
                        @else
                            <span class="text-danger fw-bold">Out of Stock</span>
                        @endif
                    </span>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <div class="input-group quantity me-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-minus" disabled>
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" id="product-quantity" class="form-control bg-secondary text-center"
                            value="1" min="1" disabled>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-primary btn-plus" disabled>
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button id="add-to-cart-btn" class="btn btn-primary px-3 d-flex align-items-center" disabled>
                        <i class="fa fa-shopping-cart"></i>
                        <span class="ms-3">Add To Cart</span>
                    </button>
                </div>

                <div class="d-flex">
                    <p class="text-dark font-weight-medium mb-0 me-2">Category:</p>
                    <span>{{ $product?->details?->category?->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 me-2">Sub Category:</p>
                    <span>{{ $product?->details?->subCategory?->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 me-2">Brand:</p>
                    <span>{{ $product?->details?->brand?->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 me-2">SKU:</p>
                    <span>{{ $product?->sku ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 me-2">Tags:</p>
                    <div class="d-inline-flex">
                        {{ $tags?->pluck('name')->implode(', ') ?? 'N/A' }}
                    </div>
                </div>
            </div>
            <!-- Product Detail End -->
        </div>
        <!-- Product Extra Detail End -->
        <div class="row">
            <div class="col">
                <div class="nav nav-tabs custom-tabs justify-content-center mb-4">
                    <a class="nav-item nav-link active" data-bs-toggle="tab" href="#tab-pane-1">Description</a>
                    <a class="nav-item nav-link" data-bs-toggle="tab" href="#tab-pane-2">Information</a>
                    <a class="nav-item nav-link" data-bs-toggle="tab" href="#tab-pane-3">Reviews
                        ({{ $productReview?->count() ?? 0 }})</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Product Description</h4>
                        {!! $product?->details?->description ?? 'No description available.' !!}
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <h4 class="mb-3">Additional Information</h4>
                        {!! $product?->details?->information ?? 'No specifications provided.' !!}
                    </div>
                    <!-- Reviews Start -->
                    <div class="tab-pane fade" id="tab-pane-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-4">
                                    @if ($productReview?->count() ?? 0 > 0)
                                        {{ $productReview?->count() ?? 0 }} Reviews for "{{ $product?->name }}"
                                    @else
                                        No reviews for "{{ $product?->name }}"
                                    @endif
                                </h5>
                                @foreach ($productReview ?? [] as $review)
                                    <div class="media {{ $review?->replies?->count() ?? 0 > 0 ? 'mb-0' : 'mb-4' }}">
                                        <img src="{{ $review?->user?->profile }}" alt="Image"
                                            class="img-fluid me-3 mt-1 rounded-circle" style="width: 45px;">
                                        <div class="media-body">
                                            <h6>{{ $review?->user?->name ?? $review?->name }}<small> -
                                                    <i>{{ $review?->created_at?->format('d-M-Y') }}</i></small></h6>
                                            <div class="star-group">
                                                <input type="hidden" class="rating-value-active"
                                                    value="{{ $review->rating ?? 0 }}">
                                                <button type="button" class="star-btn-active far fa-star" data-value="1"
                                                    style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                <button type="button" class="star-btn-active far fa-star" data-value="2"
                                                    style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                <button type="button" class="star-btn-active far fa-star" data-value="3"
                                                    style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                <button type="button" class="star-btn-active far fa-star" data-value="4"
                                                    style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                                <button type="button" class="star-btn-active far fa-star" data-value="5"
                                                    style="background: none; border: none; font-size: 15px; padding: 0; color: #ffc107; cursor: default; pointer-events: none;"></button>
                                            </div>
                                            <p>{{ $review?->review_text ?? 'No review provided.' }}</p>

                                            @can(App\Enums\Permission\CommentPermission::REPLY->value)
                                                @if ($review?->replies?->count() == 0)
                                                    <button class="btn btn-primary btn-sm reply-toggle-btn"
                                                        data-bs-target="reply-form-{{ $review->id }}">Reply</button>
                                                @endif
                                                <div id="reply-form-{{ $review->id }}" class="d-none mt-2">
                                                    <form action="{{ route('review.reply') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="comment_id" value="{{ $review->id }}">
                                                        <div class="mb-3 mb-2">
                                                            <textarea name="reply_text" class="form-control" rows="3" placeholder="Write your reply here..." required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-sm">Submit
                                                            Reply</button>
                                                        <button type="button"
                                                            class="btn btn-secondary btn-sm cancel-btn">Cancel</button>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                    <!-- Reply Reviews Start -->
                                    @foreach ($review?->replies ?? [] as $reply)
                                        <div class="media mb-4 ms-5">
                                            <img src="{{ $reply?->user?->profile }}" alt="Image"
                                                class="img-fluid me-3 mt-1 rounded-circle" style="width: 45px;">
                                            <div class="media-body">
                                                <h6>{{ $reply?->user?->name ?? 'Admin' }}<small> -
                                                        <i>{{ $reply?->created_at?->format('d-M-Y') }}</i></small></h6>
                                                <p>{{ $reply?->reply_text ?? 'No reply provided.' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- Reply Reviews End -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Reviews End -->
                </div>
            </div>
        </div>
        <!-- Product Extra Detail End -->
    </div>
    @include('web.components.product_slider', ['products' => $relatedProducts, 'title' => 'You May Also Like'])
@endsection
@push('styles')
    <style>
        #product-carousel .carousel-control-prev,
        #product-carousel .carousel-control-next {
            z-index: 999;
        }
        #product-carousel .carousel-control-prev i,
        #product-carousel .carousel-control-next i {
            color: var(--primary);
            transition: color 0.3s ease;
        }
        #product-carousel .carousel-control-prev:hover i,
        #product-carousel .carousel-control-next:hover i {
            color: var(--primary-dark) !important;
        }
        
        /* Modern Tabs Design */
        .custom-tabs.nav-tabs {
            border-bottom: 1px solid #e2e8f0;
            gap: 1rem;
        }
        .custom-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--dark);
            font-weight: 600;
            background: transparent !important;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .custom-tabs .nav-link:hover {
            color: var(--primary);
            border-bottom: 2px solid color-mix(in srgb, var(--primary) 30%, transparent);
        }
        .custom-tabs .nav-link.active {
            color: var(--primary) !important;
            border-bottom: 2px solid var(--primary) !important;
        }

        #dynamic-colors-container span.color {
            border-color: #000 !important;
        }
        #dynamic-colors-container span.color.active {
            border-color: var(--primary) !important;
        }
    </style>
@endpush
@push('scripts')
    <script type="application/ld+json">
@php
    $schema = [
        "@context" => "https://schema.org/",
        "@type" => "Product",
        "name" => addslashes($product?->name ?? 'Product'),
        "image" => url($product?->thumbnail ?? ''),
        "description" => addslashes(Str::limit(strip_tags($product?->details?->description ?? ''), 160)),
        "brand" => [
            "@type" => "Brand",
            "name" => addslashes($product?->details?->brand?->name ?? 'Generic')
        ],
        "sku" => $product?->sku ?? '',
        "offers" => [
            "@type" => "Offer",
            "url" => url()->current(),
            "priceCurrency" => ($siteSettings->currency_code ?? null) ?? 'BDT',
            "price" => $metaFinalPrice ?? (($product?->discount ?? 0) > 0 ? $product->discount : ($product?->price ?? 0)),
            "availability" => "https://schema.org/" . (($product?->stock ?? 0) > 0 ? 'InStock' : 'OutOfStock'),
            "itemCondition" => "https://schema.org/NewCondition"
        ]
    ];

    if (($productReview?->count() ?? 0) > 0) {
        $schema["aggregateRating"] = [
            "@type" => "AggregateRating",
            "ratingValue" => $finalRating ?? 5,
            "reviewCount" => $productReview->count()
        ];
    }
@endphp
{!! json_encode($schema) !!}
</script>
    <script>
        const productInventories = @json($inventoriesJson);

        $(document).ready(function() {
            // --- CLIENT-SIDE PREFETCHING (Main Product Only) ---
            if (productInventories && productInventories.length > 0) {
                productInventories.forEach(function(variant) {
                    if (variant.image) {
                        const img = new Image();
                        img.src = variant.image;
                    }
                });
            }

            const sizeInputs = $('#main-size-form .variable-size-input');
            const hasVariants = sizeInputs.length > 0;
            const productId = "{{ $product->id }}";
            let currentStock = parseInt("{{ $product->stock }}") || 0;
            let selectInventoryId = null;
            const OriginalThumbnail = "{{ $product?->media?->medium_url ?? asset('default.webp') }}";
            const currencySymbol = "{{ ($siteSettings->currency_symbol ?? null) }}";

            function toggleCartControls(enable) {
                $('#add-to-cart-btn, #product-quantity, .btn-plus, .btn-minus').prop('disabled', !enable);
            }

            if (!hasVariants && currentStock > 0) {
                toggleCartControls(true);
            } else {
                toggleCartControls(false);
            }

            // Auto select the first active size on page load
            if (hasVariants) {
                setTimeout(() => {
                    $('.variable-size-input.active').trigger('click');
                }, 100);
            }

            // Size Click
            $(document).on('click', '.variable-size-input', function() {
                $('.variable-size-input').removeClass('active');
                $(this).addClass('active');

                let sizeId = $(this).data('value');
                let stockDisplay = $('#variant-stock-display');
                let colorContainer = $('#dynamic-colors-container');
                let colorMessage = $('#color-selection-message');

                colorContainer.empty();
                colorMessage.hide();
                toggleCartControls(false);
                stockDisplay.html('<span class="text-muted fw-bold">Please select color</span>');

                // Filter colors available for this size from local JSON
                let availableColors = productInventories.filter(inv => inv.size_id == sizeId);

                if (availableColors.length > 0) {
                    let colorHtml = '';
                    // Use a Map to ensure unique colors are displayed
                    let uniqueColors = [...new Map(availableColors.map(item => [item.color_id, item])).values()];
                    
                    uniqueColors.forEach(function(inv, index) {
                        let colorCode = inv.color_code || (inv.color && inv.color.code) || '#000';
                        colorHtml += `
                            <span class="color variable-color-input" 
                                  data-value="${inv.color_id}" 
                                  style="background-color: ${colorCode}; cursor: pointer; margin-right: 10px; margin-bottom: 0;" 
                                  title="${inv.color_name}"></span>`;
                    });
                    colorContainer.html(colorHtml);

                    // Auto-select first color
                    setTimeout(() => {
                        $('.variable-color-input').first().trigger('click');
                    }, 50);
                } else {
                    colorMessage.show().text('No colors available').addClass('text-danger');
                }
            });

            // Color Click
            $(document).on('click', '.variable-color-input', function() {
                $('.variable-color-input').removeClass('active');
                $(this).addClass('active');

                let sizeId = $('.variable-size-input.active').data('value');
                let colorId = $(this).data('value');
                let priceContainer = $('#price-container');
                let stockDisplay = $('#variant-stock-display');
                let qtyInput = $('#product-quantity');

                if (sizeId && colorId) {
                    // Find specific inventory from local JSON
                    let variant = productInventories.find(inv => parseInt(inv.size_id) === parseInt(sizeId) && parseInt(inv.color_id) === parseInt(colorId));

                    if (variant) {
                        currentStock = parseInt(variant.stock) || 0;
                        qtyInput.val(1);
                        selectInventoryId = variant.id;

                        // Update variant image
                        if (variant.image) {
                            const img = $('#main-image-preview');
                            img.css('opacity', '0').prev('.img-spinner').show();
                            img.attr('src', variant.image);
                        } else {
                            const img = $('#main-image-preview');
                            img.css('opacity', '0').prev('.img-spinner').show();
                            img.attr('src', OriginalThumbnail);
                        }
                        
                        // Move carousel to first item to show the updated image
                        const carouselEl = document.getElementById('product-carousel');
                        if (carouselEl && window.bootstrap) {
                            const bsCarousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
                            bsCarousel.to(0);
                        }

                        if (currentStock > 0) {
                            toggleCartControls(true);
                            qtyInput.attr('max', currentStock);
                            stockDisplay.html(
                                `<span class="text-primary fw-bold">${currentStock} In Stock</span>`
                            );
                        } else {
                            toggleCartControls(false);
                            stockDisplay.html(
                                '<span class="text-danger fw-bold">Out of Stock</span>');
                        }

                        // Price update
                        let priceHtml = '';
                        if (variant.discount > 0) {
                            priceHtml = `<h3 class="font-weight-semi-bold mb-0 product_main_price"><span>${currencySymbol}</span>${variant.discount}</h3>
                                         <h4 class="font-weight-semi-bold text-muted mb-0 ms-2"><del><span>${currencySymbol}</span>${variant.price}</del></h4>`;
                        } else {
                            priceHtml = `<h3 class="font-weight-semi-bold mb-0 product_main_price"><span>${currencySymbol}</span>${variant.price}</h3>`;
                        }
                        priceContainer.html(priceHtml);
                    }
                }
            });

            // Quantity Check Function
            function checkStockLimit(targetValue) {
                let qtyInput = $('#product-quantity');
                if (parseInt(targetValue) > currentStock) {
                    showToast('error', 'Available stock is only ' + currentStock);
                    qtyInput.val(currentStock);
                    return false;
                }
                return true;
            }

            // Plus Button
            $('.btn-plus').on('click', function() {
                let qtyInput = $('#product-quantity');
                let nextVal = parseInt(qtyInput.val()) + 1;
                checkStockLimit(nextVal);
            });

            // Minus Button
            $('.btn-minus').on('click', function() {
                let qtyInput = $('#product-quantity');
                let currentVal = parseInt(qtyInput.val());
                if (currentVal > 1) qtyInput.val(currentVal - 1);
            });

            // Input field manual change check
            $('#product-quantity').on('change keyup', function() {
                let val = $(this).val();
                if (val !== '') {
                    checkStockLimit(val);
                }
            });

            // Add To Cart
            $('#add-to-cart-btn').on('click', function(e) {
                e.preventDefault();
                let quantity = parseInt($('#product-quantity').val());

                if (quantity > currentStock) {
                    showToast('error', 'You cannot add more than available stock.');
                    return;
                }

                // ... rest of your existing ajax call ...
                let hasSize = $('.variable-size-input').length > 0;
                let hasColor = $('.variable-color-input').length > 0;
                let sizeId = hasSize ? $('.variable-size-input.active').data('value') : null;
                let colorId = hasColor ? $('.variable-color-input.active').data('value') : null;
                let productMainPriceText = $('.product_main_price').first().text();
                let currency_symbol = "{{ ($siteSettings->currency_symbol ?? null) }}";
                let productMainPrice = parseFloat(productMainPriceText.replace('<span>' + currency_symbol + '</span>', ''));

                if ((hasSize && !sizeId) || (hasColor && !colorId)) {
                    showToast('error', 'Please select a size and color');
                    return;
                }

                let cartBtn = $(this);
                cartBtn.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm"></i> Adding...');

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        productId: productId,
                        sizeId: sizeId,
                        colorId: colorId,
                        quantity: quantity,
                    },
                    success: function(response) {
                        cartBtn.prop('disabled', false).html(
                            '<i class="fa fa-shopping-cart"></i> Add To Cart');
                        if (response.status == 'success') {
                            showToast('success', response.message);
                            $("#cartCount").text(response.cartCount);
                            $(".global-cart-count").text(response.cartCount);
                            
                            // Fetch and update the mini-cart dropdown
                            $.get('{{ route("cart.minicart") }}', function(html) {
                                $('#minicart').replaceWith(html);
                            });
                        }
                    },
                    error: function(xhr) {
                        cartBtn.prop('disabled', false).html(
                            '<i class="fa fa-shopping-cart"></i> Add To Cart');
                        if (xhr.status === 401) {
                            $("#loginModal").modal("show");
                        }
                    },
                });
            });



            $(document).on('click', '.star-btn', function() {
                let value = $(this).data('value');

                $('#rating_value').val(value);

                $('.star-btn').removeClass('fas').addClass('far');

                $('.star-btn').each(function(index) {
                    if (index < value) {
                        $(this).removeClass('far').addClass('fas');
                    }
                });
            });

            $('.star-group').each(function() {
                let $group = $(this);
                let dbValue = parseInt($group.find('.rating-value-active').val());
                if (dbValue > 0) {
                    $group.find('.star-btn-active').each(function(index) {
                        if (index < dbValue) {
                            $(this).removeClass('far').addClass('fas');
                        }
                    });
                }
            });

            document.querySelectorAll('.reply-toggle-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    document.getElementById(targetId).classList.remove('d-none');
                });
            });
            document.querySelectorAll('.cancel-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    document.getElementById(targetId).classList.add('d-none');
                });
            });
        });
    </script>
@endpush




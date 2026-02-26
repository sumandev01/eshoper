@extends('web.layouts.app')
@section('title', ($product?->details?->meta_title ?? $product?->name) . ' | ' . config('app.name'))
@section('meta_description', $product?->details?->meta_description ?? Str::limit(strip_tags($product?->details?->description ?? ''), 160))

@section('og_title', $product?->details?->meta_title ?? $product?->name)
@section('og_description', $product?->details?->meta_description ?? Str::limit(strip_tags($product?->details?->description ?? ''), 160))
@section('og_image', url($product?->thumbnail))
@section('og_url', route('productDetails', $product?->slug))

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li><a class="nav-link p-0" href="{{ route('products') }}">Products</a></li>
                        <li>Single Product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border" id="carousel-images-container">
                        <div class="carousel-item active">
                            <img id="main-image-preview" class="w-100 h-100" src="{{ $product?->thumbnail }}"
                                style="aspect-ratio: 1/1; object-fit: contain;" alt="Image">
                        </div>
                        @foreach ($product?->galleries ?? [] as $gallery)
                            <div class="carousel-item">
                                <img class="w-100 h-100" src="{{ Storage::url($gallery?->src) }}"
                                    style="aspect-ratio: 1/1; object-fit: contain;" alt="Image">
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold">{{ $product?->name }}</h3>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star-half-alt"></small>
                        <small class="far fa-star"></small>
                    </div>
                    <small class="pt-1">(50 Reviews)</small>
                </div>
                <div class="d-flex align-items-end mb-4" id="price-container">
                    @php
                        $mainPrice = $product?->price;
                        $discountPrice = $product?->discount;

                        if (isset($productInventory)) {
                            $mainPrice =
                                $productInventory?->user_main_price == 1 ? $product?->price : $productInventory?->price;
                            $discountPrice =
                                $productInventory?->user_main_discount == 1
                                    ? $product?->discount
                                    : $productInventory?->discount;
                        }
                    @endphp

                    @if (($discountPrice ?? 0) > 0)
                        <h3 class="font-weight-semi-bold mb-0 product_main_price">৳{{ $discountPrice }}</h3>
                        @if (($mainPrice ?? 0) > 0)
                            <h4 class="font-weight-semi-bold text-muted mb-0 ml-2"><del>৳{{ $mainPrice }}</del></h4>
                        @endif
                    @elseif(($mainPrice ?? 0) > 0)
                        <h3 class="font-weight-semi-bold product_main_price">৳{{ $mainPrice }}</h3>
                    @endif
                </div>

                <p class="mb-4">{{ $product?->details?->shortDescription }}</p>

                @if ($sizes && $sizes->count() > 0)
                    <div class="d-flex mb-3">
                        <p class="text-dark font-weight-medium mb-0 mr-3">Sizes:</p>
                        <form id="main-size-form">
                            @foreach ($sizes->sortByDesc('name') ?? [] as $size)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input variable-size-input"
                                        id="size-{{ $size?->id }}" name="size" value="{{ $size?->id }}">
                                    <label class="custom-control-label"
                                        for="size-{{ $size?->id }}">{{ $size?->name }}</label>
                                </div>
                            @endforeach
                        </form>
                    </div>
                @endif

                @if ($colors && $colors->count() > 0)
                    <div class="d-flex mb-4">
                        <p class="text-dark font-weight-medium mb-0 mr-3">Colors:</p>
                        <form id="main-color-form" class="d-flex flex-wrap">
                            <span id="color-selection-message" class="text-muted small">Please select a size to see
                                available colors</span>
                            <div id="dynamic-colors-container" class="d-flex"></div>
                        </form>
                    </div>
                @endif

                <div class="d-flex pt-2 mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Quantity:</p>
                    <span id="variant-stock-display">
                        @if ($product?->stock > 0)
                            <span class="text-success fw-bold product_stock">{{ $product?->stock }} In Stock (Total)</span>
                        @else
                            <span class="text-danger fw-bold">Out of Stock</span>
                        @endif
                    </span>
                </div>

                <div class="d-flex align-items-center mb-4 pt-2">
                    <div class="input-group quantity mr-3" style="width: 130px;">
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
                        <span class="ml-3">Add To Cart</span>
                    </button>
                </div>

                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Category:</p>
                    <span>{{ $product?->details?->category?->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Sub Category:</p>
                    <span>{{ $product?->details?->subCategory?->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Brand:</p>
                    <span>{{ $product?->details?->brand?->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">SKU:</p>
                    <span>{{ $product?->sku ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Tags:</p>
                    <div class="d-inline-flex">
                        {{ $tags?->pluck('name')->implode(', ') ?? 'N/A' }}
                    </div>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
                    <div class="d-inline-flex">
                        <a class="text-dark px-2" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="text-dark px-2" href=""><i class="fab fa-twitter"></i></a>
                        <a class="text-dark px-2" href=""><i class="fab fa-linkedin-in"></i></a>
                        <a class="text-dark px-2" href=""><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-2">Information</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-3">Reviews (0)</a>
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
                    <div class="tab-pane fade" id="tab-pane-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-4">1 review for "Colorful Stylish Shirt"</h4>
                                <div class="media mb-4">
                                    <img src="{{ asset('web/img/user.jpg') }}" alt="Image"
                                        class="img-fluid mr-3 mt-1" style="width: 45px;">
                                    <div class="media-body">
                                        <h6>John Doe<small> - <i>01 Jan 2045</i></small></h6>
                                        <div class="text-primary mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <p>Diam amet duo labore stet elitr ea clita ipsum, tempor labore accusam ipsum et no
                                            at. Kasd diam tempor rebum magna dolores sed sed eirmod ipsum.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-4">Leave a review</h4>
                                <small>Your email address will not be published. Required fields are marked *</small>
                                <div class="d-flex my-3">
                                    <p class="mb-0 mr-2">Your Rating * :</p>
                                    <div class="text-primary">
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                </div>
                                <form>
                                    <div class="form-group">
                                        <label for="message">Your Review *</label>
                                        <textarea id="message" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Your Name *</label>
                                        <input type="text" class="form-control" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Your Email *</label>
                                        <input type="email" class="form-control" id="email">
                                    </div>
                                    <div class="form-group mb-0">
                                        <input type="submit" value="Leave Your Review" class="btn btn-primary px-3">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel">
                    @foreach ($relatedProducts ?? [] as $item)
                        <div class="card product-item border-0 product-card" data-product-id="{{ $item?->id }}">
                            <div
                                class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <div class="position-absolute" style="top: 8px; left: 8px; z-index: 99;">
                                    <button class="btn btn-sm bg-white rounded-circle shadow-sm wishlist-btn p-1"
                                        data-product-id="{{ $item?->id }}"
                                        style="width: 32px; height: 32px; line-height: 1;">
                                        <i class="fas fa-heart"
                                            style="font-size: 13px; color: {{ in_array($item?->id, $wishlistIds ?? []) ? '#e74c3c' : '#ccc' }};"></i>
                                    </button>
                                </div>
                                <div class="save-amount-box text-center position-absolute p-0"
                                    style="top: 0; right: 0; z-index: 99;">
                                    @if ($item?->discount > 0 && $item?->discount < $item?->price)
                                        <p class="save-amount text-dark p-2 bg-primary" style="font-size: 13px;">
                                            Save ৳{{ $item?->price - $item?->discount }}
                                        </p>
                                    @else
                                        <p class="save-amount d-none p-2 bg-primary" style="font-size: 13px;"></p>
                                    @endif
                                </div>
                                <img class="img-fluid w-100" src="{{ $item?->thumbnail }}"
                                    style="aspect-ratio: 1/1; object-fit: contain;" alt="{{ $item?->name }}">
                                @if ($item?->inventories->count() > 0)
                                    <div class="varient-product position-absolute d-flex justify-content-between bg-white"
                                        style="bottom: 0; left: 0; width: 100%;">
                                        {{-- Size dropdown --}}
                                        <select class="form-control form-control-md shop-size-selector"
                                            style="width: 100px">
                                            <option value="" disabled>Size</option>
                                            @foreach ($item?->inventories->unique('size_id')->sortBy('size.name') as $index => $inv)
                                                <option value="{{ $inv?->size_id }}" {{ $index == 0 ? 'selected' : '' }}>
                                                    {{ $inv?->size?->name ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- Color dropdown --}}
                                        <select class="form-control form-control-md shop-color-selector"
                                            style="width: 100px">
                                            <option value="">Color</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3" title="{{ $item?->name }}">
                                    {{ Str::limit($item?->name, 30, '...') }}</h6>
                                <div class="d-flex justify-content-center">
                                    @if ($item?->discount > 0)
                                        <h6>৳{{ $item?->discount }}</h6>
                                        <h6 class="text-muted ml-2"><del>৳{{ $item?->price }}</del></h6>
                                    @else
                                        <h6>৳{{ $item?->price }}</h6>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="{{ route('productDetails', $item?->slug) }}" class="btn btn-sm text-dark p-0"><i
                                        class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0 shop-add-to-cart"
                                    data-product-id="{{ $item?->id }}"><i
                                        class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const sizeInputs = $('#main-size-form .variable-size-input');
            const hasVariants = sizeInputs.length > 0;
            const productId = "{{ $product->id }}";
            let currentStock = parseInt("{{ $product->stock }}") || 0;
            let selectInventoryId = null;
            const OrginalThumbnail = "{{ $product->thumbnail }}";

            function toggleCartControls(enable) {
                $('#add-to-cart-btn, #product-quantity, .btn-plus, .btn-minus').prop('disabled', !enable);
            }

            if (!hasVariants && currentStock >= 0) {
                toggleCartControls(true);
            } else {
                toggleCartControls(false);
            }

            // Size Change
            sizeInputs.on('change', function() {
                let sizeId = $(this).val();
                let stockDisplay = $('#variant-stock-display');
                let colorContainer = $('#dynamic-colors-container');
                let colorMessage = $('#color-selection-message');

                colorContainer.empty();
                colorMessage.show().text('Loading available colors...');
                toggleCartControls(false);
                stockDisplay.html('<span class="text-muted fw-bold">Please select color</span>');

                $.ajax({
                    url: "{{ route('getAvailableColors') }}",
                    method: 'GET',
                    data: {
                        product_id: productId,
                        size_id: sizeId
                    },
                    success: function(response) {
                        colorMessage.hide();
                        if (response.availableColors.length > 0) {
                            let colorHtml = '';
                            response.availableColors.forEach(function(color) {
                                colorHtml += `
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input variable-color-input"
                                            id="color-${color.id}" name="color" value="${color.id}">
                                        <label class="custom-control-label" for="color-${color.id}">${color.name}</label>
                                    </div>`;
                            });
                            colorContainer.html(colorHtml);
                        } else {
                            colorMessage.show().text('No colors available').addClass(
                                'text-danger');
                        }
                    }
                });
            });

            // Color Change
            $(document).on('change', '.variable-color-input', function() {
                let sizeId = $('.variable-size-input:checked').val();
                let colorId = $(this).val();
                let priceContainer = $('#price-container');
                let stockDisplay = $('#variant-stock-display');
                let qtyInput = $('#product-quantity');

                if (sizeId && colorId) {
                    stockDisplay.html('<span class="text-muted fw-bold">Checking stock...</span>');
                    $.ajax({
                        url: "{{ route('getSignleProductVariantBySizeId') }}",
                        method: 'GET',
                        data: {
                            product_id: productId,
                            size_id: sizeId,
                            color_id: colorId
                        },
                        success: function(response) {
                            currentStock = parseInt(response.stock) || 0;
                            qtyInput.val(1);
                            selectInventoryId = response.inventory_id;

                            if (response.image) {
                                $('#main-image-preview').attr('src', response.image);
                                $('#product-carousel').carousel(0);
                            } else {
                                $('#main-image-preview').attr('src', OrginalThumbnail);
                                $('#product-carousel').carousel(0);
                            }

                            if (currentStock > 0) {
                                toggleCartControls(true);
                                qtyInput.attr('max', currentStock);
                                stockDisplay.html(
                                    `<span class="text-success fw-bold">${currentStock} In Stock</span>`
                                );
                            } else {
                                toggleCartControls(false);
                                stockDisplay.html(
                                    '<span class="text-danger fw-bold">Out of Stock</span>');
                            }

                            // Price update...
                            let finalPrice = (response.use_main_price == 1 || !response.price) ?
                                response.product_price : response.price;
                            let finalDiscount = (response.use_main_discount == 1 || !response
                                .discount) ? response.product_discount : response.discount;
                            let priceHtml = finalDiscount > 0 ?
                                `<h3 class="font-weight-semi-bold mb-0 product_main_price">৳${finalDiscount}</h3><h4 class="font-weight-semi-bold text-muted mb-0 ml-2"><del>৳${finalPrice}</del></h4>` :
                                `<h3 class="font-weight-semi-bold product_main_price">৳${finalPrice}</h3>`;
                            priceContainer.html(priceHtml);
                        }
                    });
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
                let sizeId = hasSize ? $('.variable-size-input:checked').val() : null;
                let colorId = hasColor ? $('.variable-color-input:checked').val() : null;
                let productMainPriceText = $('.product_main_price').first().text();
                let productMainPrice = parseFloat(productMainPriceText.replace('৳', ''));

                if ((hasSize && !sizeId) || (hasColor && !colorId)) {
                    showToast('error', 'Please select a size and color');
                    return;
                }

                let cartBtn = $(this);
                cartBtn.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm"></i> Adding...');

                $.ajax({
                    url: "{{ route('addToCart') }}",
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

            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 4
                    }
                }
            });
        });
    </script>
@endpush

@extends('web.layouts.app')
@section('content')
    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Shop Detail</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shop Detail</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border" id="carousel-images-container">
                        <div class="carousel-item active">
                            <img id="main-image-preview" class="w-100 h-100" src="{{ $product->thumbnail }}"
                                style="aspect-ratio: 1/1; object-fit: contain;" alt="Image">
                        </div>
                        @foreach ($product->galleries as $gallery)
                            <div class="carousel-item">
                                <img class="w-100 h-100" src="{{ Storage::url($gallery->src) }}"
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
                <h3 class="font-weight-semi-bold">{{ $product->name }}</h3>
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
                    @if ($product->discount > 0)
                        <h3 class="font-weight-semi-bold mb-0">৳{{ $product->discount }}</h3>
                        <h4 class="font-weight-semi-bold text-muted mb-0 ml-2"><del>৳{{ $product->price }}</del></h4>
                    @else
                        <h3 class="font-weight-semi-bold">৳{{ $product->price }}</h3>
                    @endif
                </div>
                <p class="mb-4">{{ $product->details->shortDescription }}</p>
                @if ($sizes && $sizes->count() > 0)
                    <div class="d-flex mb-3">
                        <p class="text-dark font-weight-medium mb-0 mr-3">Sizes:</p>
                        <form id="main-size-form">
                            @foreach ($sizes->sortByDesc('name') ?? [] as $size)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input variable-size-input"
                                        id="size-{{ $size->id }}" name="size" value="{{ $size->id }}">
                                    <label class="custom-control-label"
                                        for="size-{{ $size->id }}">{{ $size->name }}</label>
                                </div>
                            @endforeach
                        </form>
                    </div>
                @endif
                @if ($colors && $colors->count() > 0)
                    <div class="d-flex mb-4">
                        <p class="text-dark font-weight-medium mb-0 mr-3">Colors:</p>
                        <form id="main-color-form">
                            @foreach ($colors ?? [] as $color)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input variable-color-input"
                                        id="color-{{ $color->id }}" name="color" value="{{ $color->id }}"
                                        disabled>
                                    <label class="custom-control-label"
                                        for="color-{{ $color->id }}">{{ $color->name }}</label>
                                </div>
                            @endforeach
                        </form>
                    </div>
                @endif
                <div class="d-flex pt-2 mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Quantity:</p>
                    <span id="variant-stock-display">
                        @if ($product->stock > 0)
                            <span class="text-success fw-bold">{{ $product->stock }} In Stock (Total)</span>
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
                    <span>{{ $product->details->category->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Sub Category:</p>
                    <span>{{ $product->details->subCategory->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Brand:</p>
                    <span>{{ $product->details->brand->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">SKU:</p>
                    <span>{{ $product->sku ?? 'N/A' }}</span>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Tags:</p>
                    <div class="d-inline-flex">
                        {{ $tags->pluck('name')->implode(', ') ?? 'N/A' }}
                    </div>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
                    <div class="d-inline-flex">
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-pinterest"></i>
                        </a>
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
                        {!! $product->details->description ?? 'No description available.' !!}
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <h4 class="mb-3">Additional Information</h4>
                        {!! $product->details->information ?? 'No specifications provided.' !!}
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
    <!-- Shop Detail End -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                    @foreach ($relatedProducts ?? [] as $item)
                        <div class="card product-item border-0">
                            <div
                                class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="{{ $item->thumbnail }}"
                                    style="aspect-ratio: 1/1; object-fit: contain;" alt="{{ $item->name }}">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3" title="{{ $item->name }}">{{ Str::limit($item->name, 30, '...') }}</h6>
                                <div class="d-flex justify-content-center">
                                    @if ($item->discount > 0)
                                        <h6>৳{{ $item->discount }}</h6>
                                        <h6 class="text-muted ml-2"><del>৳{{ $item->price }}</del></h6>
                                    @else
                                        <h6>৳{{ $item->price }}</h6>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i
                                        class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i
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
            const colorInputs = $('#main-color-form .variable-color-input');
            let currentStock = parseInt("{{ $product->stock }}") || 0;
            const productId = "{{ $product->id }}";

            // Check if the product has variants
            const hasVariants = $('.variable-size-input').length > 0 && $('.variable-color-input').length > 0;

            const orginalGalley = $('#carousel-images-container').html();
            const orginalControls = $('.carouse-control-prev, .carouse-control-next');

            if (!hasVariants) {
                if (currentStock > 0) {
                    $('#add-to-cart-btn').prop('disabled', false);
                    $('#product-quantity').prop('disabled', false);
                    $('.btn-plus, .btn-minus').prop('disabled', false);
                } else {
                    $('#add-to-cart-btn').prop('disabled', true);
                    $('#product-quantity').prop('disabled', true);
                    $('.btn-plus, .btn-minus').prop('disabled', true);
                }
            }

            // when size is selected
            sizeInputs.on('change', function() {
                let sizeId = $(this).val();
                let colorInputs = $('.variable-color-input');
                let stockDisplay = $('#variant-stock-display');

                $('.btn-plus, .btn-minus').prop('disabled', true);
                $('#product-quantity').prop('disabled', true);

                stockDisplay.html('<span class="text-muted fw-bold">Please select color</span>');

                // Reset colors
                colorInputs.prop('checked', false).prop('disabled', true);
                colorInputs.closest('.custom-control').css('opacity', '0.4');
                $('#add-to-cart-btn').prop('disabled', true);

                $.ajax({
                    url: "{{ route('getAvailableColors') }}",
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        size_id: sizeId
                    },
                    success: function(response) {

                        if (response.availableColors.length > 0) {
                            colorInputs.each(function() {
                                // DB theke asa id ke Number e convert korlam
                                let currentInputColorId = Number($(this).val());

                                // Check if this color id exists in the response array
                                let isAvailable = response.availableColors.some(
                                    function(id) {
                                        return Number(id) === currentInputColorId;
                                    });

                                if (isAvailable) {
                                    $(this).prop('disabled', false);
                                    $(this).closest('.custom-control').css('opacity',
                                        '1');
                                }
                            });
                        }
                    },
                    error: function(err) {
                        console.log("Error logic:", err);
                    }
                });
            });

            colorInputs.on('change', function() {
                let sizeId = $('.variable-size-input:checked').val();
                let colorId = $(this).val();
                let priceContainer = $('#price-container');

                const originalPrice = "{{ $product->price }}";
                const originalDiscountedPrice = "{{ $product->discounted_price }}";


                let cartBtn = $('#add-to-cart-btn');
                let stockDisplay = $('#variant-stock-display');
                let qtyInput = $('#product-quantity');

                if (sizeId && colorId) {
                    stockDisplay.html('<span class="text-muted fw-bold">Checking stock...</span>');
                    $.ajax({
                        url: "{{ route('checkStock') }}",
                        method: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_id: productId,
                            size_id: sizeId,
                            color_id: colorId
                        },
                        success: function(response) {
                            currentStock = parseInt(response.stock);
                            qtyInput.val(1);

                            if (response.image && response.image !== '') {
                                $('#carousel-images-container').empty();

                                let imageHtml = '';
                                imageHtml += '<div class="carousel-item active">';
                                imageHtml += '<img class="w-100 h-100" src="' + response.image +
                                    '" style="aspect-ratio: 1/1; object-fit: contain;" alt="Image">';
                                imageHtml += '</div>';

                                $('.carousel-control-prev, .carousel-control-next').hide();

                                $('#carousel-images-container').append(imageHtml);
                            } else {
                                $('#carousel-images-container').html(orginalGalley);
                                $('.carouse-control-prev, .carouse-control-next').show();

                                $('#product-carousel').carousel(0);
                            }

                            if (currentStock > 0) {
                                $('.btn-plus, .btn-minus').prop('disabled', false);
                                $('#product-quantity').prop('disabled', false);
                            } else {
                                $('.btn-plus, .btn-minus').prop('disabled', true);
                                $('#product-quantity').prop('disabled', true);
                            }

                            if (response.price && response.price > 0) {
                                let newPriceHtml = '<h3 class="font-weight-semi-bold mb-0">৳' +
                                    response.price + '</h3>';
                                if (originalPrice > response.price) {
                                    newPriceHtml +=
                                        '<h4 class="font-weight-semi-bold text-muted mb-0 ml-2"><del>৳' +
                                        originalPrice + '</del></h4>';
                                }

                                priceContainer.html(newPriceHtml);
                            } else {
                                let defaultPriceHtml = '';
                                if (originalDiscountedPrice > 0) {
                                    defaultPriceHtml =
                                        '<h3 class="font-weight-semi-bold mb-0">৳' +
                                        originalDiscountedPrice + '</h3>' +
                                        '<h4 class="font-weight-semi-bold text-muted mb-0 ml-2"><del>৳' +
                                        originalPrice + '</del></h4>';
                                } else {
                                    defaultPriceHtml =
                                        '<h3 class="font-weight-semi-bold mb-0">৳' +
                                        originalPrice + '</h3>';
                                }

                                priceContainer.html(defaultPriceHtml);
                            }

                            if (currentStock > 0) {
                                stockDisplay.html('<span class="text-success fw-bold"> ' +
                                    currentStock + ' In Stock</span>');
                                cartBtn.prop('disabled', false);
                                qtyInput.attr('max', currentStock);
                            } else {
                                stockDisplay.html(
                                    '<span class="text-danger fw-bold">Out of Stock</span>'
                                );
                                cartBtn.prop('disabled', true);
                                qtyInput.attr('max', 0);
                            }
                        }
                    });
                }
            });

            $('.btn-plus').off('click').on('click', function() {
                let qtyInput = $('#product-quantity');
                let currentVal = parseInt(qtyInput.val());

                if (currentVal < currentStock) {
                    qtyInput.val(currentVal + 1);
                } else {
                    showToast('error', 'Sorry, only ' + currentStock + ' available in stock');
                    qtyInput.val(currentStock);
                }
            });

            $('.btn-minus').off('click').on('click', function() {
                let qtyInput = $('#product-quantity');
                let currentVal = parseInt(qtyInput.val());

                if (currentVal > 1) {
                    qtyInput.val(currentVal - 1);
                }
            });

            $('#product-quantity').on('keyup change', function() {
                let val = parseInt($(this).val());
                if (val > currentStock) {
                    showToast('error', 'Sorry, only ' + currentStock + ' available in stock');
                    $(this).val(currentStock);
                }

                if (val < 1 || isNaN(val)) {
                    $(this).val(1);
                }
            });

            $('#add-to-cart-btn').on('click', function(e) {
                e.preventDefault();
                let sizeId = $('.variable-size-input:checked').val() || null;
                let colorId = $('.variable-color-input:checked').val() || null;
                let quantity = $('#product-quantity').val();
                let productId = "{{ $product->id }}";

                if (!hasVariants) {
                    if ($('.variable-size-input').length > 0 && !sizeId) {
                        showToast('error', 'Please select a size');
                        return;
                    }

                    if ($('.variable-color-input').length > 0 && !colorId) {
                        showToast('error', 'Please select a color');
                        return;
                    }
                }

                // Console log the cart item data
                console.log("--- Cart Item Data ---");
                console.log("Product ID:", productId);
                console.log("Size ID:", sizeId);
                console.log("Color ID:", colorId);
                console.log("Quantity:", quantity);
                let cartBtn = $(this);
                cartBtn.prop('disabled', true);
                cartBtn.html(
                    '<i class="spinner-border spinner-border-sm me-3" role="status" aria-hidden="true"></i> <span class="ml-3">Add To Cart</span>'
                );

                $.ajax({
                    url: "{{ route('addToCart') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        size_id: sizeId,
                        color_id: colorId,
                        quantity: quantity
                    },
                    success: function(response) {
                        console.log("Server Response:", response);
                        cartBtn.prop('disabled', false).html(
                            '<i class="fa fa-shopping-cart me-3" aria-hidden="true"></i> <span class="ml-3">Go To Cart</span>'
                        );
                        if (response.status == 'success') {
                            showToast('success', response.message);

                            if ($('#cart-count').length) {
                                $('#cart-count').text(response.cartCount);
                            } else {
                                showToast('error', response.message || 'Something went wrong!');
                            }
                        }
                    },
                    error: function(xhr) {
                        cartBtn.prop('disabled', false).html(
                            '<i class="fa fa-shopping-cart me-3" aria-hidden="true"></i> <span class="ml-3">Go To Cart</span>'
                        );

                        if (xhr.status === 401) {
                            showToast('error', 'Please login first to add products to cart.');
                            setTimeout(function() {
                                window.location.href = "{{ route('login') }}";
                            }, 1500);
                        } else {
                            showToast('error', xhr.responseJSON.message ||
                                'Something went wrong!');
                        }
                    }
                });

            });
        });
    </script>
@endpush

@extends('web.layouts.app')

@section('content')
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 150px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Our Shop</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shop</p>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-2">
        <div class="row px-xl-5">
            <div class="col-lg-3 col-md-12">
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Filter by price</h5>
                    <div class="px-2">
                        <div id="price-range-slider" class="mb-3"></div>
                        <div class="d-flex align-items-center justify-content-between">
                            <input type="text" id="min-price" class="form-control form-control-sm text-center mr-2"
                                readonly>
                            <span class="text-muted">-</span>
                            <input type="text" id="max-price" class="form-control form-control-sm text-center ml-2"
                                readonly>
                        </div>
                    </div>
                </div>
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Filter by color</h5>
                    <form id="color-filter-form">
                        @foreach ($colorQuery ?? [] as $color)
                            <div
                                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                <input type="checkbox" class="custom-control-input color-checkbox" name="colors[]"
                                    id="color-{{ $color->id }}" value="{{ $color->id }}">
                                <label class="custom-control-label"
                                    for="color-{{ $color->id }}">{{ $color->name }}</label>
                                <span class="badge border font-weight-normal">{{ $color->colors_count }}</span>
                            </div>
                        @endforeach
                    </form>
                </div>
                <div class="mb-5">
                    <h5 class="font-weight-semi-bold mb-4">Filter by size</h5>
                    <form id="size-filter-form">
                        @foreach ($sizeQuery ?? [] as $size)
                            <div
                                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                <input type="checkbox" class="custom-control-input size-checkbox" name="sizes[]"
                                    id="size-{{ $size->id }}" value="{{ $size->id }}">
                                <label class="custom-control-label"
                                    for="size-{{ $size->id }}">{{ $size->name }}</label>
                                <span class="badge border font-weight-normal">{{ $size->sizes_count }}</span>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <form action="">
                                <input type="text" id="search-product" class="form-control border-5" placeholder="Search by Product Name">
                            </form>
                            <div class="dropdown ml-4">
                                <button class="btn border dropdown-toggle" type="button" id="triggerId"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Sort by
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="triggerId">
                                    <a class="dropdown-item sort-item" href="#" data-sort="latest">Latest</a>
                                    <a class="dropdown-item sort-item" href="#" data-sort="price_low">Price: Low to
                                        High</a>
                                    <a class="dropdown-item sort-item" href="#" data-sort="price_high">Price: High to
                                        Low</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="product-data-container">
                    @include('web.layouts.partial.product_list')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Global variables for filter state
        let currentSort = 'latest';
        let maxPriceLimit = {{ \App\Models\Product::max('price') ?? 1000 }};
        let minVal = 0;
        let maxVal = maxPriceLimit;

        $(document).ready(function() {

            // 1. Sort Item Click
            $(document).on('click', '.sort-item', function(e) {
                e.preventDefault();
                currentSort = $(this).data('sort');
                $('#triggerId').text($(this).text());
                filterProducts();
            });

            // 2. Pagination Click (AJAX Pagination)
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let page = url.split('page=')[1];
                filterProducts(page);
            });

            // 3. Price Range Slider Initialization
            $("#price-range-slider").slider({
                range: true,
                min: 0,
                max: maxPriceLimit,
                values: [0, maxPriceLimit],
                slide: function(event, ui) {
                    $("#min-price").val("৳" + ui.values[0]);
                    $("#max-price").val("৳" + ui.values[1]);
                    minVal = ui.values[0];
                    maxVal = ui.values[1];
                },
                stop: function(event, ui) {
                    filterProducts();
                }
            });

            // Set initial price display
            $("#min-price").val("৳" + $("#price-range-slider").slider("values", 0));
            $("#max-price").val("৳" + $("#price-range-slider").slider("values", 1));

            // 4. Main Filter Function
            function filterProducts(page = 1) {
                let colors = [];
                $('.color-checkbox:checked').each(function() {
                    colors.push($(this).val());
                });

                let sizes = [];
                $('.size-checkbox:checked').each(function() {
                    sizes.push($(this).val());
                });

                let search = $('#search-product').val();

                $.ajax({
                    url: "{{ route('shop') }}?page=" + page,
                    method: "GET",
                    data: {
                        min_price: minVal,
                        max_price: maxVal,
                        colors: colors,
                        sizes: sizes,
                        search: search,
                        sort: currentSort
                    },
                    beforeSend: function() {
                        $('#product-data-container').css('opacity', '0.5');
                    },
                    success: function(response) {
                        $('#product-data-container').html(response);
                        $('#product-data-container').css('opacity', '1');

                        // Scroll to top of products on change
                        $('html, body').animate({
                            scrollTop: $(".col-lg-9").offset().top - 100
                        }, 500);
                    }
                });
            }

            // 5. Change events for Checkboxes
            $('.color-checkbox, .size-checkbox').on('change', function() {
                if ($(this).is(':checked')) {
                    if ($(this).hasClass('color-checkbox')) $('#color-all').prop('checked', false);
                    if ($(this).hasClass('size-checkbox')) $('#size-all').prop('checked', false);
                }
                filterProducts();
            });

            // 6. Search input events
            $('#search-product').on('keyup', function() {
                filterProducts();
            });

            // 7. Reset "All" logic
            $('#color-all').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.color-checkbox').prop('checked', false);
                    filterProducts();
                }
            });

            $('#size-all').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.size-checkbox').prop('checked', false);
                    filterProducts();
                }
            });
        });
    </script>
@endpush

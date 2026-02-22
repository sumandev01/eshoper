@extends('web.layouts.app')
@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a class="nav-link p-0" href="{{ route('root') }}">Home</a></li>
                        <li>Category</li>
                        <li>{{ $category->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-2">
        <div class="row px-xl-5">
            <div class="col-lg-3 col-md-12">
                <div class="border-bottom mb-3 pb-2">
                    <h5 class="font-weight-semi-bold mb-4">Select by price range</h5>
                    <div class="px-2">
                        <div id="price-range-slider" class="mb-3"></div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <input type="text" id="min-price" class="form-control form-control-sm text-center mr-2"
                                readonly>
                            <span class="text-muted">-</span>
                            <input type="text" id="max-price" class="form-control form-control-sm text-center ml-2"
                                readonly>
                        </div>
                    </div>
                </div>

                {{-- Color Filter --}}
                <div class="border-bottom mb-3 pb-2">
                    <h5 class="font-weight-semi-bold mb-4">Select by color</h5>
                    <form id="color-filter-form">
                        @if (count($colorQuery) > 0)
                            @foreach ($colorQuery ?? [] as $color)
                                <div
                                    class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                                    <input type="checkbox" class="custom-control-input color-checkbox" name="colors[]"
                                        id="color-{{ $color->id }}" value="{{ $color->id }}"
                                        {{ in_array($color->id, (array) request('colors')) ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="color-{{ $color->id }}">{{ $color->name }}</label>
                                    <span class="badge border font-weight-normal">{{ $color->colors_count }}</span>
                                </div>
                            @endforeach
                        @else
                            <p>No colors variants available.</p>
                        @endif
                    </form>
                </div>

                {{-- Size Filter --}}
                <div class="border-bottom mb-3 pb-2">
                    <h5 class="font-weight-semi-bold mb-4">Select by size</h5>
                    <form id="size-filter-form">
                        @if (count($sizeQuery) > 0)
                            @foreach ($sizeQuery ?? [] as $size)
                                <div
                                    class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                                    <input type="checkbox" class="custom-control-input size-checkbox" name="sizes[]"
                                        id="size-{{ $size->id }}" value="{{ $size->id }}"
                                        {{ in_array($size->id, (array) request('sizes')) ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="size-{{ $size->id }}">{{ $size->name }}</label>
                                    <span class="badge border font-weight-normal">{{ $size->sizes_count }}</span>
                                </div>
                            @endforeach
                        @else
                            <p>No sizes variants available.</p>
                        @endif
                    </form>
                </div>

                <div class="pt-3">
                    <button type="button" id="clear-filters" class="btn btn-primary btn-block font-weight-bold">
                        Clear All Filters
                    </button>
                </div>
            </div>

            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <form onsubmit="return false;">
                                <input type="text" id="search-product" class="form-control border-5"
                                    placeholder="Search by Product Name" value="{{ request('search') }}">
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
                    @include('web.layouts.partial.category_product_list')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        let currentSort = '{{ request('sort', 'latest') }}';
        let maxPriceLimit = {{ $maxPrice }};
        let minVal = {{ request('min_price', $minPrice) }};
        let maxVal = {{ request('max_price', $maxPrice) }};

        $(document).ready(function() {
            // Price Range Slider initialization
            $("#price-range-slider").slider({
                range: true,
                min: 0,
                max: maxPriceLimit,
                values: [minVal, maxVal],
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

            $("#min-price").val("৳" + minVal);
            $("#max-price").val("৳" + maxVal);

            // Filter function
            function filterProducts(page = 1) {
                let categories = [];
                $('.category-checkbox:checked').each(function() {
                    categories.push($(this).val());
                });

                let colors = [];
                $('.color-checkbox:checked').each(function() {
                    colors.push($(this).val());
                });

                let sizes = [];
                $('.size-checkbox:checked').each(function() {
                    sizes.push($(this).val());
                });

                let search = $('#search-product').val();

                // queryData object
                let queryData = {
                    page: page,
                    min_price: minVal,
                    max_price: maxVal,
                    search: search,
                    sort: currentSort
                };

                if (categories.length > 0) queryData.categories = categories;
                if (colors.length > 0) queryData.colors = colors;
                if (sizes.length > 0) queryData.sizes = sizes;

                $.ajax({
                    url: "{{ route('categoryProducts', $category->slug) }}",
                    method: "GET",
                    data: queryData,
                    beforeSend: function() {
                        $('#product-data-container').css('opacity', '0.5');
                    },
                    success: function(response) {
                        // Show the new products and make them fully visible
                        $('#product-data-container').html(response);
                        $('#product-data-container').css('opacity', '1');

                        initProductVariants('#product-data-container');

                        // 1. Create an empty object to store active filters
                        let activeParams = {};

                        // 2. Add page number only if it is more than 1
                        if (page > 1) activeParams.page = page;

                        // 3. Add price range if it is different from the default values
                        if (minVal > 0) activeParams.min_price = minVal;
                        if (maxVal < maxPriceLimit) activeParams.max_price = maxVal;

                        // 4. Get and add selected Categories, Colors, and Sizes
                        let categories = [];
                        $('.category-checkbox:checked').each(function() {
                            categories.push($(this).val());
                        });
                        if (categories.length > 0) activeParams.categories = categories;

                        let colors = [];
                        $('.color-checkbox:checked').each(function() {
                            colors.push($(this).val());
                        });
                        if (colors.length > 0) activeParams.colors = colors;

                        let sizes = [];
                        $('.size-checkbox:checked').each(function() {
                            sizes.push($(this).val());
                        });
                        if (sizes.length > 0) activeParams.sizes = sizes;

                        // 5. Add search and sorting if they are not default
                        let search = $('#search-product').val();
                        if (search !== '') activeParams.search = search;
                        if (currentSort !== 'latest') activeParams.sort = currentSort;

                        // 6. Build the base URL
                        let newUrl = window.location.protocol + "//" + window.location.host + window
                            .location.pathname;

                        // Add '?' and parameters only if at least one filter is active
                        if (Object.keys(activeParams).length > 0) {
                            newUrl += '?' + $.param(activeParams);
                        }

                        // 7. Update the browser address bar without reloading the page
                        window.history.pushState({
                            path: newUrl
                        }, '', newUrl);

                        // Smoothly scroll up to the product list
                        $('html, body').animate({
                            scrollTop: $(".col-lg-9").offset().top - 100
                        }, 500);
                    }
                });
            }

            // Events
            $(document).on('click', '.sort-item', function(e) {
                e.preventDefault();
                currentSort = $(this).data('sort');
                $('#triggerId').text($(this).text());
                filterProducts();
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let page = url.split('page=')[1];
                filterProducts(page);
            });

            $('.category-checkbox, .color-checkbox, .size-checkbox').on('change', function() {
                filterProducts();
            });

            $('#search-product').on('keyup', function() {
                filterProducts();
            });

            $('#clear-filters').on('click', function() {
                $('.category-checkbox, .color-checkbox, .size-checkbox').prop('checked', false);
                $('#search-product').val('');

                let maxLimit = {{ \App\Models\Product::max('price') ?? 1000 }};
                $("#price-range-slider").slider("option", "values", [0, maxLimit]);
                $("#min-price").val("৳" + 0);
                $("#max-price").val("৳" + maxLimit);

                minVal = 0;
                maxVal = maxLimit;
                currentSort = 'latest';
                $('#triggerId').text('Sort by');

                filterProducts(1);
                let cleanUrl = window.location.protocol + "//" + window.location.host + window.location
                    .pathname;
                window.history.pushState({
                    path: cleanUrl
                }, '', cleanUrl);
            });
        });
    </script>
@endpush

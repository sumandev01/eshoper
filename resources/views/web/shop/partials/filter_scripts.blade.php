@push('styles')
    <style>
        .ui-widget-header {
            background: var(--primary) !important;
        }

        .ui-state-active,
        .ui-widget-content .ui-state-active,
        .ui-widget-header .ui-state-active,
        a.ui-button:active,
        .ui-button:active,
        .ui-button.ui-state-active:hover {
            border: 1px solid var(--primary) !important;
            background: var(--primary) !important;
            font-weight: normal;
            color: #ffffff;
        }

        .color-checkbox:checked + .color-swatch-label {
            border-color: var(--primary) !important;
            transform: scale(1.2);
            box-shadow: 0 0 5px var(--primary);
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentSort = '{{ request('sort', 'latest') }}';
        let maxPriceLimit = {{ $maxPrice ?? 1000 }};
        let minVal = {{ request('min_price', 0) }};
        let maxVal = {{ request('max_price', $maxPrice ?? 1000) }};

        $(document).ready(function() {
            // Price Range Slider initialization
            $("#price-range-slider").slider({
                range: true,
                min: 0,
                max: maxPriceLimit,
                values: [minVal, maxVal],
                slide: function(event, ui) {
                    $("#min-price").val(siteCurrency + ui.values[0]);
                    $("#max-price").val(siteCurrency + ui.values[1]);
                    minVal = ui.values[0];
                    maxVal = ui.values[1];
                },
                stop: function(event, ui) {
                    filterProducts();
                }
            });

            $("#min-price").val(siteCurrency + minVal);
            $("#max-price").val(siteCurrency + maxVal);

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
                    url: "{!! $filterUrl !!}",
                    method: "GET",
                    data: queryData,
                    beforeSend: function() {
                        $('#product-data-container').css('opacity', '0.5');
                    },
                    success: function(response) {
                        $('#product-data-container').html(response);
                        $('#product-data-container').css('opacity', '1');

                        if (typeof initProductVariants === "function") {
                            initProductVariants('#product-data-container');
                        }

                        let activeParams = {};

                        if (page > 1) activeParams.page = page;
                        if (minVal > 0) activeParams.min_price = minVal;
                        if (maxVal < maxPriceLimit) activeParams.max_price = maxVal;

                        let categoriesList = [];
                        $('.category-checkbox:checked').each(function() {
                            categoriesList.push($(this).val());
                        });
                        if (categoriesList.length > 0) activeParams.categories = categoriesList;

                        let colorsList = [];
                        $('.color-checkbox:checked').each(function() {
                            colorsList.push($(this).val());
                        });
                        if (colorsList.length > 0) activeParams.colors = colorsList;

                        let sizesList = [];
                        $('.size-checkbox:checked').each(function() {
                            sizesList.push($(this).val());
                        });
                        if (sizesList.length > 0) activeParams.sizes = sizesList;

                        let searchInput = $('#search-product').val();
                        if (searchInput !== '') activeParams.search = searchInput;
                        if (currentSort !== 'latest') activeParams.sort = currentSort;

                        let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;

                        if (Object.keys(activeParams).length > 0) {
                            newUrl += '?' + $.param(activeParams);
                        }

                        window.history.pushState({
                            path: newUrl
                        }, '', newUrl);

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
                if ($(this).is(':checked')) {
                    if ($(this).hasClass('category-checkbox')) {
                        $('.category-checkbox').not(this).prop('checked', false);
                    } else if ($(this).hasClass('color-checkbox')) {
                        $('.color-checkbox').not(this).prop('checked', false);
                    } else if ($(this).hasClass('size-checkbox')) {
                        $('.size-checkbox').not(this).prop('checked', false);
                    }
                }
                filterProducts();
            });

            $('#search-product').on('keyup', function() {
                filterProducts();
            });

            $('#clear-filters').on('click', function() {
                $('.category-checkbox, .color-checkbox, .size-checkbox').prop('checked', false);
                $('#search-product').val('');

                let maxLimit = {{ $maxPrice ?? 1000 }};
                let minLimit = 0;

                $("#price-range-slider").slider("option", "values", [minLimit, maxLimit]);
                $("#min-price").val(siteCurrency + minLimit);
                $("#max-price").val(siteCurrency + maxLimit);

                minVal = minLimit;
                maxVal = maxLimit;
                currentSort = 'latest';
                $('#triggerId').text('Sort by');

                filterProducts(1);
                let cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.pushState({
                    path: cleanUrl
                }, '', cleanUrl);
            });
        });
    </script>
@endpush



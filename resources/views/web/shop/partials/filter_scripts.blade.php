@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
    <style>
        /* === Premium Price Range Slider === */
        #price-range-slider .noUi-target {
            background: #f0f0f0;
            border-radius: 10px;
            border: none;
            box-shadow: none;
            height: 5px;
        }

        #price-range-slider .noUi-connect {
            background: #b8727d;
            border-radius: 10px;
        }

        #price-range-slider .noUi-handle {
            width: 20px !important;
            height: 20px !important;
            border-radius: 50% !important;
            background: #fff !important;
            border: 2px solid #b8727d !important;
            box-shadow: 0 2px 8px rgba(184, 114, 125, 0.35) !important;
            top: -3px !important;
            cursor: pointer;
        }

        #price-range-slider .noUi-handle:before,
        #price-range-slider .noUi-handle:after {
            display: none !important;
        }

        #price-range-slider .noUi-tooltip {
            background: #b8727d;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 11px;
            padding: 2px 7px;
            bottom: 130%;
        }

        .price-badge {
            background: #b8727d;
            color: white;
            font-size: 13px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .price-range-dash {
            color: #999;
            font-weight: bold;
        }

        .noUi-horizontal .noUi-handle {
            right: -10px !important;
        }

        #price-range-slider .noUi-tooltip,
        #price-range-slider .noUi-connect {
            background: var(--btn-bg);
        }

        #price-range-slider .noUi-handle {
            border: 2px solid var(--btn-bg) !important;
        }

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

        .color-checkbox:checked+.color-swatch-label {
            border-color: var(--primary) !important;
            transform: scale(1.2);
            box-shadow: 0 0 5px var(--primary);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script>
        let currentSort = '{{ request('sort', 'latest') }}';
        let maxPriceLimit = {{ $maxPrice ?? 1000 }};
        let minVal = {{ request('min_price', 0) }};
        let maxVal = {{ request('max_price', $maxPrice ?? 1000) }};

        $(document).ready(function() {

            var sliderEl = document.getElementById('price-range-slider');
            if (sliderEl) {
                noUiSlider.create(sliderEl, {
                    start: [minVal, maxVal],
                    connect: true,
                    tooltips: [{
                            to: v => siteCurrency + Math.round(v)
                        },
                        {
                            to: v => siteCurrency + Math.round(v)
                        }
                    ],
                    range: {
                        'min': 0,
                        'max': maxPriceLimit > 0 ? maxPriceLimit : 1000
                    }
                });
                sliderEl.noUiSlider.on('update', function(values) {
                    minVal = Math.round(values[0]);
                    maxVal = Math.round(values[1]);
                    var minInput = document.getElementById('min-price');
                    var maxInput = document.getElementById('max-price');
                    var minDisplay = document.getElementById('min-price-display');
                    var maxDisplay = document.getElementById('max-price-display');
                    if (minInput) minInput.value = minVal;
                    if (maxInput) maxInput.value = maxVal;
                    if (minDisplay) minDisplay.innerText = siteCurrency + minVal;
                    if (maxDisplay) maxDisplay.innerText = siteCurrency + maxVal;
                });
                sliderEl.noUiSlider.on('change', function() {
                    filterProducts();
                });
            }



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

                let brands = [];
                $('.brand-checkbox:checked').each(function() {
                    brands.push($(this).val());
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
                if (brands.length > 0) queryData.brands = brands;

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

                        let brandsList = [];
                        $('.brand-checkbox:checked').each(function() {
                            brandsList.push($(this).val());
                        });
                        if (brandsList.length > 0) activeParams.brands = brandsList;

                        let searchInput = $('#search-product').val();
                        if (searchInput !== '') activeParams.search = searchInput;
                        if (currentSort !== 'latest') activeParams.sort = currentSort;

                        let newUrl = window.location.protocol + "//" + window.location.host + window
                            .location.pathname;

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

            $('.category-checkbox, .brand-checkbox, .color-checkbox, .size-checkbox').on('change', function() {
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

                document.getElementById('price-range-slider').noUiSlider.set([0, maxPriceLimit]);

                minVal = minLimit;
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

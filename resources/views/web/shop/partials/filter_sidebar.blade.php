<style>
    /* 2026 Filter Styles */
    .filter-bottom-sheet {
        transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    @media (max-width: 991.98px) {
        .filter-bottom-sheet {
            height: 85vh !important;
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.95);
            border-radius: 24px 24px 0 0;
        }
        .mobile-sticky-bottom {
            position: sticky;
            bottom: 0;
            background: #fff;
            z-index: 1020;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        .floating-filter-btn {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1040;
            padding: 12px 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(242,92,39,0.3);
            white-space: nowrap;
        }
    }
    .visual-chip-label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #475569;
        font-size: 13.5px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1.5px solid transparent;
    }
    .form-check-input:checked + .visual-chip-label {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        box-shadow: 0 4px 10px rgba(242,92,39,0.25);
    }
    /* Color Swatch Hover & Selected State */
    .color-swatch-label:hover {
        transform: scale(1.1);
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--primary);
    }
    .color-checkbox:checked + .color-swatch-label {
        border-color: var(--primary) !important;
        transform: scale(1.1);
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--primary) !important;
    }
    /* Accordion Tweaks */
    .filter-accordion .accordion-button {
        padding: 16px 0;
        font-weight: 700;
        color: var(--ink);
        background: transparent;
        box-shadow: none;
        font-size: 15px;
    }
    .filter-accordion .accordion-button:not(.collapsed) {
        color: #111827 !important;
        font-weight: 700;
    }
    .filter-accordion .accordion-body {
        padding: 4px 10px 24px 10px;
    }
    .filter-accordion .accordion-item {
        background: transparent;
        padding: 0 20px;
        border-radius: 0;
    }
</style>

<!-- Mobile Floating Button -->
<button class="btn btn-primary rounded-pill floating-filter-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#shopFilterOffcanvas">
    <i class="fas fa-sliders-h me-2"></i> Filters
</button>

<!-- Responsive Offcanvas Wrapper -->
<div class="offcanvas-lg offcanvas-bottom filter-bottom-sheet shadow-sm" tabindex="-1" id="shopFilterOffcanvas">
    <div class="offcanvas-header d-lg-none border-bottom pb-3 pt-4 px-4">
        <h5 class="offcanvas-title fw-bold" style="font-size: 18px;">Filter Products</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#shopFilterOffcanvas" aria-label="Close" onclick="if(window.bootstrap && bootstrap.Offcanvas) { const oc = bootstrap.Offcanvas.getInstance(document.getElementById('shopFilterOffcanvas')); if(oc) oc.hide(); }"></button>
    </div>
    
    <div class="offcanvas-body flex-column p-4 p-lg-0">
        
        <div class="accordion filter-accordion" id="filterAccordion">
            
            {{-- Price Range --}}
            <div class="accordion-item border-0 border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-expanded="true">
                        Price Range
                    </button>
                </h2>
                <div id="collapsePrice" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <div class="px-2 mt-4 mb-2">
                            <div id="price-range-slider"></div>
                            <input type="hidden" id="min-price">
                            <input type="hidden" id="max-price">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Category Filter --}}
            @if (isset($showCategory) && $showCategory)
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="true">
                            Categories
                        </button>
                    </h2>
                    <div id="collapseCategory" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <form id="category-filter-form" class="d-flex flex-wrap gap-2">
                                @foreach ($categoryQuery ?? [] as $category)
                                    <div>
                                        <input type="checkbox" class="form-check-input category-checkbox d-none" name="categories[]" id="category-{{ $category?->id }}" value="{{ $category?->id }}" {{ in_array($category?->id, (array) request('categories')) ? 'checked' : '' }}>
                                        <label class="visual-chip-label" for="category-{{ $category?->id }}">{{ $category?->name }}</label>
                                    </div>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Size Filter --}}
            <div class="accordion-item border-0 border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSize" aria-expanded="true">
                        Sizes
                    </button>
                </h2>
                <div id="collapseSize" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <form id="size-filter-form" class="d-flex flex-wrap gap-2">
                            @if (isset($sizeQuery) && count($sizeQuery) > 0)
                                @foreach ($sizeQuery as $size)
                                    <div>
                                        <input type="checkbox" class="form-check-input size-checkbox d-none" name="sizes[]" id="size-{{ $size?->id }}" value="{{ $size?->id }}" {{ in_array($size?->id, (array) request('sizes')) ? 'checked' : '' }}>
                                        <label class="visual-chip-label" for="size-{{ $size?->id }}">{{ $size?->name }}</label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small mb-0">No sizes available.</p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Color Filter --}}
            <div class="accordion-item border-0 border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseColor" aria-expanded="true">
                        Colors
                    </button>
                </h2>
                <div id="collapseColor" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <form id="color-filter-form">
                            @if (isset($colorQuery) && count($colorQuery) > 0)
                                <div class="d-flex flex-wrap gap-3 mt-1">
                                    @foreach ($colorQuery as $color)
                                        <div class="color-filter-item">
                                            <input type="checkbox" class="color-checkbox d-none" name="colors[]" id="color-{{ $color?->id }}" value="{{ $color?->id }}" {{ in_array($color?->id, (array) request('colors')) ? 'checked' : '' }}>
                                            <label class="color-swatch-label m-0" for="color-{{ $color?->id }}" style="width: 32px; height: 32px; border-radius: 50%; background-color: {{ $color?->color_code ?? '#000' }}; cursor: pointer; border: 2px solid transparent; box-shadow: 0 0 5px rgba(0,0,0,0.15); display: inline-block; transition: all 0.2s;" title="{{ $color?->name }}"></label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small mb-0">No colors available.</p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Brand Filter --}}
            <div class="accordion-item border-0 border-bottom">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBrand" aria-expanded="true">
                        Brands
                    </button>
                </h2>
                <div id="collapseBrand" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <form id="brand-filter-form" class="d-flex flex-wrap gap-2">
                            @if (isset($brandQuery) && count($brandQuery) > 0)
                                @foreach ($brandQuery as $brand)
                                    <div>
                                        <input type="checkbox" class="form-check-input brand-checkbox d-none" name="brands[]" id="brand-{{ $brand?->id }}" value="{{ $brand?->id }}" {{ in_array($brand?->id, (array) request('brands')) ? 'checked' : '' }}>
                                        <label class="visual-chip-label" for="brand-{{ $brand?->id }}">{{ $brand?->name }}</label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small mb-0">No brands available.</p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="p-4 mobile-sticky-bottom mt-auto">
            <button type="button" id="clear-filters" class="btn btn-outline-primary rounded-pill w-100" style="font-weight: 600; padding: 12px; border-width: 2px;">
                <i class="fas fa-redo-alt me-2"></i> Reset All Filters
            </button>
        </div>
        
    </div>
</div>

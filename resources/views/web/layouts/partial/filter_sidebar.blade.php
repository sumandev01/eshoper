<div class="border-bottom mb-3 pb-2">
    <h5 class="font-weight-semi-bold mb-4">Select by price range</h5>
    <div class="px-2">
        <div id="price-range-slider" class="mb-3"></div>
        <div class="d-flex align-items-center justify-content-between mb-2">
            <input type="text" id="min-price"
                class="form-control form-control-md bg-primary text-center mr-2 text-white" readonly>
            <span class="text-muted">-</span>
            <input type="text" id="max-price"
                class="form-control form-control-md bg-primary text-center ml-2 text-white" readonly>
        </div>
    </div>
</div>

@if(isset($showCategory) && $showCategory)
{{-- Category Filter --}}
<div class="border-bottom mb-3 pb-2">
    <h5 class="font-weight-semi-bold mb-4">Select by category</h5>
    <form id="category-filter-form">
        @foreach ($categoryQuery ?? [] as $category)
            <div
                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                <input type="checkbox" class="custom-control-input category-checkbox" name="categories[]"
                    id="category-{{ $category?->id }}" value="{{ $category?->id }}"
                    {{ in_array($category?->id, (array) request('categories')) ? 'checked' : '' }}>
                <label class="custom-control-label"
                    for="category-{{ $category?->id }}">{{ $category?->name }}</label>
            </div>
        @endforeach
    </form>
</div>
@endif

{{-- Color Filter --}}
<div class="border-bottom mb-3 pb-2">
    <h5 class="font-weight-semi-bold mb-4">Select by color</h5>
    <form id="color-filter-form">
        @if (isset($colorQuery) && count($colorQuery) > 0)
            <div class="d-flex flex-wrap">
            @foreach ($colorQuery as $color)
                <div class="color-filter-item mr-2 mb-2">
                    <input type="checkbox" class="color-checkbox d-none" name="colors[]"
                        id="color-{{ $color?->id }}" value="{{ $color?->id }}"
                        {{ in_array($color?->id, (array) request('colors')) ? 'checked' : '' }}>
                    <label class="color-swatch-label m-0"
                        for="color-{{ $color?->id }}" 
                        style="width: 25px; height: 25px; border-radius: 50%; background-color: {{ $color?->color_code ?? '#000' }}; cursor: pointer; border: 2px solid transparent; box-shadow: 0 0 3px rgba(0,0,0,0.3); display: inline-block; transition: all 0.3s;"
                        title="{{ $color?->name }}"></label>
                </div>
            @endforeach
            </div>
        @else
            <p>No colors variants available.</p>
        @endif
    </form>
</div>

{{-- Size Filter --}}
<div class="border-bottom mb-3 pb-2">
    <h5 class="font-weight-semi-bold mb-4">Select by size</h5>
    <form id="size-filter-form">
        @if (isset($sizeQuery) && count($sizeQuery) > 0)
            @foreach ($sizeQuery as $size)
                <div
                    class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-2">
                    <input type="checkbox" class="custom-control-input size-checkbox" name="sizes[]"
                        id="size-{{ $size?->id }}" value="{{ $size?->id }}"
                        {{ in_array($size?->id, (array) request('sizes')) ? 'checked' : '' }}>
                    <label class="custom-control-label"
                        for="size-{{ $size?->id }}">{{ $size?->name }}</label>
                </div>
            @endforeach
        @else
            <p>No sizes variants available.</p>
        @endif
    </form>
</div>

<div class="pt-3">
    <button type="button" id="clear-filters" class="btn btn-primary btn-block">
        <i class="fas fa-times-circle mr-2"></i>
        Clear Filters
    </button>
</div>

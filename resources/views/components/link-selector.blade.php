@props(['selectedType' => '', 'selectedRef' => '', 'prefix' => 'link', 'label' => null])

@php
    $categories = \App\Models\Category::select('id', 'name')->get();
    $products = \App\Models\Product::select('id', 'name')->get();
    $pages = \App\Models\Page::select('id', 'title')->get();
    $blogs = \App\Models\Blog::select('id', 'title')->get();

    $systemPages = [
        'home' => 'Home',
        'shop' => 'Shop',
        'cart' => 'Cart',
        'orderTracking' => 'Order Tracking',
        'contact' => 'Contact Us',
        'about' => 'About Us',
        'faq' => 'FAQ',
        'blogs' => 'Blogs',
    ];
@endphp

<div class="row gy-2 link-selector-wrapper mb-3">
    @if ($label)
        <div class="col-md-12">
            <label class="form-label">{{ $label }}</label>
        </div>
    @endif
    <div class="col-md-5">
        <select name="{{ $prefix }}_type" class="form-select link-type-select">
            <option value="">-- Select Type --</option>
            @foreach (\App\Enums\LinkTypeEnum::cases() as $case)
                <option value="{{ $case->value }}" {{ $selectedType == $case->value ? 'selected' : '' }}>
                    {{ $case->label() }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-7 link-ref-container">
        <!-- Hidden input to store the final reference ID -->
        <input type="hidden" name="{{ $prefix }}_ref_id" class="final-ref-input" value="{{ $selectedRef }}">

        <!-- System Pages -->
        <select class="form-select ref-select" data-type="system" style="display: none;">
            <option value="">-- Select Page --</option>
            @foreach ($systemPages as $key => $val)
                <option value="{{ $key }}"
                    {{ $selectedType == 'system' && $selectedRef == $key ? 'selected' : '' }}>{{ $val }}
                </option>
            @endforeach
        </select>

        <!-- Categories -->
        <select class="form-select ref-select" data-type="category" style="display: none;">
            <option value="">-- Select Category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ $selectedType == 'category' && $selectedRef == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
            @endforeach
        </select>

        <!-- Products -->
        <select class="form-select ref-select" data-type="product" style="display: none;">
            <option value="">-- Select Product --</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}"
                    {{ $selectedType == 'product' && $selectedRef == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}</option>
            @endforeach
        </select>

        <!-- Pages -->
        <select class="form-select ref-select" data-type="page" style="display: none;">
            <option value="">-- Select Dynamic Page --</option>
            @foreach ($pages as $page)
                <option value="{{ $page->id }}"
                    {{ $selectedType == 'page' && $selectedRef == $page->id ? 'selected' : '' }}>{{ $page->title }}
                </option>
            @endforeach
        </select>

        <!-- Blogs -->
        <select class="form-select ref-select" data-type="blog" style="display: none;">
            <option value="">-- Select Blog --</option>
            @foreach ($blogs as $blog)
                <option value="{{ $blog->id }}"
                    {{ $selectedType == 'blog' && $selectedRef == $blog->id ? 'selected' : '' }}>{{ $blog->title }}
                </option>
            @endforeach
        </select>

        <!-- Custom Link -->
        <input type="text" class="form-control ref-input" data-type="custom" style="display: none;"
            placeholder="https://..." value="{{ $selectedType == 'custom' ? $selectedRef : '' }}">
    </div>
</div>

@pushOnce('scripts')
    <script>
        $(document).ready(function() {
            function updateLinkSelector(wrapper) {
                let typeSelect = wrapper.find('.link-type-select');
                let selectedType = typeSelect.val();
                let refContainer = wrapper.find('.link-ref-container');
                let finalInput = refContainer.find('.final-ref-input');

                // Hide all ref inputs/selects first
                refContainer.find('.ref-select, .ref-input').hide().prop('disabled', true);

                if (selectedType) {
                    // Show the specific input/select for this type
                    let targetInput = refContainer.find(`[data-type="${selectedType}"]`);
                    targetInput.show().prop('disabled', false);

                    // Update hidden input when visible input changes
                    targetInput.off('change keyup').on('change keyup', function() {
                        finalInput.val($(this).val());
                    });

                    // Trigger once to set initial value
                    finalInput.val(targetInput.val());
                } else {
                    finalInput.val('');
                }
            }

            // Initialize on load for all components on page
            $('.link-selector-wrapper').each(function() {
                updateLinkSelector($(this));
            });

            // Handle type change
            $(document).on('change', '.link-type-select', function() {
                let wrapper = $(this).closest('.link-selector-wrapper');
                updateLinkSelector(wrapper);
            });
        });
    </script>
@endPushOnce

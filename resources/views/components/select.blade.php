<div class="form-group mb-3">
    @if ($label)
        <label for="{{ $id ?? $name }}" class="form-label font-weight-bold">
            {{ $label }} @if($required) <span class="text-danger">*</span> @endif
        </label>
    @endif

    <select 
        name="{{ $name }}" 
        id="{{ $id ?? $name }}" 
        class="form-select {{ $class ?? '' }} @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{-- Values ​​from the database are being stored here. --}}
        data-selected-value="{{ $value }}"
    >
        @if ($placeholder)
            <option value="" selected disabled>{{ $placeholder }}</option>
        @endif

        {{-- If the option is passed directly (e.g., $categories) --}}
        @if(!empty($options))
            @foreach($options as $key => $option)
                @php
                    $optValue = is_object($option) ? $option->id : (is_array($option) ? $option['id'] : $key);
                    $optLabel = is_object($option) ? $option->name : (is_array($option) ? $option['name'] : $option);
                    $isSelected = (string) $optValue === (string) $value;
                @endphp
                <option value="{{ $optValue }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach
        @endif
        {{ $slot }}
    </select>

    @error($name)
        <span class="text-danger mt-2 d-block">{{ $message }}</span>
    @enderror
</div>

@push('scripts')
<script>
    // Functions should be global and defined only once.
    if (typeof fetchDynamicSubCategories !== 'function') {
        function fetchDynamicSubCategories(parentId, targetSelectId, currentSelectedValue = null) {
            const targetSelect = document.getElementById(targetSelectId);
            if (!targetSelect || !parentId) return;

            targetSelect.innerHTML = '<option value="" selected disabled>Loading...</option>';

            // Route to fetch dynamic sub-categories
            let fetchUrl = "{{ route('sub-category.getSubCategories', ':id') }}".replace(':id', parentId);

            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) throw new Error('Data not found');
                    return response.json();
                })
                .then(data => {
                    targetSelect.innerHTML = '<option value="" selected disabled>Select Sub Category</option>';
                    
                    data.forEach(item => {
                        let option = document.createElement('option');
                        option.value = item.id;
                        option.text = item.name;
                        
                        // Matching selected value (String comparison safest)
                        if (currentSelectedValue && String(item.id) === String(currentSelectedValue)) {
                            option.selected = true;
                        }
                        targetSelect.add(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    targetSelect.innerHTML = '<option value="" selected disabled>Error Loading Data</option>';
                });
        }
    }

    // Handling dynamic sub-category loading
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            const categoryDropdown = document.getElementById('category_id');
            const subCategoryDropdown = document.getElementById('sub_category_id');

            if (categoryDropdown && subCategoryDropdown) {
                // 1. For edit mode: As soon as the page loads
                const savedParentId = categoryDropdown.value;
                const savedSubId = subCategoryDropdown.getAttribute('data-selected-value');

                if (savedParentId) {
                    fetchDynamicSubCategories(savedParentId, 'sub_category_id', savedSubId);
                }

                // 2. If the user changes the main category
                categoryDropdown.addEventListener('change', function() {
                    fetchDynamicSubCategories(this.value, 'sub_category_id');
                });
            }
        });
    })();
</script>
@endpush




{{-- <x-select 
    label="Category" 
    name="category_id" 
    id="category_id" 
    :options="$categories" 
    :value="$brand->category_id ?? ''"  // Data from the database or any other source, if available | No need for add pages call api
    placeholder="Select Category" 
/>

<x-select 
    label="Sub Category" 
    name="sub_category_id" 
    id="sub_category_id" 
    :options="$subCategories ?? []"   // Data from json if main category call to sub category using api
    :value="$brand->subCategory_id ?? ''" // Data from the database or any other source, if available | No need for add pages call api
    placeholder="Select Sub Category" 
/> --}}
@php
    $id = $id ?? $name;
    $currentValue = old($name, $value);
@endphp

<div class="form-group mb-3">
    @if ($label)
        <label for="{{ $id }}" class="form-label font-weight-bold">
            {{ $label }} @if($required) <span class="text-danger">*</span> @endif
        </label>
    @endif

    <select 
        name="{{ $name }}" 
        id="{{ $id }}" 
        class="form-select {{ $class ?? '' }} @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        data-selected-value="{{ $currentValue }}"
        style="padding: 0.64rem 1.375rem !important;"
    >
        @if ($placeholder)
            <option value="" {{ $required ? 'disabled' : '' }} {{ (string) $currentValue === '' || $currentValue === null ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif

        @if(!empty($options))
            @foreach($options as $key => $option)
                @php
                    if ($option instanceof \BackedEnum) {
                        $optValue = $option->value;
                        $optLabel = $option->value;
                    } elseif (is_object($option)) {
                        $optValue = $option->id ?? $key;
                        $optLabel = $option->name ?? (method_exists($option, '__toString') ? (string) $option : $key);
                    } elseif (is_array($option)) {
                        $optValue = $option['id'] ?? $key;
                        $optLabel = $option['name'] ?? $key;
                    } else {
                        $optValue = $key;
                        $optLabel = $option;
                    }
                    $isSelected = (string) $optValue === (string) $currentValue;
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
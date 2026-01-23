@php
    $id = $id ?? $name;
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
        data-selected-value="{{ $value }}"
    >
        @if ($placeholder)
            <option value="" selected disabled>{{ $placeholder }}</option>
        @endif

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
@if($inputGroup ?? false)
<div class="form-group">
    @if(isset($label))
        <label for="{{ $name }}" class="form-label font-weight-bold {{ $labelClass ?? '' }}">{{ $label }} @if (!empty($required)) <span class="text-danger">*</span> @endif </label>
    @endif
    <div class="input-group">
        <span class="input-group-text">{{ $inputGroupText ?? '' }}</span>
        <input type="{{ $type ?? 'text' }}"
        name="{{ $name }}"
        class="form-control rounded-end {{ $class ?? '' }}"
        id="{{ $id ?? $name }}"
        placeholder="{{ $placeholder ?? '' }}"
        value="{{ old($name, $value ?? '') }}"
        maxlength="{{ $maxlength ?? '' }}"
        @if (isset($step)) step="{{ $step }}" @elseif (($type ?? 'text') === 'number') step="any" @endif
        @if (!empty($required)) required="{{ $required ? 'required' : false }}" @endif
        @if (!empty($disabled)) disabled="{{ $disabled ? 'disabled' : false }}" @endif
        @if (!empty($readonly)) readonly="{{ $readonly ? 'readonly' : false }}" @endif
        />
    </div>
    @error ($name)
        <span class="text-danger mt-2 d-block">{{ $errors->first($name) }}</span>
    @enderror
</div>
@else
<div class="form-group">
    @if(isset($label))
        <label for="{{ $name }}" class="form-label font-weight-bold {{ $labelClass ?? '' }}">{{ $label }} @if (!empty($required)) <span class="text-danger">*</span> @endif </label>
    @endif

    <input type="{{ $type ?? 'text' }}"
    name="{{ $name }}"
    class="form-control rounded {{ $class ?? '' }}"
    id="{{ $id ?? $name }}"
    placeholder="{{ $placeholder ?? '' }}"
    value="{{ old($name, $value ?? '') }}"
    maxlength="{{ $maxlength ?? '' }}"
    @if (isset($step)) step="{{ $step }}" @elseif (($type ?? 'text') === 'number') step="any" @endif
    @if (!empty($required)) required="{{ $required ? 'required' : false }}" @endif
    @if (!empty($disabled)) disabled="{{ $disabled ? 'disabled' : false }}" @endif
    @if (!empty($readonly)) readonly="{{ $readonly ? 'readonly' : false }}" @endif
    />

    @error ($name)
        <span class="text-danger mt-2 d-block">{{ $errors->first($name) }}</span>
    @enderror
</div>
@endif
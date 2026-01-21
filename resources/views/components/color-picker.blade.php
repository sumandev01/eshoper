<div class="form-group mb-3">
    <label for="{{ $name }}_text" class="form-label fw-bold">{{ $label }} @if (!empty($required))
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        <!-- Text Input -->
        <input type="text" name="{{ $name }}" id="{{ $name }}_text" value="{{ old($name, $value) }}"
            class="form-control" placeholder="#000000" oninput="updateColorPicker('{{ $name }}')">

        <!-- Color Picker Input -->
        <input type="color" id="{{ $name }}_picker" value="{{ old($name, $value) }}"
            class="form-control form-control-color rounded-0 color p-0" style="max-width: 50px; padding: 5px;"
            oninput="updateTextInput('{{ $name }}')">
    </div>

    @error($name)
        <span class="text-danger mt-2 d-block">{{ $errors->first($name) }}</span>
    @enderror
</div>
<style>
    input[type="color"]::-webkit-color-swatch {
        border: none;
        border-radius: 0;
        /* This removes the rounded corners */
    }

    /* Specific styles for Firefox to remove border radius */
    input[type="color"]::-moz-color-swatch {
        border: none;
        border-radius: 0;
        /* This removes the rounded corners */
    }
</style>
<script>
    // The color picker will update when you input text.
    function updateColorPicker(name) {
        const textInput = document.getElementById(name + '_text');
        const colorPicker = document.getElementById(name + '_picker');

        // Check if the input is a valid hex color
        if (/^#[0-9A-F]{6}$/i.test(textInput.value)) {
            colorPicker.value = textInput.value;
        }
    }

    // The text input will update when you select a color.
    function updateTextInput(name) {
        const textInput = document.getElementById(name + '_text');
        const colorPicker = document.getElementById(name + '_picker');

        textInput.value = colorPicker.value.toUpperCase();
    }
</script>
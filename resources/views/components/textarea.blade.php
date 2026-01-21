<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-bold">
            {{ $label }} @if($required) <span class="text-danger">*</span> @endif
        </label>
    @endif

    @if($editor)
        <div id="quill-wrapper-{{ $name }}" class="bg-white">
            <div id="editor-{{ $name }}" style="height: {{ $rows * 40 }}px;">
                {!! $value !!}
            </div>
        </div>

        <input type="hidden" name="{{ $name }}" id="hidden-{{ $name }}" value="{{ $value }}">

        @if($wordcount || $maxlength)
            <div class="mt-1 text-muted" style="font-size: 0.85rem;">
                <span id="count-{{ $name }}">0</span> 
                {{ $maxlength ? "/ $maxlength" : "" }} characters
            </div>
        @endif

        {{-- Error message for Editor --}}
        @error($name)
            <span class="text-danger mt-2 d-block">{{ $errors->first($name) }}</span>
        @enderror

        @push('styles')
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
            <style>
                .ql-toolbar.ql-snow { border-radius: 5px 5px 0 0; border: 1px solid #ced4da; }
                .ql-container.ql-snow { border-radius: 0 0 5px 5px; border: 1px solid #ced4da; }
            </style>
        @endpush

        @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
        <script>
            $(document).ready(function() {
                const toolbarOptions = [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }], 
                    ['link'], 
                    ['clean']
                ];

                const quill = new Quill('#editor-{{ $name }}', {
                    modules: { toolbar: toolbarOptions },
                    theme: 'snow',
                    placeholder: '{{ $placeholder }}'
                });

                window.insertImageToQuill_{{ str_replace('-', '_', $name) }} = function(imageUrl) {
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', imageUrl, Quill.Sources.USER);
                    quill.setSelection(range.index + 1);
                };

                const hiddenInput = document.getElementById('hidden-{{ $name }}');
                const display = document.getElementById('count-{{ $name }}');

                quill.on('text-change', function() {
                    hiddenInput.value = quill.root.innerHTML;
                    const length = quill.getText().trim().length;
                    if(display) { display.innerText = length; }
                });
            });
        </script>
        @endpush
    @else
        {{-- Standard Textarea --}}
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            rows="{{ $rows }}" 
            placeholder="{{ $placeholder }}" 
            maxlength="{{ $maxlength }}" 
            @if($required) required @endif 
            class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror"
        >{{ $value }}</textarea>

        @if($wordcount || $maxlength)
            <div class="mt-1 text-muted" style="font-size: 0.85rem;">
                <span id="count-{{ $name }}">0</span> 
                {{ $maxlength ? "/ $maxlength" : "" }} characters
            </div>
        @endif

        {{-- Error message for Textarea --}}
        @error($name)
            <span class="text-danger mt-2 d-block">{{ $errors->first($name) }}</span>
        @enderror

        @push('scripts')
        <script>
            $(document).ready(function() {
                const textarea = document.getElementById('{{ $name }}');
                const display = document.getElementById('count-{{ $name }}');

                if (textarea && display) {
                    display.innerText = textarea.value.length;
                    textarea.addEventListener('input', function() {
                        display.innerText = this.value.length;
                    });
                }
            });
        </script>
        @endpush
    @endif
</div>
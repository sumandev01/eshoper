<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label text-dark">{{ $label }}</label>
    @endif

    @if($editor)
        <div class="editor-container">
            <textarea
                class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror"
                name="{{ $name }}" 
                id="editor-{{ $name }}" 
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
            >{!! $value !!}</textarea>
        </div>

        @if($wordcount || $maxlength)
            <div class="mt-1 text-muted" style="font-size: 0.85rem;">
                <span id="count-{{ $name }}">0</span> 
                {{ $maxlength ? "/ $maxlength" : "" }} characters
            </div>
        @endif

        <style>
            #editor-{{ $name }} + .ck-editor .ck-editor__editable {
                min-height: {{ $rows * 50 }}px !important;
            }
            .ck-powered-by { display: none !important; }
        </style>

        @push('scripts')
        <script>
            $(document).ready(function() {
                if (typeof ClassicEditor !== 'undefined') {
                    ClassicEditor
                        .create(document.querySelector('#editor-{{ $name }}'), {
                            // Toolbar configuration starts here
                            toolbar: {
                                items: [
                                    'heading', '|',
                                    'bold', 'italic', 'link', '|',
                                    'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                                    'bulletedList', 'numberedList', 'blockQuote', '|',
                                    'insertTable', 'mediaEmbed', 'undo', 'redo'
                                ]
                            },
                            heading: {
                                options: [
                                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                                ]
                            },
                            placeholder: '{{ $placeholder }}',
                            // Toolbar configuration ends here
                        })
                        .then(editor => {
                            window.editor_{{ str_replace('-', '_', $name) }} = editor; // Store editor instance
                            
                            const display = document.getElementById('count-{{ $name }}');
                            const initialText = editor.getData().replace(/<[^>]*>/g, '');
                            if(display) display.innerText = initialText.length;

                            editor.model.document.on('change:data', () => {
                                const data = editor.getData().replace(/<[^>]*>/g, '');
                                const count = data.length;
                                if(display) {
                                    display.innerText = count;
                                    @if($maxlength)
                                        display.style.color = (count > {{ $maxlength }}) ? 'red' : 'inherit';
                                    @endif
                                }
                            });
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            });
        </script>
        @endpush
    @else
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror"
            minlength="{{ $minlength }}"
            maxlength="{{ $maxlength }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
        >{{ $value }}</textarea>
    @endif
    
    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
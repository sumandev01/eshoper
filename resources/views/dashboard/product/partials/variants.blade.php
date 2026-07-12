<!-- Variants Section -->
<div class="card mb-4 shadow-sm" id="variants-section">
    <div class="card-header pt-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Product Variants</h5>
        <button type="button" class="btn btn-sm btn-success" id="add-variant-btn">
            <i class="mdi mdi-plus"></i> Add Variant
        </button>
    </div>
    <div class="card-body">
        <div id="variants-container">
            <!-- Variant rows will be appended here -->
        </div>
        <div id="no-variants-msg" class="text-center text-muted py-3">
            No variants added. Click "Add Variant" to add size/color variations.
        </div>
    </div>
</div>
@push('styles')
    <style>
        #variants-container label {
            font-size: 14px !important;
        }

        #variants-container .check-box.mt-2 {
            display: flex;
        }

        select.form-select.variant-size,
        select.form-select.variant-color {
            padding: 9px;
            border-radius: 0;
        }

        .use-main-price,
        .use-main-discount {
            margin-top: 0 !important;
            margin-right: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        const existingVariants = @json($existingVariants ?? []);
        $(document).ready(function() {
            // --- Dynamic Variants Logic ---
            const sizes = @json($sizes ?? []);
            const colors = @json($colors ?? []);
            let variantIndex = 0;

            function addVariantRow(vData = null) {
                $('#no-variants-msg').hide();

                let sizeOptions = '<option value="">Select Size</option>';
                sizes.forEach(s => {
                    let selected = (vData && vData.size_id == s.id) ? 'selected' : '';
                    sizeOptions += `<option value="${s.id}" ${selected}>${s.name}</option>`;
                });

                let colorOptions = '<option value="">Select Color</option>';
                colors.forEach(c => {
                    let selected = (vData && vData.color_id == c.id) ? 'selected' : '';
                    colorOptions += `<option value="${c.id}" ${selected}>${c.name}</option>`;
                });

                let stock = vData ? (vData.stock || 0) : 0;
                let price = vData ? (vData.price || '') : '';
                let discount = vData ? (vData.discount || '') : '';
                let mediaId = vData ? (vData.media_id || '') : '';
                let useMainPrice = (vData && (vData.use_main_price == 1 || vData.use_main_price === true)) ?
                    'checked' : '';
                let useMainDiscount = (vData && (vData.use_main_discount == 1 || vData.use_main_discount ===
                    true)) ? 'checked' : '';
                let priceDisabled = useMainPrice ? 'disabled' : '';
                let discountDisabled = useMainDiscount ? 'disabled' : '';

                // Safely determine the image preview for existing variants (from edit mode)
                let imageHtml = '';
                if (vData && vData.image) {
                    imageHtml = `
                <div id="media-preview-var_${variantIndex}" class="me-3 rounded p-1 bg-white border" style="height: 60px; width: 60px; display: flex; justify-content: center; align-items: center;">
                    <img src="${vData.image}" class="img-thumbnail" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                </div>
            `;
                } else {
                    imageHtml = `
                <div id="media-preview-var_${variantIndex}" class="me-3 rounded p-1 bg-white border" style="height: 60px; width: 60px; display: flex; justify-content: center; align-items: center;">
                    <div class="text-center text-muted defaultImagePlaceholder">
                        <i class="mdi mdi-image-outline fs-3"></i>
                    </div>
                </div>
            `;
                }

                let rowHtml = `
            <div class="variant-row border rounded shadow-sm mb-3" style="background-color: #fcfcfc;" data-index="${variantIndex}">
                <div class="d-flex justify-content-between align-items-center bg-light p-2 border-bottom">
                    <h6 class="m-0 text-primary fw-bold"><i class="mdi mdi-cube-outline me-1"></i> Variant #${variantIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-variant rounded-circle" title="Remove Variant" style="width: 28px; height: 28px; padding: 0; line-height: 28px;">
                        <i class="mdi mdi-trash-can"></i>
                    </button>
                </div>
                <div class="p-3">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label font-weight-bold">Size</label>
                            <select name="variants[${variantIndex}][size_id]" class="form-select variant-size">
                                ${sizeOptions}
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label font-weight-bold">Color</label>
                            <select name="variants[${variantIndex}][color_id]" class="form-select variant-color">
                                ${colorOptions}
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label font-weight-bold">Stock <span class="text-danger">*</span></label>
                            <input type="number" name="variants[${variantIndex}][stock]" class="form-control variant-stock" value="${stock}" min="0" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4 mb-2 checkBox">
                            <label class="form-label font-weight-bold">Price</label>
                            <input type="number" name="variants[${variantIndex}][price]" class="form-control variant-price" step="0.01" placeholder="Enter Price" value="${price}" ${priceDisabled}>
                            <div class="check-box mt-2">
                                <input class="form-check-input use-main-price" type="checkbox" name="variants[${variantIndex}][use_main_price]" value="1" ${useMainPrice}>
                                <label style="cursor:pointer; font-size: 13px;" onclick="$(this).prev().click()">Default Main Price</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2 checkBox">
                            <label class="form-label font-weight-bold">Discount Price</label>
                            <input type="number" name="variants[${variantIndex}][discount]" class="form-control variant-discount" step="0.01" placeholder="Enter Discount" value="${discount}" ${discountDisabled}>
                            <div class="check-box mt-2">
                                <input class="form-check-input use-main-discount" type="checkbox" name="variants[${variantIndex}][use_main_discount]" value="1" ${useMainDiscount}>
                                <label style="cursor:pointer; font-size: 13px;" onclick="$(this).prev().click()">Default Main Discount</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2 border-start">
                            <label class="form-label font-weight-bold">Image <span class="text-danger">*</span></label><br>
                            <div class="d-flex align-items-center">
                                ${imageHtml}
                                <div>
                                    <input type="hidden" name="variants[${variantIndex}][media_id]" class="variant-media-input" value="${mediaId}" required>
                                    <button type="button" class="btn btn-secondary btn-sm open-media-picker" data-target-id="var_${variantIndex}" data-multiple="false" data-is-quill="false">Choose Image</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-danger error-msg mt-2 d-none font-weight-bold"></div>
                </div>
            </div>
        `;
                $('#variants-container').append(rowHtml);
                variantIndex++;
                updateTotalStock();
            }

            $('#add-variant-btn').click(function() {
                addVariantRow();
            });

            // 1. Check for old validation errors first
            let oldVariants = @json(old('variants', []));

            // 2. If no old validation variants, check for existing DB variants (Edit page)
            if ((!oldVariants || Object.keys(oldVariants).length === 0) && typeof existingVariants !==
                'undefined' && Object.keys(existingVariants).length > 0) {
                oldVariants = existingVariants;
            }

            // 3. Render variants
            if (oldVariants && Object.keys(oldVariants).length > 0) {
                Object.values(oldVariants).forEach(v => {
                    addVariantRow(v);
                });
            }

            $(document).on('click', '.remove-variant', function() {
                $(this).closest('.variant-row').remove();
                if ($('.variant-row').length === 0) {
                    $('#no-variants-msg').show();
                    $('#main_quantity').prop('readonly', false);
                }
                updateTotalStock();
            });

            $(document).on('input', '.variant-stock', function() {
                updateTotalStock();
            });

            $(document).on('change', '.use-main-price', function() {
                let p = $(this).closest('.checkBox').find('.variant-price');
                if ($(this).is(':checked')) {
                    p.val('');
                    p.prop('disabled', true);
                } else {
                    p.prop('disabled', false);
                }
            });

            $(document).on('change', '.use-main-discount', function() {
                let p = $(this).closest('.checkBox').find('.variant-discount');
                if ($(this).is(':checked')) {
                    p.val('');
                    p.prop('disabled', true);
                } else {
                    p.prop('disabled', false);
                }
            });

            function updateTotalStock() {
                let total = 0;
                let rows = $('.variant-row');
                if (rows.length > 0) {
                    rows.each(function() {
                        let stock = parseInt($(this).find('.variant-stock').val()) || 0;
                        total += stock;
                    });
                    $('#main_quantity').val(total).prop('readonly', true);
                }
            }

            // Real-time Duplicate Prevention
            $(document).on('change', '.variant-size, .variant-color', function() {
                let currentRow = $(this).closest('.variant-row');
                let currentSize = currentRow.find('.variant-size').val();
                let currentColor = currentRow.find('.variant-color').val();
                let currentIndex = currentRow.data('index');

                if (!currentSize && !currentColor) return;

                let currentCombo = (currentSize || 'none') + '-' + (currentColor || 'none');
                let isDuplicate = false;

                $('.variant-row').each(function() {
                    if ($(this).data('index') !== currentIndex) {
                        let size = $(this).find('.variant-size').val();
                        let color = $(this).find('.variant-color').val();
                        let combo = (size || 'none') + '-' + (color || 'none');

                        if (combo === currentCombo && combo !== 'none-none') {
                            isDuplicate = true;
                        }
                    }
                });

                if (isDuplicate) {
                    if (typeof showToast === 'function') {
                        showToast('error', 'This Size & Color combination is already selected!');
                    } else {
                        alert('This Size and Color combination is already selected in another variant!');
                    }
                    $(this).val(''); // Reset the selection
                    currentRow.find('.error-msg').text('Duplicate selection cleared.').removeClass(
                    'd-none');
                    setTimeout(() => currentRow.find('.error-msg').addClass('d-none'), 3000);
                } else {
                    currentRow.find('.error-msg').addClass('d-none');
                }
            });

            // Duplicate Validation before Submit
            $('form').on('submit', function(e) {
                // Ensure image inputs get the correct value from modal
                $('.variant-row').each(function() {
                    let index = $(this).data('index');
                    let modalGeneratedInput = $(this).find(`input[name="var_${index}"]`);
                    if (modalGeneratedInput.length) {
                        $(this).find('.variant-media-input').val(modalGeneratedInput.val());
                    }
                });

                $('.error-msg').addClass('d-none').text('');
                let isValid = true;
                let combinations = [];

                $('.variant-row').each(function() {
                    let size = $(this).find('.variant-size').val();
                    let color = $(this).find('.variant-color').val();
                    let media = $(this).find('.variant-media-input').val();

                    if (!size && !color) {
                        $(this).find('.error-msg').text(
                            'Error: Either Size or Color must be selected.').removeClass(
                            'd-none');
                        isValid = false;
                    }
                    if (!media) {
                        $(this).find('.error-msg').text('Error: Image is required for variant.')
                            .removeClass('d-none');
                        isValid = false;
                    }

                    let combo = (size || 'none') + '-' + (color || 'none');
                    if (combinations.includes(combo)) {
                        $(this).find('.error-msg').text(
                            'Error: Duplicate Size and Color combination!').removeClass(
                            'd-none');
                        isValid = false;
                    } else {
                        combinations.push(combo);
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    $('#product_submit_btn').prop('disabled', false);
                }
            });
        });
    </script>
@endpush

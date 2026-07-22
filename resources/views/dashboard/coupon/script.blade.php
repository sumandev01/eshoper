<script>
    $(document).ready(function() {
        $('#type').on('change', function() {
            let selectedType = $(this).val();
            let typeInfo = $('.type_info');
            
            if (selectedType === 'percentage') {
                $('#max_discount').prop('disabled', false);
                $('#max_discount').prop('required', true);
                typeInfo.text('Note: Discount will be applied as a percentage.');
            } else {
                $('#max_discount').prop('disabled', true);
                $('#max_discount').prop('required', false);
                $('#max_discount').val('');
                typeInfo.text('Note: Amount should be in {{ $siteSettings->currency_code ?? "your default currency" }} only.');
            }
        });

        // Trigger change on page load to set correct initial state
        $('#type').trigger('change');

        // Prevent space in coupon code
        $('input[name="code"]').on('input', function() {
            $(this).val($(this).val().replace(/\s/g, ''));
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Select All Checkbox
        const selectAllCheckbox = document.querySelector('.select-all-checkbox');
        const itemCheckboxes = document.querySelectorAll('.bulk-item-checkbox');
        const bulkActionContainer = document.getElementById('bulkActionContainer');
        const selectedCountSpan = document.getElementById('selectedCount');
        const bulkDeleteBtn = document.querySelector('.bulkDeleteBtn');

        function updateBulkActionUI() {
            if (!bulkActionContainer) return;
            const checkedBoxes = document.querySelectorAll('.bulk-item-checkbox:checked');
            const count = checkedBoxes.length;
            
            if (selectedCountSpan) selectedCountSpan.innerText = count;
            
            if (count > 0) {
                bulkActionContainer.classList.remove('d-none');
            } else {
                bulkActionContainer.classList.add('d-none');
            }

            // Sync selectAll checkbox state
            if (selectAllCheckbox && itemCheckboxes.length > 0) {
                selectAllCheckbox.checked = (count === itemCheckboxes.length);
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                updateBulkActionUI();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionUI);
        });

        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                let paramName = this.getAttribute('data-param') || 'ids';
                if (!paramName.endsWith('[]')) {
                    paramName += '[]';
                }

                const checkedBoxes = document.querySelectorAll('.bulk-item-checkbox:checked');
                if (checkedBoxes.length === 0) return;

                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are about to delete " + selectedIds.length + " selected items. You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitBulkDeleteForm(url, paramName, selectedIds);
                        }
                    });
                } else if (confirm('Are you sure you want to delete ' + selectedIds.length + ' selected items?')) {
                    submitBulkDeleteForm(url, paramName, selectedIds);
                }
            });
        }

        function submitBulkDeleteForm(url, paramName, selectedIds) {
            let form = document.createElement('form');
            form.action = url;
            form.method = 'POST';

            let tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            form.appendChild(tokenInput);

            selectedIds.forEach(id => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = paramName;
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });
</script>
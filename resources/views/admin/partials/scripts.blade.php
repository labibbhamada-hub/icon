{{-- jQuery --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

{{-- Bootstrap --}}
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const deleteModalElement =
            document.getElementById('deleteConfirmModal');

        const confirmDeleteButton =
            document.getElementById('confirmDeleteButton');

        if (!deleteModalElement || !confirmDeleteButton) {
            return;
        }

        const deleteModal =
            new bootstrap.Modal(deleteModalElement);

        let pendingDeleteForm = null;

        document.addEventListener(
            'submit',
            function(event) {

                const form = event.target;

                if (
                    !(form instanceof HTMLFormElement) ||
                    !form.classList.contains('delete-form')
                ) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();

                pendingDeleteForm = form;

                deleteModal.show();

            },
            true
        );

        confirmDeleteButton.addEventListener(
            'click',
            function() {

                if (!pendingDeleteForm) {
                    return;
                }

                const form = pendingDeleteForm;

                pendingDeleteForm = null;

                confirmDeleteButton.disabled = true;

                confirmDeleteButton.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-1"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    Deleting...
                `;

                deleteModal.hide();

                form.submit();

            }
        );

        deleteModalElement.addEventListener(
            'show.bs.modal',
            function() {

                confirmDeleteButton.disabled = false;

                confirmDeleteButton.innerHTML = `
                    <i class="bi bi-trash me-1"></i>
                    Yes, Delete
                `;

            }
        );

    });
</script>

{{-- AdminLTE --}}
<script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>

{{-- Toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

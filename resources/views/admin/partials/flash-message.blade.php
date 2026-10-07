@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 5000,
                extendedTimeOut: 1000,
                preventDuplicates: true,
                newestOnTop: true,
            };

            @if (session('success'))
                toastr.success(
                    @json(session('success'))
                );
            @endif

            @if (session('error'))
                toastr.error(
                    @json(session('error'))
                );
            @endif

            @if (session('warning'))
                toastr.warning(
                    @json(session('warning'))
                );
            @endif

            @if (session('info'))
                toastr.info(
                    @json(session('info'))
                );
            @endif

        });
    </script>
@endpush

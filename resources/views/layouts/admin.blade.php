<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title') | ICON 2026 CMS
    </title>

    {{-- Inter Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="icon" href="{{ asset('assets/images/logo/vanda.jpeg') }}">

    {{-- Bootstrap --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> --}}

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    {{-- AdminLTE --}}
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">

    {{-- Custom Admin --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">

    @stack('styles')

</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        @include('admin.partials.navbar')

        @include('admin.partials.sidebar')

        <main class="app-main pb-4">

            <div class="app-content-header">
                <div class="container-fluid">
                    @include('admin.partials.flash-message')
                    @yield('header')
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

        </main>

        @include('admin.partials.footer')

    </div>

    @include('admin.partials.footer')

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">

                <div class="modal-header rounded-0">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">
                        Confirm Delete
                    </h5>

                    <button type="button" class="btn-close rounded-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex align-items-start gap-3">

                        <div class="text-danger fs-3">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>

                        <div>
                            <div class="fw-semibold mb-1">
                                Are you sure?
                            </div>

                            <div class="text-muted">
                                This action cannot be undone.
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer rounded-0">

                    <button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" class="btn btn-danger rounded-0" id="confirmDeleteButton">
                        <i class="bi bi-trash me-1"></i>
                        Yes, Delete
                    </button>

                </div>

            </div>
        </div>
    </div>

    @include('admin.partials.scripts')

    @stack('scripts')

</body>

</html>

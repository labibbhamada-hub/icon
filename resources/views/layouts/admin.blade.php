<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title') | ICON 2026 CMS
    </title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    {{-- Bootstrap --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> --}}
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">
    {{-- AdminLTE --}}
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
    {{-- Custom --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}"> --}}
    @stack('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        @include('admin.partials.navbar')

        @include('admin.partials.sidebar')

        <main class="app-main">
            <div class="app-content">
                <div class="container-fluid pt-3">
                    @include('admin.partials.flash-message')
                </div>
            </div>

            @yield('content')

        </main>

        @include('admin.partials.footer')

    </div>

    @include('admin.partials.scripts')

    @stack('scripts')
</body>

</html>

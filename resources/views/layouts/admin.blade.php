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

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Bootstrap --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> --}}

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    {{-- AdminLTE --}}
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">

    {{-- Custom Admin --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

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

    @include('admin.partials.scripts')

    @stack('scripts')

</body>

</html>

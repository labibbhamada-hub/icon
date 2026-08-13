<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title') | ICON 2026 Reviewer
    </title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">

    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">

    @stack('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        @include('reviewer.partials.navbar')

        @include('reviewer.partials.sidebar')

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

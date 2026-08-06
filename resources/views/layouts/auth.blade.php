<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>@yield('title') | ICON 2026</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte/dist/css/adminlte.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}">
</head>

<body class="login-page bg-body-secondary">

    @yield('content')
    
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
</body>

</html>
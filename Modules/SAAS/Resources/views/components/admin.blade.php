@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-menu="vertical" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('modules/saas/images/favicon.png') }}">
    {{-- @vite([config('saas.asset_path') . '/sass/admin.scss']) --}}

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/daterangepicker.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/css/style.css">
    <link rel="stylesheet" id="primaryColor" href="{{ asset('modules/saas') }}/css/blue-color.css">
    <link rel="stylesheet" id="rtlStyle" href="#">
    @stack('css')
</head>

<body class="body-padding body-p-top light-theme">
    <x-saas::admin-preloader />
    <x-saas::admin-header />
    <x-saas::admin-rightsidebar />
    <x-saas::admin-mainsidebar />

    <div class="main-content">
        {{ $slot }}
        <x-saas::admin-footer />
    </div>
    <script>
        window.logoSrc = "{{ asset('modules/saas/images/logo_white.png') }}";
    </script>
    {{-- @vite([config('saas.asset_path') . '/js/admin.js']) --}}

    <script src="{{ asset('modules/saas') }}/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/apexcharts.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/moment.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/daterangepicker.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('modules/saas') }}/js/dashboard.js"></script>
    <script src="{{ asset('modules/saas') }}/js/main.js"></script>
</body>

</html>

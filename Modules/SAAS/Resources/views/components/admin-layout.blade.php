@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-menu="vertical" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('modules/saas/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('modules/admin') }}/vendor/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('modules/admin') }}/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="{{ asset('modules/admin') }}/vendor/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('modules/admin') }}/vendor/css/daterangepicker.css">
    <link rel="stylesheet" href="{{ asset('modules/admin') }}/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('modules/admin') }}/css/style.css">
    <link rel="stylesheet" id="primaryColor" href="{{ asset('modules/admin') }}/css/blue-color.css">
    <link rel="stylesheet" id="rtlStyle" href="#">
    @stack('css')
</head>

<body class="body-padding body-p-top light-theme">
    <x-saas::_preloader />
    <x-saas::_header />
    <x-saas::_rightsidebar />
    <x-saas::_mainsidebar />

    <div class="main-content">
        {{ $slot }}
        <x-saas::_footer />
    </div>

    <script>
        window.logoSrc = "{{ asset('modules/saas/images/logo_black.png') }}";
    </script>
    <script src="{{ asset('modules/admin') }}/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('modules/admin') }}/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="{{ asset('modules/admin') }}/vendor/js/apexcharts.js"></script>
    <script src="{{ asset('modules/admin') }}/vendor/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('modules/admin') }}/vendor/js/moment.min.js"></script>
    <script src="{{ asset('modules/admin') }}/vendor/js/daterangepicker.js"></script>
    <script src="{{ asset('modules/admin') }}/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('modules/admin') }}/js/dashboard.js"></script>
    @include('saas::_includes.main-js')
</body>

</html>

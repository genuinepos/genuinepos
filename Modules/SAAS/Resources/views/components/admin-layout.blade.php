@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-menu="vertical" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('modules/saas/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/daterangepicker.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/css/style.css">
    <link rel="stylesheet" id="primaryColor" href="{{ asset('modules/saas') }}/css/blue-color.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet" type="text/css" />
    {{-- <link rel="stylesheet" id="rtlStyle" href="" type="text/css"> --}}
    <style>
        .toast-success {
            background-color: #51A351 !important;
        }
    </style>
    @stack('css')
</head>

<body class="body-padding body-p-top light-theme">
    <x-saas::_preloader />
    <x-saas::_header />
    <x-saas::_rightsidebar />
    <x-saas::_mainsidebar />

    <div class="main-content">
        <x-saas::_messages />
        {{ $slot }}
        <x-saas::_footer />
        <form action="#" id="deleted_form" method="POST">
            @csrf
            @method('DELETE')
        </form>
        <form action="#" id="restore_form" method="POST">
            @csrf
            @method('PATCH')
        </form>
    </div>

    <script>
        window.logoSrc = "{{ asset('modules/saas/images/logo_black.png') }}";
    </script>
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/apexcharts.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/moment.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/daterangepicker.js"></script>
    <!--Toaster.js js link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('modules/saas') }}/js/dashboard.js"></script>
    @include('saas::_includes.main-js')
    @stack('js')
</body>

</html>

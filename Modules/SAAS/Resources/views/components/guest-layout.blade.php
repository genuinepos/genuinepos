@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-menu="vertical" data-nav-size="nav-default">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('modules/saas/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/vendor/css/OverlayScrollbars.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('modules/saas') }}/css/blue-color.css">

    @stack('css')
</head>

<body class="dark-theme">
    <!-- preloader start -->
    <div class="preloader d-none">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- preloader end -->

    <!-- theme color hidden button -->
    <button class="header-btn theme-color-btn d-none"><i class="fa-light fa-sun-bright"></i></button>
    <!-- theme color hidden button -->

    <!-- main content start -->
    <div class="main-content login-panel">
        {{ $slot }}
        <x-saas::_footer />
    </div>
    <!-- main content end -->
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('modules/saas') }}/vendor/js/jquery.overlayScrollbars.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $('.password-show').on('click', function() {
            $(this).find('i').toggleClass('fa-eye-slash fa-eye');
            var textType = $(this).siblings('input').attr('type');
            var passType;
            if (textType == 'text') {
                passType = 'password';
            } else {
                passType = 'text';
            }
            $(this).siblings('input').attr('type', passType);
        });
    </script>
     {{-- {!! NoCaptcha::renderJs() !!} --}}
     <script src="//www.google.com/recaptcha/api.js?" async defer></script>
</body>

</html>

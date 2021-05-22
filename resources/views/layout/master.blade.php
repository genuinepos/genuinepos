<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>Genuine POS</title>

    <!-- Icon -->
    <link rel="shortcut icon" href="{{ asset('public/favicon.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

   @include('layout._stylesheet')
   @stack('stylesheets')

</head>

<body style="background: #EEF0F8!important;">
    <div class="all__content">
        @include('partials.sidebar')

        <div class="main-woaper_t">
            @include('partials.header')
            <div style="background: #EEF0F8;">
                @yield('content')
            </div>
        </div>
    </div>
    @include('layout._script')
    @stack('scripts')
    <!-- Logout form for global -->
    <form id="logout_form" class="d-none" action="{{ route('logout') }}" method="POST">@csrf</form>
    <!-- Logout form for global end -->
</body>

</html>

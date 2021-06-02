<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>@yield('title') Genuine POS</title>

    <!-- Icon -->
    <link rel="shortcut icon" href="{{ asset('public/favicon.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layout._stylesheet')
    @stack('stylesheets')

</head>

<body id="dashboard-8" style="background: #EEF0F8!important;">
    <div class="all__content">
        @include('partials.sidebar')

        <div class="main-woaper">
            @include('partials.header')
            <div style="background: #EEF0F8;">
                @yield('content')
            </div>
        </div>
        <footer>
            <div class="logo_wrapper">
                <img src="{{ asset('public/uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="" class="logo">
            </div>
        </footer>
    </div>
    @include('layout._script')
    @stack('scripts')
    <!-- Logout form for global -->
    <form id="logout_form" class="d-none" action="{{ route('logout') }}" method="POST">@csrf</form>
    <!-- Logout form for global end -->
</body>

</html>

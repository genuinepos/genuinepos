<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>@yield('title') {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('billing.partials.__stylesheet')
    @stack('css')
</head>

<body class="inner">
    @include('billing.partials.header', ['heading' => $heading])

    @yield('content')

    <form id="logout_form" class="d-hide" action="{{ route('logout') }}" method="POST">@csrf</form>

    @include('billing.partials.__script')
    @stack('js')
</body>

</html>

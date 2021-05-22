<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title') Genuine POS</title>
    <!-- Icon -->
    <link rel="shortcut icon" href="{{ asset('public/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/fontawesome/css/all.css')}}">
    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/selectize.css')}}">
    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/dropzone.css')}}">

    <link href="{{ asset('public/backend/css/reset.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/typography.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/body.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/shCore.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/jquery.jqplot.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/jquery-ui-1.8.18.custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/data-table.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/form.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/ui-elements.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/wizard.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/sprite.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/backend/css/gradient.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/comon.css') }} ">
    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/style.css') }}">


</head>

<body>
    @yield('content')
</body>

</html>

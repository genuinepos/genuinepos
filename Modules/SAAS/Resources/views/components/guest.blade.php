@props(['title' => null])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ (isset($title) ? $title . ' | ' : '') . config('app.name') }}</title>

    {{-- <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/bootstrap.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/plan-cart.css') }}">
    @stack('css')
</head>

<body class="inner">
    <!--------------------------------- CART SECTION START --------------------------------->
    <div class="tab-section py-120">
        <div class="container">
            {{ $slot }}
        </div>
    </div>
    <!--------------------------------- CART SECTION END --------------------------------->

    <!-- js files -->
    <script src="{{asset('backend/asset/cdn/js/jquery-3.6.0.js')}}"></script>
    <script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
    @stack('js')
</body>

</html>

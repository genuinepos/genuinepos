<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __("Customer Registration") }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <h2>{{ __("Customer Registration") }}</h2>
    <p>
        Dear {{ $user->name }}, Your account has been successfully registered. Please Verify by clicking this link below.
        <a href="#" clas="btn btn-primary">{{ __("Vefiry Email") }}</a>
        {{-- <a href="{{ $vefifyLink }}">{{ __("Vefiry Email") }}</a> --}}
    </p>
    <p>
        {{ $hello }}
    </p>
</body>
</html>

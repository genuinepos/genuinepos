<!DOCTYPE html>
<html>
<title>{{ __('Advertisement') }}</title>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            /* overflow:hidden; */
        }
    </style>

    <video style="width: 100%;height:95%;" controls autoplay>
        <source src="{{ asset('uploads/advertisement/' . $data[0]->video) }}" type="video/mp4">
        {{ __('Your browser does not support the video tag.') }}
    </video>

</body>

</html>

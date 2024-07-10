<!DOCTYPE html>
<html>
<title>{{ __('Ads - ') }}{{ config('app.name') }}</title>
<link rel="shortcut icon" href="{{ asset('favicon.png') }}">

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            /* Prevent scrollbars */
        }

        video {
            width: 100%;
            height: 100vh;
            position: relative;
        }

        video {
            object-fit: cover;
        }
    </style>

    <video style="width: 100%;height:95%;" controls autoplay loop>
        <source src="{{ file_link('advertisementAttachment', $advertisement->attachments[0]->video) }}" type="video/mp4">
        {{ __('Your browser does not support the video tag.') }}
    </video>
</body>

</html>

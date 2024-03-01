<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __("Email Verification Code") }} - {{ config('app.name') }}</title>

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="{{ asset('assets/css/email.css') }}">
</head>

<body>
    <div class="email-container">
        <h2 class="page-title">{{ __("Email Verification Code") }}</h2>
        <table>
            <tbody>
                <tr>
                    <td>
                        <div class="mail-header">
                            <div class="logo">
                                <a href="#" target="_blank"><img src="{{ asset('assets/images/logo_black.png') }}" alt="Logo"></a>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <h2 class="mail-title">{{ __("Email Verification Code") }} : <span style="font-weight: 600;">{{ $code }}</span></h2>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="mail-footer">
                            <div class="footer-social">
                                <a href="{{ config('speeddigit.facebook') }}" title="Facebook" target="_blank"><img src="{{ asset('assets/images/facebook.png') }}" alt="facebook"></a>
                                <a href="{{ config('speeddigit.youtube') }}" title="Instagram" target="_blank"><img src="{{ asset('assets/images/instagram.png') }}" alt="instagram"></a>
                                <a href="{{ config('speeddigit.twitter') }}" title="Twitter" target="_blank"><img src="{{ asset('assets/images/twitter.png') }}" alt="twitter"></a>
                                <a href="{{ config('speeddigit.linkedin') }}" title="Linkedin" target="_blank"><img src="{{ asset('assets/images/linkedin.png') }}" alt="linkedin"></a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>

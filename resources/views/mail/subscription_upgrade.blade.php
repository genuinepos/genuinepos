
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Upgrade - {{ config('app.name') }}</title>

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="{{ asset('assets/css/email.css') }}">
</head>
<body>
    <div class="email-container">
        <h2 class="page-title">Subscription Successfully Upgraded</h2>
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
                        <div class="mail-img">
                            <img src="{{ asset('assets/images/confirm.png') }}" alt="Success">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="mail-title">Subscription Successfully Upgraded, Here We Go!</h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mail-body">
                            <div><strong>Dear {{ $user->name }}</strong>,</div>
                            <p>We are excited to inform you that your subscription has been <b>successfully upgraded</b>! You now have access to all the premium features and benefits that come with your new subscription level.</p><br><br>
                            <p>Thank you for choosing us!</p>
                            <p>Best Regards,</p>
                            <p>GPOS</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mail-footer">
                            <div class="logo"><img src="{{ asset('assets/images/logo_black.png') }}" alt="LOGO"></div>
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

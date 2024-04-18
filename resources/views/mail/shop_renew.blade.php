<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __("Email Verification Code") }} - {{ config('app.name') }}</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Lato", sans-serif;
            padding: 30px 0;
            width: 100%;
            font-size: 12px;
        }

        img {
            max-width: 100%;
            vertical-align: middle;
        }

        a {
            display: inline-block;
            text-decoration: none;
            color: #0D99FF;
            -webkit-transition: 0.3s;
            transition: 0.3s;
        }

        .email-container {
            max-width: 550px;
            padding: 0 5px;
            margin: auto;
            text-align: center;
        }

        .email-container .page-title {
            text-align: left;
            font-family: "Poppins", sans-serif;
            font-size: 30px;
            line-height: 1;
            font-weight: 600;
            color: #595959;
            margin-bottom: 30px;
        }

        .email-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            padding: 4px 20px;
        }

        .mail-footer {
            border-top: 1px solid #dfe9ff;
            padding: 25px 20px;
            margin: 0 -1px -1px;
        }

        .mail-footer .logo {
            padding-top: 0;
        }

        .footer-social {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            gap: 10px;
        }

        .footer-social a {
            width: 30px;
        }

        @media only screen and (max-width: 991px) and (min-width: 320px) {
            body {
                padding: 20px 0;
            }

            .email-container .page-title {
                text-align: left;
                font-size: 20px;
                margin-bottom: 20px;
            }

            .logo {
                padding: 10px;
            }

            .logo img {
                max-width: 120px;
            }

            .mail-footer {
                padding: 15px 10px;
            }

            .mail-footer .logo {
                padding-bottom: 15px;
            }

            .footer-social a {
                width: 25px;
            }
        }

        /* Custom styles for invoice */
        .email-container table {
            margin-bottom: 20px;
        }

        .email-container table th,
        .email-container table td {
            padding: 8px;
            text-align: left;
        }

        table tr {
            font-size: 11px;
            font-weight: bold;
        }

        table {
            margin: 10px 0;
        }

        p {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h2 class="page-title">Renew Successfully</h2>
        <table>
            <tbody>
                <tr>
                    <td>
                        <div class="mail-header">
                            <div class="logo">
                                <a href="#" target="_blank"><img src="{{ url('/') }}/assets/images/logo_black.png'" alt="Logo"></a>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <h2 class="mail-title">Renew Process completed Successfully, Here We Go!</h2>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="mail-body">
                            <div>Dear <strong>{{ $user->prefix . ' '. $user->name . $user->last_name }}</strong>,</div>
                            <p>We are excited to inform you that your renew process has been completed <b>successfully</b></p><br><br>
                            <p>Thank you for choosing us!</p>
                            <p>Best Regards,</p>
                            <p>GPOS</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mail-footer">
                            <div class="logo"><img src="{{ url('/') }}/assets/images/logo_black.png" alt="LOGO"></div>
                            <div class="footer-social">
                                <a href="{{ config('speeddigit.facebook') }}" title="Facebook" target="_blank"><img src="{{ url('/') }}/assets/social/facebook.png" alt="facebook"></a>
                                <a href="{{ config('speeddigit.youtube') }}" title="Instagram" target="_blank"><img src="{{ url('/') }}/assets/social/instagram.png" alt="instagram"></a>
                                <a href="{{ config('speeddigit.twitter') }}" title="Twitter" target="_blank"><img src="{{ url('/') }}/assets/social/twitter.png" alt="twitter"></a>
                                <a href="{{ config('speeddigit.linkedin') }}" title="Linkedin" target="_blank"><img src="{{ url('/') }}/assets/social/linkedin.png" alt="linkedin"></a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>


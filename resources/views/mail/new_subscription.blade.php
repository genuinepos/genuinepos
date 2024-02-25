
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirm - {{ config('app.name') }}</title>

    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="{{ asset('assets/css/email.css') }}">
</head>
<body>
    <div class="email-container">
        <h2 class="page-title">Subscription Confirm</h2>
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
                        <h2 class="mail-title">Subscription Success, Here We Go!</h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mail-body">
                            <div><strong>Dear {{ $user->name }}</strong>,</div>
                            <p>Welcome to the GPOS! We are thrilled to have you on board. Your subscription has been activated and you now have full access to all the features and benefits we offer.</p><br><br>
                            <p>As a subscriber, you'll receive regular updates, exclusive content, and premium support. We're committed to providing you with the best experience possible.</p><br><br>
                            <p>If you have any questions or need assistance, please don't hesitate to reach out to us. We're here to help!</p><br><br>
                            <p>Thank you for choosing us. We look forward to serving you.</p><br><br>
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
                                <a href="#" title="Facebook" target="_blank"><img src="assets/images/facebook.png" alt="facebook"></a>
                                <a href="#" title="Instagram" target="_blank"><img src="assets/images/instagram.png" alt="instagram"></a>
                                <a href="#" title="Twitter" target="_blank"><img src="assets/images/twitter.png" alt="twitter"></a>
                                <a href="#" title="Linkedin" target="_blank"><img src="assets/images/linkedin.png" alt="linkedin"></a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>

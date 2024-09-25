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
        <h2 class="page-title">{{ __("Subscription Plan Upgraded Successfully") }}</h2>
        <table>
            <tbody>
                <tr>
                    <td>
                        <div class="mail-header">
                            <div class="logo">
                                <a href="#" target="_blank"><img src="{{ url('/') }}/assets/images/app_logo.png" alt="Logo"></a>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>{{ __("Full Name") }}</td>
                    <td>: {{ $user->name }}</td>
                </tr>

                <tr>
                    <td>{{ __("Email") }}</td>
                    <td>: {{ $user->email }}</td>
                </tr>

                <tr>
                    <td>{{ __("Go To Your App") }}</td>
                    <td>: <a href="{{ $appUrl }}">{{ $appUrl }}</a></td>
                </tr>

                @if ($isTrialPlan == 1)
                    <tr>
                        <td>Details:</td>
                    </tr>

                    <tr>
                        <td>
                            <table>
                                <tr style="border-bottom: 1px solid black;">
                                    <th>{{ __("Plan") }}</th>
                                    <th>{{ __("Price") }}</th>
                                    <th>{{ __("Store Count") }}</th>
                                    <th>{{ __("Price Period") }}</th>
                                    <th>{{ __("Price Period Count") }}</th>
                                    <th>{{ __("Subtotal") }}</th>
                                </tr>

                                <tr>
                                    <td>{{ $planName }}</td>
                                    <td>{{ App\Utils\Converter::format_in_bdt($data['shop_price']) }}</td>
                                    <td>{{ $data['shop_count'] }}</td>
                                    <td>{{ $data['shop_price_period'] }}</td>
                                    <td>{{ $data['shop_price_period'] == 'lifetime' ? 'Lifetime' : $data['shop_price_period_count'] }}</td>
                                    <td>{{ App\Utils\Converter::format_in_bdt($data['shop_subtotal']) }}</td>
                                </tr>

                                @if (isset($data['has_business']))
                                    <tr>
                                        <td>{{ __("Multi Store Management System") }}({{ location_label('business') }})</td>
                                        <td>{{ App\Utils\Converter::format_in_bdt($data['business_price']) }}</td>
                                        <td>...</td>
                                        <td>{{ $data['business_price_period'] }}</td>
                                        <td>{{ $data['business_price_period'] == 'lifetime' ? 'Lifetime' : $data['business_price_period_count'] }}</td>
                                        <td>{{ App\Utils\Converter::format_in_bdt($data['business_subtotal']) }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td colspan="5" style="text-align: right;">{{ __("Net Total") }}</td>
                                    <td>{{ App\Utils\Converter::format_in_bdt($data['net_total']) }}</td>
                                </tr>

                                <tr>
                                    <td colspan="5" style="text-align: right;">{{ __("Discount") }}</td>
                                    <td>{{ App\Utils\Converter::format_in_bdt($data['discount']) }}</td>
                                </tr>

                                <tr>
                                    <td colspan="5" style="text-align: right;">{{ __("Total Payable") }}</td>
                                    <td>{{ App\Utils\Converter::format_in_bdt($data['total_payable']) }}</td>
                                </tr>

                                <tr>
                                    <td colspan="5" style="text-align: right;">{{ __("Paid") }}</td>
                                    <td>{{ $data['payment_status'] == 1 ? App\Utils\Converter::format_in_bdt($data['total_payable']) : '0.00' }}</td>
                                </tr>

                                <tr>
                                    <td colspan="5" style="text-align: right;">{{ __("Due") }}</td>
                                    <td>{{ $data['payment_status'] == 1 ? '0.00' : App\Utils\Converter::format_in_bdt($data['total_payable']) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ __("Amount Details") }}:</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">{{ __("Net Total") }}</td>
                        <td>{{ App\Utils\Converter::format_in_bdt($data['net_total']) }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: right;">{{ __("Adjusted Amount") }}</td>
                        <td>{{ App\Utils\Converter::format_in_bdt($data['total_adjusted_amount']) }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: right;">{{ __("Discount") }}</td>
                        <td>{{ App\Utils\Converter::format_in_bdt($data['discount']) }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: right;">{{ __("Total Payable") }}</td>
                        <td>{{ App\Utils\Converter::format_in_bdt($data['total_payable']) }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: right;">{{ __("Paid") }}</td>
                        <td>{{ $data['payment_status'] == 1 ? App\Utils\Converter::format_in_bdt($data['total_payable']) : '0.00' }}</td>
                    </tr>

                    <tr>
                        <td style="text-align: right;">{{ __("Due") }}</td>
                        <td>{{ $data['payment_status'] == 1 ? '0.00' : App\Utils\Converter::format_in_bdt($data['total_payable']) }}</td>
                    </tr>
                @endif
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
                <tr>
                    <td>
                        <a href="{{ config('speeddigit.website') }}">{{ __("Visit our website") }}</a> |
                        <a href="{{ config('speeddigit.contact_us') }}">{{ __("Contact Us") }}</a>
                    </td>
                </tr>
                <tr>
                    <td>Copyright © gposs.com, All rights reserved.</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>

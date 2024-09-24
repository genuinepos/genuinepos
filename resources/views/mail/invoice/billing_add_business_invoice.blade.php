<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .mb-30 {
            margin-bottom: 30px !important;
        }

        .mail-primary-button {
            height: 45px;
            line-height: 45px;
            background: #0D99FF;
            color: #fff;
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-size: 18px;
            padding: 0 25px;
            border-radius: 10px;
            -webkit-box-shadow: 0px 3px 0px 0px #0072b7;
            box-shadow: 0px 3px 0px 0px #0072b7;
            text-shadow: 1px 1x #464646;
        }

        .email-container {
            max-width: 740px;
            padding: 0 5px;
            margin: auto;
            text-align: center;
        }

        .email-container .page-title {
            font-family: "Poppins", sans-serif;
            font-size: 30px;
            line-height: 1;
            font-weight: 600;
            color: #595959;
            margin-bottom: 30px;
        }

        .email-container table {
            width: 100%;
            background: rgba(0, 0, 0, 0.02);
            background: #F9F9F9;
            /* border: 1px solid rgba(0, 0, 0, 0.05); */
            border-collapse: collapse;
        }

        .mail-header {
            margin-bottom: 30px;
        }

        .logo {
            padding: 4px 20px;
        }

        .mail-img {
            max-width: 200px;
            min-height: 150px;
            margin: 0 auto 30px;
            padding: 0 20px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .mail-img-2 {
            max-width: 300px;
            min-height: 200px;
            margin: 0 auto 30px;
            padding: 0 20px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .mail-title {
            font-family: "Poppins", sans-serif;
            font-size: 24px;
            line-height: 1.5;
            font-weight: 600;
            color: #595959;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .mail-body {
            padding: 0 10px 15px;
        }

        .mail-body p {
            font-size: 18px;
            line-height: 28px;
            color: #797979;
            margin-bottom: 20px;
        }

        .mail-body .row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin: -10px -10px 0;
        }

        .mail-body .col-6 {
            width: calc(50% - 20px);
            padding: 0 10px;
            margin-top: 20px;
        }

        .mail-body .product-card {
            border: 1px solid #e4e4e4;
            border-radius: 5px;
            overflow: hidden;
        }

        .mail-body .product-card .part-img {
            aspect-ratio: 1/1;
            background: #f1f4fb;
            padding: 25px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            border-bottom: 1px solid #e4e4e4;
        }

        .mail-body .product-card .part-img img {
            vertical-align: middle;
        }

        .mail-body .product-card .part-txt {
            font-family: "Poppins", sans-serif;
            padding: 10px 15px;
        }

        .mail-body .product-card .product-title {
            display: block;
            font-size: 14px;
            line-height: 1.3;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .mail-body .product-card .product-title a {
            color: #595959;
        }

        .mail-body .product-card .product-price {
            display: block;
            font-size: 16px;
            line-height: 1;
            font-weight: 500;
            color: #797979;
        }

        .mail-footer {
            border-top: 1px solid #dfe9ff;
            /* background: #313131; */
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

            .mb-30 {
                margin-bottom: 15px !important;
            }

            .mail-primary-button {
                height: 35px;
                line-height: 35px;
                font-size: 14px;
                padding: 0 15px;
                border-radius: 5px;
            }

            .email-container .page-title {
                font-size: 20px;
                margin-bottom: 20px;
            }

            .logo {
                padding: 10px;
            }

            .logo img {
                max-width: 120px;
            }

            .mail-header {
                margin-bottom: 20px;
            }

            .mail-img {
                max-width: 120px;
                min-height: 70px;
                margin-bottom: 20px;
            }

            .mail-img-2 {
                max-width: 150px;
                min-height: 100px;
            }

            .mail-title {
                font-size: 16px;
                margin-bottom: 5px;
            }

            .mail-body {
                padding: 0 10px 5px;
            }

            .mail-body p {
                font-size: 14px;
                line-height: 24px;
                margin-bottom: 5px;
            }

            .mail-body .row {
                margin: -5px -5px 0;
            }

            .mail-body .col-6 {
                width: calc(50% - 10px);
                padding: 0 5px;
                margin-top: 10px;
            }

            .mail-body .product-card .part-img {
                padding: 15px;
            }

            .mail-body .product-card .part-txt {
                padding: 10px;
            }

            .mail-body .product-card .product-title {
                font-size: 12px;
                margin-bottom: 7px;
            }

            .mail-body .product-card .product-price {
                font-size: 14px;
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
            /* border: 1px solid #000; */
        }

        .email-container table th,
        .email-container table td {
            /* border: 1px solid #000; */
            padding: 8px;
            text-align: left;
        }

        .signature-row p {
            font-size: 12px;
        }

        .bottom_content {
            font-size: 11px;
            text-align: right;
        }

        table tr {
            font-size: 11px;
            font-weight: bold;
        }

        table {
            margin: 10px 0;
        }

        .customer {
            width: auto;
            float: left;
            margin-right: 20px;
            /* Add some space between elements */
        }

        .invoice {
            width: auto;
            float: left;
            margin-left: 138px;
            /* Add some space between elements */
            text-align: center;
        }

        .invoice2 {
            width: auto;
            float: left;
            margin-left: 216px;
            /* Add some space between elements */
            text-align: center;
        }

        .date {
            width: auto;
            float: right;
        }

        p {
            font-size: 12px;
        }
    </style>
</head>
@php
    $planPriceCurrency = \Modules\SAAS\Utils\PlanPriceCurrencySymbol::currencySymbol();
@endphp
<body>
    <div class="email-container">
        <h2 class="page-title">{{ __("Add Multi Store Management System Invoice") }}</h2>
        <table>
            <tbody>
                <tr>
                    <td>
                        <div class="mail-header">
                            <div class="logo">
                                <img style="height: height; width:auto;" src="{{ url('/') }}/assets/images/app_logo.png" alt="System Logo" class="logo__img">
                            </div>
                            <div class="content_customer" style="text-align: right;">
                                <span style="font-weight: bold;">{{ config('speeddigit.name') }}</span> <br>
                                {{ __("Address") }} : {{ config('speeddigit.address') }} <br>
                                <span style="font-weight: bold;">Phone:</span> {{ config('speeddigit.phone') }}, <span style="font-weight: bold;">Email:</span> {{ config('speeddigit.email') }}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mail-body">
                            <div class="customer">
                                <strong>{{ __("Customer") }}: {{ $user->name }}</strong><br>
                                {{ __("Address") }}: {{ $user->address }} <br>
                                {{ __("Phone") }}: {{ $user->phone }}<br>
                            </div>

                            <div class="invoice">
                                <strong>{{ __("INVOICE") }}</strong><br>
                            </div>

                            <div class="date">
                                {{ __("Date") }}: {{ date('Y-m-d') }}<br>
                            </div>

                            <br> <br> <br>
                            <table width="100%" border="1" cellspacing="0" cellpadding="5" style="margin: 10px 0; padding: 10px;">
                                <!-- Table header -->
                                <tr>
                                    <th>{{ __("Store/Company Name") }}</th>
                                    <th>{{ __("Price (As Per Plan)") }}</th>
                                    <th>{{ __("Price Period") }}</th>
                                    <th>{{ __("Price Period Count") }}</th>
                                    <th>{{ __("Subtotal") }}</th>
                                </tr>
                                <!-- Table data -->

                                <tr>
                                    <td>{{ __("Multi Store Management System") }}({{ __("Company") }})</td>
                                    <td>{{ \App\Utils\Converter::format_in_bdt($data['business_price']) }}</td>
                                    <td>{{ $data['business_price_period'] }}</td>
                                    <td>{{ $data['business_price_period_count'] != 'lifetime' ? $data['business_price_period_count'] : 'Lifetime' }}</td>
                                    <td>{{ \App\Utils\Converter::format_in_bdt($data['business_subtotal']) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="4" style="text-align: right;">Net Total Amount {{ $planPriceCurrency }}</th>
                                    <th>: {{ \App\Utils\Converter::format_in_bdt($data['net_total']) }}</th>
                                </tr>

                                <tr>
                                    <th colspan="4" style="text-align: right;">Discount</th>
                                    <th>: {{ $planPriceCurrency }} {{ \App\Utils\Converter::format_in_bdt($data['discount']) }}</th>
                                </tr>

                                <tr>
                                    <th colspan="4" style="text-align: right;">Tax:</th>
                                    <th>: {{ __("Free") }}</th>
                                </tr>

                                <tr>
                                    <th colspan="4" style="text-align: right;">Total Payable</th>
                                    <th>: {{ $planPriceCurrency }} {{ \App\Utils\Converter::format_in_bdt($data['total_payable']) }}</th>
                                </tr>

                                <tr>
                                    <th colspan="4" style="text-align: right;">Paid</th>
                                    <th>: {{ $planPriceCurrency }} {{ \App\Utils\Converter::format_in_bdt($data['total_payable']) }}</th>
                                </tr>

                                <tr>
                                    <th colspan="4" style="text-align: right;">Due</th>
                                    <th>: {{ $planPriceCurrency }} 0.00</th>
                                </tr>
                            </table>

                            <br><br><br>
                            <p style="text-align:center; margin-top:50px; font-size:16px;">Thanks for using our service</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="mail-footer">
                            <div class="logo"><img src="{{ url('/') }}/assets/logo/logo.png" alt="LOGO"></div>
                            <div class="footer-social">
                                <a href="{{ config('speeddigit.facebook') }}" title="Facebook" target="_blank"><img src="{{ url('/') }}/assets/social/facebook.png" alt="facebook"></a>
                                <a href="{{ config('speeddigit.Instagram') }}" title="Instagram" target="_blank"><img src="{{ url('/') }}/assets/social/instagram.png" alt="instagram"></a>
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

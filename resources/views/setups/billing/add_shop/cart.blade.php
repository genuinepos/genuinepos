<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>{{ __("Add Shop") }} - GPOS</title>

    @php
        $rtl  = app()->isLocale('ar');
    @endphp

    @if($rtl)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic&display=swap" rel="stylesheet">
        <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets/fontawesome6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <style>
        .error{
            font-size: 11px;
            color: red;
        }

        .tab-section .tab-nav .single-nav {
            height: 35px;
            font-size: 15px;
        }

        .def-btn {
            height: 40px;
            line-height: 40px;
            padding: 0 30px;
            font-size: 13px;
            cursor: pointer;
        }

        .tab-section .tab-contents .tab-next-btn {
            font-size: 13px;
            text-align: center;
        }

        .tab-section .tab-contents .billing-details .form-row {
            gap: 10px 20px;
        }

        .tab-section .tab-contents .billing-details .form-row .form-control {
            font-size: 14px;
            height: 35px;
            line-height: 33px;
            padding: 0 15px;
        }

        .domain-field span.txt {
            font-size: 17px;
        }

        .tab-section .tab-contents .billing-details .title {
            font-size: 16px;
        }

        .form-row .col-md-4 {
            width: 32%;
        }

        .col-md-8 {
            flex: 0 0 auto;
            width: 65.666667%;
        }

        label {
            font-size: 13px !important;
        }

        .tab-section .tab-nav {
            margin-bottom: 28px;
        }

        span.selection {
            width: 100%;
        }

        .select2-container .select2-selection--single {
            height: 35px;
            background: rgba(241, 241, 241, 0.5);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 33px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px;
        }

        .dropify-wrapper {
            height: 120px !important;
        }

        .tab-section .tab-contents .billing-details .form-row {
            margin-bottom: -10px;
        }

        .form-control {
            -webkit-appearance: listbox;
        }

        /* Startup form header style start */
        .startup-form-header .navigation {
            display: flex;
            align-items: center;
            /* position: fixed; */
            width: 100%;
            z-index: 99;
            padding: 0px;
            left: 0px;
        }

        .startup-form-header .panel__nav {
            background: linear-gradient(#444444, #0a0b0c);
            border-top: 1px solid #29b0fd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .top-menu {
            width: 100%;
        }

        .logo__sec {
            width: 142px;
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            float: left;
        }

        img.logo__img {
            width: auto !important;
            height: auto !important;
            max-height: 35px;
            max-width: 110px;
            vertical-align: middle;
        }

        .notify-menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .company-name {
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            padding-left: 15px;
        }

        .company-name p {
            font-size: 13px;
            line-height: 100%;
            font-weight: 500;
            color: #fff;
            margin-bottom: 4px;
        }

        .company-name span {
            display: block;
            font-size: 11px;
            line-height: 100%;
            color: #d1d1d1;
        }

        .company-name span strong {
            color: #e9e9e9;
        }

        .startup-form-header ul.head__cn {
            border-left: 1px solid #000000;
            display: flex;
        }

        .startup-form-header .top-icon {
            background: #0a0b0c;
        }

        .startup-form-header ul.head__cn li .nav-btn {
            background: linear-gradient(#424242, #000000) !important;
            border-left: 1px solid #424242;
            border-right: 1px solid #000000;

            display: flex !important;
            align-items: center;
            text-align: left;
            height: 45px !important;
            max-height: none;
            color: #fff;
            border: 0;
            border-radius: 0;
            padding: 0 10px;
            position: relative;
        }

        .startup-form-header ul.head__cn li a {
            color: #ffffff;
        }

        .startup-form-header ul.head__cn li a span {
            font-size: 11px;
            font-weight: 300;
        }
    </style>
</head>

@php
    $planPriceCurrency = \Modules\SAAS\Utils\PlanPriceCurrencySymbol::currencySymbol();
@endphp

<body class="inner">
    @include('setups.billing.partial.header', ['heading' => 'Add Shop'])
    <div class="tab-section py-120 mt-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tab-nav">
                        <button class="single-nav single-tab stepOneTab active" data-tab="stepOneTab">
                            <span class="txt">{{ __("Step One") }}</span>
                            <span class="sl-no">{{ __("01") }}</span>
                        </button>

                        <button class="single-nav single-tab stepTwoTab" data-tab="stepTwoTab">
                            <span class="txt">{{ __("Step Two") }}</span>
                            <span class="sl-no">{{ __("02") }}</span>
                        </button>

                        {{-- <button class="single-nav" data-tab="stepThreeTab" disabled>
                            <span class="txt">{{ __("Step Three") }}</span>
                            <span class="sl-no">{{ __("03") }}</span>
                        </button> --}}
                    </div>

                    <div class="tab-contents">
                        <form id="add_shop_form" action="{{ route('software.service.billing.add.shop.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_status" value="1">
                            <input type="hidden" name="payment_method_name" value="Cash-On-Delivery">
                            <input type="hidden" name="payment_trans_id" value="N/A">

                            <div class="single-tab active" id="stepOneTab">
                                @include('setups.billing.add_shop.partials.view_partials.step_one')
                            </div>

                            <div class="single-tab" id="stepTwoTab">
                                @include('setups.billing.add_shop.partials.view_partials.step_two')
                            </div>
                        </form>

                        <div class="single-tab" id="stepThreeTab">
                            <div class="check-icon">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                </svg>
                            </div>
                            <div class="order-complete-msg">
                                <h2>{{ __("Shop is added successfully.") }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout form for global -->
    <form id="logout_form" class="d-hide" action="{{ route('logout') }}" method="POST">@csrf</form>
    <!-- Logout form for global end -->

    <!-- js files -->
    <script src="{{asset('backend/js/jquery-1.7.1.min.js')}}"></script>
    <script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
    <script src="{{asset('backend/js/number-bdt-formater.js')}}"></script>
    @include('setups.billing.add_shop.partials.js_partial.js')
    <script>
        $(document).on('click', '#logout_option', function(e) {
            e.preventDefault();
            $.confirm({
                'title': 'Logout Confirmation',
                'content': 'Are you sure, you want to logout?',
                'buttons': {
                    'Yes': {
                        'btnClass': 'yes btn-modal-primary',
                        'action': function() {
                            $('#logout_form').submit();
                        }
                    },
                    'No': {
                        'btnClass': 'no btn-danger',
                        'action': function() {
                            console.log('Canceled.');
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>

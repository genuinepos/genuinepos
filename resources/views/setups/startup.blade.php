<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>{{ __('Startup') }}</title>

    @php
        $rtl = app()->isLocale('ar');
    @endphp

    @if ($rtl)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic&display=swap" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @endif

    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/cart.css') }}">
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">

    <style>
        .tab-section .tab-nav .single-nav {
            height: 35px;
            font-size: 15px;
        }

        .def-btn {
            height: 40px;
            line-height: 40px;
            padding: 0 30px;
            font-size: 13px;
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

        label {
            font-size: 13px !important;
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
    </style>
</head>

<body class="inner">
    <div class="tab-section py-120">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tab-nav">
                        <button class="single-nav active" data-tab="checkOutTab" disabled>
                            <span class="txt">{{ __('Business Setup') }}</span>
                            {{-- <span class="sl-no">01</span> --}}
                        </button>
                    </div>

                    <div class="tab-contents">
                        <div class="single-tab active" id="checkOutTab">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="billing-details business-setup">
                                        <h3 class="title">{{ __('Business Setup') }}</h3>
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label for="business_name">{{ __('Business Name') }} <span class="text-danger">*</span></label>
                                                <input required type="text" name="business_name" id="business_name" class="form-control" value="{{ $generalSettings['business_or_shop__business_name'] }}" placeholder="{{ __('Enter Business Name') }}">
                                                <span class="error error_business_name"></span>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="address">{{ __('Address') }} <span class="text-danger">*</span></label>
                                                <input required type="text" name="business_address" class="form-control" id="business_address" value="{{ $generalSettings['business_or_shop__address'] }}" placeholder="{{ __('Business Address') }}">
                                                <span class="error error_business_address"></span>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="phone">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                                <input required type="text" name="business_phone" class="form-control" id="business_phone" value="{{ $generalSettings['business_or_shop__phone'] }}" placeholder="{{ __('Business Phone') }}">
                                                <span class="error error_business_phone"></span>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label for="email">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                                <input required type="email" name="business_email" class="form-control" id="business_email" value="{{ $generalSettings['business_or_shop__email'] }}" placeholder="{{ __('Enter Email Address') }}">
                                                <span class="error error_business_email"></span>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="country">{{ __('Currency') }} <span class="text-danger">*</span></label>
                                                <select required name="business_currency_id" id="business_currency_id" class="form-control select wide select2">
                                                    <option value="" hidden="">{{ __('Select Currency') }}</option>
                                                    @foreach ($currencies as $currency)
                                                        <option data-currency_symbol="{{ $currency->symbol }}" {{ $generalSettings['business_or_shop__currency_id'] == $currency->id ? 'SELECTED' : '' }} value="{{ $currency->id }}">
                                                            {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="business_currency_symbol" id="business_currency_symbol" value="{{ $generalSettings['business_or_shop__currency_symbol'] }}">
                                                <span class="error error_business_currency_id"></span>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="country">{{ __('Stock Accounting Method') }}</label>
                                                <select required name="stock_accounting_method" id="stock_accounting_method" class="form-control select wide">
                                                    @foreach (App\Utils\Util::stockAccountingMethods() as $key => $item)
                                                        <option value="{{ $key }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label for="business_date_format">{{ __('Stock Accounting Method') }}</label>
                                                <select name="business_date_format" class="form-control" id="business_date_format">
                                                    <option value="d-m-Y">{{ __('DD-MM-YYYY') }} | {{ date('d-m-Y') }} </option>
                                                    <option value="m-d-Y">{{ __('MM-DD-YYYY') }} | {{ date('m-d-Y') }}</option>
                                                    <option value="Y-m-d">{{ __('YYYY-MM-DD') }} | {{ date('Y-m-d') }}</option>
                                                </select>
                                                <span class="error error_business_date_format"></span>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="time_format">{{ __('Time Format') }}</label>
                                                <select name="business_time_format" class="form-control" id="business_time_format">
                                                    <option value="12">{{ __('12 Hour') }}</option>
                                                    <option value="24">{{ __('24 Hour') }}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="business_timezone">{{ __('Time Zone') }} <span class="text-danger">*</span></label>
                                                <select name="business_timezone" class="form-control select2" id="business_timezone">
                                                    <option value="">{{ __('Time Zone') }}</option>
                                                    @foreach ($timezones as $key => $timezone)
                                                        <option {{ ($generalSettings['business_or_shop__timezone'] ?? 'Asia/Dhaka') == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $timezone }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_business_timezone"></span>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label for="business_account_start_date">{{ __('Account Start Date') }} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" name="business_account_start_date" class="form-control" id="business_account_start_date" autocomplete="off" value="{{ $generalSettings['business_or_shop__account_start_date'] }}">
                                                </div>
                                                <span class="error error_business_account_start_date"></span>
                                            </div>


                                            <div class="col-md-4">
                                                <label for="business_financial_year_start_month">{{ __('Financial Year Start Month') }}</label>
                                                <div class="input-group">
                                                    <select name="business_financial_year_start_month" id="business_financial_year_start_month" class="form-control select2">
                                                        @php
                                                            $months = \App\Enums\Months::cases();
                                                        @endphp
                                                        @foreach ($months as $month)
                                                            <option {{ $month->value == $generalSettings['business_or_shop__financial_year_start_month'] ? 'SELECTED' : '' }} value="{{ $month->value }}">{{ $month->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label>{{ __('Current Financial Year') }} <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input readonly type="text" class="form-control fw-bold" id="current_financial_year" autocomplete="off" value="{{ $generalSettings['business_or_shop__financial_year'] }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>{{ __('Business Logo') }} <small class="text-danger">{{ __('Recommended Size : H : 40px; W: 110px;') }}</small></label>
                                                <input type="file" name="business_logo" id="business_logo" data-allowed-file-extensions="png jpeg jpg gif">
                                                <span class="error error_business_logo"></span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="def-btn palce-order tab-next-btn btn-success float-end" id="palceOrder">Place Order <i class="fa-light fa-truck-arrow-right"></i></button>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--------------------------------- CART SECTION END --------------------------------->

    <!-- js files -->
    <script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
    <script src="{{ asset('backend/asset/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/dropify/js/dropify.min.js') }}"></script>
    <script>
        $('#business_logo').dropify({
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong happended.'
            }
        });

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</body>

</html>

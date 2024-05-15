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
                            <option data-currency_symbol="{{ $currency->symbol }}" {{ auth()->user()->currency_id == $currency->id ? 'SELECTED' : '' }} value="{{ $currency->id }}">
                                {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="business_currency_symbol" id="business_currency_symbol" value="{{ auth()?->user()?->currency?->symbol }}">
                    <span class="error error_business_currency_id"></span>
                </div>

                <div class="col-md-4">
                    <label for="country">{{ __('Stock Accounting Method') }}</label>
                    <select required name="business_stock_accounting_method" id="business_stock_accounting_method" class="form-control select wide">
                        @foreach (App\Utils\Util::stockAccountingMethods() as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="business_date_format">{{ __('Date Format') }}</label>
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
                    <select required name="business_timezone" class="form-control select2" id="business_timezone">
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
                        <input type="text" name="business_account_start_date" class="form-control" id="business_account_start_date" value="{{ date('Y-m-d') }}" autocomplete="off">
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
                        <input readonly type="text" class="form-control fw-bold" id="current_financial_year" value="{{ $generalSettings['business_or_shop__financial_year'] }}" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label>{{ __('Business Logo') }} <small class="text-danger">{{ __('Recommended Size : H : 60px; W: 200px;') }}</small></label>
                    <input type="file" name="business_logo" id="business_logo" data-allowed-file-extensions="png jpeg jpg gif">
                    <span class="error error_business_logo"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    @if (isset($onlyBusinessSetup))
                        <button type="button" class="def-btn btn-secondary float-end bg-secondary text-white submit_blue_btn d-none">{{ __('Finish') }}</button>
                        <button type="submit" class="def-btn btn-success float-end submit_button">{{ __('Finish') }}</button>
                        <button type="button" class="btn loading_button float-end d-none"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>
                    @else
                        <a class="def-btn tab-next-btn float-end" id="single-nav" data-tab="createBranchTab">{{ __('Next Step') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

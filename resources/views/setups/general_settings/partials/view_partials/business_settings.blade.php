<form id="business_settings_form" class="setting_form p-2" action="{{ route('settings.business.settings') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Company Settings') }}</h6>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Company Name') }} <span class="text-danger">*</span></label>
            <input required type="text" name="business_name" class="form-control" value="{{ $generalSettings['business_or_shop__business_name'] }}" placeholder="{{ __('Company Name') }}" autocomplete="off">
            <span class="error error_business_name"></span>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Address') }} <span class="text-danger">*</span></label>
            <input required type="text" name="address" class="form-control" value="{{ $generalSettings['business_or_shop__address'] }}" placeholder="{{ __('Company address') }}" autocomplete="off">
            <span class="error error_address"></span>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Phone') }} <span class="text-danger">*</span></label>
            <input required type="text" name="phone" class="form-control" placeholder="{{ __('Company phone number') }}" value="{{ $generalSettings['business_or_shop__phone'] }}">
            <span class="error error_phone"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Email') }} <span class="text-danger">*</span></label>
            <input required type="text" name="email" class="form-control" placeholder="Company email address" value="{{ $generalSettings['business_or_shop__email'] }}">
            <span class="error error_email"></span>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Default Profit') }}(%)</label>
            <input type="number" name="default_profit" class="form-control" autocomplete="off" data-name="Default profit" id="default_profit" value="{{ $generalSettings['business_or_shop__default_profit'] }}">
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Currency') }} <span class="text-danger">*</span></label>
            <select required name="currency_id" class="form-control select2" id="currency_id">
                @foreach ($currencies as $currency)
                    <option @selected($generalSettings['business_or_shop__currency_id'] == $currency->id) data-currency_symbol="{{ $currency->symbol }}" value="{{ $currency->id }}">
                        {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="currency_symbol" id="currency_symbol" value="{{ $generalSettings['business_or_shop__currency_symbol'] }}">
            <span class="error error_currency_id"></span>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Stock Accounting Method') }} <span class="text-danger">*</span></label>
            <select name="stock_accounting_method" class="form-control" data-name="Stock Accounting Method" id="stock_accounting_method">
                @php
                    $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'] ?? null;
                @endphp
                @foreach (\App\Enums\StockAccountingMethod::cases() as $item)
                    <option @selected($stockAccountingMethod == $item->value) value="{{ $item->value }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Date Format') }} <span class="text-danger">*</span></label>
            <select required name="date_format" class="form-control" data-name="Date format" id="date_format">
                <option @selected($generalSettings['business_or_shop__date_format'] == 'd-m-Y') value="d-m-Y">{{ __('DD-MM-YYYY') }} | {{ date('d-m-Y') }} </option>
                <option @selected($generalSettings['business_or_shop__date_format'] == 'm-d-Y') value="m-d-Y">{{ __('MM-DD-YYYY') }} | {{ date('m-d-Y') }}</option>
                <option @selected($generalSettings['business_or_shop__date_format'] == 'Y-m-d') value="Y-m-d">{{ __('YYYY-MM-DD') }} | {{ date('Y-m-d') }}</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Time Format') }} <span class="text-danger">*</span></label>
            <select required name="time_format" class="form-control" data-name="Time format" id="time_format">
                <option value="12">{{ __('12 Hour') }}</option>
                <option @selected($generalSettings['business_or_shop__time_format'] == '24') value="24">{{ __('24 Hour') }}</option>
            </select>
            <span class="error error_time_format"></span>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Time Zone') }} <span class="text-danger">*</span> {{ now()->format('Y-m-d') }}</label>
            <select required name="timezone" class="form-control select2" data-name="Time format" id="time_format">
                <option value="">{{ __('Time Zone') }}</option>
                @foreach ($timezones as $key => $timezone)
                    <option @selected(($generalSettings['business_or_shop__timezone'] ?? 'Asia/Dhaka') == $key) value="{{ $key }}">{{ $timezone }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Account Start Date') }} <span class="text-danger">*</span></label>
            <div class="input-group">
                <input required type="text" name="account_start_date" class="form-control" id="account_start_date" autocomplete="off" value="{{ $generalSettings['business_or_shop__account_start_date'] }}">
            </div>
            <span class="error error_account_start_date"></span>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Financial Year Start Month') }} <span class="text-danger">*</span></label>
            <div class="input-group">
                <select name="financial_year_start_month" id="financial_year_start_month" class="form-control select2">
                    @php
                        $months = \App\Enums\Months::cases();
                    @endphp
                    @foreach ($months as $month)
                        <option @selected($month->value == $generalSettings['business_or_shop__financial_year_start_month']) value="{{ $month->value }}">{{ $month->name }}</option>
                    @endforeach
                </select>
            </div>
            <span class="error error_financial_year_start_month"></span>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Current Financial Year') }} <span class="text-danger">*</span></label>
            <div class="input-group">
                <input readonly type="text" class="form-control fw-bold" id="current_financial_year" autocomplete="off" value="{{ $generalSettings['business_or_shop__financial_year'] }}">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-8">
            <label class="fw-bold">{{ __('Company Logo') }} <small class="red-label-notice">{{ __('Recommended Size : H : 40px; W: 100px;') }}</small></label>
            <input type="file" class="form-control" name="business_logo" id="business_logo" @if ($generalSettings['business_or_shop__business_logo']) data-default-file="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" @endif>
            <span class="error error_business_logo"></span>
            <a href="#" class="btn btn-sm btn-danger mt-1" id="deleteBusinessLogo">{{ __('Remove Company Logo') }}</a>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button business_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>

<form id="delete_business_logo_form" action="{{ route('settings.business.logo.delete') }}">
    @csrf
    @method('DELETE')
</form>

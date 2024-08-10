<form id="branch_settings_form" class="setting_form p-2" action="{{ route('branches.update', $branch->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Store Settings') }}</h6>
        </div>
    </div>

    <input type="hidden" name="branch_type" value="{{ $branch->branch_type }}">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Store Name') }} <span class="text-danger">*</span></label>
                    <input {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'readonly' : 'required' }} type="text" name="name" class="form-control" id="name" value="{{ $branch?->name ? $branch->name : $branch?->parentBranch?->name }}" placeholder="{{ __('Store Name') }}" />
                    <span class="error error_branch_name"></span>
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Area Name') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="area_name" class="form-control" id="area_name" value="{{ $branch->area_name }}" placeholder="{{ __('Area Name') }}" />
                    <span class="error error_code"></span>
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Store ID') }}</label>
                    <input readonly type="text" name="branch_code" class="form-control fw-bold" id="branch_code" value="{{ $branch->branch_code }}" placeholder="{{ __('Store ID') }}" />
                    <span class="error error_branch_code"></span>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Phone') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="phone" class="form-control" id="phone" value="{{ $branch->phone }}" placeholder="{{ __('Phone No') }}" />
                    <span class="error error_phone"></span>
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Alternative Phone') }}</label>
                    <input type="text" name="alternate_phone_number" class="form-control" id="alternate_phone_number" value="{{ $branch->alternate_phone_number }}" placeholder="{{ __('Alternative Phone') }}" />
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Business Indentification No.') }}</label>
                    <input type="text" name="bin" class="form-control" id="bin" value="{{ $branch->bin }}" placeholder="{{ __('Business Indentification No.') }}" />
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Vat/Tax No.') }}</label>
                    <input type="text" name="tin" class="form-control" id="tin" value="{{ $branch->tin }}" placeholder="{{ __('Alternative Phone') }}" />
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Country') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="country" class="form-control" id="country" value="{{ $branch->country }}" placeholder="{{ __('Country') }}" />
                    <span class="error error_country"></span>
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('State') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="state" class="form-control" id="state" value="{{ $branch->state }}" placeholder="{{ __('State') }}" />
                    <span class="error error_state"></span>
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('City') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="city" class="form-control" id="city" value="{{ $branch->city }}" placeholder="{{ __('City') }}" />
                    <span class="error error_city"></span>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Zip-Code') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="zip_code" class="form-control" id="zip_code" value="{{ $branch->zip_code }}" placeholder="Zip code" />
                    <span class="error error_code"></span>
                </div>

                <div class="col-lg-8 col-md-6">
                    <label class="fw-bold">{{ __('Address') }}</label>
                    <input required type="text" name="address" class="form-control" id="address" value="{{ $branch->address }}" placeholder="{{ __('Address') }}" />
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Email') }}</label>
                    <input type="text" name="email" class="form-control" id="email" value="{{ $branch->email }}" placeholder="{{ __('Email Address') }}" />
                </div>

                <div class="col-lg-4 col-md-6">
                    <label class="fw-bold">{{ __('Website') }}</label>
                    <input type="text" name="website" class="form-control" id="website" value="{{ $branch->website }}" placeholder="{{ __('Website Url') }}" />
                </div>
            </div>

            <div class="row mt-1">
                @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                    <div class="col-lg-8 col-md-6">
                        <label class="fw-bold">{{ __('Logo') }} <small class="text-danger" style="font-size: 9px;">{{ __('Req. size H: 40px * W: 100px') }}</small></label>
                        <input type="file" name="logo" class="form-control" id="logo" @if ($branch->logo) data-default-file="{{ file_link('branchLogo', $branch?->logo) }}" @endif />
                        <a href="#" class="btn btn-sm btn-danger mt-1" id="deleteBranchLogo">{{ __('Remove Store Logo') }}</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-3" style="border-left: 1px solid #000;">
            <div class="row mt-1">
                <div class="col-md-12">
                    <label class="fw-bold">{{ __('Date Format') }}</label>
                    <select name="date_format" class="form-control" id="date_format">
                        <option value="d-m-Y" {{ $branch->date_format == 'd-m-Y' ? 'SELECTED' : '' }}>{{ __('DD-MM-YYYY') }} | {{ date('d-m-Y') }}</option>
                        <option value="m-d-Y" {{ $branch->date_format == 'm-d-Y' ? 'SELECTED' : '' }}>{{ __('MM-DD-YYYY') }} | {{ date('m-d-Y') }}</option>
                        <option value="Y-m-d" {{ $branch->date_format == 'Y-m-d' ? 'SELECTED' : '' }}>{{ __('YYYY-MM-DD') }} | {{ date('Y-m-d') }}</option>
                    </select>
                    <span class="error error_date_format"></span>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-12">
                    <label class="fw-bold">{{ __('Time Format') }}</label>
                    <select name="time_format" class="form-control" id="time_format">
                        <option {{ $generalSettings['business_or_shop__time_format'] == '12' ? 'SELECTED' : '' }} value="12">{{ __('12 Hour') }}</option>
                        <option {{ $generalSettings['business_or_shop__time_format'] == '24' ? 'SELECTED' : '' }} value="24">{{ __('24 Hour') }}</option>
                    </select>
                    <span class="error error_time_format"></span>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-12">
                    <label class="fw-bold">{{ __('Time Zone') }} <span class="text-danger">*</span> {{ now()->format('Y-m-d') }}</label>
                    <select required name="timezone" class="form-control select2" id="timezone">
                        <option value="">{{ __('Time Zone') }}</option>
                        @foreach ($timezones as $key => $timezone)
                            <option {{ ($generalSettings['business_or_shop__timezone'] ?? 'Asia/Dhaka') == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $timezone }}</option>
                        @endforeach
                    </select>
                    <span class="error error_time_format"></span>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-12">
                    <label class="fw-bold">{{ __('Currency') }} <span class="text-danger">*</span></label>
                    <select name="currency_id" class="form-control select2" id="currency_id">
                        @foreach ($currencies as $currency)
                            <option data-currency_symbol="{{ $currency->symbol }}" {{ $generalSettings['business_or_shop__currency_id'] == $currency->id ? 'SELECTED' : '' }} value="{{ $currency->id }}">
                                {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="currency_symbol" id="currency_symbol" value="{{ $generalSettings['business_or_shop__currency_symbol'] }}">
                    <span class="error error_currency_id"></span>
                </div>
            </div>

            @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                <div class="row mt-1" id="stock_accounting_method_field">
                    <div class="col-md-12">
                        <label class="fw-bold">{{ __('Stock Accounting Method') }}</label>
                        <select name="stock_accounting_method" class="form-control" id="stock_accounting_method">
                            @php
                                $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'] ?? null;
                            @endphp
                            @foreach (\App\Enums\StockAccountingMethod::cases() as $item)
                                <option @selected($stockAccountingMethod == $item->value) value="{{ $item->value }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <span class="error error_financial_year_start"></span>
                    </div>
                </div>

                <div class="form-group row mt-1" id="account_start_date_field">
                    <div class="col-md-12">
                        <label class="fw-bold">{{ __('Account Start Date') }} <span class="text-danger">*</span></label>
                        <input type="text" name="account_start_date" class="form-control" id="account_start_date" value="{{ $generalSettings['business_or_shop__account_start_date'] }}" autocomplete="off">
                        <span class="error error_account_start_date"></span>
                    </div>
                </div>

                <div class="form-group row mt-1" id="financial_year_start_month_field">
                    <div class="col-md-12">
                        <label class="fw-bold">{{ __('Financial Year Start Month') }}</label>
                        <div class="input-group">
                            <select name="financial_year_start_month" id="financial_year_start_month" class="form-control select2">
                                @php
                                    $months = \App\Enums\Months::cases();
                                @endphp
                                @foreach ($months as $month)
                                    <option {{ $generalSettings['business_or_shop__financial_year_start_month'] == $month->value ? 'SELECTED' : '' }} value="{{ $month->value }}">{{ $month->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="error error_financial_year_start_month"></span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button branch_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>

<form id="delete_branch_logo_form" action="{{ route('branches.logo.delete', $branch->id) }}">
    @csrf
    @method('DELETE')
</form>

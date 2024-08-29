<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="billing-details create-branch">
            <h3 class="title">{{ __('Create Store') }}</h3>
            <input type="hidden" name="branch_type" value="1">
            <input type="hidden" name="parent_branch_id">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="phone">{{ __('Store Category') }} <span class="text-danger">*</span></label>
                    <select required name="branch_category" class="form-control select2" id="branch_category" data-next="branch_name">
                        <option value="">{{ __('Select Store Category') }}</option>
                        @foreach (\App\Enums\BranchCategory::cases() as $category)
                            <option value="{{ $category->value }}">{{ str($category->name)->headline() }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="phone">{{ __('Store ID') }} <span class="text-danger">*</span></label>
                    <input required readonly type="text" name="branch_code" class="form-control fw-bold" id="branch_code" value="01">
                    <span class="error error_branch_id"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="branch_name">{{ __('Store Name') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="branch_name" id="branch_name" class="form-control" placeholder="{{ __('Enter Store Name') }}" autocomplete="off">
                    <span class="error error_branch_name"></span>
                </div>

                <div class="col-md-4">
                    <label for="address">{{ __('Area Name') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="branch_area_name" class="form-control" id="business_address" placeholder="{{ __('Store Area Name') }}" autocomplete="off">
                    <span class="error error_branch_area_name"></span>
                </div>

                <div class="col-md-4">
                    <label for="phone">{{ __('Phone No.') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="branch_phone" class="form-control" id="branch_phone" placeholder="{{ __('Store Phone No') }}">
                    <span class="error error_branch_id"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="branch_alternative_phone">{{ __('Alternative Phone') }}</label>
                    <input type="text" name="branch_alternative_phone" class="form-control" id="branch_alternative_phone" placeholder="{{ __('Alternative Phone') }}">
                    <span></span>
                </div>

                <div class="col-md-4">
                    <label for="branch_bin">{{ __('Business Indentification No.') }}</label>
                    <input type="text" name="branch_bin" class="form-control" id="branch_bin" placeholder="{{ __('Business Indentification No.') }}">
                </div>

                <div class="col-md-4">
                    <label for="branch_bin">{{ __('Vat/Tax No.') }}</label>
                    <input type="text" name="branch_tin" class="form-control" id="branch_tin" placeholder="{{ __('Vat/Tax No.') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="branch_country">{{ __('Country') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="branch_country" class="form-control" id="branch_country" placeholder="{{ __('Country') }}">
                    <span class="error error_branch_country"></span>
                </div>

                <div class="col-md-4">
                    <label for="branch_state">{{ __('State') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="branch_state" class="form-control" id="branch_state" placeholder="{{ __('State') }}">
                    <span class="error error_branch_state"></span>
                </div>

                <div class="col-md-4">
                    <label for="branch_city">{{ __('City') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="branch_city" class="form-control" id="branch_city" placeholder="{{ __('City') }}">
                    <span class="error error_branch_city"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="branch_zip_code">{{ __('Zip-code') }} <span class="text-danger">*</span></label>
                    <input required type="text" name="branch_zip_code" class="form-control" id="branch_zip_code" placeholder="{{ __('Zip-code') }}">
                    <span class="error error_branch_country"></span>
                </div>

                <div class="col-md-8">
                    <label for="branch_address">{{ __('Address') }}</label>
                    <input type="text" name="branch_address" class="form-control" id="branch_address" placeholder="{{ __('Address') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="branch_email">{{ __('Email Address') }}</label>
                    <input type="email" name="branch_email" class="form-control" id="branch_email" placeholder="{{ __('Store Email Address') }}">
                    <span></span>
                </div>

                <div class="col-md-4">
                    <label for="branch_website">{{ __('Website') }}</label>
                    <input type="text" name="branch_website" class="form-control" id="branch_website" placeholder="{{ __('Store Website') }}">
                </div>

                <div class="col-md-4">
                    <label>{{ __('Store Logo') }} <small class="text-danger">{{ __('Recommended Size : H : 60px; W: 200px;') }}</small></label>
                    <input type="file" name="branch_logo" id="branch_logo" data-allowed-file-extensions="png jpeg jpg gif">
                    <span class="error error_branch_logo"></span>
                </div>
            </div>

            <h3 class="title">{{ __('Store Settings') }}</h3>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="branch_date_format">{{ __('Date Format') }}</label>
                    <select name="branch_date_format" class="form-control" id="branch_date_format">
                        <option value="d-m-Y">{{ __('DD-MM-YYYY') }} | {{ date('d-m-Y') }} </option>
                        <option value="m-d-Y">{{ __('MM-DD-YYYY') }} | {{ date('m-d-Y') }}</option>
                        <option value="Y-m-d">{{ __('YYYY-MM-DD') }} | {{ date('Y-m-d') }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="branch_time_format">{{ __('Time Format') }}</label>
                    <select name="branch_time_format" class="form-control" id="branch_time_format">
                        <option value="12">{{ __('12 Hour') }}</option>
                        <option value="24">{{ __('24 Hour') }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="branch_timezone">{{ __('Time Zone') }} <span class="text-danger">*</span></label>
                    <select required name="branch_timezone" class="form-control select2" id="branch_timezone">
                        <option value="">{{ __('Time Zone') }}</option>
                        @foreach ($timezones as $key => $timezone)
                            <option {{ ($generalSettings['business_or_shop__timezone'] ?? 'Asia/Dhaka') == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $timezone }}</option>
                        @endforeach
                    </select>
                    <span class="error error_branch_timezone"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="branch_stock_accounting_method">{{ __('Stock Accounting Method') }}</label>
                    <select required name="branch_stock_accounting_method" id="branch_stock_accounting_method" class="form-control select wide">
                        @foreach (\App\Enums\StockAccountingMethod::cases() as $item)
                            <option value="{{ $item->value }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="branch_financial_year_start_month">{{ __('Financial Year Start Month') }}</label>
                    <div class="input-group">
                        <select name="branch_financial_year_start_month" id="branch_financial_year_start_month" class="form-control select2">
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
                    <label for="branch_account_start_date">{{ __('Account Start Date') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" name="branch_account_start_date" class="form-control" id="branch_account_start_date" value="{{ date('Y-m-d') }}" autocomplete="off">
                    </div>
                    <span class="error error_branch_account_start_date"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <label for="branch_currency_id">{{ __('Currency') }} <span class="text-danger">*</span></label>
                    <select required name="branch_currency_id" id="branch_currency_id" class="form-control select wide select2">
                        <option value="" hidden="">{{ __('Select Currency') }}</option>
                        @foreach ($currencies as $currency)
                            <option data-currency_symbol="{{ $currency->symbol }}" {{ auth()->user()->currency_id == $currency->id ? 'SELECTED' : '' }} value="{{ $currency->id }}">
                                {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="branch_currency_symbol" id="branch_currency_symbol" value="{{ auth()?->user()?->currency?->symbol }}">
                    <span class="error error_branch_currency_id"></span>
                </div>

                <div class="col-md-4">
                    <label for="branch_auto_repayment_sales_and_purchase_return">{{ __('Auto Repay: Due Sales/P.Returns (Receipt)') }}</label>
                    <select required name="branch_auto_repayment_sales_and_purchase_return" class="form-control" id="branch_auto_repayment_sales_and_purchase_return">
                        <option value="0">{{ __("No") }}</option>
                        <option value="1">{{ __("Yes") }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="branch_auto_repayment_purchase_and_sales_return">{{ __('Auto Repay: Purchases/S.Returns (Payment)') }}</label>
                    <select required name="branch_auto_repayment_purchase_and_sales_return" class="form-control" id="branch_auto_repayment_purchase_and_sales_return">
                        <option value="0">{{ __("No") }}</option>
                        <option value="1">{{ __("Yes") }}</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <a class="btn btn-sm btn-success" id="add_initial_user_btn">{{ __('Add Store User') }}</a>
                    <input type="hidden" name="add_initial_user" id="add_initial_user" value="0">
                </div>
            </div>

            <div class="branch_initial_user_field d-none">
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="branch_user_first_name">{{ __('First Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="branch_user_first_name" class="form-control branch-user-required-field" id="branch_user_first_name" placeholder="{{ __('Store User: First name') }}" autocomplete="off">
                        <span class="error error_branch_user_first_name"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="branch_user_last_name">{{ __('Last Name') }}</label>
                        <input type="text" name="branch_user_last_name" class="form-control" id="branch_user_last_name" placeholder="{{ __('Store User: Last Name') }}" autocomplete="off">
                    </div>

                    <div class="col-md-4">
                        <label for="branch_user_phone">{{ __('Phone No') }} <span class="text-danger">*</span></label>
                        <input type="text" name="branch_user_phone" class="form-control branch-user-required-field" id="branch_user_phone" placeholder="{{ __('Store User: Phone No.') }}" autocomplete="off">
                        <span class="error error_branch_user_phone"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-4">
                        <label for="branch_user_email">{{ __('Email') }} <span class="text-danger">*</span></label>
                        <input type="email" name="branch_user_email" class="form-control branch-user-required-field" id="branch_user_email" placeholder="{{ __('Store User: Email') }}" autocomplete="off">
                        <span class="error error_branch_user_email"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="branch_user_username">{{ __('Username') }} <span class="text-danger">*</span></label>
                        <input type="text" name="branch_user_username" class="form-control branch-user-required-field" id="branch_user_username" placeholder="{{ __('Store User: Username') }}" autocomplete="off">
                        <span class="error error_branch_user_username"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="role_id">{{ __('Role Permission') }} <span class="text-danger">*</span> </label>
                        <select name="role_id" class="form-control branch-user-required-field" id="role_id">
                            <option value="">{{ __('Select Role Permission') }}</option>
                            @foreach ($roles as $role)
                                @if ($role->name != 'superadmin' && $role->name != 'admin')
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="error error_role_id"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-4">
                        <label for="password">{{ __('Password') }}</label>
                        <input type="text" name="password" class="form-control branch-user-required-field" id="password" placeholder="{{ __('Password') }}" autocomplete="off">
                        <span class="error error_password"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="password_confirmation">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                        <input type="text" name="password_confirmation" class="form-control branch-user-required-field" id="password_confirmation" placeholder="{{ __('Confirm Password') }}" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="def-btn btn-secondary float-end bg-secondary text-white submit_blue_btn d-none">{{ __('Finish') }}</button>
                    <button type="submit" class="def-btn btn-success float-end submit_button">{{ __('Finish') }}</button>
                    <a class="def-btn tab-next-btn float-end me-1" id="single-nav" data-tab="businessSetupTab">{{ __('Previous Step') }}</a>
                    <button type="button" class="btn loading_button float-end d-none"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __('Add Store') }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="add_branch_form" action="{{ route('branches.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-9" style="border-right: 1px solid #000;">
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Store Type') }}</label>
                                <select onchange="changeBranchType(this);" name="branch_type" class="form-control" id="branch_type" data-next="branch_category">
                                    @foreach (\App\Enums\BranchType::cases() as $branchType)
                                        <option value="{{ $branchType->value }}">{{ preg_replace('/[A-Z]/', ' ' . "$0", $branchType->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-6 branch_category_field">
                                <label class="fw-bold">{{ __('Category') }} <span class="text-danger">*</span></label>
                                <select required name="category" class="form-control" id="branch_category" data-next="branch_name">
                                    <option value="">{{ __('Select Store Category') }}</option>
                                    @foreach (\App\Enums\BranchCategory::cases() as $category)
                                        <option value="{{ $category->value }}">{{ str($category->name)->headline() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-6 parent_branches_field d-hide">
                                <label class="fw-bold">{{ __('Parent Store') }} <span class="text-danger">*</span></label>
                                <select onchange="getBranchCode(this);" name="parent_branch_id" class="form-control" id="branch_parent_branch_id" data-next="area_name">
                                    <option value="">{{ __('Select Parent Store') }}</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name . '/' . $branch->branch_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6 branch_name_field">
                                <label class="fw-bold">{{ __('Store Name') }} <span class="text-danger">*</span></label>
                                <input required type="text" name="name" class="form-control" id="branch_name" data-next="branch_area_name" placeholder="{{ __('Store Name') }}" />
                                <span class="error error_branch_name"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Area Name') }} <span class="text-danger">*</span></label>
                                <input required type="text" name="area_name" class="form-control" id="branch_area_name" data-next="branch_phone" placeholder="{{ __('Area Name') }}" />
                                <span class="error error_branch_area_name"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                <input required type="text" name="phone" class="form-control" data-name="Phone number" id="branch_phone" data-next="branch_alternate_phone_number" placeholder="{{ __('Phone No') }}" autocomplete="off" />
                                <span class="error error_branch_phone"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Store ID') }} <span class="text-danger">*</span></label>
                                <input readonly required type="text" name="branch_code" class="form-control fw-bold" id="branch_code" data-next="branch_phone" value="{{ $branchCode }}" placeholder="{{ __('Store ID') }}" autocomplete="off" />
                                <span class="error error_branch_code"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Alternative Phone') }} </label>
                                <input type="text" name="alternate_phone_number" class="form-control" id="branch_alternate_phone_number" data-next="branch_bin" placeholder="{{ __('Alternative Phone') }}" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Business Indentification No.') }} </label>
                                <input type="text" name="bin" class="form-control" id="branch_bin" data-next="branch_tin" placeholder="{{ __('Business Indentification Number') }}" autocomplete="off" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Vat/Tax No.') }} </label>
                                <input type="text" name="tin" class="form-control" id="branch_tin" data-next="branch_country" placeholder="{{ __('Vat/Tax Number') }}" autocomplete="off" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Country') }} <span class="text-danger">*</span></label>
                                <input required type="text" name="country" class="form-control" id="branch_country" data-next="branch_state" placeholder="{{ __('Country') }}" />
                                <span class="error error_branch_country"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('State') }} <span class="text-danger">*</span></label>
                                <input required type="text" name="state" class="form-control" id="branch_state" data-next="branch_city" placeholder="{{ __('State') }}" />
                                <span class="error error_branch_state"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('City') }} <span class="text-danger">*</span></label>
                                <input required type="text" name="city" class="form-control" id="branch_city" data-next="branch_zip_code" placeholder="{{ __('City') }}" />
                                <span class="error error_branch_city"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Zip-Code') }} <span class="text-danger">*</span></label>
                                <input required type="text" name="zip_code" class="form-control" id="branch_zip_code" data-next="branch_address" placeholder="Zip code" />
                                <span class="error error_branch_zip_code"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-6 col-md-6">
                                <label class="fw-bold">{{ __('Address') }} <span class="text-danger">*</span></label>
                                <input required type="text" name="address" class="form-control" id="branch_address" data-next="branch_email" placeholder="{{ __('Address') }}" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Email') }}</label>
                                <input type="text" name="email" class="form-control" id="branch_email" data-next="branch_website" placeholder="{{ __('Email address') }}" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Website') }}</label>
                                <input type="text" name="website" class="form-control" id="branch_website" data-next="branch_date_format" placeholder="{{ __('Website Url') }}" />
                            </div>

                            <div class="col-lg-6 col-md-6 branch_log_field">
                                <label class="fw-bold">{{ __('Logo') }} <small class="text-danger" style="font-size: 9px;">{{ __('Req. size H:40px * W:100px') }}</small></label>
                                <input type="file" name="logo" class="form-control " id="logo" />
                                <span class="error error_branch_logo"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Date Format') }}</label>
                                <select name="date_format" class="form-control" id="branch_date_format" data-next="branch_time_format">
                                    <option value="d-m-Y" {{ $generalSettings['business_or_shop__date_format'] == 'd-m-Y' ? 'SELECTED' : '' }}>{{ date('d-m-Y') }} | {{ __('DD-MM-YYYY') }}</option>
                                    <option value="m-d-Y" {{ $generalSettings['business_or_shop__date_format'] == 'm-d-Y' ? 'SELECTED' : '' }}>{{ date('m-d-Y') }} | {{ __('MM-DD-YYYY') }}</option>
                                    <option value="Y-m-d" {{ $generalSettings['business_or_shop__date_format'] == 'Y-m-d' ? 'SELECTED' : '' }}>{{ date('Y-m-d') }} | {{ __('YYYY-MM-DD') }}</option>
                                </select>
                                <span class="error error_date_format"></span>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Time Format') }}</label>
                                <select name="time_format" class="form-control" id="branch_time_format" data-next="branch_timezone">
                                    <option value="12">{{ __('12 Hour') }}</option>
                                    <option value="24">{{ __('24 Hour') }}</option>
                                </select>
                                <span class="error error_time_format"></span>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Time Zone') }} <span class="text-danger">*</span> {{ now()->format('Y-m-d') }}</label>
                                <select required name="timezone" class="form-control" id="branch_timezone" data-next="branch_stock_accounting_method">
                                    <option value="">{{ __('Time Zone') }}</option>
                                    @foreach ($timezones as $key => $timezone)
                                        <option {{ ($generalSettings['business_or_shop__timezone'] ?? 'Asia/Dhaka') == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $timezone }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_time_format"></span>
                            </div>
                        </div>

                        <div class="row mt-1" id="stock_accounting_method_field">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Stock Accounting Method') }}</label>
                                <select name="stock_accounting_method" class="form-control" id="branch_stock_accounting_method" data-next="branch_account_start_date">
                                    @foreach (\App\Enums\StockAccountingMethod::cases() as $item)
                                        <option value="{{ $item->value }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_financial_year_start"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1" id="account_start_date_field">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Account Start Date') }} <span class="text-danger">*</span></label>
                                <input type="text" name="account_start_date" class="form-control" id="branch_account_start_date" value="{{ date('Y-m-d') }}" data-next="branch_financial_year_start_month" autocomplete="off">
                                <span class="error error_account_start_date"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1" id="financial_year_start_month_field">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Financial Year Start Month') }}</label>
                                <div class="input-group">
                                    <select name="financial_year_start_month" id="branch_financial_year_start_month" class="form-control select2" data-next="branch_currency_id">
                                        @php
                                            $months = \App\Enums\Months::cases();
                                        @endphp
                                        @foreach ($months as $month)
                                            <option value="{{ $month->value }}">{{ $month->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="error error_financial_year_start_month"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Currency') }}</label>
                                <div class="input-group">
                                    <select required name="currency_id" class="form-control select2" id="branch_currency_id" data-next="add_initial_user_btn">
                                        @foreach ($currencies as $currency)
                                            <option data-currency_symbol="{{ $currency->symbol }}" {{ $generalSettings['business_or_shop__currency_id'] == $currency->id ? 'SELECTED' : '' }} value="{{ $currency->id }}">
                                                {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="currency_symbol" id="branch_currency_symbol" value="{{ $generalSettings['business_or_shop__currency_symbol'] }}">
                                </div>
                                <span class="error error_currency_id"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12 text-center">
                        <button type="button" value="1" class="btn text-white btn-sm btn-success float-center" id="add_initial_user_btn">
                            <i class="fas fa-user text-white"></i> {{ __('Add Initial User') }}
                        </button>
                        <input type="hidden" name="add_initial_user" id="add_initial_user" value="0">
                    </div>
                </div>

                <div class="add_initial_user_section" style="display: none;">
                    <div class="row mt-1">
                        <div class="col-lg-3 col-md-6">
                            <label> <b>{{ __('First Name') }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="user_first_name" class="form-control initial_user_required_field" id="user_first_name" data-next="user_last_name" placeholder="{{ __('First Name') }}" autocomplete="off" />
                            <span class="error error_user_first_name"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __('Last Name') }}</b></label>
                            <input type="text" name="user_last_name" class="form-control" id="user_last_name" data-next="user_phone" placeholder="{{ __('Last Name') }}" autocomplete="off" />
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __('Phone') }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="user_phone" class="form-control initial_user_required_field" id="user_phone" data-next="role_id" placeholder="{{ __('User Phone Number') }}" autocomplete="off" />
                            <span class="error error_user_phone"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>{{ __('Role Permission') }} </b> <span class="text-danger">*</span> </label>
                            <select name="role_id" id="role_id" class="form-control initial_user_required_field" data-next="user_username">
                                <option value="">{{ __('Select Role Permission') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <span class="error error_role_id"></span>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __('Email') }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="user_email" class="form-control initial_user_required_field" id="user_email" data-next="user_username" placeholder="{{ __('Username') }}" autocomplete="off" />
                            <span class="error error_username"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __('Username') }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="user_username" class="form-control initial_user_required_field" id="user_username" data-next="password" placeholder="{{ __('Username') }}" autocomplete="off" />
                            <span class="error error_username"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label><b>{{ __('Password') }} </b> <span class="text-danger">*</span> </label>
                            <input type="text" name="password" class="form-control initial_user_required_field" id="password" data-next="password_confirmation" placeholder="{{ __('Password') }}" autocomplete="off" />
                            <span class="error error_password"></span>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label> <b>{{ __('Confirm Password') }}</b> <span class="text-danger">*</span> </label>
                            <input type="text" name="password_confirmation" class="form-control" id="password_confirmation" data-next="branch_save" placeholder="{{ __('Confirm Password') }}" autocomplete="off" />
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-1">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button branch_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        <button type="button" id="branch_save" class="btn btn-sm btn-success branch_submit_button">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('branches.ajax_view.js_partials.create_js')

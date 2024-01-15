@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element { border: 1px solid #7e0d3d; }

        label { font-size: 12px !important; }

        ul.menus_unorder_list { list-style: none; float: left; width: 100%; }

        ul.menus_unorder_list .menu_list { display: block; text-align: center; margin-bottom: 5px; }

        ul.menus_unorder_list .menu_list:last-child { margin-bottom: 0; }

        ul.menus_unorder_list .menu_list .menu_btn { color: black; padding: 5px 1px; display: block; font-size: 11px; box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.1); border-bottom: 1px solid transparent; border-radius: 5px; background: white; transition: .2s; }

        ul.menus_unorder_list .menu_list .menu_btn.menu_active { border-color: var(--dark-color-1); color: #504d4d !important; font-weight: 600; }

        .hide-all { display: none; }
    </style>
@endpush
@section('title', 'Shop Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('General Settings') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="p-1">
            <div class="form_element rounded m-0">
                <div class="element-body">
                    <div class="settings_form_area">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="settings_side_menu">
                                    <ul class="menus_unorder_list">
                                        <li class="menu_list">
                                            <a class="menu_btn menu_active" data-form="branch_settings_form" href="#">{{ __('Shop Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="dashboard_settings_form" href="#">{{ __('Dashboard Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="product_settings_form" href="#">{{ __('Product Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="purchase_settings_form" href="#">{{ __('Purchase Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="manufacturing_settings_form" href="#">{{ __('Manufacturing Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="add_sale_settings_form" href="#">{{ __('Add Sale Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="pos_settings_form" href="#">{{ __('Pos Sale Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="prefix_settings_form" href="#">{{ __('Prefix Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="invoice_layout_settings_form" href="#">{{ __('Invoice Layout Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="print_page_size_settings_form" href="#">{{ __('Print Page Size') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="system_settings_form" href="#">{{ __('System Settings') }}</a>
                                        </li>

                                        @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="point_settings_form" href="#">{{ __('Reward Point Settings') }}</a>
                                            </li>
                                        @endif

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="module_settings_form" href="#">{{ __('Modules Settings') }}</a>
                                        </li>

                                        @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="email_settings_form" href="#">{{ __('Send Email Settings') }}</a>
                                            </li>
                                        @endif

                                        @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                            <li class="menu_list">
                                                <a class="menu_btn" data-form="sms_settings_form" href="#">{{ __('Send SMS Settings') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <form id="branch_settings_form" class="setting_form p-2" action="{{ route('branches.update', $branch->id) }}" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Shop Settings') }}</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <input type="hidden" name="branch_type" value="{{ $branch->branch_type }}">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <label class="fw-bold">{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                                                    <input {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'readonly' : 'required' }} type="text" name="name" class="form-control" id="name" value="{{ $branch->name }}" placeholder="{{ __('Shop Name') }}" />
                                                    <span class="error error_branch_name"></span>
                                                </div>

                                                <div class="col-lg-4 col-md-6">
                                                    <label class="fw-bold">{{ __('Shop ID') }}</label>
                                                    <input readonly type="text" name="branch_code" class="form-control" id="branch_code" value="{{ $branch->branch_code }}" placeholder="{{ __('Shop ID') }}" />
                                                    <span class="error error_branch_code"></span>
                                                </div>

                                                <div class="col-lg-4 col-md-6">
                                                    <label class="fw-bold">{{ __('Area Name') }} <span class="text-danger">*</span></label>
                                                    <input required type="text" name="area_name" class="form-control" id="area_name" value="{{ $branch->area_name }}" placeholder="{{ __('Area Name') }}" />
                                                    <span class="error error_code"></span>
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

                                                @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                                    <div class="col-lg-4 col-md-6">
                                                        <label class="fw-bold">{{ __('Logo') }} <small class="text-danger">{{ __('Logo size 200px * 70px') }}</small></label>
                                                        <input type="file" name="logo" class="form-control " id="logo" />
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3" style="border-left: 1px solid #000;">
                                            <div class="row mt-1">
                                                <div class="col-md-12">
                                                    <label class="fw-bold">{{ __('Date Format') }}</label>
                                                    <select name="date_format" class="form-control" id="date_format">
                                                        <option value="d-m-Y" {{ $branch->date_format == 'd-m-Y' ? 'SELECTED' : '' }}>{{ date('d-m-Y') }}</option>
                                                        <option value="m-d-Y" {{ $branch->date_format == 'm-d-Y' ? 'SELECTED' : '' }}>{{ date('m-d-Y') }}</option>
                                                        <option value="Y-m-d" {{ $branch->date_format == 'Y-m-d' ? 'SELECTED' : '' }}>{{ date('Y-m-d') }}</option>
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
                                                            @foreach (App\Utils\Util::stockAccountingMethods() as $key => $item)
                                                                <option {{ $generalSettings['business_or_shop__stock_accounting_method'] == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $item }}</option>
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
                                                                    <option {{ $generalSettings['business_or_shop__financial_year_start_month'] == $month ? 'SELECTED' : '' }} value="{{ $month->value }}">{{ $month->name }}</option>
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

                                <form id="dashboard_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.dashboard', $branch->id) }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Dashboard Settings') }}</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label><strong>{{ __('View Stock Expiry Alert For') }} </strong> <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="view_stock_expiry_alert_for" class="form-control" id="view_stock_expiry_alert_for" data-name="Day amount" autocomplete="off" value="{{ $generalSettings['dashboard__view_stock_expiry_alert_for'] }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text input-group-text-sm" id="basic-addon1">{{ __('Days') }}</span>
                                                </div>
                                            </div>
                                            <span class="error error_view_stock_expiry_alert_for"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button dashboard_setting_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="product_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.product', $branch->id) }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Product Settings') }}</h6>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3 col-sm-6">
                                            <label class="fw-bold">{{ __('Product Code Prefix(SKU)') }}</label>
                                            <input type="text" name="product_code_prefix" class="form-control" id="product_code_prefix" value="{{ $generalSettings['product__product_code_prefix'] }}" autocomplete="off">
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <label class="fw-bold">{{ __('Default Unit') }}</label>
                                            <select name="default_unit_id" class="form-control" id="default_unit_id">
                                                <option value="null">{{ __('None') }}</option>
                                                @foreach ($units as $unit)
                                                    <option {{ $generalSettings['product__default_unit_id'] == $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-lg-3 col-md-4">
                                            <label class="fw-bold">{{ __('Enable Brands') }}</label>
                                            <select name="is_enable_brands" class="form-control" id="is_enable_brands">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['product__is_enable_brands'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-4">
                                            <label class="fw-bold">{{ __('Enable Categories') }}</label>
                                            <select name="is_enable_categories" class="form-control" id="is_enable_categories">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['product__is_enable_categories'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-4">
                                            <label class="fw-bold">{{ __('Enable Subcategories') }}</label>
                                            <select name="is_enable_sub_categories" class="form-control" id="is_enable_sub_categories">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['product__is_enable_sub_categories'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 col-md-4">
                                            <label class="fw-bold">{{ __('Enable Price Vat/Tax') }}</label>
                                            <select name="is_enable_price_tax" class="form-control" id="is_enable_price_tax">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['product__is_enable_price_tax'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-lg-3 col-md-4">
                                            <label class="fw-bold">{{ __('Enable Product Warranty') }}</label>
                                            <select name="is_enable_warranty" class="form-control" id="is_enable_warranty">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['product__is_enable_warranty'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button product_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="purchase_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.purchase', $branch->id) }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Purchase Settings') }}</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="fw-bold">{{ __('Enable Editing Product Price From Purchase Screen') }}</label>
                                            <select name="is_edit_pro_price" class="form-control" id="is_edit_pro_price" autofocus>
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['purchase__is_edit_pro_price'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="fw-bold">{{ __('Enable Lot Number') }}</label>
                                            <select name="is_enable_lot_no" class="form-control" id="is_enable_lot_no">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['purchase__is_enable_lot_no'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button purchase_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button type="submit" id="save_changes_btn" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="manufacturing_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.manufacturing', $branch->id) }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Manufacturing Settings') }}</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="fw-bold">{{ __('Production Voucher Prefix') }}</label>
                                            <input type="text" name="production_voucher_prefix" class="form-control" id="production_voucher_prefix" placeholder="{{ __('Product Voucher Prefix') }}" value="{{ $generalSettings['manufacturing__production_voucher_prefix'] }}" autocomplete="off">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="fw-bold">{{ __('Enable Editing Ingredients Quantity In Production') }}</label>
                                            <select name="is_edit_ingredients_qty_in_production" class="form-control" id="is_edit_ingredients_qty_in_production">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['manufacturing__is_edit_ingredients_qty_in_production'] == 0 ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-6">
                                            <label class="fw-bold">{{ __('Update Product Cost And Selling Price Based On Net Cost') }}</strong> <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Update Product Cost And Selling Price Based On Total Production Cost, On Finalizing Production') }}" class="fas fa-info-circle tp"></i></label>
                                            <select name="is_update_product_cost_and_price_in_production" class="form-control" id="is_update_product_cost_and_price_in_production">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['manufacturing__is_update_product_cost_and_price_in_production'] == 0 ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button manufacturing_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button type="submit" id="save_changes_btn" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="add_sale_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.add.sale', $branch->id) }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Add Sale Settings') }}</h6>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="fw-bold">{{ __('Default Sale Discount') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-percent text-dark input_f"></i></span>
                                                </div>
                                                <input type="text" name="default_sale_discount" class="form-control" id="default_sale_discount" autocomplete="off" value="{{ $generalSettings['add_sale__default_sale_discount'] }}" autofocus>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">{{ __('Default Selling Price Group') }}</label>
                                            <select name="default_price_group_id" class="form-control" id="default_price_group_id">
                                                <option value="null">{{ __('None') }}</option>
                                                @foreach ($priceGroups as $priceGroup)
                                                    <option {{ $generalSettings['add_sale__default_price_group_id'] == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">{{ __('Add Sale Default Tax') }}</label>
                                            <select class="form-control" name="default_tax_ac_id" id="add_sale_default_tax_ac_id">
                                                <option value="">{{ __('None') }}</option>
                                                @foreach ($taxAccounts as $tax)
                                                    <option {{ $generalSettings['add_sale__default_tax_ac_id'] == $tax->id ? 'SELECTED' : '' }} value="{{ $tax->id }}">{{ $tax->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button add_sale_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="pos_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.pos', $branch->id) }}" method="post">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Enable Multiple Payment') }} </strong></label>
                                            <select class="form-control" name="is_enabled_multiple_pay" id="is_enabled_multiple_pay" autofocus>
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_enabled_multiple_pay'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Enable Draft') }}</label>
                                            <select class="form-control" name="is_enabled_draft" id="is_enabled_draft">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_enabled_draft'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Enable Quotation') }}</label>
                                            <select class="form-control" name="is_enabled_quotation" id="is_enabled_quotation">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_enabled_quotation'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Enable Suspend') }}</label>
                                            <select class="form-control" name="is_enabled_suspend" id="is_enabled_suspend">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_enabled_suspend'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Enable Discount') }}</label>
                                            <select class="form-control" name="is_enabled_discount" id="is_enabled_discount">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_enabled_discount'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Enable Sale Tax') }}</label>
                                            <select class="form-control" name="is_enabled_order_tax" id="is_enabled_order_tax">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_enabled_order_tax'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Pos Sale Default Tax') }}</label>
                                            <select class="form-control" name="default_tax_ac_id" id="pos_default_tax_ac_id">
                                                <option value="">{{ __('None') }}</option>
                                                @foreach ($taxAccounts as $tax)
                                                    <option {{ $generalSettings['pos__default_tax_ac_id'] == $tax->id ? 'SELECTED' : '' }} value="{{ $tax->id }}">{{ $tax->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Show Recent Transactions') }}</label>
                                            <select class="form-control" name="is_show_recent_transactions" id="is_show_recent_transactions">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_show_recent_transactions'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Enable Full Credit Sale') }}</label>
                                            <select class="form-control" name="is_enabled_credit_full_sale" id="is_enabled_credit_full_sale">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_enabled_credit_full_sale'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-3">
                                            <label class="fw-bold">{{ __('Enable Hold Invoice') }}</label>
                                            <select class="form-control" name="is_enabled_hold_invoice" id="is_enabled_hold_invoice">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option {{ $generalSettings['pos__is_enabled_hold_invoice'] == '0' ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button pos_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="prefix_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.prefix', $branch->id) }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Prefix Settings') }}</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Invoice Prefix') }}</label>
                                            <input type="text" name="sales_invoice_prefix" class="form-control" id="sales_invoice_prefix" value="{{ $generalSettings['prefix__sales_invoice_prefix'] }}" placeholder="{{ __('Invoice Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Quotation Prefix') }}</label>
                                            <input type="text" name="quotation_prefix" class="form-control" id="quotation_prefix" value="{{ $generalSettings['prefix__quotation_prefix'] }}" placeholder="{{ __('Shop ID') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Sales Order Prefix') }}</label>
                                            <input type="text" name="sales_order_prefix" class="form-control" id="sales_order_prefix" value="{{ $generalSettings['prefix__sales_order_prefix'] }}" placeholder="{{ __('Sales Order Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Sales Return Prefix') }}</label>
                                            <input type="text" name="sales_return_prefix" class="form-control" id="sales_return_prefix" value="{{ $generalSettings['prefix__sales_return_prefix'] }}" placeholder="{{ __('Sales Return Prefix') }}" />
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Payment Voucher Prefix') }}</label>
                                            <input type="text" name="payment_voucher_prefix" class="form-control" id="payment_voucher_prefix" value="{{ $generalSettings['prefix__payment_voucher_prefix'] }}" placeholder="{{ __('Payment Voucher Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Receipt Voucher Prefix') }}</label>
                                            <input type="text" name="receipt_voucher_prefix" class="form-control" id="receipt_voucher_prefix" value="{{ $generalSettings['prefix__receipt_voucher_prefix'] }}" placeholder="{{ __('Receipt Voucher Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Expense Voucher Prefix') }}</label>
                                            <input type="text" name="expense_voucher_prefix" class="form-control" id="expense_voucher_prefix" value="{{ $generalSettings['prefix__expense_voucher_prefix'] }}" placeholder="{{ __('Expense Voucher Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Contra Voucher Prefix') }}</label>
                                            <input type="text" name="contra_voucher_prefix" class="form-control" id="branch_setting_contra_voucher_prefix" value="{{ $generalSettings['prefix__contra_voucher_prefix'] }}" placeholder="{{ __('Expense Voucher Prefix') }}" />
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Purchase Invoice Prefix') }}</label>
                                            <input type="text" name="purchase_invoice_prefix" class="form-control" id="purchase_invoice_prefix" value="{{ $generalSettings['prefix__purchase_invoice_prefix'] }}" placeholder="{{ __('Purchase Invoice Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Purchase Order Voucher Prefix') }}</label>
                                            <input type="text" name="purchase_order_prefix" class="form-control" id="purchase_order_prefix" value="{{ $generalSettings['prefix__purchase_order_prefix'] }}" placeholder="{{ __('Purchase Order Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Purchase Return Voucher Prefix') }}</label>
                                            <input type="text" name="purchase_return_prefix" class="form-control" id="purchase_return_prefix" value="{{ $generalSettings['prefix__purchase_return_prefix'] }}" placeholder="{{ __('Purchase Return Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Stock Adjustment Voucher Prefix') }}</label>
                                            <input type="text" name="stock_adjustment_prefix" class="form-control" id="stock_adjustment_prefix" value="{{ $generalSettings['prefix__stock_adjustment_prefix'] }}" placeholder="{{ __('Stock Adjustment Voucher Prefix') }}" />
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Payroll Voucher Prefix') }}</label>
                                            <input type="text" name="payroll_voucher_prefix" class="form-control" id="payroll_voucher_prefix" value="{{ $generalSettings['prefix__payroll_voucher_prefix'] }}" placeholder="{{ __('Payroll Voucher Prefix') }}" />
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="fw-bold">{{ __('Payroll Payment Voucher Prefix') }}</label>
                                            <input type="text" name="payroll_payment_voucher_prefix" class="form-control" id="payroll_payment_voucher_prefix" value="{{ $generalSettings['prefix__payroll_payment_voucher_prefix'] }}" placeholder="{{ __('Payroll Voucher Prefix') }}" />
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Supplier ID') }}</strong></label>
                                            <input type="text" name="supplier_id" class="form-control" id="supplier_id" value="{{ $generalSettings['prefix__supplier_id'] }}" autocomplete="off">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Customer ID') }} </strong></label>
                                            <input type="text" name="customer_id" class="form-control" value="{{ $generalSettings['prefix__customer_id'] }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button prefix_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="invoice_layout_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.invoice.layout', $branch->id) }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Invoice Layout Settings') }}</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <label class="fw-bold">{{ __('Add Sale Default Invoice Layout') }}</label>
                                            <select name="add_sale_invoice_layout_id" class="form-control" id="add_sale_invoice_layout_id">
                                                @foreach ($invoiceLayouts as $invoiceLayout)
                                                    <option {{ $generalSettings['invoice_layout__add_sale_invoice_layout_id'] == $invoiceLayout->id ? 'SELECTED' : '' }} value="{{ $invoiceLayout->id }}">{{ $invoiceLayout->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label class="fw-bold">{{ __('Pos Sale Default Invoice Layout') }}</label>
                                            <select name="pos_sale_invoice_layout_id" class="form-control" id="pos_sale_invoice_layout_id">
                                                @foreach ($invoiceLayouts as $invoiceLayout)
                                                    <option {{ $generalSettings['invoice_layout__pos_sale_invoice_layout_id'] == $invoiceLayout->id ? 'SELECTED' : '' }} value="{{ $invoiceLayout->id }}">{{ $invoiceLayout->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button invoice_layout_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button type="submit" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="print_page_size_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.print.page.size', $branch->id) }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('Print Settings') }}</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row mt-3">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Add Sale') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="add_sale_page_size" class="form-control" id="add_sale_page_size">
                                                                @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                                                    <option {{ $generalSettings['print_page_size__add_sale_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('pos Sale') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="pos_sale_page_size" class="form-control" id="pos_sale_page_size">
                                                                @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                                                    <option {{ $generalSettings['print_page_size__pos_sale_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Quotation') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="quotation_page_size" class="form-control" id="quotation_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__quotation_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Sales Order') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="sales_order_page_size" class="form-control" id="sales_order_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__sales_order_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Draft') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="draft_page_size" class="form-control" id="draft_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__draft_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Sales Return') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="sales_return_page_size" class="form-control" id="sales_return_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__sales_return_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Purchase') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="purchase_page_size" class="form-control" id="purchase_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__purchase_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Purchase Order') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="purchase_order_page_size" class="form-control" id="purchase_order_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__purchase_order_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Purchase Return') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="purchase_return_page_size" class="form-control" id="purchase_return_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__purchase_return_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Transfer Stock') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="transfer_stock_voucher_page_size" class="form-control" id="transfer_stock_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__transfer_stock_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('S. Adjustment') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="stock_adjustment_voucher_page_size" class="form-control" id="stock_adjustment_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__stock_adjustment_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Receipt Vch.') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="receipt_voucher_page_size" class="form-control" id="receipt_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__receipt_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Payment Vch.') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="payment_voucher_page_size" class="form-control" id="payment_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__payment_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Expense Vch.') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="expense_voucher_page_size" class="form-control" id="expense_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__expense_voucher_page_size'] == $item->value ? 'SELECTED' : '' }}  value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Contra Vch.') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="contra_voucher_page_size" class="form-control" id="contra_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__contra_voucher_page_size'] == $item->value ? 'SELECTED' : '' }}  value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Payroll Vch.') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="payroll_voucher_page_size" class="form-control" id="payroll_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__payroll_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Payroll Payment') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="payroll_payment_voucher_page_size" class="form-control" id="payroll_payment_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__payroll_payment_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('BOM Voucher') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="bom_voucher_page_size" class="form-control" id="bom_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__bom_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-1">
                                                    <div class="input-group">
                                                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Production Vch.') }}</label>
                                                        <div class="col-md-9">
                                                            <select name="production_voucher_page_size" class="form-control" id="production_voucher_page_size">
                                                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                                                    <option {{ $generalSettings['print_page_size__production_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button print_page_size_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="system_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.system', $branch->id) }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">{{ __('System Settings') }}</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label class="fw-bold">{{ __('Theme Color') }}</label>
                                            <select name="theme_color" class="form-control" id="theme_color">
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'dark-theme' ? 'SELECTED' : '' }} value="dark-theme">{{ __('Default Theme') }}</option>
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'red-theme' ? 'SELECTED' : '' }} value="red-theme">{{ __('Red Theme') }}</option>
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'blue-theme' ? 'SELECTED' : '' }} value="blue-theme">{{ __('Blue Theme') }}</option>
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'light-theme' ? 'SELECTED' : '' }} value="light-theme">{{ __('Light Theme') }}</option>
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'orange-theme' ? 'SELECTED' : '' }} value="orange-theme">{{ __('Orange Theme') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="fw-bold">{{ __('Default datatable page entries') }}</label>
                                            <select name="datatable_page_entry" class="form-control" id="datatable_page_entry">
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 10 ? 'SELECTED' : '' }} value="10">{{ __('10') }}</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 25 ? 'SELECTED' : '' }} value="25">{{ __('25') }}</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 50 ? 'SELECTED' : '' }} value="50">{{ __('50') }}</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 100 ? 'SELECTED' : '' }} value="100">{{ __('100') }}</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 500 ? 'SELECTED' : '' }} value="500">{{ __('500') }}</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 1000 ? 'SELECTED' : '' }} value="1000">{{ __('1000') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button system_setting_loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                    <form id="point_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.reward.point', $branch->id) }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <h6 class="text-primary mb-3"><b>{{ __('Reward Point Settings') }}</b></h6>
                                        </div>

                                        <div class="form-group row mt-2">
                                            <div class="col-md-4">
                                                <div class="row ">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['reward_point_settings__enable_cus_point'] == '1' ? 'CHECKED' : '' }} name="enable_cus_point"> &nbsp; <b>{{ __('Enable Reward Point') }}</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="fw-bold">{{ __('Reward Point Display Name') }}</label>
                                                <input type="text" name="point_display_name" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__point_display_name'] }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mt-2">
                                            <h6 class="text-primary mb-1"><b>{{ __('Earning Settings') }}</b></h6>
                                            <div class="col-md-4">
                                                <label class="fw-bold">{{ __('Amount spend for unit point') }}
                                                    <i data-bs-toggle="tooltip" data-bs-placement="left" title="{{ __("Example: If you set it as 10, then for every $10 spent by customer they will get one reward points. If the customer purchases for $1000 then they will get 100 reward points") }}." class="fas fa-info-circle tp"></i></label>
                                                <input type="number" step="any" name="amount_for_unit_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__amount_for_unit_rp'] }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="fw-bold">{{ __('Minimum order total to earn reward') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Example: If you set it as 100 then customer will get reward points only if there invoice total is greater or equal to 100. If invoice total is 99 then they won’t get any reward points.You can set it as minimum 1.') }}" class="fas fa-info-circle tp"></i></label>
                                                <input type="number" step="any" name="min_order_total_for_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_order_total_for_rp'] }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="fw-bold">{{ __('Maximum points per order') }} <i data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Maximum reward points customers can earn in one invoice. Leave it empty if you don’t want any such restrictions.') }}" class="fas fa-info-circle tp"></i></label>
                                                <input type="number" step="any" name="max_rp_per_order" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__max_rp_per_order'] }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mt-2">
                                            <h6 class="text-primary mb-1"><b>{{ __('Redeem Points Settings') }}</b></h6>
                                            <div class="col-md-4">
                                                <label class="fw-bold">{{ __('Redeem amount per unit point') }}
                                                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __("example: If 1 point is $1 then enter the value as 1. If 2 points is $1 then enter the value as 0.50") }}" class="fas fa-info-circle tp"></i></label>
                                                <input type="number" step="any" name="redeem_amount_per_unit_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__redeem_amount_per_unit_rp'] }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bold">{{ __('Minimum order total to redeem points') }}
                                                    <i data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Minimum order total for which customers can redeem points. Leave it blank if you don’t need this restriction or you need to give something for free.') }}" class="fas fa-info-circle tp"></i></label>
                                                <input type="number" step="any" name="min_order_total_for_redeem" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_order_total_for_redeem'] }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bold">{{ __('Minimum redeem point') }}
                                                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Minimum redeem points that can be used per invoice. Leave it blank if you don’t need this restriction.') }}" class="fas fa-info-circle tp"></i></label>
                                                <input type="number" step="any" name="min_redeem_point" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_redeem_point'] }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-2">
                                            <div class="col-md-4">
                                                <label class="fw-bold">{{ __('Maximum redeem point per order') }}
                                                    <i data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Maximum points that can be used in one order. Leave it blank if you don’t need this restriction') }}." class="fas fa-info-circle tp"></i></label>
                                                <input type="number" step="any" name="max_redeem_point" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__max_redeem_point'] }}">
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <div class="btn-loading">
                                                    <button type="button" class="btn loading_button reward_point_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                    <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif

                                <form id="module_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.module', $branch->id) }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary"><b>{{ __('Module Settings') }}</b></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__purchases'] == '1' ? 'CHECKED' : '' }} name="purchases" autocomplete="off"> &nbsp; <b>{{ __('Purchases') }}</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__add_sale'] == '1' ? 'CHECKED' : '' }} name="add_sale" autocomplete="off"> &nbsp; <b>{{ __('Add Sale') }}</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__pos'] == '1' ? 'CHECKED' : '' }} name="pos" autocomplete="off"> &nbsp; <b>{{ __('POS') }}</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__transfer_stock'] == '1' ? 'CHECKED' : '' }} name="transfer_stock" autocomplete="off">
                                                    &nbsp; <b>{{ __('Transfers Stock') }}</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__service'] == '1' ? 'CHECKED' : '' }} name="service" autocomplete="off"> &nbsp; <b>{{ __('Service') }}</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__stock_adjustments'] == '1' ? 'CHECKED' : '' }} name="stock_adjustments" autocomplete="off"> &nbsp; <b>{{ __('Stock Adjustments') }}</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__accounting'] == '1' ? 'CHECKED' : '' }} name="accounting" autocomplete="off"> &nbsp; <b>{{ __('Accounting') }}</b>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__contacts'] == '1' ? 'CHECKED' : '' }} name="contacts" autocomplete="off"> &nbsp; <b>{{ __('Contacts') }}</b>
                                                </p>
                                            </div>
                                        </div>

                                        @if ($generalSettings['addons__hrm'] == 1)
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['modules__hrms'] == '1' ? 'CHECKED' : '' }} name="hrms" autocomplete="off"> &nbsp; <b>{{ __('Human Resource Management') }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__manage_task'] == '1' ? 'CHECKED' : '' }} name="manage_task" autocomplete="off"> &nbsp; <b>{{ __('Manage Task') }}</b>
                                                </p>
                                            </div>
                                        </div>

                                        @if ($generalSettings['addons__manufacturing'] == 1)
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" @if (isset($generalSettings['modules__manufacturing'])) {{ $generalSettings['modules__manufacturing'] == '1' ? 'CHECKED' : '' }} @endif name="manufacturing" autocomplete="off">
                                                        &nbsp;<b>{{ __('Manufacture') }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button module_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                    <form id="email_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.send.email', $branch->id) }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <div class="setting_form_heading">
                                                <h6 class="text-primary">{{ __('Send Email Settings') }}</h6>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4 mt-1">
                                                <div class="row mt-4">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['send_email__send_invoice_via_email'] == '1' ? 'CHECKED' : '' }} name="send_invoice_via_email"> &nbsp; <b>{{ __('Send Invoice After Sale Via Email') }}</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-1">
                                                <div class="row mt-4">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['send_email__send_notification_via_email'] == '1' ? 'CHECKED' : '' }} name="send_notification_via_email"> &nbsp; <b>{{ __('Send Notification Via Email') }}</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-1">
                                                <div class="row mt-4">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['send_email__customer_due_reminder_via_email'] == '1' ? 'CHECKED' : '' }} name="customer_due_reminder_via_email"> &nbsp; <b>{{ __('Custome Due Remainder Via Email') }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4 mt-1">
                                                <div class="row mt-4">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['send_email__user_forget_password_via_email'] == '1' ? 'CHECKED' : '' }} name="user_forget_password_via_email"> &nbsp; <b> {{ __('User Forget Password Via Email') }}</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-1">
                                                <div class="row mt-4">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['send_email__coupon_offer_via_email'] == '1' ? 'CHECKED' : '' }} name="coupon_offer_via_email"> &nbsp; <b>{{ __('Coupon Offer Via Email') }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <div class="btn-loading">
                                                    <button type="button" class="btn loading_button email_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                    <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif

                                @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                    <form id="sms_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.sms', $branch->id) }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <div class="setting_form_heading">
                                                <h6 class="text-primary">{{ __('Send SMS Setttings') }}</h6>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-4 mt-1">
                                                <div class="row mt-4">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['send_sms__send_invoice_via_sms'] == '1' ? 'CHECKED' : '' }} name="send_invoice_via_sms"> &nbsp; <b>{{ __('Send Invoice After Sale Via Sms') }}</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-1">
                                                <div class="row mt-4">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $generalSettings['send_sms__send_notification_via_sms'] == '1' ? 'CHECKED' : '' }} name="send_notification_via_sms"> &nbsp; <b>{{ __('Send Notification Via Sms') }}</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-1">
                                                <div class="row mt-4">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="customer_due_reminder_via_sms" {{ $generalSettings['send_sms__customer_due_reminder_via_sms'] == '1' ? 'CHECKED' : '' }}> &nbsp; <b>{{ __('Customer Due Remainder Via Sms') }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <div class="btn-loading">
                                                    <button type="button" class="btn loading_button sms_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                    <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.menu_btn', function(e) {
                e.preventDefault();
                var form_name = $(this).data('form');
                $('.setting_form').hide(500);
                $('#' + form_name).show(500);
                $('.menu_btn').removeClass('menu_active');
                $(this).addClass('menu_active d-block');
            });
        });

        $('#branch_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.branch_settings_loading_btn').show();
            var url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.error').html('');
                    toastr.success(data);
                    $('.branch_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.branch_settings_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $('#dashboard_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.dashboard_setting_loading_btn').show();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.error').html('');
                    $('.loading_button').hide();
                }, error: function(err) {

                    $('.dashboard_setting_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $('#product_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.product_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.product_settings_loading_btn').hide();
                }, error: function(err) {

                    $('.product_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#purchase_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.purchase_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.purchase_settings_loading_btn').hide();
                }, error: function(err) {

                    $('.purchase_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#manufacturing_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.manufacturing_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.manufacturing_settings_loading_btn').hide();
                }, error: function(err) {

                    $('.manufacturing_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#add_sale_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.add_sale_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success(data);
                    $('.error').html('');
                    $('.add_sale_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.add_sale_settings_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#pos_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.pos_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success(data);
                    $('.pos_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.pos_settings_loading_btn').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#prefix_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.prefix_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.prefix_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.prefix_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#invoice_layout_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.invoice_layout_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.invoice_layout_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.invoice_layout_settings_loading_btn').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#print_page_size_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.print_page_size_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.print_page_size_settings_loading_btn').hide();
                },error: function(err) {

                    $('.print_page_size_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#system_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.system_setting_loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.system_setting_loading_button').hide();
                },
                error: function(err) {

                    $('.system_setting_loading_button').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#point_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.reward_point_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.reward_point_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.reward_point_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#module_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.module_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.module_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.module_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#email_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.email_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.email_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.email_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $('#sms_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.sms_settings_loading_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.sms_settings_loading_btn').hide();
                },
                error: function(err) {

                    $('.sms_settings_loading_btn').hide();

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    } else if (err.status == 403) {

                        toastr.error("{{ __('Access Denied') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('change', '#currency_id', function(e) {
            var currencySymbol = $(this).find('option:selected').data('currency_symbol');
            $('#currency_symbol').val(currencySymbol);
        });
    </script>
@endpush

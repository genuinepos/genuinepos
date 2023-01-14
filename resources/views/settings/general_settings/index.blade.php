@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {
            border: 1px solid #7e0d3d;
        }
        label {
            font-size: 12px !important;
        }
        ul.menus_unorder_list {
            list-style: none;
            float: left;
            width: 100%;
        }
        ul.menus_unorder_list .menu_list {
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }
        ul.menus_unorder_list .menu_list:last-child {
            margin-bottom: 0;
        }
        ul.menus_unorder_list .menu_list .menu_btn {
            color: black;
            padding: 6px 1px;
            display: block;
            font-size: 11px;
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid transparent;
            border-radius: 5px;
            background: white;
            transition: .2s;
        }
        ul.menus_unorder_list .menu_list .menu_btn.menu_active {
            border-color: var(--dark-color-1);
            color: #504d4d!important;
            font-weight: 600;
        }
        .hide-all {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cogs"></span>
                    <h5>@lang('menu.general_settings')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
                </a>
            </div>
        </div>
        <div class="p-3">
            <div class="form_element rounded m-0">

                <div class="element-body">
                    <div class="settings_form_area">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="settings_side_menu">
                                    <ul class="menus_unorder_list">
                                        <li class="menu_list">
                                            <a class="menu_btn menu_active" data-form="business_settings_form"
                                                href="#">@lang('menu.business_settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="tax_settings_form" href="#">@lang('menu.tax_settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="dashboard_settings_form" href="#">@lang('menu.dashboard_settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="prefix_settings_form" href="#">@lang('menu.prefix_settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="system_settings_form" href="#">@lang('menu.system_settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="point_settings_form" href="#">@lang('menu.reward_point_settings')</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="module_settings_form" href="#">{{ __('Modules Settings') }}</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="es_settings_form" href="#">@lang('menu.send_email_sms_settings')</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <form id="business_settings_form" class="setting_form p-2"
                                    action="{{ route('settings.business.settings') }}" method="post"
                                    enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('menu.business_settings') </h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.business_name') </strong></label>
                                            <input type="text" name="shop_name" class="form-control bs_input"
                                                autocomplete="off"
                                                value="{{ $generalSettings['business__shop_name'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.address') </strong></label>
                                            <input type="text" name="address" class="form-control bs_input"
                                                autocomplete="off" placeholder="Business address"
                                                value="{{ $generalSettings['business__address'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.phone') </strong></label>
                                            <input type="text" name="phone" class="form-control bs_input" placeholder="Business phone number"
                                                value="{{ $generalSettings['business__phone'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.email') </strong></label>
                                            <input type="text" name="email" class="form-control bs_input" placeholder="Business email address"
                                                value="{{ $generalSettings['business__email'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.start_date') </strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="start_date" class="form-control" autocomplete="off"
                                                    value="{{ $generalSettings['business__start_date'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Default Profit') }}(%) </strong><span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="default_profit" class="form-control bs_input"
                                                autocomplete="off" data-name="Default profit" id="default_profit"
                                                value="{{ $generalSettings['business__default_profit'] }}">
                                            <span class="error error_default_profit"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Business Logo') }} </strong> <small class="red-label-notice">Required Size : H : 40px; W: 110px;</small></label>
                                            <input type="file" class="form-control" name="business_logo" id="business_logo">
                                            <small>{{ __('Previous logo (if exists) will be replaced') }}</small><br>

                                            <span class="error error_business_logo"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Currency') }}</strong><span class="text-danger">*</span></label>
                                            <select name="currency" class="form-control bs_input" data-name="Currency"
                                                id="currency">
                                                @foreach ($currencies as $currency)
                                                    <option
                                                        {{ $generalSettings['business__currency'] == $currency->symbol ? 'SELECTED' : '' }}
                                                        value="{{ $currency->symbol }}">
                                                        {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_currency"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Stock Accounting Method') }} </strong> <span
                                                    class="text-danger">*</span></label>
                                            <select name="stock_accounting_method" class="form-control bs_input"
                                                data-name="Stock Accounting Method" id="stock_accounting_method">
                                                @php
                                                    $stock_accounting_method = $generalSettings['business__stock_accounting_method'] ?? NULL;
                                                @endphp
                                                @foreach (App\Utils\Util::stockAccountingMethods() as $key => $item)
                                                    <option {{ $stock_accounting_method == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_financial_year_start"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Date Format') }}</strong><span class="text-danger">*</span></label>
                                            <select name="date_format" class="form-control bs_input" data-name="Date format"
                                                id="date_format">
                                                <option value="d-m-Y"
                                                    {{ $generalSettings['business__date_format'] == 'd-m-Y' ? 'SELECTED' : '' }}>
                                                    dd-mm-yyyy</option>
                                                <option value="m-d-Y"
                                                    {{ $generalSettings['business__date_format'] == 'm-d-Y' ? 'SELECTED' : '' }}>
                                                    mm-dd-yyyy</option>
                                                <option value="Y-m-d" {{ $generalSettings['business__date_format'] == 'Y-m-d' ? 'SELECTED' : '' }}>
                                                    yyyy-mm-dd</option>
                                            </select>
                                            <span class="error error_date_format"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Time Format') }}</strong><span class="text-danger">*</span></label>
                                            <select name="time_format" class="form-control bs_input" data-name="Time format"
                                                id="time_format">
                                                <option value="12"
                                                    {{ $generalSettings['business__time_format'] == '12' ? 'SELECTED' : '' }}>
                                                    12 Hour</option>
                                                <option value="24"
                                                    {{ $generalSettings['business__time_format'] == '24' ? 'SELECTED' : '' }}>
                                                    24 Hour</option>
                                            </select>
                                            <span class="error error_time_format"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Time Zone') }} </strong><span class="text-danger">*</span> {{  now()->format('Y-m-d') }}</label>
                                            <select name="timezone" class="form-control bs_input" data-name="Time format"
                                                id="time_format">
                                                <option value="">{{ __('Time Zone') }}</option>
                                                @foreach ($timezones as $key => $timezone)
                                                    <option
                                                        {{ ($generalSettings['business__timezone'] ?? 'Asia/Dhaka') == $key ? 'SELECTED' : '' }}
                                                        value="{{ $key }}">{{ $timezone }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_time_format"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="tax_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.tax.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('menu.tax_settings')</h6>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label>Tax 1 Name <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_1_name" class="form-control" autocomplete="off"
                                                placeholder="GST / VAT / Other"
                                                value="{{ $generalSettings['tax__tax_1_name'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Tax 1 No <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_1_no" class="form-control" autocomplete="off"
                                                value="{{ $generalSettings['tax__tax_1_no'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Tax 2 Name <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_2_name" class="form-control" autocomplete="off"
                                                placeholder="GST / VAT / Other"
                                                value="{{ $generalSettings['tax__tax_2_name'] }}">
                                        </div>

                                        <div class="col-md-4 mt-2">
                                            <label>Tax 2 No<span class="text-danger">*</span></label>
                                            <input type="text" name="tax_2_no" class="form-control" autocomplete="off"
                                                value="{{ $generalSettings['tax__tax_2_no'] }}">
                                        </div>

                                        <div class="col-md-8">
                                            <div class="row mt-5">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ $generalSettings['tax__is_tax_en_purchase_sale'] == '1' ? 'CHECKED' : '' }} name="is_tax_en_purchase_sale" id="is_tax_en_purchase_sale">
                                                        &nbsp; {{ __('Enable inline tax in purchase and sell') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="dashboard_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.dashboard.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('menu.dashboard_settings')</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label><strong>View Stock Expiry Alert For </strong> <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="view_stock_expiry_alert_for"
                                                    class="form-control dbs_input" id="dbs_view_stock_expiry_alert_for"
                                                    data-name="Day amount" autocomplete="off"
                                                    value="{{ $generalSettings['dashboard__view_stock_expiry_alert_for'] }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text input-group-text-sm"
                                                        id="basic-addon1">@lang('menu.days')</span>
                                                </div>
                                            </div>
                                            <span class="error error_dbs_view_stock_expiry_alert_for"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="prefix_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.prefix.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('menu.prefix_settings')</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>{{ __('PURCHASE Invoice') }} </strong></label>
                                            <input type="text" name="purchase_invoice" class="form-control"
                                                autocomplete="off"
                                                value="{{ $generalSettings['prefix__purchase_invoice'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Sale Invoice </strong></label>
                                            <input type="text" name="sale_invoice" class="form-control" autocomplete="off"
                                                value="{{ $generalSettings['prefix__sale_invoice'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.purchase_return') </strong></label>
                                            <input type="text" name="purchase_return" class="form-control"
                                                autocomplete="off"
                                                value="{{ $generalSettings['prefix__purchase_return'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Stock Transfer') }} </strong></label>
                                            <input type="text" name="stock_transfer" class="form-control" autocomplete="off"
                                                value="{{ $generalSettings['prefix__stock_transfer'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.stock_adjustment') </strong></label>
                                            <input type="text" name="stock_djustment" class="form-control"
                                                autocomplete="off"
                                                value="{{ $generalSettings['prefix__stock_adjustment'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.sale_return') </strong></label>
                                            <input type="text" name="sale_return" class="form-control" autocomplete="off"
                                                value="{{ $generalSettings['prefix__sale_return'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.expenses') </strong></label>
                                            <input type="text" name="expenses" class="form-control" autocomplete="off"
                                                value="{{ $generalSettings['prefix__expenses'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Expense Payment') }} </strong></label>
                                            <input type="text" name="expanse_payment" class="form-control"
                                                autocomplete="off"
                                                value="{{ $generalSettings['prefix__expanse_payment'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Purchase Payment') }} </strong></label>
                                            <input type="text" name="purchase_payment" class="form-control"
                                                autocomplete="off"
                                                value="{{ $generalSettings['prefix__purchase_payment'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Sale Payment') }} </strong></label>
                                            <input type="text" name="sale_payment" class="form-control" autocomplete="off"
                                                value="{{ $generalSettings['prefix__sale_payment'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.supplier_id')</strong></label>
                                            <input type="text" name="supplier_id" class="form-control"
                                                autocomplete="off" value="{{ $generalSettings['prefix__supplier_id'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.customer') ID </strong></label>
                                            <input type="text" name="customer_id" class="form-control" autocomplete="off"
                                                value="{{ $generalSettings['prefix__customer_id'] }}">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="system_settings_form" class="setting_form hide-all" action="{{ route('settings.system.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('menu.system_settings')</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Theme Color </strong></label>
                                            <select name="theme_color" class="form-control" id="theme_color">
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'dark-theme' ? 'SELECTED' : '' }} value="dark-theme">Default Theme</option>
                                                <option  {{ ($generalSettings['system__theme_color'] ?? '') == 'red-theme' ? 'SELECTED' : '' }} value="red-theme">Red Theme</option>
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'blue-theme' ? 'SELECTED' : '' }} value="blue-theme">Blue Theme</option>
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'light-theme' ? 'SELECTED' : '' }} value="light-theme">Light Theme</option>
                                                <option {{ ($generalSettings['system__theme_color'] ?? '') == 'orange-theme' ? 'SELECTED' : '' }} value="orange-theme">Orange Theme</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Default datatable page entries </strong></label>
                                            <select name="datatable_page_entry" class="form-control" id="datatable_page_entry">
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 10 ? 'SELECTED' : '' }} value="10">10</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 25 ? 'SELECTED' : '' }} value="25">25</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 50 ? 'SELECTED' : '' }} value="50">50</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 100 ? 'SELECTED' : '' }} value="100">100</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 500 ? 'SELECTED' : '' }} value="500">500</option>
                                                <option {{ ($generalSettings['system__datatables_page_entry'] ?? 0) == 1000 ? 'SELECTED' : '' }} value="1000">1000</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="point_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.reward.point.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <h6 class="text-primary mb-3"><b>@lang('menu.reward_point_settings')</b></h6>
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
                                            <label><strong>{{ __('Reward Point Display Name') }} </strong></label>
                                            <input type="text" name="point_display_name" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__point_display_name'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <h6 class="text-primary mb-1"><b>{{ __('Earning Settings') }}</b></h6>
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Amount spend for unit point') }} </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="left" title="Example: If you set it as 10, then for every $10 spent by customer they will get one reward points. If the customer purchases for $1000 then they will get 100 reward points." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="amount_for_unit_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__amount_for_unit_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Minimum order total to earn reward') }} </strong> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Example: If you set it as 100 then customer will get reward points only if there invoice total is greater or equal to 100. If invoice total is 99 then they won’t get any reward points.You can set it as minimum 1." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_order_total_for_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_order_total_for_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Maximum points per order') }} </strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Maximum reward points customers can earn in one invoice. Leave it empty if you don’t want any such restrictions." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="max_rp_per_order" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__max_rp_per_order'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">

                                        <h6 class="text-primary mb-1"><b>{{ __('Redeem Points Settings') }}</b></h6>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Redeem amount per unit point') }} </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="example: If 1 point is $1 then enter the value as 1. If 2 points is $1 then enter the value as 0.50" class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="redeem_amount_per_unit_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__redeem_amount_per_unit_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Minimum order total to redeem points') }} </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="right" title="Minimum order total for which customers can redeem points. Leave it blank if you don’t need this restriction or you need to give something for free." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_order_total_for_redeem" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_order_total_for_redeem'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Minimum redeem point') }} </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum redeem points that can be used per invoice. Leave it blank if you don’t need this restriction." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_redeem_point" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_redeem_point'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Maximum redeem point per order') }} </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="right" title="Maximum points that can be used in one order. Leave it blank if you don’t need this restriction." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="max_redeem_point" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__max_redeem_point'] }}">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="module_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.module.settings') }}" method="post">
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
                                                    <input type="checkbox"
                                                        {{ $generalSettings['modules__purchases'] == '1' ? 'CHECKED' : '' }}
                                                        name="purchases" autocomplete="off"> &nbsp; <b>@lang('menu.purchases')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ $generalSettings['modules__add_sale'] == '1' ? 'CHECKED' : '' }}
                                                        name="add_sale" autocomplete="off"> &nbsp; <b>@lang('menu.add_sale')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ $generalSettings['modules__pos'] == '1' ? 'CHECKED' : '' }}
                                                        name="pos" autocomplete="off"> &nbsp; <b>{{ __('POS') }}</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ $generalSettings['modules__transfer_stock'] == '1' ? 'CHECKED' : '' }}
                                                        name="transfer_stock" autocomplete="off">
                                                    &nbsp; <b>{{ __('Transfers Stock') }}</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ $generalSettings['modules__stock_adjustment'] == '1' ? 'CHECKED' : '' }}
                                                        name="stock_adjustment" autocomplete="off"> &nbsp; <b>@lang('menu.stock_adjustment')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ $generalSettings['modules__expenses'] == '1' ? 'CHECKED' : '' }}
                                                        name="expenses" autocomplete="off"> &nbsp; <b>@lang('menu.expenses')</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ $generalSettings['modules__accounting'] == '1' ? 'CHECKED' : '' }}
                                                        name="accounting" autocomplete="off"> &nbsp; <b>@lang('menu.accounting')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ $generalSettings['modules__contacts'] == '1' ? 'CHECKED' : '' }}
                                                        name="contacts" autocomplete="off"> &nbsp; <b>@lang('menu.contacts')</b>
                                                </p>
                                            </div>
                                        </div>

                                        @if ($generalSettings['addons__hrm'] == 1)
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox"
                                                            {{ $generalSettings['modules__hrms'] == '1' ? 'CHECKED' : '' }}
                                                            name="hrms" autocomplete="off"> &nbsp; <b>@lang('menu.human_resource_management')</b>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['modules__requisite'] == '1' ? 'CHECKED' : '' }} name="requisite" autocomplete="off"> &nbsp; <b>{{ __('Requisite') }}</b>
                                                </p>
                                            </div>
                                        </div>

                                        @if ($generalSettings['addons__manufacturing'] == 1)
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox"
                                                            @if (isset($generalSettings['modules__manufacturing']))
                                                                {{ $generalSettings['modules__manufacturing'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                            name="manufacturing" autocomplete="off">
                                                        &nbsp;<b>{{ __('Manufacture') }}</b>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($generalSettings['addons__service'] == 1)
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox"
                                                            @if (isset($generalSettings['modules__service']))
                                                                {{ $generalSettings['modules__service'] == '1' ? 'CHECKED' : '' }}
                                                            @endif
                                                            name="service" autocomplete="off">
                                                        &nbsp;<b>@lang('menu.service')</b>
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form id="es_settings_form" class="setting_form hide-all"
                                    action="{{ route('settings.send.email.sms.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">@lang('menu.send_email_sms_settings')</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['email_settings__send_inv_via_email'] == '1' ? 'CHECKED' : '' }} name="send_inv_via_email"> &nbsp; <b>@lang('menu.send_invoice_after_sale_via_email')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['email_settings__send_notice_via_sms'] == '1' ? 'CHECKED' : '' }} name="send_notice_via_sms"> &nbsp; <b>@lang('menu.send_notification_after_sale_via_sms')</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ $generalSettings['email_settings__customer_due_reminder_via_email'] == '1' ? 'CHECKED' : '' }} name="cmr_due_rmdr_via_email"> &nbsp; <b>@lang('menu.customer_remainder_via_email')</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" name="cmr_due_rmdr_via_sms" {{ $generalSettings['email_settings__customer_due_reminder_via_sms'] == '1' ? 'CHECKED' : '' }}> &nbsp; <b>@lang('menu.customer_remainder_via_sms')</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                                <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_change')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
            //$('.setting_form').hide();
            $(document).on('click', '.menu_btn', function(e) {
                e.preventDefault();
                var form_name = $(this).data('form');
                $('.setting_form').hide(500);
                $('#' + form_name).show(500);
                $('.menu_btn').removeClass('menu_active');
                $(this).addClass('menu_active d-block');
            });
        });

        $('#business_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.bs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {

                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {

                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {

                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#tax_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.bs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {
                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#dashboard_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.dbs_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val()
                if (idValue == '') {
                    countErrorField += 1;
                    $('#' + inputId).addClass('is-invalid');
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#prefix_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#system_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                    window.location = "{{ url()->current() }}";
                }
            });
        });

        $('#point_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#module_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#es_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush

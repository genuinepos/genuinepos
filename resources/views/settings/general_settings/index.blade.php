@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        label {font-size: 12px !important;}
        ul.menus_unorder_list {list-style: none;float: left;width: 100%;}
        ul.menus_unorder_list .menu_list {border: 1px solid lightgray;display: block;text-align: center;background: linear-gradient(#8c0437ee, #1e000d);}
        ul.menus_unorder_list .menu_list .menu_btn {color: white;padding: 6px 1px;display: block; font-size: 11px;}
        .menu_active {background: white;color: #504d4d!important;font-weight: 700;}
    </style>
@endpush
@section('content')
    <div class="body-woaper mt-5">
        <div class="container-fluid pt-1">
            <div class="form_element">
                <div class="py-2 px-2 form-header">
                    <div class="row">
                        <div class="col-6"><h5>General Settings</h5></div>

                        <div class="col-6">
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                </div>
               
                <div class="element-body">
                    <div class="settings_form_area">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="settings_side_menu">
                                    <ul class="menus_unorder_list">
                                        <li class="menu_list">
                                            <a class="menu_btn menu_active" data-form="business_settings_form"
                                                href="#">Business Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="tax_settings_form" href="#">Tax Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="product_settings_form" href="#">Product Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="contact_settings_form" href="#">Contact Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="sale_settings_form" href="#">Add Sale Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="pos_settings_form" href="#">POS Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="purchase_settings_form" href="#">Purchase Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="dashboard_settings_form" href="#">Dashboard Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="prefix_settings_form" href="#">Prefix Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="system_settings_form" href="#">System Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="point_settings_form" href="#">Reward Point Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="module_settings_form" href="#">Modules Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="es_settings_form" href="#">Send Email & SMS Settings</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="email_settings_form" href="#">Email Setting</a>
                                        </li>

                                        <li class="menu_list">
                                            <a class="menu_btn" data-form="sms_settings_form" href="#">SMS Setting</a>
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
                                            <h6 class="text-primary">Business Settings </h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Business Name :</strong></label>
                                            <input type="text" name="shop_name" class="form-control bs_input"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Address :</strong></label>
                                            <input type="text" name="address" class="form-control bs_input"
                                                autocomplete="off" placeholder="Business address"
                                                value="{{ json_decode($generalSettings->business, true)['address'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Phone :</strong></label>
                                            <input type="text" name="phone" class="form-control bs_input" placeholder="Business phone number"
                                                value="{{ json_decode($generalSettings->business, true)['phone'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>Email :</strong></label>
                                            <input type="text" name="email" class="form-control bs_input" placeholder="Business email address"
                                                value="{{ json_decode($generalSettings->business, true)['email'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Start Date :</strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                </div>
                                                <input type="text" name="start_date" class="form-control" autocomplete="off"
                                                    value="{{ json_decode($generalSettings->business, true)['start_date'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Default Profit(%) :</strong><span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="default_profit" class="form-control bs_input"
                                                autocomplete="off" data-name="Default profit" id="default_profit"
                                                value="{{ json_decode($generalSettings->business, true)['default_profit'] }}">
                                            <span class="error error_default_profit"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>Business Logo :</strong><span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="business_logo" id="business_logo">
                                            <small>Previous logo (if exists) will be replaced</small>
                                            <span class="error error_business_logo"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Currency :</strong><span class="text-danger">*</span></label>
                                            <select name="currency" class="form-control bs_input" data-name="Currency"
                                                id="currency">
                                                @foreach ($currencies as $currency)
                                                    <option
                                                        {{ json_decode($generalSettings->business, true)['currency'] == $currency->symbol ? 'SELECTED' : '' }}
                                                        value="{{ $currency->symbol }}">
                                                        {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_currency"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Financial year start month:</strong> <span
                                                    class="text-danger">*</span></label>
                                            <select name="financial_year_start" class="form-control bs_input"
                                                data-name="Financial year start month" id="financial_year_start">
                                                @foreach ($months as $month)
                                                    <option value="{{ $month->month }}"
                                                        {{ json_decode($generalSettings->business, true)['financial_year_start'] == $month->month ? 'SELECTED' : '' }}>
                                                        {{ $month->month }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_financial_year_start"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>Date Format:</strong><span class="text-danger">*</span></label>
                                            <select name="date_format" class="form-control bs_input" data-name="Date format"
                                                id="date_format">
                                                <option value="d-m-Y"
                                                    {{ json_decode($generalSettings->business, true)['date_format'] == 'd-m-Y' ? 'SELECTED' : '' }}>
                                                    dd-mm-yyyy</option>
                                                <option value="m-d-Y"
                                                    {{ json_decode($generalSettings->business, true)['date_format'] == 'm-d-Y' ? 'SELECTED' : '' }}>
                                                    mm-dd-yyyy</option>
                                                <option value="m/d/Y"
                                                    {{ json_decode($generalSettings->business, true)['date_format'] == 'm/d/Y' ? 'SELECTED' : '' }}>
                                                    mm/dd/yyyy</option>
                                                <option value="d/m/Y"
                                                    {{ json_decode($generalSettings->business, true)['date_format'] == 'd/m/Y' ? 'SELECTED' : '' }}>
                                                    dd/mm/yyyy</option>
                                            </select>
                                            <span class="error error_date_format"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Time Format:</strong><span class="text-danger">*</span></label>
                                            <select name="time_format" class="form-control bs_input" data-name="Time format"
                                                id="time_format">
                                                <option value="12"
                                                    {{ json_decode($generalSettings->business, true)['time_format'] == '12' ? 'SELECTED' : '' }}>
                                                    12 Hour</option>
                                                <option value="24"
                                                    {{ json_decode($generalSettings->business, true)['time_format'] == '24' ? 'SELECTED' : '' }}>
                                                    24 Hour</option>
                                            </select>
                                            <span class="error error_time_format"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Time Zone:</strong><span class="text-danger">*</span></label>
                                            <select name="timezone" class="form-control bs_input" data-name="Time format"
                                                id="time_format">
                                                <option value="">TimeZone</option>
                                                @foreach ($timezones as $timezone)
                                                    <option
                                                        {{ json_decode($generalSettings->business, true)['timezone'] == $timezone->name ? 'SELECTED' : '' }}
                                                        value="{{ $timezone->name }}">{{ $timezone->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_time_format"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="tax_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.tax.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Tax Settings</h6>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label>Tax 1 Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_1_name" class="form-control" autocomplete="off"
                                                placeholder="GST / VAT / Other"
                                                value="{{ json_decode($generalSettings->tax, true)['tax_1_name'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Tax 1 No : <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_1_no" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->tax, true)['tax_1_no'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Tax 2 Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_2_name" class="form-control" autocomplete="off"
                                                placeholder="GST / VAT / Other"
                                                value="{{ json_decode($generalSettings->tax, true)['tax_2_name'] }}">
                                        </div>

                                        <div class="col-md-4 mt-2">
                                            <label>Tax 2 No : <span class="text-danger">*</span></label>
                                            <input type="text" name="tax_2_no" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->tax, true)['tax_2_no'] }}">
                                        </div>

                                        <div class="col-md-8">
                                            <div class="row mt-5">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->tax, true)['is_tax_en_purchase_sale'] == '1' ? 'CHECKED' : '' }} name="is_tax_en_purchase_sale" id="is_tax_en_purchase_sale"> 
                                                        &nbsp; Enable inline tax in purchase and sell
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="product_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.product.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Product Settings</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Product Code Prefix (SKU) :</strong></label>
                                            <input type="text" name="product_code_prefix" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Default Unit :</strong></label>
                                            <select name="default_unit_id" class="form-control" id="default_unit_id">
                                                <option value="null">None</option>
                                                @foreach ($units as $unit)
                                                    <option
                                                        {{ json_decode($generalSettings->product, true)['default_unit_id'] == $unit->id ? 'SELECTED' : '' }}
                                                        value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->product, true)['is_enable_brands'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_enable_brands"> &nbsp; <b>Enable Brands</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->product, true)['is_enable_categories'] == '1' ? 'CHECKED' : '' }} name="is_enable_categories"> &nbsp; <b>Enable Categories</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1' ? 'CHECKED' : '' }} name="is_enable_sub_categories"> &nbsp; <b>Enable Sub-Categories</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_enable_price_tax"> &nbsp; <b>Enable Price & Tax info</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->product, true)['is_enable_warranty'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_enable_warranty"> &nbsp; <b>Enable Warranty</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="contact_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.contact.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Contact Settings</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Default Credit Limit <i data-bs-toggle="tooltip" data-bs-placement="top" title="Leave it blank if you don’t need this restriction." class="fas fa-info-circle tp"></i> :</strong></label>
                                            <input type="text" name="contact_default_cr_limit" class="form-control" autocomplete="off" value="{{ $generalSettings->contact_default_cr_limit }}" placeholder="Default Credit Limit">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="sale_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.sale.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Sale Settings</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Default Sale Discount :</strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-percent text-dark input_i"></i></span>
                                                </div>
                                                <input type="text" name="default_sale_discount" class="form-control"
                                                    autocomplete="off"
                                                    value="{{ json_decode($generalSettings->sale, true)['default_sale_discount'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Default Sale Tax :</strong></label>
                                            <select name="default_tax_id" class="form-control">
                                                <option value="null">None</option>
                                                @foreach ($taxes as $tax)
                                                    <option
                                                        {{ json_decode($generalSettings->sale, true)['default_tax_id'] == $tax->tax_percent ? 'SELECTED' : '' }}
                                                        value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Sales Commission Agent:</strong></label>
                                            <select class="form-control" name="sales_cmsn_agnt">
                                                <option
                                                    {{ json_decode($generalSettings->sale, true)['sales_cmsn_agnt'] == 'disable' ? 'SELECTED' : '' }}
                                                    value="disable">Disable</option>
                                                <option
                                                    {{ json_decode($generalSettings->sale, true)['sales_cmsn_agnt'] == 'logged_in_user' ? 'SELECTED' : '' }}
                                                    value="logged_in_user">Logged in user</option>
                                                <option
                                                    {{ json_decode($generalSettings->sale, true)['sales_cmsn_agnt'] == 'user' ? 'SELECTED' : '' }}
                                                    value="user">Select from user&#039;s list</option>
                                                <option
                                                    {{ json_decode($generalSettings->sale, true)['sales_cmsn_agnt'] == 'select_form_cmsn_list' ? 'SELECTED' : '' }}
                                                    value="select_form_cmsn_list">Select from commission agent&#039;s list
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>Default Selling Price Group :</strong></label>
                                            <select name="default_price_group_id" class="form-control">
                                                <option value="null">None</option>
                                                @foreach ($price_groups as $pg)
                                                    <option {{ json_decode($generalSettings->sale, true)['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="pos_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.pos.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">POS Settings</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->pos, true)['is_disable_multiple_pay'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_disable_multiple_pay"> &nbsp; <b>Disable Multiple Pay</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->pos, true)['is_disable_draft'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_disable_draft"> &nbsp; <b>Disable Draft</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox" {{ json_decode($generalSettings->pos, true)['is_disable_quotation'] == '1' ? 'CHECKED' : '' }} name="is_disable_quotation"> &nbsp; <b>Disable Quotation</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->pos, true)['is_disable_challan'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_disable_challan"> &nbsp; <b>Disable Challan</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox" {{ json_decode($generalSettings->pos, true)['is_disable_discount'] == '1' ? 'CHECKED' : '' }} name="is_disable_discount"> &nbsp; <b>Disable Discount</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->pos, true)['is_disable_order_tax'] == '1' ? 'CHECKED' : '' }} name="is_disable_order_tax"> &nbsp; <b>Disable order tax</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->pos, true)['is_show_recent_transactions'] == '1' ? 'CHECKED' : '' }} name="is_show_recent_transactions" autocomplete="off"> &nbsp; <b>Don't show recent transactions</b> 
                                                </p>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->pos, true)['is_show_credit_sale_button'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_show_credit_sale_button"> &nbsp; <b>Show Due Sale Button</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->pos, true)['is_disable_hold_invoice'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_disable_hold_invoice"> &nbsp; <b>Disable Hold Invoice</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap mt-3">
                                                    <input type="checkbox" name="is_show_partial_sale_button"
                                                        {{ json_decode($generalSettings->pos, true)['is_show_partial_sale_button'] == '1' ? 'CHECKED' : '' }}>
                                                    &nbsp; &nbsp; <b>Show Partial Sale Button</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="purchase_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.purchase.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Purchase Settings</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-7">
                                            <div class="row mt-2">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_edit_pro_price"> &nbsp; <b>Enable editing  product price from purchase screen</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="row mt-2">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->purchase, true)['is_enable_status'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_enable_status"> &nbsp; <b>Enable Purchase Status</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="row mt-2">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1' ? 'CHECKED' : '' }}
                                                        name="is_enable_lot_no"> &nbsp; <b>Enable Lot number</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="dashboard_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.dashboard.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Dashboard Settings</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label><strong>View Stock Expiry Alert For :</strong> <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="view_stock_expiry_alert_for"
                                                    class="form-control dbs_input" id="dbs_view_stock_expiry_alert_for"
                                                    data-name="Day amount" autocomplete="off"
                                                    value="{{ json_decode($generalSettings->dashboard, true)['view_stock_expiry_alert_for'] }}">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text input-group-text-sm"
                                                        id="basic-addon1">Days</span>
                                                </div>
                                            </div>
                                            <span class="error error_dbs_view_stock_expiry_alert_for"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="prefix_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.prefix.settings') }}" method="post">
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Prefix Settings</h6>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Purchase Invoice :</strong></label>
                                            <input type="text" name="purchase_invoice" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['purchase_invoice'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Sale Invoice :</strong></label>
                                            <input type="text" name="sale_invoice" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['sale_invoice'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Purchase Return :</strong></label>
                                            <input type="text" name="purchase_return" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['purchase_return'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Stock Transfer :</strong></label>
                                            <input type="text" name="stock_transfer" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['stock_transfer'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Stock Adjustment :</strong></label>
                                            <input type="text" name="stock_djustment" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['stock_djustment'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Sale Return :</strong></label>
                                            <input type="text" name="sale_return" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['sale_return'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Expenses :</strong></label>
                                            <input type="text" name="expenses" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['expenses'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Expense Payment :</strong></label>
                                            <input type="text" name="expanse_payment" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['expanse_payment'] }}">
                                        </div>
                                      
                                        <div class="col-md-4">
                                            <label><strong>Purchase Payment :</strong></label>
                                            <input type="text" name="purchase_payment" class="form-control"
                                                autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['purchase_payment'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Sale Payment :</strong></label>
                                            <input type="text" name="sale_payment" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['sale_payment'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>Supplier ID:</strong></label>
                                            <input type="text" name="supplier_id" class="form-control"
                                                autocomplete="off" value="{{ json_decode($generalSettings->prefix, true)['supplier_id'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Customer ID :</strong></label>
                                            <input type="text" name="customer_id" class="form-control" autocomplete="off"
                                                value="{{ json_decode($generalSettings->prefix, true)['customer_id'] }}">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="system_settings_form" class="setting_form d-none"
                                action="" method="post">
                                <div class="form-group">
                                    <div class="setting_form_heading">
                                        <h6 class="text-primary">Prefix Settings</h6>
                                    </div>
                                </div>
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label><strong>Theme Color :</strong></label>
                                        <select name="theme_color" class="form-control" id="theme_color">
                                            <option value="null">Select Theme Color</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label><strong>Default datatable page entries :</strong></label>
                                        <select name="datatable_page_entry" class="form-control" id="datatable_page_entry">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                        <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                    </div>
                                </div>
                            </form>

                                <form id="point_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.reward.point.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <h6 class="text-primary mb-3"><b>Reward Point Settings</b></h6>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == '1' ? 'CHECKED' : '' }} name="enable_cus_point"> &nbsp; <b>Enable Reward Point</b>  
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Reward Point Display Name :</strong></label>
                                            <input type="text" name="point_display_name" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['point_display_name'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <h6 class="text-primary mb-1"><b>Earning Settings</b></h6>
                                        <div class="col-md-4">
                                            <label><strong>Amount spend for unit point : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="left" title="Example: If you set it as 10, then for every $10 spent by customer they will get one reward points. If the customer purchases for $1000 then they will get 100 reward points." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="amount_for_unit_rp" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['amount_for_unit_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Minimum order total to earn reward :</strong> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Example: If you set it as 100 then customer will get reward points only if there invoice total is greater or equal to 100. If invoice total is 99 then they won’t get any reward points.You can set it as minimum 1." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_order_total_for_rp" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['min_order_total_for_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Maximum points per order :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Maximum reward points customers can earn in one invoice. Leave it empty if you don’t want any such restrictions." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="max_rp_per_order" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['max_rp_per_order'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        
                                        <h6 class="text-primary mb-1"><b>Redeem Points Settings</b></h6>
                                       
                                        <div class="col-md-4">
                                            <label><strong>Redeem amount per unit point : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="example: If 1 point is $1 then enter the value as 1. If 2 points is $1 then enter the value as 0.50" class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="redeem_amount_per_unit_rp" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['redeem_amount_per_unit_rp'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Minimum order total to redeem points : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="right" title="Minimum order total for which customers can redeem points. Leave it blank if you don’t need this restriction or you need to give something for free." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_order_total_for_redeem" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['min_order_total_for_redeem'] }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>Minimum redeem point : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum redeem points that can be used per invoice. Leave it blank if you don’t need this restriction." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="min_redeem_point" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['min_redeem_point'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <label><strong>Maximum redeem point per order : </strong>
                                                <i data-bs-toggle="tooltip" data-bs-placement="right" title="Maximum points that can be used in one order. Leave it blank if you don’t need this restriction." class="fas fa-info-circle tp"></i></label>
                                            <input type="number" step="any" name="max_redeem_point" class="form-control" autocomplete="off" value="{{ json_decode($generalSettings->reward_poing_settings, true)['max_redeem_point'] }}">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="module_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.module.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary"><b>Module Settings</b></h6>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['purchases'] == '1' ? 'CHECKED' : '' }}
                                                        name="purchases" autocomplete="off"> &nbsp; <b>Purchases</b>  
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['add_sale'] == '1' ? 'CHECKED' : '' }}
                                                        name="add_sale" autocomplete="off"> &nbsp; <b>Add Sale</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['pos'] == '1' ? 'CHECKED' : '' }}
                                                        name="pos" autocomplete="off"> &nbsp; <b>POS</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['transfer_stock'] == '1' ? 'CHECKED' : '' }}
                                                        name="transfer_stock" autocomplete="off">
                                                    &nbsp; <b>Transfers Stock</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['stock_adjustment'] == '1' ? 'CHECKED' : '' }}
                                                        name="stock_adjustment" autocomplete="off"> &nbsp; <b>Stock Adjustment</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['expenses'] == '1' ? 'CHECKED' : '' }}
                                                        name="expenses" autocomplete="off"> &nbsp; <b>Expenses</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['accounting'] == '1' ? 'CHECKED' : '' }}
                                                        name="accounting" autocomplete="off"> &nbsp; <b>Accounting</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['contacts'] == '1' ? 'CHECKED' : '' }}
                                                        name="contacts" autocomplete="off"> &nbsp; <b>Contacts</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{ json_decode($generalSettings->modules, true)['hrms'] == '1' ? 'CHECKED' : '' }}
                                                        name="hrms" autocomplete="off"> &nbsp; <b>Human Resource Management</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-2">
                                        <div class="col-md-4">
                                            <div class="row ">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->modules, true)['requisite'] == '1' ? 'CHECKED' : '' }} name="requisite" autocomplete="off"> &nbsp; <b>Requisite</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="es_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.send.email.sms.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Send Email & SMS Settings</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->send_es_settings, true)['send_inv_via_email'] == '1' ? 'CHECKED' : '' }} name="send_inv_via_email"> &nbsp; <b>Send Invoice After Sale Via Email</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->send_es_settings, true)['send_notice_via_sms'] == '1' ? 'CHECKED' : '' }} name="send_notice_via_sms"> &nbsp; <b>Send Notification After Sale Via SMS</b> 
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" {{ json_decode($generalSettings->send_es_settings, true)['cmr_due_rmdr_via_email'] == '1' ? 'CHECKED' : '' }} name="cmr_due_rmdr_via_email"> &nbsp; <b>Customer Due Remainder Via Email</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" name="cmr_due_rmdr_via_sms" {{ json_decode($generalSettings->send_es_settings, true)['cmr_due_rmdr_via_sms'] == '1' ? 'CHECKED' : '' }}> &nbsp; <b>Customer Due Remainder Via SMS</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="email_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.email.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">Email Settings</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>MAIL MAILER : </strong></label>
                                            <input type="text" name="MAIL_MAILER" class="form-control es_input"
                                                placeholder="MAIL MAILER" autocomplete="off"
                                                value="{{ env('MAIL_MAILER') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>MAIL HOST :</strong></label>
                                            <input type="text" name="MAIL_HOST" class="form-control es_input"
                                                placeholder="MAIL HOST" autocomplete="off"
                                                value="{{ env('MAIL_HOST') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>MAIL PORT :</strong></label>
                                            <input type="text" name="MAIL_PORT" class="form-control  es_input"
                                                placeholder="MAIL PORT" autocomplete="off"
                                                value="{{ env('MAIL_PORT') }}">
                                        </div>
                                    </div>
                                        
                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>MAIL_USERNAME :</strong></label>
                                            <input type="text" name="MAIL_USERNAME" class="form-control es_input"
                                                placeholder="MAIL USERNAME" autocomplete="off"
                                                value="{{ env('MAIL_USERNAME') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>MAIL PASSWORD :</strong></label>
                                            <input type="text" name="MAIL_PASSWORD" class="form-control es_input"
                                                placeholder="MAIL PASSWORD" autocomplete="off"
                                                value="{{ env('MAIL_PASSWORD') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>MAIL ENCRYPTION :</strong></label>
                                            <input type="text" name="MAIL_ENCRYPTION" class="form-control  es_input"
                                                placeholder="MAIL ENCRYPTION" autocomplete="off"
                                                value="{{ env('MAIL_ENCRYPTION') }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        <div class="col-md-4">
                                            <label><strong>MAIL FROM ADDRESS :</strong></label>
                                            <input type="text" name="MAIL_FROM_ADDRESS" class="form-control es_input"
                                                placeholder="MAIL FROM ADDRESS" autocomplete="off"
                                                value="{{ env('MAIL_FROM_ADDRESS') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>MAIL FROM NAME :</strong></label>
                                            <input type="text" name="MAIL_FROM_NAME" class="form-control es_input"
                                                placeholder="MAIL FROM NAME" autocomplete="off"
                                                value="{{ env('MAIL_FROM_NAME') }}">
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{  env('MAIL_ACTIVE') == 'true' ? 'CHECKED' : '' }}
                                                        name="MAIL_ACTIVE" autocomplete="off"> &nbsp; <b>Is Active</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mt-1">
                                        
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                        </div>
                                    </div>
                                </form>

                                <form id="sms_settings_form" class="setting_form d-none"
                                    action="{{ route('settings.sms.settings') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="setting_form_heading">
                                            <h6 class="text-primary">SMS Settings</h6>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label><strong>SMS URL : </strong></label>
                                            <input type="text" name="SMS_URL" class="form-control"
                                                placeholder="SMS URL" autocomplete="off"
                                                value="{{ env('SMS_URL') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label><strong>API KEY : </strong></label>
                                            <input type="text" name="API_KEY" class="form-control"
                                                placeholder="API KEY" autocomplete="off"
                                                value="{{ env('API_KEY') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label><strong>SENDER ID : </strong></label>
                                            <input type="text" name="SENDER_ID" class="form-control"
                                                placeholder="SENDER ID" autocomplete="off"
                                                value="{{ env('SENDER_ID') }}">
                                        </div>

                                        <div class="col-md-3 mt-1">
                                            <div class="row mt-4">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox"
                                                        {{  env('SMS_ACTIVE') == 'true' ? 'CHECKED' : '' }}
                                                        name="SMS_ACTIVE" autocomplete="off"> &nbsp; <b>Is Active</b> 
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
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
                $(this).addClass('menu_active');
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
                    console.log(data);
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
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#product_settings_form').on('submit', function(e) {
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
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#contact_settings_form').on('submit', function(e) {
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

        $('#sale_settings_form').on('submit', function(e) {
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
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#pos_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#purchase_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    console.log(data);
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
                    console.log(data);
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
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
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
                    console.log(data);
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
                    console.log(data);
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
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#email_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });

        $('#sms_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    console.log(data);
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush

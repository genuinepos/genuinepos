@extends('layout.master')
@push('stylesheets')
    <style>
        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }

        .selected_quotation {
            background-color: #645f61;
            color: #fff !important;
        }

        .quotation_search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
        }

        .quotation_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .quotation_search_result ul li a {
            color: #6b6262;
            font-size: 10px;
            display: block;
            padding: 0px 3px;
        }

        .quotation_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 100%;
            z-index: 9999999;
            padding: 0;
            left: 0%;
            display: none;
            border: 1px solid #706a6d;
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 0px 2px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 11px;
            padding: 2px 2px;
            display: block;
            border: 1px solid lightgray;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70 !important;
            color: #fff !important;
        }


        .dropify-wrapper {
            height: 100px !important;
        }

        tags.tagify {
            min-width: 100%;
        }

        .tagify__input {
            min-width: 100%;
        }

        span.tagify__tag-text {
            font-size: 11px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/css-toggle-switch@latest/dist/toggle-switch.css" />
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
@endpush

@section('title', __('Add Job Card - '))

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name g-1">
                <div class="col-md-6">
                    <h6>{{ __('Add Job Card') }}</h6>
                </div>

                <div class="col-md-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                </div>
            </div>
        </div>
        <form id="add_job_card_form" action="{{ route('services.job.cards.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" name="action" id="action" value="">
            <section class="p-lg-1 p-1">
                <div class="row g-1">
                    <div class="col-lg-12">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1">
                                        <div class="col-md-4">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ __('Customer') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="customer_account_id" class="form-control select2" id="customer_account_id" data-next="date">
                                                            @foreach ($customerAccounts as $customerAccount)
                                                                @php
                                                                    $accountType = $customerAccount->sub_sub_group_number == 6 ? '' : ' -(' . __('Supplier') . ')';
                                                                @endphp
                                                                <option @selected(isset($quotation) && $quotation->customer_account_id == $customerAccount->id) data-default_balance_type="{{ $customerAccount->default_balance_type }}" data-sub_sub_group_number="{{ $customerAccount->sub_sub_group_number }}" data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone . $accountType }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text {{ $generalSettings['subscription']->features['contacts'] == 0 || !auth()->user()->can('customer_add') ? 'disabled_element' : '' }} add_button" id="{{ $generalSettings['subscription']->features['contacts'] == 1 && auth()->user()->can('customer_add') ? 'addContact' : '' }}"><i class="fas fa-plus-square text-dark input_i"></i></span>
                                                        </div>
                                                    </div>
                                                    <span class="error error_customer_account_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Job Card No.') }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="job_card_no" class="form-control fw-bold" id="job_card_no" value="{{ $jobCardNo }}" placeholder="{{ __('Job Card No.') }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control" id="date" data-next="service_type" placeholder="{{ __('Date') }}" autocomplete="off">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Service Type') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select required name="service_type" class="form-control" id="service_type" data-next="delivery_date">
                                                        @foreach (\App\Enums\ServiceType::cases() as $item)
                                                            <option value="{{ $item->value }}">{{ str($item->name)->headline() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="error error_status_id"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 pick_up_on_address_field d-hide">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Pick Up/On site address') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="address" class="form-control" id="address" data-next="delivery_date" placeholder="{{ __('Pick up/On site address') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Delivery Date') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="delivery_date" class="form-control" id="delivery_date" data-next="quotation" placeholder="{{ __('Delivery Date') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Quotation ID.') }}</b></label>
                                                <div class="col-8">
                                                    <div style="position: relative;">
                                                        <input type="text" name="quotation" class="form-control fw-bold" id="quotation" data-next="brand_id" value="{{ $quotation?->quotation_id }}" placeholder="{{ __('Quotation ID.') }}" autocomplete="off">
                                                        <input type="hidden" name="quotation_id" id="quotation_id" value="{{ $quotation?->id }}">

                                                        <div class="quotation_search_result d-hide">
                                                            <ul id="quotation_list" class="list-unstyled"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1">
                                        @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value)
                                            <div class="col-md-4">
                                                <div class="input-group flex-nowrap">
                                                    <label class="col-4"><b>{{ __('Brand.') }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select name="brand_id" class="form-control select2" id="brand_id" data-next="device_id">
                                                                <option value="">{{ __('Select Brand') }}</option>
                                                                @foreach ($brands as $brand)
                                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button {{ !auth()->user()->can('product_brand_add') ? 'disabled_element' : '' }}" id="{{ auth()->user()->can('product_brand_add') ? 'addBrand' : '' }}"><i class="fas fa-plus-square input_i"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-4">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="device_id" class="form-control select2" id="device_id" data-next="device_model_id">
                                                            <option value="">{{ __('Select') }} {{ isset($generalSettings['service_settings__device_label']) ? $generalSettings['service_settings__device_label'] : __('Device') }}</option>
                                                            @foreach ($devices as $device)
                                                                <option value="{{ $device->id }}">{{ $device->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" id="addDevice"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Device Model') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="device_model_id" class="form-control select2" id="device_model_id" data-next="serial_no">
                                                            <option value="">{{ __('Select') }} {{ isset($generalSettings['service_settings__device_model_label']) ? $generalSettings['service_settings__device_model_label'] : __('Device Model') }}</option>
                                                            @foreach ($deviceModels as $deviceModel)
                                                                <option data-checklist="{{ $deviceModel->service_checklist }}" value="{{ $deviceModel->id }}">{{ $deviceModel->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" id="addDeviceModel"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-12">
                                            <p><span class="fw-bold">{{ __('Pre Servicing Checklist: ') }}</span> <small>{{ __('N/A = Not Applicable') }}</small></p>
                                        </div>

                                        <hr>
                                        <div class="row gx-3" id="check_list_area">
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4">
                                                    <b>
                                                        {{ isset($generalSettings['service_settings__serial_number_label']) ? $generalSettings['service_settings__serial_number_label'] : __('Serial Number') }}
                                                    </b>
                                                </label>
                                                <div class="col-8">
                                                    <input type="text" name="serial_no" class="form-control" id="serial_no" data-next="password" placeholder="{{ isset($generalSettings['service_settings__serial_number_label']) ? $generalSettings['service_settings__serial_number_label'] : __('Serial Number') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Password') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="password" class="form-control" id="password" data-next="price_group_id" placeholder="{{ __('Password') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Product Configuration') }}</b></label>
                                                <div class="col-8">
                                                    <input name="product_configuration" class="tags-look" id="product_configuration" placeholder="{{ __('Product Configuration') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Problem Reports') }}</b></label>
                                                <div class="col-8">
                                                    <input name="problems_report" class="tags-look" id="problems_report" placeholder="{{ __('Problem Reported By The Customer') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Product Condition') }}</b></label>
                                                <div class="col-8">
                                                    <input name="product_condition" class="tags-look" id="product_condition" placeholder="{{ __('Condition Of The Product') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body py-0">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-8">
                                            <p class="pt-1"><span class="fw-bold">{{ __('Add Related Service And Parts') }}</span></p>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group mt-1">
                                                <label class="col-4"><b>{{ __('Price Group') }}</b></label>
                                                <div class="col-8">
                                                    <select name="price_group_id" class="form-control" id="price_group_id" data-next="search_product">
                                                        <option value="">{{ __('Default Selling Price Group') }}</option>
                                                        @foreach ($priceGroups as $priceGroup)
                                                            <option {{ $generalSettings['add_sale__default_price_group_id'] == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="searching_area" style="position: relative;">
                                                <label class="fw-bold">{{ __('Search Product') }}</label>
                                                <div class="input-group">
                                                    <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __('Search Product By Name/Code') }}" autocomplete="off">
                                                    @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value && auth()->user()->can('product_add'))
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text {{ !auth()->user()->can('product_add') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('product_add') ? 'addProduct' : '' }}"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="select_area">
                                                    <ul id="list" class="variant_list_area"></ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hidden_fields d-none">
                                            <input type="hidden" id="e_unique_id">
                                            <input type="hidden" id="e_unit_cost_inc_tax">
                                            <input type="hidden" id="e_item_name">
                                            <input type="hidden" id="e_product_id">
                                            <input type="hidden" id="e_variant_id">
                                            <input type="hidden" id="e_is_manage_stock">
                                            <input type="hidden" id="e_tax_amount">
                                            <input type="hidden" id="e_is_show_emi_on_pos">
                                            <input type="hidden" id="e_price_inc_tax">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Quantity') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control fw-bold w-60" id="e_quantity" placeholder="{{ __('Quantity') }}" value="0.00">
                                                <select id="e_unit_id" class="form-control w-40">
                                                    <option value="">{{ __('Unit') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Unit Price (Exc. Tax)') }}</label>
                                            <input {{ auth()->user()->can('edit_price_sale_screen') ? '' : 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_price_exc_tax" placeholder="{{ __('Price Exc. Tax') }}" value="0.00" autocomplete="off">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Discount') }}</label>
                                            <div class="input-group">
                                                <input {{ auth()->user()->can('edit_discount_sale_screen') ? '' : 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_discount" placeholder="{{ __('Discount') }}" value="0.00" autocomplete="off">

                                                <select id="e_discount_type" class="form-control">
                                                    <option value="1">{{ __('Fixed') }}(0.00)</option>
                                                    <option value="2">{{ __('Percentage') }}(%)</option>
                                                </select>

                                                <input type="hidden" id="e_discount_amount">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Vat/Tax') }}</label>
                                            <div class="input-group">
                                                <select id="e_tax_ac_id" class="form-control w-50">
                                                    <option data-product_tax_percent="0.00" value="">{{ __('NoTax') }}</option>
                                                    @foreach ($taxAccounts as $taxAccount)
                                                        <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                            {{ $taxAccount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select id="e_tax_type" class="form-control w-50" tabindex="-1">
                                                    <option value="1">{{ __('Exclusive') }}</option>
                                                    <option value="2">{{ __('Inclusive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 align-items-end">
                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Subtotal') }}</label>
                                            <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <a href="#" class="btn btn-sm btn-success" id="add_item">{{ __('Add') }}</a>
                                            <input type="reset" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger" value="{{ __('Reset') }}">
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="sale-item-sec">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="display data__table table sale-product-table">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">{{ __('Product') }}</th>
                                                                <th class="text-start">{{ __('Quantity') }}</th>
                                                                <th class="text-start">{{ __('Unit') }}</th>
                                                                <th class="text-start">{{ __('Price Inc. Tax') }}</th>
                                                                <th class="text-start">{{ __('Subtotal') }}</th>
                                                                <th class="text-start"><i class="fas fa-minus text-dark"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="jobcard_product_list">

                                                            @if (isset($quotation))

                                                                @php
                                                                    $itemUnitsArray = [];
                                                                @endphp

                                                                @foreach ($quotation->saleProducts as $saleProduct)
                                                                    @php
                                                                        if (isset($saleProduct->product_id)) {
                                                                            $itemUnitsArray[$saleProduct->product_id][] = [
                                                                                'unit_id' => $saleProduct->product->unit->id,
                                                                                'unit_name' => $saleProduct->product->unit->name,
                                                                                'unit_code_name' => $saleProduct->product->unit->code_name,
                                                                                'base_unit_multiplier' => 1,
                                                                                'multiplier_details' => '',
                                                                                'is_base_unit' => 1,
                                                                            ];
                                                                        }

                                                                        $baseUnitMultiplier = $saleProduct?->unit?->base_unit_multiplier ? $saleProduct?->unit?->base_unit_multiplier : 1;
                                                                    @endphp

                                                                    <tr id="select_item">
                                                                        <td class="text-start">
                                                                            @php
                                                                                $variant = $saleProduct->variant_id ? ' -' . $saleProduct->variant->variant_name : '';

                                                                                $variantId = $saleProduct->variant_id ? $saleProduct->variant_id : 'noid';

                                                                                $baseUnitMultiplier = $saleProduct?->unit?->base_unit_multiplier ? $saleProduct?->unit?->base_unit_multiplier : 1;
                                                                            @endphp

                                                                            <span class="product_name">{{ $saleProduct->product->name . $variant }} {!! $saleProduct?->product?->is_manage_stock == 0 ? ' <span class="badge badge-sm bg-primary"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '' !!}</span>
                                                                            <input type="hidden" id="item_name" value="{{ $saleProduct->product->name . $variant }}">
                                                                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $saleProduct->product_id }}">
                                                                            <input type="hidden" value="{{ $variantId }}" id="variant_id" name="variant_ids[]">
                                                                            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $saleProduct->tax_type }}">
                                                                            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $saleProduct->tax_ac_id }}">
                                                                            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $saleProduct->unit_tax_percent }}">
                                                                            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $saleProduct->unit_tax_amount }}">
                                                                            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $saleProduct->unit_discount_type }}">
                                                                            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $saleProduct->unit_discount }}">
                                                                            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $saleProduct->unit_discount_amount }}">
                                                                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $saleProduct->unit_cost_inc_tax }}">

                                                                            <input type="hidden" class="unique_id" id="{{ $saleProduct->product_id . $variantId }}" value="{{ $saleProduct->product_id . $variantId }}">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <span id="span_quantity" class="fw-bold">{{ $saleProduct->quantity }}</span>
                                                                            <input type="hidden" name="quantities[]" id="quantity" value="{{ $saleProduct->quantity }}">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <span id="span_unit">{{ $saleProduct?->unit?->name }}</span>
                                                                            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $saleProduct?->unit?->id }}">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="{{ $saleProduct->unit_price_exc_tax }}">
                                                                            <input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="{{ $saleProduct->unit_price_inc_tax }}">
                                                                            <span id="span_unit_price_inc_tax" class="fw-bold">{{ $saleProduct->unit_price_inc_tax }}</span>
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <strong><span id="span_subtotal">{{ $saleProduct->subtotal }}</span></strong>
                                                                            <input type="hidden" value="{{ $saleProduct->subtotal }}" readonly name="subtotals[]" id="subtotal">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Technician Comment') }}</b></label>
                                                <input type="text" name="technician_comment" class="form-control" id="technician_comment" data-next="status_id" placeholder="{{ __('Technician Comment') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ __('Status') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="status_id" class="form-control select2" id="status_id" data-next="due_date">
                                                            <option value="">{{ __('Select Status') }}</option>
                                                            @foreach ($status as $status)
                                                                @php
                                                                    $defaultStatus = isset($generalSettings['service_settings__default_status_id']) ? $generalSettings['service_settings__default_status_id'] : null;
                                                                @endphp
                                                                <option @selected($defaultStatus == $status->id) value="{{ $status->id }}" data-icon="fa-solid fa-circle" data-color="{{ $status->color_code }}">{{ $status->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" id="addStatus"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Total Cost') }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_cost" class="form-control fw-bold" id="total_cost" placeholder="{{ __('0.00') }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Document') }}</b></label>
                                                <div class="col-8">
                                                    <input type="file" name="document" class="form-control" id="document" autocomplete="off">
                                                    <span class="error error_document"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Due Date') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="due_date" class="form-control" id="due_date" data-next="custom_field_1" placeholder="{{ __('Due Date') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Send Notification') }}</b></label>
                                                <div class="col-8">
                                                    <select name="send_notification" class="form-control" id="send_notification">
                                                        <option value="">{{ __('None') }}</option>
                                                        <option value="email">{{ __('Email') }}</option>
                                                        <option value="sms">{{ __('Sms') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_1_label']) ? $generalSettings['service_settings__custom_field_1_label'] : __('Custom Field 1') }}</b></label>
                                                <input type="text" name="custom_field_1" class="form-control" id="custom_field_1" data-next="custom_field_2" placeholder="{{ isset($generalSettings['service_settings__custom_field_1_label']) ? $generalSettings['service_settings__custom_field_1_label'] : __('Custom Field 1') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_2_label']) ? $generalSettings['service_settings__custom_field_2_label'] : __('Custom Field 1') }}</b></label>
                                                <input type="text" name="custom_field_2" class="form-control" id="custom_field_2" data-next="custom_field_3" placeholder="{{ isset($generalSettings['service_settings__custom_field_2_label']) ? $generalSettings['service_settings__custom_field_2_label'] : __('Custom Field 2') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_3_label']) ? $generalSettings['service_settings__custom_field_3_label'] : __('Custom Field 1') }}</b></label>
                                                <input type="text" name="custom_field_3" class="form-control" id="custom_field_3" data-next="custom_field_4" placeholder="{{ isset($generalSettings['service_settings__custom_field_3_label']) ? $generalSettings['service_settings__custom_field_3_label'] : __('Custom Field 3') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_4_label']) ? $generalSettings['service_settings__custom_field_4_label'] : __('Custom Field 4') }}</b></label>
                                                <input type="text" name="custom_field_4" class="form-control" id="custom_field_4" data-next="custom_field_5" placeholder="{{ isset($generalSettings['service_settings__custom_field_4_label']) ? $generalSettings['service_settings__custom_field_4_label'] : __('Custom Field 4') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_5_label']) ? $generalSettings['service_settings__custom_field_5_label'] : __('Custom Field 5') }}</b></label>
                                                <input type="text" name="custom_field_5" class="form-control" id="custom_field_5" data-next="save_and_print" placeholder="{{ isset($generalSettings['service_settings__custom_field_5_label']) ? $generalSettings['service_settings__custom_field_5_label'] : __('Custom Field 5') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>

                                <button type="submit" name="action" value="save_and_print" class="btn btn-success submit_button p-1" id="save_and_print">{{ __('Save & Print') }}</button>
                                <button type="submit" name="action" value="save" class="btn btn-success submit_button p-1" id="save">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <div class="modal fade" id="deviceAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>

    <div class="modal fade" id="deviceModelAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>

    <div class="modal fade" id="statusAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>

    @if ($generalSettings['subscription']->features['contacts'] == 1 && auth()->user()->can('customer_add'))
        <div class="modal fade" id="addOrEditContactModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif

    @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value && auth()->user()->can('product_add'))
        <div class="modal fade" id="addQuickProductModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

        <div class="modal fade" id="unitAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="categoryAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif

    @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value && auth()->user()->can('product_brand_add'))
        <!-- Add Brand Modal -->
        <div class="modal fade" id="brandAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <!-- Add Brand Modal End -->
    @endif
@endsection
@push('scripts')
    @include('services.job_cards.js_partials.create_js')
@endpush

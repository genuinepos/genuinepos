@extends('layout.master')
@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
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

@section('title', __('Edit Job Card - '))

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name g-1">
                <div class="col-md-6">
                    <h6>{{ __('Edit Job Card') }}</h6>
                </div>

                <div class="col-md-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                </div>
            </div>
        </div>
        <form id="edit_job_card_form" action="{{ route('services.job.cards.update', $jobCard->id) }}" enctype="multipart/form-data" method="POST">
            @csrf
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
                                                                <option @selected($customerAccount->id == $jobCard->customer_account_id) data-default_balance_type="{{ $customerAccount->default_balance_type }}" data-sub_sub_group_number="{{ $customerAccount->sub_sub_group_number }}" data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone }}</option>
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
                                                    <input readonly type="text" name="job_card_no" class="form-control fw-bold" id="job_card_no" value="{{ $jobCard->job_no }}" placeholder="{{ __('Job Card No.') }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control" id="date" data-next="service_type" value="{{ date($dateFormat, strtotime($jobCard->date_ts)) }}" placeholder="{{ __('Date') }}" autocomplete="off">
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
                                                            <option @selected($item->value == $jobCard->service_type) value="{{ $item->value }}">{{ str($item->name)->headline() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="error error_status_id"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4 pick_up_on_address_field {{ $jobCard->service_type == 1 ? 'd-hide' : '' }} ">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Pick Up/On site address') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="address" class="form-control" id="address" data-next="delivery_date" value="{{ $jobCard->address }}" placeholder="{{ __('Pick up/On site address') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Delivery Date') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="delivery_date" class="form-control" id="delivery_date" data-next="brand_id" value="{{ isset($jobCard->delivery_date_ts) ? date($dateFormat, strtotime($jobCard->delivery_date_ts)) : '' }}" placeholder="{{ __('Delivery Date') }}" autocomplete="off">
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
                                                                    <option @selected($brand->id == $jobCard->brand_id) value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                                                <label class="col-4"><b>{{ __('Device') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="device_id" class="form-control select2" id="device_id" data-next="device_model_id">
                                                            <option value="">{{ __('Select Device') }}</option>
                                                            @foreach ($devices as $device)
                                                                <option @selected($device->id == $jobCard->device_id) value="{{ $device->id }}">{{ $device->name }}</option>
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
                                                <label class="col-4"><b>{{ __('Device Model') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select name="device_model_id" class="form-control select2" id="device_model_id" data-next="serial_no">
                                                            <option value="">{{ __('Select Device Model') }}</option>
                                                            @foreach ($deviceModels as $deviceModel)
                                                                <option @selected($deviceModel->id == $jobCard->device_model_id) data-checklist="{{ $deviceModel->service_checklist }}" value="{{ $deviceModel->id }}">{{ $deviceModel->name }}</option>
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
                                            @if (isset($jobCard->service_checklist) && is_array($jobCard->service_checklist))
                                                @php
                                                    $index = 0;
                                                @endphp
                                                @foreach ($jobCard->service_checklist as $key => $value)
                                                    <div class="col-md-2">
                                                        <p class="fw-bold text-primary">{{ $key }}</p>
                                                        <div class="switch-toggle switch-candy">
                                                            <input type="radio" @checked($value == 'yes') id="{{ $index }}_yes" name="checklist[{{ $key }}]" value="yes">
                                                            <label for="{{ $index }}_yes" class="text-success">✔</label>

                                                            <input type="radio" @checked($value == 'no') id="{{ $index }}_no" name="checklist[{{ $key }}]" value="no">
                                                            <label for="{{ $index }}_no" class="text-danger">❌</label>

                                                            <input type="radio" @checked($value == 'na') id="{{ $index }}_na" name="checklist[{{ $key }}]" value="na">
                                                            <label for="{{ $index }}_na">N/A</label>
                                                            <a></a>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $index++;
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Serial Number') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="serial_no" class="form-control" id="serial_no" value="{{ $jobCard->serial_no }}" data-next="password" placeholder="{{ __('Serial Number') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Password') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="password" class="form-control" id="password" data-next="price_group_id" value="{{ $jobCard->password }}" placeholder="{{ __('Password') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Product Configuration') }}</b></label>
                                                <div class="col-8">
                                                    <input name="product_configuration" class="tags-look" id="product_configuration" value="{{ $jobCard->product_configuration }}" placeholder="{{ __('Product Configuration') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Problem Reports') }}</b></label>
                                                <div class="col-8">
                                                    <input name="problems_report" class="tags-look" id="problems_report" value="{{ $jobCard->problems_report }}" placeholder="{{ __('Problem Reported By The Customer') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Product Condition') }}</b></label>
                                                <div class="col-8">
                                                    <input name="product_condition" class="tags-look" id="product_condition" value="{{ $jobCard->product_condition }}" placeholder="{{ __('Condition Of The Product') }}">
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
                                                    @if (auth()->user()->can('product_add'))
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
                                            <input type="number" step="any" class="form-control fw-bold" id="e_price_exc_tax" placeholder="{{ __('Price Exc. Tax') }}" value="0.00">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Discount') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_discount" placeholder="{{ __('Discount') }}" value="0.00">

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
                                                            @php
                                                                $itemUnitsArray = [];
                                                            @endphp

                                                            @foreach ($jobCard->jobCardProducts as $jobCardProduct)
                                                                @php
                                                                    if (isset($jobCardProduct->product_id)) {
                                                                        $itemUnitsArray[$jobCardProduct->product_id][] = [
                                                                            'unit_id' => $jobCardProduct->product->unit->id,
                                                                            'unit_name' => $jobCardProduct->product->unit->name,
                                                                            'unit_code_name' => $jobCardProduct->product->unit->code_name,
                                                                            'base_unit_multiplier' => 1,
                                                                            'multiplier_details' => '',
                                                                            'is_base_unit' => 1,
                                                                        ];
                                                                    }

                                                                    $baseUnitMultiplier = $jobCardProduct?->unit?->base_unit_multiplier ? $jobCardProduct?->unit?->base_unit_multiplier : 1;
                                                                @endphp

                                                                <tr id="select_item">
                                                                    <td class="text-start">
                                                                        @php
                                                                            $variant = $jobCardProduct->variant_id ? ' -' . $jobCardProduct->variant->variant_name : '';

                                                                            $variantId = $jobCardProduct->variant_id ? $jobCardProduct->variant_id : 'noid';

                                                                            $baseUnitMultiplier = $jobCardProduct?->unit?->base_unit_multiplier ? $jobCardProduct?->unit?->base_unit_multiplier : 1;
                                                                        @endphp

                                                                        <span class="product_name">{{ $jobCardProduct->product->name . $variant }} {!! $jobCardProduct?->product?->is_manage_stock == 0 ? ' <span class="badge badge-sm bg-primary"><i class="fas fa-wrench mr-1 text-white"></i></span>' : '' !!}</span>
                                                                        <input type="hidden" id="item_name" value="{{ $jobCardProduct->product->name . $variant }}">
                                                                        <input type="hidden" name="product_ids[]" id="product_id" value="{{ $jobCardProduct->product_id }}">
                                                                        <input type="hidden" value="{{ $variantId }}" id="variant_id" name="variant_ids[]">
                                                                        <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $jobCardProduct->tax_type }}">
                                                                        <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $jobCardProduct->tax_ac_id }}">
                                                                        <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $jobCardProduct->unit_tax_percent }}">
                                                                        <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $jobCardProduct->unit_tax_amount }}">
                                                                        <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $jobCardProduct->unit_discount_type }}">
                                                                        <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $jobCardProduct->unit_discount }}">
                                                                        <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $jobCardProduct->unit_discount_amount }}">
                                                                        <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $jobCardProduct->unit_cost_inc_tax }}">
                                                                        <input type="hidden" name="job_card_product_ids[]" value="{{ $jobCardProduct->id }}">

                                                                        <input type="hidden" class="unique_id" id="{{ $jobCardProduct->product_id . $variantId }}" value="{{ $jobCardProduct->product_id . $variantId }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_quantity" class="fw-bold">{{ $jobCardProduct->quantity }}</span>
                                                                        <input type="hidden" name="quantities[]" id="quantity" value="{{ $jobCardProduct->quantity }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_unit">{{ $jobCardProduct?->unit?->name }}</span>
                                                                        <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $jobCardProduct?->unit?->id }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="{{ $jobCardProduct->unit_price_exc_tax }}">
                                                                        <input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="{{ $jobCardProduct->unit_price_inc_tax }}">
                                                                        <span id="span_unit_price_inc_tax" class="fw-bold">{{ $jobCardProduct->unit_price_inc_tax }}</span>
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <strong><span id="span_subtotal">{{ $jobCardProduct->subtotal }}</span></strong>
                                                                        <input type="hidden" value="{{ $jobCardProduct->subtotal }}" readonly name="subtotals[]" id="subtotal">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
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
                                                <input type="text" name="technician_comment" class="form-control" id="technician_comment" data-next="status_id" value="{{ $jobCard->technician_comment }}" placeholder="{{ __('Technician Comment') }}" autocomplete="off">
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
                                                                <option @selected($jobCard->status_id == $status->id) value="{{ $status->id }}" data-icon="fa-solid fa-circle" data-color="{{ $status->color_code }}">{{ $status->name }}</option>
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
                                                    <input readonly type="number" step="any" name="total_cost" class="form-control fw-bold" id="total_cost" value="{{ $jobCard->total_cost }}" placeholder="{{ __('0.00') }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Document') }}</b></label>
                                                <div class="col-8">
                                                    <input type="file" name="document" class="form-control" id="document" @if ($jobCard->document) data-default-file="{{ asset('uploads/' . tenant('id') . '/' . 'services/documents/' . $jobCard->document) }}" @endif>
                                                    <span class="error error_document"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Due Date') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="due_date" class="form-control" id="due_date" data-next="custom_field_1" value="{{ isset($jobCard->due_date_ts) ? date($dateFormat, strtotime($jobCard->due_date_ts)) : '' }}" placeholder="{{ __('Due Date') }}" autocomplete="off">
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
                                                <input type="text" name="custom_field_1" class="form-control" id="custom_field_1" data-next="custom_field_2" value="{{ $jobCard->custom_field_1 }}" placeholder="{{ isset($generalSettings['service_settings__custom_field_1_label']) ? $generalSettings['service_settings__custom_field_1_label'] : __('Custom Field 1') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_2_label']) ? $generalSettings['service_settings__custom_field_2_label'] : __('Custom Field 2') }}</b></label>
                                                <input type="text" name="custom_field_2" class="form-control" id="custom_field_2" data-next="custom_field_3" value="{{ $jobCard->custom_field_2 }}" placeholder="{{ isset($generalSettings['service_settings__custom_field_2_label']) ? $generalSettings['service_settings__custom_field_2_label'] : __('Custom Field 2') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_3_label']) ? $generalSettings['service_settings__custom_field_3_label'] : __('Custom Field 3') }}</b></label>
                                                <input type="text" name="custom_field_3" class="form-control" id="custom_field_3" data-next="custom_field_4" value="{{ $jobCard->custom_field_3 }}" placeholder="{{ isset($generalSettings['service_settings__custom_field_3_label']) ? $generalSettings['service_settings__custom_field_3_label'] : __('Custom Field 3') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 gy-1 mt-1">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_4_label']) ? $generalSettings['service_settings__custom_field_4_label'] : __('Custom Field 4') }}</b></label>
                                                <input type="text" name="custom_field_4" class="form-control" id="custom_field_4" data-next="custom_field_5" value="{{ $jobCard->custom_field_4 }}" placeholder="{{ isset($generalSettings['service_settings__custom_field_4_label']) ? $generalSettings['service_settings__custom_field_4_label'] : __('Custom Field 4') }}" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ isset($generalSettings['service_settings__custom_field_5_label']) ? $generalSettings['service_settings__custom_field_5_label'] : __('Custom Field 5') }}</b></label>
                                                <input type="text" name="custom_field_5" class="form-control" id="custom_field_5" data-next="save_changes" value="{{ $jobCard->custom_field_5 }}" placeholder="{{ isset($generalSettings['service_settings__custom_field_5_label']) ? $generalSettings['service_settings__custom_field_5_label'] : __('Custom Field 5') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>

                                <button type="submit" class="btn btn-success submit_button p-1" id="save_changes">{{ __('Save Changes') }}</button>
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

    @if (auth()->user()->can('product_add'))
        <div class="modal fade" id="addQuickProductModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

        <div class="modal fade" id="unitAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="categoryAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif

    <!-- Add Brand Modal -->
    <div class="modal fade" id="brandAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    <!-- Add Brand Modal End -->
@endsection
@push('scripts')
    @include('services.job_cards.js_partials.edit_js')
@endpush

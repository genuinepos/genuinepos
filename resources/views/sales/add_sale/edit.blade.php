@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .input-group-text {
            font-size: 12px !important;
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
            margin: 2px 0px;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70 !important;
            color: #fff !important;
        }

        .input-group-text-sale {
            font-size: 7px !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        .border_red {
            border: 1px solid red !important;
        }

        #display_pre_due {
            font-weight: 600;
        }

        input[type=number]#quantity::-webkit-inner-spin-button,
        input[type=number]#quantity::-webkit-outer-spin-button {
            opacity: 1;
            margin: 0;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: inline-block;
            width: 143px;
        }

        /*.select2-selection:focus {
                     box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
                } */
        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }

        .side-number-field input {
            font-size: 14px;
        }

        .big_amount_field {
            height: 36px;
            font-size: 24px;
            margin-bottom: 3px;
        }

        .checkbox_input_wrap {
            text-align: right;
        }

        .select2-selection:focus {
            box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
            color: #212529;
            background-color: #fff;
            border-color: #86b7fe;
            outline: 0;
        }

        .btn-sale {
            width: calc(50% - 4px);
            padding-left: 0;
            padding-right: 0;
        }

        .sale-item-sec {
            height: 215px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
@endpush
@section('title', 'Edit Add Sale - ')
@section('content')
    @php
        $generalProductSearchService = new App\Services\GeneralSearch\GeneralProductSearchService();

        $account = $sale?->customer;
        $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
        $branchId = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;
        $__branchId = $account?->group?->sub_sub_group_number == 6 ? $branchId : '';
        $amounts = $accountBalanceService->accountBalance(accountId: $account->id, fromDate: null, toDate: null, branchId: $__branchId);
    @endphp
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cart-plus"></span>
                    <h6>{{ __('Edit Add Sale') }}</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="p-1">
            <form id="edit_sale_form" action="{{ route('sales.update', $sale->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section>
                    <div class="sale-content">
                        <div class="row g-1">
                            <div class="col-md-9">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body p-1">
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Customer') }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select name="customer_account_id" class="form-control select2" id="customer_account_id" data-next="status">
                                                                @foreach ($customerAccounts as $customerAccount)
                                                                    <option data-default_balance_type="{{ $customerAccount->default_balance_type }}" data-sub_sub_group_number="{{ $customerAccount->sub_sub_group_number }}" {{ $customerAccount->id == $sale->customer_account_id ? 'SELECTED' : '' }} data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text {{ !auth()->user()->can('customer_add')? 'disabled_element': '' }} add_button" id="{{ auth()->user()->can('customer_add')? 'addContact': '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error error_customer_account_id"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Closing Bal.') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" id="closing_balance" class="form-control fw-bold text-danger" value="{{ $amounts['closing_balance_in_flat_amount'] }}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Status') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select name="status" class="form-control" id="status" data-next="date">
                                                            <option value="{{ \App\Enums\SaleStatus::Final->value }}">{{ \App\Enums\SaleStatus::Final->name }}</option>
                                                        </select>
                                                        <span class="error error_status"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Invoice ID') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control fw-bold" value="{{ $sale->invoice_id }}" placeholder="{{ __('Invoice ID') }}" autocomplete="off" tabindex="-1">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>{{ __('Date') }} <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <input required type="text" name="date" class="form-control" value="{{ date($generalSettings['business__date_format'], strtotime($sale->date)) }}" data-next="sale_account_id" autocomplete="off" id="date">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Sales Account') }} <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <select name="sale_account_id" class="form-control" id="sale_account_id" data-next="price_group_id">
                                                            @foreach ($saleAccounts as $saleAccount)
                                                                <option {{ $saleAccount->id == $sale->sale_account_id ? 'SELECTED' : '' }} value="{{ $saleAccount->id }}">
                                                                    {{ $saleAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_sale_account_id"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Price Group') }}</b></label>
                                                    <div class="col-8">
                                                        <select name="price_group_id" class="form-control" id="price_group_id" data-next="search_product">
                                                            <option value="">{{ __('Default Selling Price Group') }}</option>
                                                            @foreach ($priceGroups as $priceGroup)
                                                                <option {{ $generalSettings['sale__default_price_group_id'] == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body py-0">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="fw-bold">{{ __('Search Product') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __('Search Product By Name/Code') }}" autocomplete="off">
                                                        @if (auth()->user()->can('product_add'))
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text {{ !auth()->user()->can('product_add')? 'disabled_element': '' }} add_button" id="{{ auth()->user()->can('product_add')? 'addProduct': '' }}"><i class="fas fa-plus-square text-dark input_f"></i></span>
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
                                                <input type="hidden" id="e_tax_amount">
                                                <input type="hidden" id="e_is_show_emi_on_pos">
                                                <input type="hidden" id="e_price_inc_tax">
                                                <input type="hidden" id="e_current_quantity" value="0">
                                                <input type="hidden" id="e_current_warehouse_id">
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
                                                <input {{ auth()->user()->can('edit_price_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_price_exc_tax" placeholder="{{ __('Price Exc. Tax') }}" value="0.00">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Discount') }}</label>
                                                <div class="input-group">
                                                    <input {{ auth()->user()->can('edit_discount_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_discount" placeholder="{{ __('Discount') }}" value="0.00">

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
                                                <label class="fw-bold">{{ __('IMEI/SL No./Other Info') }}</label>
                                                <input type="number" step="any" class="form-control fw-bold" id="e_descriptions" value="" placeholder="{{ __('IMEI/SL No./Other Info.') }}">
                                            </div>

                                            <div class="col-xl-2 col-md-6 warehouse_field">
                                                <label class="fw-bold">{{ __('Warehouse') }}</label>
                                                <select class="form-control" id="e_warehouse_id">
                                                    <option value="">{{ __('Select Warehouse') }}</option>
                                                    @foreach ($warehouses as $w)
                                                        @php
                                                            $isGlobal = $w->is_global == 1 ? ' (' . __('Global Access') . ')' : '';
                                                        @endphp
                                                        <option data-w_name="{{ $w->warehouse_name . '/' . $w->warehouse_code . $isGlobal }}" value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code . $isGlobal }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Subtotal') }}</label>
                                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <a href="#" class="btn btn-sm btn-success" id="add_item">{{ __('Add') }}</a>
                                                <input type="reset" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger" value="{{ __('Reset') }}">
                                            </div>

                                            <div class="col-xl-2 col-md-6 offset-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button p-1 m-0">{{ __('Stock') }}</span>
                                                    </div>

                                                    <input type="text" readonly class="form-control text-success fw-bold" autocomplete="off" id="stock_quantity" placeholder="{{ __('Stock Quantity') }}" tabindex="-1">
                                                </div>
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
                                                                    <th class="text-start">{{ __('Stock Location') }}</th>
                                                                    <th class="text-start">{{ __('Quantity') }}</th>
                                                                    <th class="text-start">{{ __('Unit') }}</th>
                                                                    <th class="text-start">{{ __('Price Inc. Tax') }}</th>
                                                                    <th class="text-start">{{ __('Subtotal') }}</th>
                                                                    <th class="text-start"><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_product_list">
                                                                @php
                                                                    $itemUnitsArray = [];
                                                                @endphp

                                                                @foreach ($sale->saleProducts as $saleProduct)
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
                                                                    @endphp

                                                                    <tr id="select_item">
                                                                        <td class="text-start">
                                                                            @php
                                                                                $variant = $saleProduct->variant_id ? ' -' . $saleProduct->variant->variant_name : '';

                                                                                $variantId = $saleProduct->variant_id ? $saleProduct->variant_id : 'noid';

                                                                                $currentStock = $generalProductSearchService->getAvailableStock(productId: $saleProduct->product_id, variantId: $saleProduct->variant_id, branchId: $sale->branch_id);

                                                                                $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;
                                                                            @endphp

                                                                            <span class="product_name">{{ $saleProduct->product->name . $variant }}</span>
                                                                            <input type="hidden" id="item_name" value="{{ $saleProduct->product->name . $variant }}">
                                                                            <input type="hidden" id="is_show_emi_on_pos" value="{{ $saleProduct->product->is_show_emi_on_pos }}">
                                                                            <input type="hidden" name="descriptions[]" id="descriptions" value="{{ $saleProduct->description }}">
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
                                                                            <input type="hidden" name="sale_product_ids[]" value="{{ $saleProduct->id }}">
                                                                            <input type="hidden" id="current_quantity" value="{{ $saleProduct->quantity }}">
                                                                            <input type="hidden" id="current_stock" value="{{ $currentStock }}">
                                                                            <input type="hidden" class="unique_id" id="{{ $saleProduct->product_id . $variantId . $saleProduct->warehouse_id }}" value="{{ $saleProduct->product_id . $variantId . $saleProduct->warehouse_id }}">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="{{ $saleProduct->warehouse_id }}">
                                                                            <input type="hidden" id="current_warehouse_id" value="{{ $saleProduct->warehouse_id }}">

                                                                            @php
                                                                                $stockLocationName = '';
                                                                                if ($saleProduct?->warehouse) {
                                                                                    $stockLocationName = $saleProduct->warehouse->warehouse_name . '-(' . $saleProduct->warehouse->warehouse_code . ')';
                                                                                } else {
                                                                                    if ($sale?->branch) {
                                                                                        if ($sale?->branch?->parentBranch) {
                                                                                            $stockLocationName = $sale?->branch?->parentBranch->name . '(' . $sale?->branch?->area_name;
                                                                                        } else {
                                                                                            $stockLocationName = $sale?->branch?->name . '(' . $sale?->branch?->area_name;
                                                                                        }
                                                                                    } else {
                                                                                        $stockLocationName = $generalSettings['business__business_name'];
                                                                                    }
                                                                                }
                                                                            @endphp

                                                                            <span id="stock_location_name">{{ $stockLocationName }}</span>
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
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <div class="row gx-2 gy-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Ship. Details') }} </b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="shipment_details" class="form-control" id="shipment_details" value="{{ $sale->shipment_details }}" data-next="shipment_address" placeholder="{{ __('Shipment Details') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Ship. Address') }} </b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" value="{{ $sale->shipment_address }}" data-next="shipment_status" placeholder="{{ __('Shipment Address') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Ship. Status') }} </b></label>
                                                    <div class="col-8">
                                                        <select name="shipment_status" class="form-control" id="shipment_status" data-next="delivered_to">
                                                            <option value="">{{ __('Shipment Status') }}</option>
                                                            @foreach (\App\Enums\ShipmentStatus::cases() as $shipmentStatus)
                                                                <option {{ $sale->shipment_status == $shipmentStatus->value ? 'SELECTED' : '' }} value="{{ $shipmentStatus->value }}">{{ $shipmentStatus->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Delivered To') }} </b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" value="{{ $sale->delivered_to }}" data-next="note" placeholder="{{ __('Delivered To') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Sales Note') }}</b></label>
                                                    <div class="col-8">
                                                        <input name="note" type="text" class="form-control" id="note" value="{{ $sale->note }}" data-next="payment_note" placeholder="{{ __('Sales Note') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Payment Note') }}</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="order_discount" placeholder="{{ __('Payment Note') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form_element rounded m-0">
                                    <div class="element-body side-number-field">
                                        <div class="row gx-2">
                                            <label class="col-md-5 text-end"><b>{{ __('Total Item') }}</b></label>
                                            <div class="col-md-7">
                                                <input readonly type="number" step="any" name="total_item" id="total_item" class="form-control fw-bold" value="{{ $sale->total_item }}" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            <label class="col-md-5 text-end"><b>{{ __('Total Qty') }}</b></label>
                                            <div class="col-md-7">
                                                <input readonly type="number" step="any" name="total_qty" id="total_qty" class="form-control fw-bold" value="{{ $sale->total_qty }}" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            <label class="col-md-5 text-end"><b>{{ __('Net Total Amount') }}</b></label>
                                            <div class="col-md-7">
                                                <input readonly type="number" step="any" class="form-control fw-bold" name="net_total_amount" id="net_total_amount" value="{{ $sale->net_total_amount }}" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            <label class="col-md-5 text-end"><b>{{ __('Sale Discount') }}</b></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <input name="order_discount" type="number" step="any" class="form-control fw-bold" id="order_discount" data-next="order_discount_type" value="{{ $sale->order_discount }}">
                                                    <input name="order_discount_amount" step="any" type="number" class="d-hide" id="order_discount_amount" value="{{ $sale->order_discount_amount }}" tabindex="-1">

                                                    <select name="order_discount_type" class="form-control" id="order_discount_type" data-next="sale_tax_ac_id">
                                                        <option value="1">{{ __('Fixed') }}(0.00)</option>
                                                        <option {{ $sale->order_discount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __('Percentage') }}(%)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            <label class="col-md-5 text-end"><b>{{ __('Sale Tax') }}</b></label>
                                            <div class="col-md-7">
                                                <select name="sale_tax_ac_id" class="form-control" id="sale_tax_ac_id" data-next="shipment_charge">
                                                    <option data-order_tax_percent="0.00" value="">{{ __('No Vat/Tax') }}</option>
                                                    @foreach ($taxAccounts as $taxAccount)
                                                        <option {{ $sale->sale_tax_ac_id == $taxAccount->id ? 'SELECTED' : '' }} data-order_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                            {{ $taxAccount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="number" step="any" class="d-none" name="order_tax_percent" id="order_tax_percent" value="{{ $sale->order_tax_percent }}">
                                                <input type="number" step="any" class="d-none" name="order_tax_amount" id="order_tax_amount" value="{{ $sale->order_tax_amount }}">
                                            </div>
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            <label class="col-md-5 text-end"><b>{{ __('Shipment Charge') }}</b></label>
                                            <div class="col-md-7">
                                                <input name="shipment_charge" type="number" step="any" class="form-control fw-bold" id="shipment_charge" data-next="received_amount" value="{{ $sale->shipment_charge }}">
                                            </div>
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            <label class="col-md-5 text-end"><b>{{ __('Total Invoice Amt.') }}</b></label>
                                            <div class="col-md-7">
                                                <input type="number" step="any" name="total_invoice_amount" id="total_invoice_amount" class="form-control fw-bold" value="{{ $sale->total_invoice_amount }}" tabindex="-1">
                                                <input type="number" step="any" name="sales_ledger_amount" id="sales_ledger_amount" class="d-none" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="payment_body">
                                            <div class="row gx-2 mt-1">
                                                <label class="col-md-5 text-end"><b>{{ __('Received Amt.') }} >></b></label>
                                                <div class="col-md-7">
                                                    <input type="number" step="any" name="received_amount" class="form-control big_amount_field fw-bold" id="received_amount" data-next="payment_method_id" value="0.00" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="row gx-2 mt-1">
                                                <label class="col-md-5 text-end"><b>{{ __('Payment Method') }}</b></label>
                                                <div class="col-md-7">
                                                    <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                                        @foreach ($methods as $method)
                                                            <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">
                                                                {{ $method->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_payment_method_id"></span>
                                                </div>
                                            </div>

                                            <div class="row gx-2 mt-1">
                                                <label class="col-md-5 text-end"><b>{{ __('Debit A/c') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-md-7">
                                                    <select name="account_id" class="form-control" id="account_id" data-next="save_changes">
                                                        @foreach ($accounts as $ac)
                                                            @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                                                @continue
                                                            @endif

                                                            <option value="{{ $ac->id }}">
                                                                @php
                                                                    $acNo = $ac->account_number ? ', A/c No : ' . $ac->account_number : '';
                                                                    $bank = $ac?->bank ? ', Bank : ' . $ac?->bank?->name : '';
                                                                @endphp
                                                                {{ $ac->name . $acNo . $bank }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_account_id"></span>
                                                </div>
                                            </div>

                                            <div class="row gx-2 mt-1">
                                                <label class="col-md-5 text-end"><b>{{ __('Previous Received') }}</b></label>
                                                <div class="col-md-7">
                                                    <input readonly type="number" step="any" class="form-control fw-bold text-success" name="previous_received" id="previous_received" value="{{ $sale->paid }}" tabindex="-1">
                                                </div>
                                            </div>

                                            <div class="row gx-2 mt-1">
                                                <label class="col-md-5 text-end"><b>{{ __('Curr. Balance') }}</b></label>
                                                <div class="col-md-7">
                                                    <input readonly type="number" step="any" class="form-control fw-bold text-danger" name="current_balance" id="current_balance" value="{{ $amounts['closing_balance_in_flat_amount'] }}" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex justify-content-end pt-3">
                                                <div class="btn-loading d-flex flex-wrap gap-2 w-100 text-end justify-content-end">
                                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                                    <button type="submit" id="save_changes" class="btn btn-sale btn-success submit_button">{{ __('Save Changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    @if (auth()->user()->can('customer_add'))
        <div class="modal fade" id="addOrEditContactModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        </div>
    @endif

    @if (auth()->user()->can('product_add'))
        <div class="modal fade" id="addQuickProductModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

        <div class="modal fade" id="unitAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="categoryAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="brandAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif
@endsection
@push('scripts')
    @include('sales.add_sale.js_partials.add_sale_edit_js_script')
@endpush

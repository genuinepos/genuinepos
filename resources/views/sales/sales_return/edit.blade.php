@extends('layout.master')
@push('stylesheets')
    <style>
        b {
            font-weight: 500 !important;
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

        .data_preloader {
            top: 2.3%
        }

        .selected_invoice {
            background-color: #645f61;
            color: #fff !important;
        }

        .invoice_search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
        }

        .invoice_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .invoice_search_result ul li a {
            color: #6b6262;
            font-size: 10px;
            display: block;
            padding: 0px 3px;
        }

        .invoice_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        .selectProduct {
            background-color: #645f61;
            color: #fff !important;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 95%;
            z-index: 9999999;
            padding: 0;
            left: 5%;
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

        .element-body {
            overflow: initial !important;
        }

        /* .select2-container--open .select2-dropdown--below {
                width: 298px !important;
            } */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Edit Sales Return - ')
@section('content')
    @php
        $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
        $amounts = $accountBalanceService->accountBalance(accountId: $return->customer_account_id);
    @endphp

    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-7">
                    <div class="name-head">
                        <h6>{{ __('Edit Sales Return') }}</h6>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="col-md-5">
                        <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button d-inline"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-1">
            <form id="edit_sales_return_form" action="{{ route('sales.returns.update', $return->id) }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Customers') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select required name="customer_account_id" class="form-control select2" id="customer_account_id" data-next="sale_invoice_id" autofocus>
                                                @foreach ($customerAccounts as $customerAccount)
                                                    <option @selected($customerAccount->id == $return->customer_account_id) data-default_balance_type="{{ $customerAccount->default_balance_type }}" data-sub_sub_group_number="{{ $customerAccount->sub_sub_group_number }}" data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone . ' | ' . $customerAccount->account_group_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_customer_account_id"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Closing Balance') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" class="form-control text-danger fw-bold" id="closing_balance" value="{{ $amounts['closing_balance_in_flat_amount'] }}" tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Sales Invoice') }}</b></label>
                                        <div class="col-8">
                                            <div style="position: relative;">
                                                <input type="text" name="sale_invoice_id" id="sale_invoice_id" class="form-control fw-bold" data-next="warehouse_id" value="{{ $return?->sale?->invoice_id }}" placeholder="{{ __('Search Sales Invoice ID') }}" autocomplete="off">
                                                <input type="hidden" name="sale_id" id="sale_id" value="{{ $return?->sale_id }}">

                                                <div class="invoice_search_result d-hide">
                                                    <ul id="invoice_list" class="list-unstyled"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (count($warehouses) > 0)

                                        <input name="warehouse_count" value="YES" type="hidden" />
                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>{{ __('Warehouse') }}</b> <span class="text-danger">*</span></label>
                                            <div class="col-8">
                                                <select required class="form-control" name="warehouse_id" id="warehouse_id" data-next="date">
                                                    <option value="">{{ __('Select Warehouse') }}</option>
                                                    @foreach ($warehouses as $w)
                                                        <option @selected($return?->warehouse_id == $w->id) data-warehouse_name="{{ $w->warehouse_name }}" data-warehouse_code="{{ $w->warehouse_code }}" value="{{ $w->id }}">
                                                            {{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_warehouse_id"></span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>{{ __('Store Location') }}</b></label>
                                            <div class="col-8">
                                                <input readonly type="text" class="form-control" value="{{ $branchName }}" />
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Return Date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business_or_shop__date_format'], strtotime($return?->date)) }}" data-next="sale_account_id" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Sales Ledger') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select name="sale_account_id" class="form-control select2" id="sale_account_id" data-next="price_group_id">
                                                @foreach ($saleAccounts as $saleAccount)
                                                    <option @selected($saleAccount->id == $return->sale_account_id) value="{{ $saleAccount->id }}">
                                                        {{ $saleAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_sale_account_id"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Voucher No') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control fw-bold" value="{{ $return->voucher_no }}" placeholder="{{ __('Voucher No') }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Price Group') }}</b></label>
                                        <div class="col-8">
                                            <select name="price_group_id" class="form-control" id="price_group_id" data-next="search_product">
                                                <option value="">{{ __('Default Selling Price') }}</option>
                                                @foreach ($priceGroups as $priceGroup)
                                                    <option {{ $generalSettings['add_sale__default_price_group_id'] == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <section>
                        <div class="card mb-1 p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row g-xxl-4 align-items-end">
                                        <div class="col-xl-6">
                                            <div class="searching_area" style="position: relative;">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-barcode text-dark input_f"></i>
                                                        </span>
                                                    </div>

                                                    <input @disabled($return?->sale_id) type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __('Search Product By Name/Code') }}" autocomplete="off">
                                                </div>

                                                <div class="select_area">
                                                    <ul id="list" class="variant_list_area"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-xxl-4 align-items-end">
                                        <div class="hidden_fields">
                                            <input type="hidden" id="e_unique_id">
                                            <input type="hidden" id="e_item_name">
                                            <input type="hidden" id="e_product_id">
                                            <input type="hidden" id="e_variant_id">
                                            <input type="hidden" id="e_tax_amount">
                                            <input type="hidden" id="e_unit_cost_inc_tax">
                                            <input type="hidden" id="e_unit_price_inc_tax">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Quantity') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control w-60 fw-bold" id="e_return_quantity" placeholder="{{ __('Return Quantity') }}" value="0.00">
                                                <select id="e_unit_id" class="form-control w-40">
                                                    <option value="">{{ __('Unit') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Unit Price(Exc. Tax)') }}</label>
                                            <input type="number" step="any" class="form-control fw-bold" id="e_unit_price_exc_tax" placeholder="{{ __('Unit Price(Exc. Tax)') }}" value="0.00">
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Discount (Per Unit)') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_discount" placeholder="{{ __('Discount (Per Unit)') }}" value="0.00">
                                                <input type="hidden" id="e_discount" value="0.00">
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
                                                <select id="e_tax_ac_id" class="form-control">
                                                    <option data-product_tax_percent="0.00" value="">{{ __('NoVat/Tax') }}</option>
                                                    @foreach ($taxAccounts as $taxAccount)
                                                        <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                            {{ $taxAccount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select id="e_tax_type" class="form-control" tabindex="-1">
                                                    <option value="1">{{ __('Exclusive') }}</option>
                                                    <option value="2">{{ __('Inclusive') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-6">
                                            <label class="fw-bold">{{ __('Subtotal') }}</label>
                                            <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                        </div>

                                        <div class="col-xl-1 col-md-6">
                                            <div class="btn-box-2">
                                                <a href="#" class="btn btn-sm btn-success" id="add_item">{{ __('Add') }}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">{{ __('Product') }}</th>
                                                                    <th class="text-start">{{ __('Unit Cost (Inc. Tax)') }}</th>
                                                                    <th class="text-start">{{ __('Sold Qty') }}</th>
                                                                    <th class="text-start">{{ __('Return Qty') }}</th>
                                                                    <th class="text-start">{{ __('Unit') }}</th>
                                                                    <th class="text-start">{{ __('Subtotal') }}</th>
                                                                    <th class="text-start"><i class="fas fa-minus text-white"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="return_item_list">
                                                                @foreach ($return?->saleReturnProducts as $returnProduct)
                                                                    @php
                                                                        if (isset($returnProduct->product_id)) {
                                                                            $itemUnitsArray[$returnProduct->product_id][] = [
                                                                                'unit_id' => $returnProduct->product->unit->id,
                                                                                'unit_name' => $returnProduct->product->unit->name,
                                                                                'unit_code_name' => $returnProduct->product->unit->code_name,
                                                                                'base_unit_multiplier' => 1,
                                                                                'multiplier_details' => '',
                                                                                'is_base_unit' => 1,
                                                                            ];
                                                                        }

                                                                        $variant = $returnProduct?->variant ? ' - ' . $returnProduct?->variant?->variant_name : '';

                                                                        $productCode = $returnProduct?->variant ? $returnProduct?->variant?->variant_code : $returnProduct?->product?->product_code;

                                                                        $variantId = $returnProduct->variant_id ? $returnProduct->variant_id : 'noid';
                                                                    @endphp

                                                                    <tr id="select_item">
                                                                        <td class="text-start">
                                                                            <span class="product_name">{{ $returnProduct->product->name . $variant . ' (' . $productCode . ')' }}</span>
                                                                            <input type="hidden" id="item_name" value="{{ $returnProduct->product->name . $variant . ' (' . $productCode . ')' }}">
                                                                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $returnProduct->product_id }}">
                                                                            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                                            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $returnProduct->tax_ac_id ? $returnProduct->tax_ac_id : '' }}">
                                                                            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $returnProduct->tax_type }}">
                                                                            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $returnProduct->unit_tax_percent }}">
                                                                            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $returnProduct->unit_tax_amount }}">
                                                                            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $returnProduct->unit_discount_type }}">
                                                                            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $returnProduct->unit_discount }}">
                                                                            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $returnProduct->unit_discount_amount }}">
                                                                            <input type="hidden" name="sale_product_ids[]" value="{{ $returnProduct->sale_product_id }}">
                                                                            <input type="hidden" name="sale_return_product_ids[]" value="{{ $returnProduct->id }}">
                                                                            <input type="hidden" class="unique_id" id="{{ $returnProduct->product_id . $returnProduct->sale_product_id . $variantId }}" value="{{ $returnProduct->product_id . $returnProduct->sale_product_id . $variantId }}">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <span id="span_unit_price_inc_tax" class="fw-bold">{{ $returnProduct->unit_price_inc_tax }}</span>
                                                                            <input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="{{ $returnProduct->unit_price_exc_tax }}">
                                                                            <input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="{{ $returnProduct->unit_price_inc_tax }}">
                                                                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $returnProduct->unit_cost_inc_tax }}">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <span id="span_sold_qty" class="fw-bold">{{ $returnProduct->sold_quantity }}</span>
                                                                            <input type="hidden" name="sold_quantities[]" value="{{ $returnProduct->sold_quantity }}">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <span id="span_return_quantity" class="fw-bold">{{ $returnProduct->return_qty }}</span>
                                                                            <input type="hidden" name="return_quantities[]" id="return_quantity" value="{{ $returnProduct->return_qty }}">
                                                                        </td>

                                                                        <td class="text text-start">
                                                                            <span id="span_unit" class="fw-bold">{{ $returnProduct?->unit?->name }}</span>
                                                                            <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $returnProduct?->unit_id }}">
                                                                        </td>

                                                                        <td class="text text-start">
                                                                            <span id="span_subtotal" class="fw-bold">{{ $returnProduct?->return_subtotal }}</span>
                                                                            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $returnProduct?->return_subtotal }}" tabindex="-1">
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
                            </div>
                        </div>
                    </section>

                    <div class="row g-1">
                        <div class="col-md-6">
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row g-1">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Total Item') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="{{ $return?->total_item }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Total Qty') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="{{ $return?->total_qty }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Net Total Amount') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="net_total_amount" id="net_total_amount" class="form-control fw-bold" value="{{ $return?->net_total_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Return Discount') }}</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <input name="return_discount" type="number" class="form-control fw-bold" id="return_discount" value="{{ $return?->return_discount }}" data-next="return_discount_type">
                                                                <input name="return_discount_amount" type="number" step="any" class="d-none" id="return_discount_amount" value="{{ $return?->return_discount_amount }}">
                                                                <select name="return_discount_type" class="form-control" id="return_discount_type" data-next="return_tax_ac_id">
                                                                    <option value="1">{{ __('Fixed') }}(0.00)</option>
                                                                    <option @selected($return?->return_discount_type == 2) value="2">{{ __('Percentage') }}(%)</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __('Return Tax') }}</b></label>
                                                        <div class="col-8">
                                                            <select name="return_tax_ac_id" class="form-control" id="return_tax_ac_id" data-next="note">
                                                                <option data-return_tax_percent="0.00" value="">{{ __('NoTax') }}</option>
                                                                @foreach ($taxAccounts as $taxAccount)
                                                                    <option @selected($return?->return_tax_ac_id == $taxAccount->id) data-return_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                        {{ $taxAccount->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input name="return_tax_percent" type="number" step="any" class="d-none" id="return_tax_percent" value="{{ $return?->return_tax_percent }}">
                                                            <input name="return_tax_amount" type="number" step="any" class="d-none" id="return_tax_amount" value="{{ $return?->return_tax_amount }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class=" col-4"><b>{{ __('Return Note') }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="note" class="form-control" id="note" data-next="paying_amount" value="{{ $return?->note }}" placeholder="{{ __('Return Note') }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element m-0 rounded">
                                <div class="element-body">
                                    <div class="row g-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Total Return Amount') }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_return_amount" id="total_return_amount" class="form-control fw-bold" value="{{ $return?->total_return_amount }}" placeholder="{{ __('Total Return Amount') }}" tabindex="-1">
                                                    <input type="hidden" name="curr_total_return_amount" id="curr_total_return_amount" value="{{ $return?->total_return_amount }}">
                                                    <input type="hidden" name="sale_ledger_amount" id="sale_ledger_amount">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Paying Amount') }}</b> <strong>>></strong></label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <input type="number" step="any" name="paying_amount" class="form-control fw-bold w-75" id="paying_amount" value="0.00" data-next="payment_date" autocomplete="off">
                                                        <input type="text" name="payment_date" class="form-control w-25" id="payment_date" value="{{ date($generalSettings['business_or_shop__date_format']) }}" data-next="payment_method_id" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Payment Method') }} <span class="text-danger">*</span></b> </label>
                                                <div class="col-8">
                                                    <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                                        @foreach ($methods as $method)
                                                            <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">{{ $method->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_payment_method_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Credit A/c') }} <span class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="account_id" class="form-control" id="account_id" data-next="payment_note">
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
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class=" col-4"><b>{{ __('Payment Note') }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="save_changes" placeholder="{{ __('payment Note') }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Previous Paid & Due (On Voucher)') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <input readonly type="text" class="form-control text-success fw-bold" id="previous_paid" value="{{ $return->paid }}" placeholder="{{ __('0.00') }}">

                                                        <input readonly type="text" class="form-control text-danger fw-bold" id="due_on_voucher" placeholder="{{ __('0.00') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Current Balance') }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" name="current_balance" class="form-control text-danger fw-bold" id="current_balance" value="0.00" placeholder="{{ __('0.00') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row justify-content-center mt-1">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>
                            <button type="submit" id="save_changes" value="save" class="btn btn-success submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sales.sales_return.js_partials.edit_js')
@endpush

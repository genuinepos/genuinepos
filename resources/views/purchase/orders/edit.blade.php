@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
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
            border: 1px solid var(--main-color);
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 4px 4px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 10px;
            padding: 2px 2px;
            display: block;
            border: 1px solid gray;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70;
            color: #fff !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        h6.collapse_table:hover {
            background: lightgray;
            padding: 3px;
            cursor: pointer;
        }

        .c-delete:focus {
            border: 1px solid gray;
            padding: 2px;
        }

        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }

        .checkbox_input_wrap {
            text-align: right;
        }

        .big_amount_field {
            height: 36px;
            font-size: 24px !important;
            margin-bottom: 3px;
        }
    </style>
@endpush

@section('title', 'Edit Purchase Order - ')
@section('content')
    @php
        $account = $order?->supplier;
        $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
        $branchId = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;
        $__branchId = $account?->group?->sub_sub_group_number == 6 ? $branchId : '';
        $amounts = $accountBalanceService->accountBalance(accountId: $account->id, fromDate: null, toDate: null, branchId: $__branchId);
    @endphp
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Edit Purchase Order') }}</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="p-1">
            <form id="edit_purchase_order_form" action="{{ route('purchase.orders.update', $order->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-lg-1">
                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Supplier') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <div class="input-group flex-nowrap">
                                                <select name="supplier_account_id" class="form-control select2" id="supplier_account_id" data-next="pay_term_number">
                                                    <option value="">{{ __('Select Supplier') }}</option>
                                                    @foreach ($supplierAccounts as $supplierAccount)
                                                        <option data-default_balance_type="{{ $supplierAccount->default_balance_type }}" data-sub_sub_group_number="{{ $supplierAccount->sub_sub_group_number }}" {{ $supplierAccount->id == $order->supplier_account_id ? 'SELECTED' : '' }} data-pay_term="{{ $supplierAccount->pay_term }}" data-pay_term_number="{{ $supplierAccount->pay_term_number }}" value="{{ $supplierAccount->id }}">{{ $supplierAccount->name . '/' . $supplierAccount->phone }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text {{ $generalSettings['subscription']->features['contacts'] == 0 || !auth()->user()->can('supplier_add') ? 'disabled_element' : '' }} add_button" id="{{ $generalSettings['subscription']->features['contacts'] == 1 && auth()->user()->can('supplier_add') ? 'addContact' : '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                                </div>
                                            </div>
                                            <span class="error error_supplier_id"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Closing. Balance') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" id="closing_balance" class="form-control text-danger fw-bold" value="{{ $amounts['closing_balance_in_flat_amount'] }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('P/o ID.') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" name="order_id" id="order_id" class="form-control fw-bold" data-next="pay_term_number" value="{{ $order->invoice_id }}" placeholder="{{ __('Purchase Order Id') }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class=" col-4"><b>{{ __('Pay Term') }}</b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="text" name="pay_term_number" class="form-control" id="pay_term_number" data-next="pay_term" value="{{ $order->pay_term_number }}" placeholder="{{ __("Number") }}" autocomplete="off">
                                                <select name="pay_term" class="form-control" id="pay_term" data-next="date">
                                                    <option value="">@lang('menu.pay_term')</option>
                                                    <option {{ $order->pay_term == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.days')</option>
                                                    <option {{ $order->pay_term == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.months')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input required type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business_or_shop__date_format'], strtotime($order->date)) }}" data-next="delivery_date" placeholder="dd-mm-yyyy" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Delivery Date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input required type="text" name="delivery_date" class="form-control" id="delivery_date" data-next="purchase_account_id" value="{{ $order->delivery_date }}" placeholder="{{ $generalSettings['business_or_shop__date_format'] }}" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Purchase A/c') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select name="purchase_account_id" class="form-control" id="purchase_account_id" data-next="search_product">
                                                @foreach ($purchaseAccounts as $purchaseAccount)
                                                    <option {{ $purchaseAccount->id == $order->purchase_account_id ? 'SELECTED' : '' }} value="{{ $purchaseAccount->id }}">
                                                        {{ $purchaseAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_purchase_account_id"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card ps-2 pb-1 pe-2">
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <div class="row align-items-end">
                                    <input type="hidden" id="e_unique_id">
                                    <input type="hidden" id="e_item_name">
                                    <input type="hidden" id="e_product_id">
                                    <input type="hidden" id="e_variant_id">
                                    <input type="hidden" id="e_tax_amount">
                                    <input type="hidden" id="e_unit_cost_with_discount">
                                    <input type="hidden" id="e_subtotal">
                                    <input type="hidden" id="e_unit_cost_inc_tax">

                                    <div class="col-xl-4 col-md-4">
                                        <div class="searching_area" style="position: relative;">
                                            <label class="fw-bold">{{ __('Search Product') }}</label>
                                            <div class="input-group">
                                                <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="{{ __('Serach Product By Name/Code') }}">

                                                @if (auth()->user()->can('product_add'))
                                                    <div class="input-group-prepend">
                                                        <span id="addProduct" class="input-group-text add_button"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="select_area">
                                                <ul id="list" class="variant_list_area"></ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Quantity') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                            <select id="e_unit_id" class="form-control w-40">
                                                <option value="">{{ __('Unit') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Unit Cost (Exc. Tax)') }}</label>
                                        <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_exc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Discount') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_discount" value="0.00" placeholder="0.00" autocomplete="off">
                                            <select id="e_discount_type" class="form-control w-40">
                                                <option value="1">{{ __('Fixed') }}(0.00)</option>
                                                <option value="2">{{ __('Percentage') }}(%)</option>
                                            </select>
                                            <input type="hidden" id="e_discount_amount">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Vat/Tax') }}</label>
                                        <div class="input-group">
                                            <select id="e_tax_ac_id" class="form-control w-50">
                                                <option data-product_tax_percent="0.00" value="">{{ __('NoVat/Tax') }}</option>
                                                @foreach ($taxAccounts as $taxAccount)
                                                    <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                        {{ $taxAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select id="e_tax_type" class="form-control w-50">
                                                <option value="1">{{ __('Exclusive') }}</option>
                                                <option value="2">{{ __('Inclusive') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Short Description') }}</label>
                                        <input type="text" step="any" class="form-control fw-bold" id="e_description" placeholder="{{ __('Short Description') }}" autocomplete="off">
                                    </div>

                                    @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __('Profit(%) & Selling Price') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_profit_margin" placeholder="@lang('menu.profit_margin')" autocomplete="off">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_selling_price" placeholder="@lang('menu.selling_price_exc_tax')" autocomplete="off">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Linetotal') }}</label>
                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_linetotal" value="0.00" placeholder="0.00" tabindex="-1">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <a href="#" class="btn btn-sm btn-success me-2" id="add_item">{{ __('Add') }}</a>
                                        <a href="#" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger">@lang('menu.reset')</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="sale-item-sec">
                                <div class="sale-item-inner">
                                    <div class="table-responsive">
                                        <table class="display data__table table-striped">
                                            <thead class="staky">
                                                <tr>
                                                    <th>{{ __('Product') }}</th>
                                                    <th>{{ __('Order Qty') }}</th>
                                                    <th>{{ __('Unit Cost (Exc. Tax)') }}</th>
                                                    <th>{{ __('Discount') }}</th>
                                                    <th>{{ __('Tax') }}</th>
                                                    <th>{{ __('Net Unit Cost (Inc. Tax)') }}</th>
                                                    <th>{{ __('Linetotal') }}</th>

                                                    @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                                        <th>{{ __('Profit Marine') }}</th>
                                                        <th>{{ __('Selling Price (Exc. Tax)') }}</th>
                                                    @endif
                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="purchase_order_product_list">
                                                @php
                                                    $itemUnitsArray = [];
                                                @endphp
                                                @foreach ($order->purchaseOrderProducts as $orderProduct)
                                                    @php
                                                        $variant = $orderProduct->variant ? ' - ' . $orderProduct->variant->variant_name : '';
                                                        $variantId = $orderProduct->product_variant_id ? $orderProduct->product_variant_id : 'noid';

                                                        if (isset($orderProduct->product_id)) {
                                                            $itemUnitsArray[$orderProduct->product_id][] = [
                                                                'unit_id' => $orderProduct->product->unit->id,
                                                                'unit_name' => $orderProduct->product->unit->name,
                                                                'unit_code_name' => $orderProduct->product->unit->code_name,
                                                                'base_unit_multiplier' => 1,
                                                                'multiplier_details' => '',
                                                                'is_base_unit' => 1,
                                                            ];
                                                        }
                                                    @endphp

                                                    <tr id="select_item">
                                                        <td>
                                                            <span id="span_item_name">{{ $orderProduct->product->name . $variant }}</span>
                                                            <input type="hidden" id="item_name" value="{{ $orderProduct->product->name . $variant }}">
                                                            <input type="hidden" name="descriptions[]" id="description" value="{{ $orderProduct->description }}">
                                                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $orderProduct->product_id }}">
                                                            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                            <input type="hidden" name="purchase_order_product_ids[]" value="{{ $orderProduct->id }}">
                                                            <input type="hidden" id="{{ $orderProduct->product_id . $variantId }}" value="{{ $orderProduct->product_id . $variantId }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_quantity_unit" class="fw-bold">{{ $orderProduct->ordered_quantity . '/' . $orderProduct?->unit?->name }}</span>
                                                            <input type="hidden" name="quantities[]" id="quantity" value="{{ $orderProduct->ordered_quantity }}">
                                                            <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $orderProduct->unit_id }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_unit_cost_exc_tax" class="fw-bold">{{ $orderProduct->unit_cost_exc_tax }}</span>
                                                            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $orderProduct->unit_cost_exc_tax }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_discount_amount" class="fw-bold">{{ $orderProduct->unit_discount_amount }}</span>
                                                            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $orderProduct->unit_discount_type }}">
                                                            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $orderProduct->unit_discount }}">
                                                            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $orderProduct->unit_discount_amount }}">
                                                            <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ $orderProduct->unit_cost_with_discount }}">
                                                            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $orderProduct->subtotal }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_tax_percent" class="fw-bold">{{ $orderProduct->unit_tax_percent . '%' }}</span>
                                                            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $orderProduct->tax_ac_id }}">
                                                            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $orderProduct->unit_tax_type }}">
                                                            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $orderProduct->unit_tax_percent }}">
                                                            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $orderProduct->unit_tax_amount }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $orderProduct->net_unit_cost }}</span>
                                                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $orderProduct->net_unit_cost }}">
                                                            <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ $orderProduct->net_unit_cost }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_linetotal" class="fw-bold">{{ $orderProduct->line_total }}</span>
                                                            <input type="hidden" name="linetotals[]" id="linetotal" value="{{ $orderProduct->line_total }}">
                                                        </td>

                                                        @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                                            <td>
                                                                <span id="span_profit" class="fw-bold">{{ $orderProduct->profit_margin }}</span>
                                                                <input type="hidden" name="profits[]" id="profit" value="{{ $orderProduct->profit_margin }}">
                                                            </td>

                                                            <td>
                                                                <span id="span_selling_price" class="fw-bold">{{ $orderProduct->selling_price }}</span>
                                                                <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ $orderProduct->selling_price }}">
                                                            </td>
                                                        @endif

                                                        <td>
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
                </section>

                <section>
                    <div class="row g-3 py-1">
                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">

                                                        <label class="col-4"><b>{{ __('Total Item') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="{{ $order->total_item }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Total Qty') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="{{ $order->total_qty }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>{{ __('Net Total Amount') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control fw-bold" value="{{ $order->net_total_amount }}" tabindex="-1">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Order Discount') }}</b></label>
                                                        <div class="col-8">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <select name="order_discount_type" class="form-control" id="order_discount_type" data-next="order_discount">
                                                                        <option {{ $order->order_discount_type == 1 ? 'SELECTED' : '' }} value="1">{{ __('Fixed') }}(0.00)</option>
                                                                        <option {{ $order->order_discount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __('Percentage') }}(%)</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <input name="order_discount" type="number" class="form-control fw-bold" id="order_discount" value="{{ $order->order_discount }}" data-next="purchase_tax_ac_id">
                                                                </div>
                                                            </div>
                                                            <input name="order_discount_amount" type="number" step="any" class="d-hide" id="order_discount_amount" value="{{ $order->order_discount_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Order Tax') }}</b></label>
                                                        <div class="col-8">
                                                            <select name="purchase_tax_ac_id" class="form-control" id="purchase_tax_ac_id" data-next="shipment_charge">
                                                                <option data-purchase_tax_percent="0.00" value="">@lang('menu.no_tax')</option>
                                                                @foreach ($taxAccounts as $taxAccount)
                                                                    <option {{ $taxAccount->id == $order->purchase_tax_ac_id ? 'SELECTED' : '' }} data-purchase_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                        {{ $taxAccount->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="number" step="any" name="purchase_tax_percent" class="d-none" id="purchase_tax_percent" value="{{ $order->purchase_tax_percent }}">
                                                            <input name="purchase_tax_amount" type="number" step="any" class="d-hide" id="purchase_tax_amount" value="{{ $order->purchase_tax_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Shipment Charge') }}</b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_charge" type="number" class="form-control fw-bold" id="shipment_charge" data-next="shipment_details" value="{{ $order->shipment_charge }}">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Shipment Details') }}</b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_details" type="text" class="form-control" id="shipment_details" data-next="paying_amount" value="{{ $order->shipment_details }}" placeholder="{{ __('Shipment Details') }}">
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
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Total Ordered Amount') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="total_ordered_amount" id="total_ordered_amount" class="form-control fw-bold" value="{{ $order->total_purchase_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __('Paying Amount') }}</b> {{ $generalSettings['business_or_shop__currency_symbol'] }} <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="paying_amount" class="form-control big_amount_field fw-bold" id="paying_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Payment Method') }}<span class="text-danger">*</span></b> </label>
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
                                                    <div class="input-group mt-1">
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
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __('Payment Note') }}</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="order_note" placeholder="{{ __('Payment Note') }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Order Note') }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="order_note" id="order_note" class="form-control" data-next="save_changes" value="{{ $order->purchase_note }}" placeholder="{{ __('Order Note') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __('Current Balance') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" class="form-control text-danger fw-bold" name="current_balance" id="current_balance" value="{{ $amounts['closing_balance_in_flat_amount'] }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row justify-content-center">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>@lang('menu.loading')...</span> </button>
                            <button type="submit" id="save_changes" class="btn btn-success submit_button">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ( $generalSettings['subscription']->features['contacts'] == 1 && auth()->user()->can('supplier_add'))
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
    @include('purchase.orders.js_partials.purchaseOrderEditJsScript')
    <script>
        $('.select2').select2();

        var itemUnitsArray = @json($itemUnitsArray);

        $('select').on('select2:close', function(e) {

            var nextId = $(this).data('next');

            setTimeout(function() {

                $('#' + nextId).focus();
            }, 100);
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                if ($(this).attr('id') == 'paying_amount' && ($('#paying_amount').val() == 0 || $('#paying_amount').val() == '')) {

                    $('#save_changes').focus().select();
                    return;
                }

                $('#' + nextId).focus().select();
            }
        });

        // $('#supplier_account_id').select2('focus');
        setTimeout(function() {

            $('#supplier_account_id').focus().select();
        }, 1000);
    </script>
@endpush

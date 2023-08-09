@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}"/>
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute; width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 11px; padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .selectProduct{background-color: #746e70; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        h6.collapse_table:hover {background: lightgray; padding: 3px; cursor: pointer;}
        .c-delete:focus {border: 1px solid gray;padding: 2px;}
        label.col-2,label.col-3,label.col-4,label.col-5,label.col-6 { text-align: right; padding-right: 10px;}
        .checkbox_input_wrap {text-align: right;}
    </style>
@endpush

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-shopping-cart"></span>
                    <h6>{{ __('Edit Purchase Order') }}</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-lg-3 p-1">
            <form id="edit_purchase_order_form" action="{{ route('purchases.order.update', $order->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-lg-3 mb-1">
                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Supplier') }}</b></label>
                                        <div class="col-8">
                                            <div class="input-group flex-nowrap">
                                                <input readonly type="text" class="form-control fw-bold" id="supplier_name" value="{{ $order?->supplier?->name }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Curr. Balance') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" id="current_balance" class="form-control fw-bold" value="0.00" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('P/o ID') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" id="order_id"  class="form-control fw-bold" value="{{ $order->invoice_id }}" data-next="warehouse_id" placeholder="{{ __('Purchase Order ID') }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class=" col-4"><b>{{ __('Pay Term') }}</b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="text" name="pay_term_number" class="form-control"
                                                id="pay_term_number" value="{{ $order->pay_term_number }}" data-next="pay_term" placeholder="Number">

                                                <select name="pay_term" class="form-control" id="pay_term" data-next="date">
                                                    <option value="">@lang('menu.pay_term')</option>
                                                    <option {{ $order->pay_term == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.days')</option>
                                                    <option {{ $order->pay_term == 1 ? 'SELECTED' : '' }} value="2">@lang('menu.months')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input required type="text" name="date" class="form-control"
                                             id="date" value="{{ date($generalSettings['business__date_format'], strtotime($order->date)) }}" data-next="delivery_date" placeholder="dd-mm-yyyy" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Delivery Date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input required type="text" name="delivery_date" class="form-control"
                                             id="delivery_date" value="{{ date($generalSettings['business__date_format'], strtotime($order->delivery_date)) }}" data-next="purchase_account_id" placeholder="{{ $generalSettings['business__date_format'] }}" autocomplete="off">
                                            <span class="error error_delivery_date"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Purchase A/c') }} <span class="text-danger">*</span></b></label>
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
                    <div class="card ps-1 pb-1 pe-1">
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
                                            <label class="fw-bold">@lang('menu.search_product')</label>
                                            <div class="input-group">
                                                <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_product')" autofocus>

                                                @if (auth()->user()->can('product_add'))
                                                    <div class="input-group-prepend">
                                                        <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="select_area">
                                                <ul id="list" class="variant_list_area"></ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.quantity')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                            <select id="e_unit" class="form-control w-40">
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.unit_cost_exc_tax')</label>
                                        <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_exc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.discount')</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_discount" value="0.00" placeholder="0.00" autocomplete="off">
                                            <select id="e_discount_type" class="form-control w-40">
                                                <option value="1">@lang('menu.fixed')(0.00)</option>
                                                <option value="2">@lang('menu.percentage')(%)</option>
                                            </select>
                                            <input type="hidden" id="e_discount_amount">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.tax')</label>
                                        <div class="input-group">
                                            <select id="e_tax_percent" class="form-control w-50">
                                                <option value="0.00">@lang('menu.no_tax')</option>
                                                @foreach ($taxes as $tax)
                                                    <option value="{{ $tax->tax_percent }}">
                                                        {{ $tax->tax_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select id="e_tax_type" class="form-control w-50">
                                                <option value="1">@lang('menu.exclusive')</option>
                                                <option value="2">@lang('menu.inclusive')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">@lang('menu.short_description')</label>
                                        <input type="text" step="any" class="form-control fw-bold" id="e_description" placeholder="@lang('menu.short_description')" autocomplete="off">
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
                                        <label class="fw-bold">@lang('menu.line_total')</label>
                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_linetotal" value="0.00" placeholder="0.00" tabindex="-1">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <a href="#" class="btn btn-sm btn-success me-2" id="add_item">@lang('menu.add')</a>
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
                                                    <th>@lang('menu.product')</th>
                                                    <th>@lang('menu.quantity')</th>
                                                    <th>@lang('menu.unit_cost_exc_tax')</th>
                                                    <th>@lang('menu.discount')</th>
                                                    <th>@lang('menu.unit_tax')</th>
                                                    <th>{{ __('Net Unit Cost (Inc. Tax)') }}</th>
                                                    <th>@lang('menu.line_total')</th>

                                                    @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                                        <th>@lang('menu.x_margin')(%)</th>
                                                        <th>@lang('menu.selling_price_exc_tax')</th>
                                                    @endif
                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="purchase_order_product_list">
                                                @foreach ($order->purchase_order_products as $purchaseOrderProduct)
                                                    @php
                                                        $variant = $purchaseOrderProduct->variant ? ' - '.$purchaseOrderProduct->variant->variant_name : '';
                                                        $variantId = $purchaseOrderProduct->product_variant_id ? $purchaseOrderProduct->product_variant_id : 'noid';
                                                    @endphp

                                                    <tr id="select_item">
                                                        <td>
                                                            <span id="span_item_name">{{ $purchaseOrderProduct->product->name . $variant }}</span>
                                                            <input type="hidden" id="item_name" value="{{ $purchaseOrderProduct->product->name . $variant }}">
                                                            <input type="hidden" name="descriptions[]" id="description" value="{{ $purchaseOrderProduct->description }}">
                                                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $purchaseOrderProduct->product_id }}">
                                                            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                            <input type="hidden" name="purchase_product_ids[]" value="{{ $purchaseOrderProduct->id }}">
                                                            <input type="hidden" id="{{ $purchaseOrderProduct->product_id.$variantId }}" value="{{ $purchaseOrderProduct->product_id.$variantId }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_quantity_unit" class="fw-bold">{{ $purchaseOrderProduct->order_quantity . '/' . $purchaseOrderProduct?->unit }}</span>
                                                            <input type="hidden" name="quantities[]" id="quantity" value="{{ $purchaseOrderProduct->order_quantity }}">
                                                            <input type="hidden" name="units[]" step="any" id="unit" value="{{ $purchaseOrderProduct->unit }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_unit_cost_exc_tax" class="fw-bold">{{ $purchaseOrderProduct->unit_cost }}</span>
                                                            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $purchaseOrderProduct->unit_cost }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_discount_amount" class="fw-bold">{{ $purchaseOrderProduct->unit_discount_amount }}</span>
                                                            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $purchaseOrderProduct->unit_discount_type }}">
                                                            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $purchaseOrderProduct->unit_discount }}">
                                                            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $purchaseOrderProduct->unit_discount_amount }}">
                                                            <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ $purchaseOrderProduct->unit_cost_with_discount }}">
                                                            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $purchaseOrderProduct->subtotal }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_tax_percent" class="fw-bold">{{ $purchaseOrderProduct->unit_tax_percent.'%' }}</span>
                                                            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $purchaseOrderProduct->tax_type }}">
                                                            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $purchaseOrderProduct->unit_tax_percent }}">
                                                            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $purchaseOrderProduct->unit_tax }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $purchaseOrderProduct->net_unit_cost }}</span>
                                                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $purchaseOrderProduct->net_unit_cost }}">
                                                            <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ $purchaseOrderProduct->net_unit_cost }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_linetotal" class="fw-bold">{{ $purchaseOrderProduct->line_total }}</span>
                                                            <input type="hidden" name="linetotals[]" id="linetotal" value="{{ $purchaseOrderProduct->line_total }}">
                                                        </td>

                                                        @if ($generalSettings['purchase__is_edit_pro_price'] == '1')

                                                            <td>
                                                                <span id="span_profit" class="fw-bold">{{ $purchaseOrderProduct->profit_margin }}</span>
                                                                <input type="hidden" name="profits[]" id="profit" value="{{ $purchaseOrderProduct->profit_margin }}">
                                                            </td>

                                                            <td>
                                                                <span id="span_selling_price" class="fw-bold">{{ $purchaseOrderProduct->selling_price }}</span>
                                                                <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ $purchaseOrderProduct->selling_price }}">
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
                    <div class="row g-3 py-3">
                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.total_item')</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="{{ $order->total_item }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.total_quantity')</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="{{ $order->po_qty }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>@lang('menu.net_total_amount')</b></label>
                                                    <div class="col-8">
                                                        <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control fw-bold" value="{{ $order->net_total_amount }}" tabindex="-1">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.order_discount') </b></label>
                                                        <div class="col-8">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <select name="order_discount_type" class="form-control" id="order_discount_type" data-next="order_discount">
                                                                        <option {{ $order->order_discount_type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.fixed')(0.00)</option>
                                                                        <option {{ $order->order_discount_type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.percentage')(%)</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <input name="order_discount" type="number" class="form-control fw-bold" id="order_discount" value="{{ $order->order_discount }}" data-next="order_tax_percent">
                                                                </div>
                                                            </div>
                                                            <input name="order_discount_amount" type="number" step="any" class="d-hide" id="order_discount_amount" value="{{ $order->order_discount_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.order_tax') </b></label>
                                                        <div class="col-8">
                                                            <select name="order_tax_percent" class="form-control" id="order_tax_percent" data-next="shipment_charge">
                                                                <option value="0.00">@lang('menu.no_tax')</option>
                                                                @foreach ($taxes as $tax)
                                                                    <option {{ $order->purchase_tax_percent == $tax->tax_percent ? 'SELECTED' : '' }} value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input name="order_tax_amount" type="number" step="any" class="d-hide" id="order_tax_amount" value="{{ $order->purchase_tax_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.shipment_cost') </b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_charge" type="number" class="form-control fw-bold" id="shipment_charge" value="{{ $order->shipment_charge }}" data-next="shipment_details">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Shipment Details') }}</b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_details" type="text" class="form-control" id="shipment_details" value="{{ $order->shipment_details }}" data-next="purchase_note" placeholder="@lang('menu.shipment_details')">
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
                                                        <label class=" col-4"><b>@lang('menu.total_ordered_amount') </b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="total_ordered_amount" id="total_ordered_amount" class="form-control fw-bold" value="{{ $order->total_purchase_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.paying_amount') </b> {{ $generalSettings['business__currency'] }} <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="paying_amount" class="form-control fw-bold" id="paying_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.payment_method')<span
                                                            class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                                                @foreach ($methods as $method)
                                                                    <option
                                                                        data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                                                        value="{{ $method ->id }}">
                                                                        {{ $method->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_payment_method_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Credit A/c') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control" id="account_id" data-next="payment_note">
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">
                                                                        @php
                                                                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                                                            $bank = $account->bank ? ', BK : '.$account->bank : '';
                                                                            $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                                                                            $balance = ', BL : '.$account->balance;
                                                                        @endphp
                                                                        {{ $account->name.$accountType.$bank.$ac_no.$balance }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_account_id"></span>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.order_due') </b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <input readonly type="number" step="any" class="form-control fw-bold text-danger" name="order_due" id="order_due" value="{{ $order->due }}" tabindex="-1">
                                                                <input readonly type="number" step="any" class="form-control fw-bold text-success" name="previous_paid" id="previous_paid" value="{{ $order->paid }}" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.payment_note') </b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="save_and_print" placeholder="@lang('menu.payment_note')" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Order Note') }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="order_note" id="order_note" class="form-control" value="{{ $order->purchase_note }}" data-next="paying_amount" placeholder="@lang('menu.order_note').">
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
                            <button type="submit" id="save_and_print" value="1" class="btn btn-sm btn-success submit_button">@lang('menu.save_print')</button>
                            <button type="submit" id="save" value="2" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_supplier')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_supplier_modal_body"></div>
            </div>
        </div>
    </div>

    <!--Add Product Modal-->
    @if (auth()->user()->can('product_add'))
        <div class="modal fade" id="addQuickProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif
@endsection
@push('scripts')
    @include('purchases.orders.js_partials.purchaseOrderEditJsScript')
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script>
        $('.select2').select2();

        $('select').on('select2:close', function (e) {

            var nextId = $(this).data('next');

            setTimeout(function () {

                $('#'+nextId).focus();
            }, 100);
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#'+nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e){

            var nextId = $(this).data('next');

            if (e.which == 13) {

                if (nextId == 'warehouse_id' && $('#warehouse_id').val() == undefined) {

                    $('#date').focus().select();
                    return;
                }

                if ($(this).attr('id') == 'paying_amount' && ($('#paying_amount').val() == 0 ||  $('#paying_amount').val() == '' )) {

                    $('#save_and_print').focus().select();
                    return;
                }

                $('#'+nextId).focus().select();
            }
        });
    </script>
@endpush

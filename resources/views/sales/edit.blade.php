@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 88.3%;z-index: 9999999;padding: 0;left: 6%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 11px;padding: 4px 3px;display: block;border: 1px solid lightgray;margin-top: 3px;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .selectProduct {background-color: #746e70;color: #fff !important;}
        .input-group-text-sale {font-size: 7px !important;}
        .border_red {border: 1px solid red!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        label.col-2,label.col-3,label.col-4,label.col-5,label.col-6 { text-align: right; padding-right: 10px;}
        .checkbox_input_wrap {text-align: right;}
    </style>
    <link rel="stylesheet" href="{{ asset('backend/asset/css/bootstrap-datepicker.min.css') }}">
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-edit"></span>
                    <h6>@lang('menu.edit_sale')</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <form id="edit_sale_form" action="{{ route('sales.update', $sale->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">

                        <div class="element-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class=" col-4"><b>@lang('menu.customer') :</b> </label>
                                        <div class="col-8">
                                            <div class="input-group width-60">
                                                <input readonly type="text" value="{{ $sale->customer ? $sale->customer->name : 'Walk-In-Customer' }}" id="customer_name" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"> <b>@lang('menu.warehouse') :</b> </label>
                                        <div class="col-8">
                                            {{-- <input readonly type="text" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : $generalSettings['business__shop_name'].'(HO)' }}" tabindex="-1"> --}}
                                            <select name="warehouse_id" class="form-control" id="warehouse_id">
                                                <option value="">@lang('menu.select_warehouse')</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option data-w_name="{{ $warehouse->name.'/'.$warehouse->code }}" value="{{ $warehouse->id }}">
                                                        {{ $warehouse->name.'/'.$warehouse->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.invoice_id') :</b> </label>
                                        <div class="col-8">
                                            <input type="text" name="invoice_id" id="invoice_id" class="form-control" value="{{ $sale->invoice_id }}">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.attachment') :</b>
                                            <i data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice related any file.Ex: Scanned cheque, payment prove file etc." class="fas fa-info-circle tp"></i></label>
                                        <div class="col-8">
                                            <input type="file" name="attachment" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4">@lang('menu.status') : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select name="status" class="form-control add_input" data-name="Status"
                                                id="status">
                                                <option value="">@lang('menu.select_status')</option>
                                                @foreach (App\Utils\SaleUtil::saleStatus() as $key => $status)
                                                    <option {{ $sale->status == $key ? 'SELECTED' : '' }} value="{{ $key }}">
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_status"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.price_group') :</b></label>
                                        <div class="col-8">
                                            <select name="price_group_id" class="form-control"
                                                id="price_group_id">
                                                <option value="">@lang('menu.default_selling_price')</option>
                                                @foreach ($price_groups as $pg)
                                                    <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"> <b>@lang('menu.sale_date') :</b> <span
                                            class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input type="text" name="date" class="form-control" id="date" autocomplete="off"
                                                value="{{ date($generalSettings['business__date_format'], strtotime($sale->date)) }}">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class=" col-4"> <b>@lang('menu.sale_ac') :</b> <span
                                            class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select name="sale_account_id" class="form-control add_input"
                                                id="sale_account_id" data-name="Sale account">
                                                @foreach ($saleAccounts as $saleAc)
                                                    <option {{ $sale->sale_account_id == $saleAc->id ? 'SELECTED': '' }} value="{{ $saleAc->id }}">{{ $saleAc->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_sale_account_id"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row g-3">
                            <div class="col-lg-9">
                                <div class="card mb-3">
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('menu.item_search')</label>
                                                    <div class="input-group ">

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark input_f"></i></span>
                                                        </div>

                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="Search Product by product code(SKU) / Scan bar code" autofocus>

                                                        @if(auth()->user()->can('product_add'))
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

                                            <div class="col-md-3">
                                                <label class="col-form-label"></label>
                                                <div class="input-group ">

                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button p-1 m-0">@lang('menu.stock')</span>
                                                    </div>

                                                    <input type="text" readonly class="form-control text-success stock_quantity" id="stock_quantity" placeholder="Stock Quantity" tabindex="-1">
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
                                                                    <th class="text-start">@lang('menu.product')</th>
                                                                    <th class="text-start">@lang('menu.stock_location')</th>
                                                                    <th class="text-center">@lang('menu.quantity')</th>
                                                                    <th>@lang('menu.unit')</th>
                                                                    <th class="text-center">@lang('menu.price_inc_tax')</th>
                                                                    <th>@lang('menu.sub_total')</th>
                                                                    <th><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_list">
                                                                @php $index = 0; @endphp
                                                                @foreach ($sale->sale_products as $s_product)
                                                                    <tr>
                                                                        <td class="text-start">
                                                                            <a href="#" class="text-success" id="edit_product">
                                                                                @php
                                                                                    $variant = $s_product->product_variant_id != null ? ' -'.$s_product->variant->variant_name : '';
                                                                                @endphp

                                                                                <span class="product_name product_dscr_btn">{{ $s_product->product->name.$variant }}</span>

                                                                            </a><br/><input type="{{ $s_product->product->is_show_emi_on_pos == 1 ? 'text' : 'hidden'}}" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here." value="{{ $s_product->description ? $s_product->description : '' }}">
                                                                            <input value="{{ $s_product->product_id }}" type="hidden" id="product_id" name="product_ids[]">

                                                                            @if ($s_product->product_variant_id != null)

                                                                                <input value="{{ $s_product->product_variant_id }}" type="hidden" class="variantId-{{ $s_product->product_variant_id }}" id="variant_id" name="variant_ids[]">
                                                                            @else

                                                                                <input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">
                                                                            @endif

                                                                            <input type="hidden" id="tax_type" value="{{ $s_product->product->tax_type }}">

                                                                            <input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="{{ $s_product->unit_tax_percent }}">

                                                                            <input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="{{ $s_product->unit_tax_amount }}">

                                                                            <input value="{{ $s_product->unit_discount_type }}" name="unit_discount_types[]" type="hidden" id="unit_discount_type">

                                                                            <input value="{{ $s_product->unit_discount }}" name="unit_discounts[]" type="hidden" id="unit_discount">

                                                                            <input name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount" value="{{ $s_product->unit_discount_amount }}">

                                                                            <input name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax" value="{{ $s_product->unit_cost_inc_tax }}">

                                                                            @php
                                                                                $previous_sold_quantity = 0;
                                                                                if ($sale->status == 1) {

                                                                                    $previous_sold_quantity = $s_product->quantity;
                                                                                }
                                                                            @endphp

                                                                            <input type="hidden" id="previous_quantity" value="{{ $previous_sold_quantity }}">

                                                                            <input type="hidden" id="qty_limit" value="{{ $qty_limits[$index] }}">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="hidden" class="{{ $s_product->product_id . $s_product->stock_branch_id . $s_product->stock_warehouse_id }}" id="unique_id" value="{{ $s_product->product_id . $s_product->stock_branch_id . $s_product->stock_warehouse_id }}">
                                                                            <input type="hidden" name="branch_ids[]" id="branch_id" value="{{ $s_product->stock_branch_id }}">
                                                                            <input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="{{ $s_product->stock_warehouse_id ? $s_product->stock_warehouse_id : 'NULL' }}">

                                                                            @if ($s_product->stock_warehouse_id)

                                                                                <span>{{ $s_product->warehouse->warehouse_name.'/'.$s_product->warehouse->warehouse_code }}</span>
                                                                            @else
                                                                                @if ($s_product->stock_branch_id)

                                                                                    {{ $s_product->branch->name.'/'.$s_product->branch->branch_code }}
                                                                                @else

                                                                                    {{ $generalSettings['business__shop_name'] }}<b>(HO)</b>
                                                                                @endif
                                                                            @endif

                                                                        </td>

                                                                        <td>
                                                                            <input value="{{ $s_product->quantity }}" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">
                                                                            <p class="text-danger" id="stock_error"></p>
                                                                        </td>

                                                                        <td class="text">
                                                                            <span class="span_unit">{{ $s_product->unit }}</span>

                                                                            <input  name="units[]" type="hidden" id="unit" value="{{ $s_product->unit }}">
                                                                        </td>

                                                                        <td>
                                                                            <input name="unit_prices_exc_tax[]" type="hidden" value="{{ $s_product->unit_price_exc_tax }}" id="unit_price_exc_tax">

                                                                            <input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="{{ $s_product->unit_price_inc_tax }}" tabindex="-1">
                                                                        </td>

                                                                        <td class="text text-center">
                                                                            <strong><span class="span_subtotal">{{ $s_product->subtotal }}</span></strong>
                                                                            <input value="{{ $s_product->subtotal }}" readonly name="subtotals[]" type="hidden" id="subtotal" tabindex="-1">
                                                                        </td>

                                                                        <td class="text-center">
                                                                            <a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    @php $index++; @endphp
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded m-0 payment_body">
                                    <div class="element-body">
                                        <div class="row gx-2 gy-1">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>{{ __('Ship Details') }} :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="@lang('menu.shipment_details')" value="{{ $sale->shipment_details }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>{{ __('Ship Address') }} :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" value="{{ $sale->shipment_address }}" placeholder="{{ __('Shipment Address') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Ship Status') }} :</b></label>
                                                    <div class="col-8">
                                                        <select name="shipment_status" class="form-control" id="shipment_status">
                                                            <option value="">@lang('menu.shipment_status')</option>
                                                            @foreach (App\Utils\SaleUtil::saleShipmentStatus() as $key => $shipmentStatus)
                                                                <option {{ $sale->shipment_status == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $shipmentStatus }}
                                                                </option>
                                                             @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Delivered To') }} :</b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" value="{{ $sale->delivered_to }}" placeholder="{{ __('Delivered To') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.sale_note') :</b></label>
                                                    <div class="col-8">
                                                        <input name="sale_note" type="text" class="form-control" id="sale_note" placeholder="@lang('menu.sale_note')" value="{{ $sale->sale_note }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.payment_note'):</b></label>
                                                    <div class="col-8">
                                                        <input name="payment_note" type="text" class="form-control" id="payment_note" placeholder="@lang('menu.payment_note')" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form_element rounded m-0 number-fields">
                                    <div class="element-body">
                                        <div class="row g-2 mb-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.total_item') :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" step="any" name="total_item" id="total_item" class="form-control" value="{{ $sale->total_item }}" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.net_total') :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" step="any" class="form-control" name="net_total_amount" id="net_total_amount" value="{{ $sale->net_total_amount }}" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.discount') :</label>
                                            <div class="col-sm-3">
                                                <select name="order_discount_type" class="form-control" id="order_discount_type">
                                                    <option {{ $sale->order_discount_type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.fixed')</option>
                                                    <option {{ $sale->order_discount_type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.percentage')</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input name="order_discount" type="number" step="any" class="form-control" id="order_discount" value="{{ $sale->order_discount }}">
                                                <input name="order_discount_amount" type="number" step="any" class="d-hide" id="order_discount_amount" value="{{ $sale->order_discount_amount }}">
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.order_tax') :</label>
                                            <div class="col-sm-7">
                                                <select name="order_tax" class="form-control" id="order_tax">
                                                    <option value="0.00">@lang('menu.no_tax')</option>
                                                    @foreach ($taxes as $tax)
                                                        <option {{ $tax->tax_percent == $sale->order_tax_percent ? 'SELECTED' : '' }} value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="number" step="any" class="d-hide" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.shipment_cost') :</label>
                                            <div class="col-sm-7">
                                                <input name="shipment_charge" type="number" step="any" class="form-control" id="shipment_charge" value="{{ $sale->shipment_charge }}">
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.total_payable') :</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control" type="number" step="any" name="total_payable_amount" id="total_payable_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="payment_body {{ $sale->status == 1 || $sale->status == 3 ? '' : 'd-hide' }}">

                                            <div class="row g-2 mb-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.paid') :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" name="previous_paid" class="form-control text-success" id="previous_paid" value="{{ $sale->paid }}" tabindex="-1">
                                                </div>
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.cr_receivable') :</label>
                                                <div class="col-sm-7">
                                                    <input readonly class="form-control" name="current_receivable" type="number" step="any" id="current_receivable" value="" tabindex="-1">
                                                </div>
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.cash_receive') : >></label>
                                                <div class="col-sm-7">
                                                    <input type="number" step="any" name="paying_amount" class="form-control" id="paying_amount" value="0.00" autocomplete="off">
                                                </div>
                                            </div>

                                            {{-- <div class="row">
                                                <label class="col-sm-5 col-form-label">@lang('menu.change') :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" name="change_amount" class="form-control" id="change_amount" value="0.00">
                                                </div>
                                            </div> --}}

                                            <div class="row g-2 mb-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.paid_by') :</label>
                                                <div class="col-sm-7">
                                                    <select name="payment_method_id" class="form-control" id="payment_method_id">
                                                        @foreach ($methods as $method)
                                                            <option
                                                                data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                                                value="{{ $method->id }}">
                                                                {{ $method->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.debit') A/C : <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select name="account_id" class="form-control" id="account_id" data-name="@lang('menu.debit') A/C">
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

                                            <div class="row g-2 mb-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.due') :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" class="form-control text-danger" name="total_due" id="total_due" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="btn-loading">
                                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>@lang('menu.loading')...</span> </button>
                                                    <button type="submit" id="save" class="btn btn-sm btn-success submit_button">@lang('menu.save_change') </button>
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

    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info">Samsung A30 - Black-4GB-64GB - (black-4gb-64gb-85554687)</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="update_selling_product" action="">
                        @if(auth()->user()->can('view_product_cost_is_sale_screed'))
                            <p>
                                <span class="btn btn-sm btn-primary d-hide" id="show_cost_section">
                                    <span>{{ $generalSettings['business__currency'] }}</span>
                                    <span id="unit_cost">1,200.00</span>
                                </span>

                                <span class="btn btn-sm btn-info text-white" id="show_cost_button">@lang('menu.cost')</span>
                            </p>
                        @endif

                        <div class="form-group">
                            <label> <strong>@lang('menu.quantity')</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="Quantity" tabindex="-1"/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>@lang('menu.unit_price_exc_tax')</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" step="any" {{ auth()->user()->can('edit_price_sale_screen') ? '' : 'readonly' }} step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price"/>
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if(auth()->user()->can('edit_discount_sale_screen'))
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>@lang('menu.discount_type')</strong>  :</label>
                                    <select class="form-control " id="e_unit_discount_type">
                                        <option value="2">@lang('menu.percentage')</option>
                                        <option value="1">@lang('menu.fixed')</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>@lang('menu.discount')</strong>  :</label>
                                    <input type="number" step="any" class="form-control " id="e_unit_discount" value="0.00"/>
                                    <input type="hidden" id="e_discount_amount"/>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.tax')</strong> :</label>
                                <select class="form-control" id="e_unit_tax">
                                    <option value="0.00">@lang('menu.no_tax')</option>
                                    @foreach ($taxes as $tax)
                                       <option value="{{ $tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.tax_type')</strong>  :</label>
                                <select class="form-control" id="e_tax_type">
                                    <option value="1">@lang('menu.exclusive')</option>
                                    <option value="2">@lang('menu.exclusive')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>@lang('menu.sale_unit')</strong> :</label>
                            <select class="form-control" id="e_unit"></select>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal End-->

    @if(auth()->user()->can('product_add'))
        <!--Add Product Modal-->
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_product')</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="add_product_body"></div>
                </div>
            </div>
        </div>
        <!--Add Product Modal End-->
    @endif
@endsection
@push('scripts')
    @include('sales.partials.addSaleEditJsScript')
@endpush

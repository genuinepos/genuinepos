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
                    <h6>@lang('menu.add_purchase')</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-lg-3 p-1">
            <form id="add_purchase_form" action="{{ route('purchases.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action" value="">
                <section>
                    <div class="form_element rounded mt-0 mb-lg-3 mb-1">

                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"> <b>@lang('menu.supplier')</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <div class="input-group flex-nowrap">
                                                <select name="supplier_id" class="form-control select2" id="supplier_id" data-next="warehouse_id" autofocus>
                                                    <option value="">@lang('menu.select_supplier')</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option data-pay_term="{{ $supplier->pay_term }}" data-pay_term_number="{{ $supplier->pay_term_number }}" value="{{ $supplier->id }}">{{ $supplier->name.'/'.$supplier->phone }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text add_button" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                </div>
                                            </div>
                                            <span class="error error_supplier_id"></span>
                                        </div>
                                    </div>

                                    @if (count($warehouses) > 0)

                                        <input name="warehouse_count" value="YES" type="hidden"/>
                                        <div class="input-group mt-1">
                                            <label class="col-4"> <b>@lang('menu.warehouse') </b><span
                                                class="text-danger">*</span></label>
                                            <div class="col-8">
                                                <select class="form-control" name="warehouse_id" id="warehouse_id" data-next="invoice_id">
                                                    <option value="">@lang('menu.select_warehouse')</option>
                                                    @foreach ($warehouses as $w)
                                                        <option value="{{ $w->id }}">{{ $w->warehouse_name.'/'.$w->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_warehouse_id"></span>
                                            </div>
                                        </div>
                                    @else

                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>@lang('menu.store_location') </b> </label>
                                            <div class="col-8">
                                                <input readonly type="text" name="branch_id" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : $generalSettings['business__shop_name'].' (HO)' }}"/>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.invoice_id') </b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If you keep this field empty, The Purchase Invoice ID will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                        <div class="col-8">
                                            <input type="text" name="invoice_id" id="invoice_id" class="form-control" data-next="purchase_status" placeholder="@lang('menu.purchase_invoice_id')" autocomplete="off">
                                            <span class="error error_invoice_id"></span>
                                        </div>
                                    </div>

                                    @if ($generalSettings['purchase__is_enable_status'] == '1')
                                        <div class="input-group mt-1">
                                            <label class=" col-4"><b>@lang('menu.status') </b></label>
                                            <div class="col-8">
                                                <select class="form-control changeable" name="purchase_status" id="purchase_status" data-next="date">
                                                    <option value="1">@lang('menu.purchase')</option>
                                                    {{-- <option value="2">@lang('menu.pending')</option> --}}
                                                    <option value="3">@lang('menu.ordered')</option>
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group mt-1">
                                            <label class=" col-4"><span
                                                class="text-danger">*</span> <b>@lang('menu.status')</b> </label>
                                            <div class="col-8">
                                                <input readonly type="text" class="form-control" value="Purchase">
                                                <input type="hidden" name="purchase_status" id="purchase_status" value="1">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('PUR./PO. Date') }}</b></label>
                                        <div class="col-8">
                                            <input type="text" name="date" class="form-control"
                                             id="date" value="{{ date($generalSettings['business__date_format']) }}" data-next="pay_term_number" placeholder="dd-mm-yyyy" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class=" col-4"><b>@lang('menu.pay_term') </b> </label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="text" name="pay_term_number" class="form-control"
                                                id="pay_term_number" data-next="pay_term" placeholder="Number">
                                                <select name="pay_term" class="form-control" id="pay_term" data-next="delivery_date">
                                                    <option value="">@lang('menu.pay_term')</option>
                                                    <option value="1">@lang('menu.days')</option>
                                                    <option value="2">@lang('menu.months')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class=" col-4"><b>@lang('menu.delivery_date') </b></label>
                                        <div class="col-8">
                                            <input type="text" name="delivery_date" class="form-control" id="delivery_date" data-next="purchase_account_id" placeholder="DD-MM-YYYY" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.purchase_ac') <span
                                            class="text-danger">*</span></b></label>
                                        <div class="col-8">
                                            <select name="purchase_account_id" class="form-control" id="purchase_account_id" data-next="search_product">
                                                @foreach ($purchaseAccounts as $purchaseAccount)
                                                    <option value="{{ $purchaseAccount->id }}">
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
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="searching_area" style="position: relative;">
                                    <label class="col-form-label">@lang('menu.item_search')</label>

                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode text-dark input_f"></i></span>
                                        </div>
                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="Search Product by product code(SKU) / Scan bar code" autofocus>
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
                        </div> --}}

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
                                    <input type="hidden" id="e_has_batch_no_expire_date">

                                    <div class="col-xl-4 col-md-4">
                                        <div class="searching_area" style="position: relative;">
                                            <label class="fw-bold">@lang('menu.search_item')</label>
                                            <div class="input-group">
                                                <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="@lang('menu.search_product')">

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
                                                <option value="">@lang('menu.unit')</option>
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
                                                <option value="">@lang('menu.no_tax')</option>
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
                                        <label class="fw-bold">{{ __('Batch No & Expire Date') }}</label>
                                        <div class="input-group">
                                            <input readonly type="text" step="any" class="form-control fw-bold" id="e_batch_number" placeholder="Batch No" autocomplete="off">
                                            <input readonly type="text" step="any" class="form-control fw-bold" id="e_expire_date" placeholder="Expire Date" autocomplete="off">
                                        </div>
                                    </div>

                                    @if ($generalSettings['purchase__is_enable_lot_no'] == '1')
                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">@lang('menu.lot_number')</label>
                                            <input type="text" step="any" class="form-control fw-bold" id="e_lot_number" placeholder="@lang('menu.lot_number')" autocomplete="off">
                                        </div>
                                    @endif

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
                                            <tbody id="purchase_list"></tbody>
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

                                                        <label class="col-4"><b>@lang('menu.total_item') </b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.total_quantity') </b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.order_discount') </b></label>
                                                        <div class="col-8">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <select name="order_discount_type" class="form-control" id="order_discount_type" data-next="order_discount">
                                                                        <option value="1">@lang('menu.fixed')(0.00)</option>
                                                                        <option value="2">@lang('menu.percentage')(%)</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <input name="order_discount" type="number" class="form-control fw-bold" id="order_discount" value="0.00" data-next="purchase_tax">
                                                                </div>
                                                            </div>
                                                            <input name="order_discount_amount" type="number" step="any" class="d-hide" id="order_discount_amount" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.order_tax') </b><span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="purchase_tax" class="form-control" id="purchase_tax" data-next="shipment_charge">
                                                                <option value="0.00">@lang('menu.no_tax')</option>
                                                            </select>
                                                            <input name="purchase_tax_amount" type="number" step="any" class="d-hide" id="purchase_tax_amount" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.shipment_cost') </b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_charge" type="number" class="form-control" id="shipment_charge" value="0.00" data-next="shipment_details">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.shipment_details') </b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_details" type="text" class="form-control" id="shipment_details" data-next="purchase_note" placeholder="@lang('menu.shipment_details')">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.purchase') /@lang('menu.order_note') </b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="purchase_note" id="purchase_note" class="form-control" data-next="paying_amount" placeholder="@lang('menu.order_note').">
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
                                                        <label class=" col-4"><b>@lang('menu.net_total_amount') </b>  {{ $generalSettings['business__currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>@lang('menu.total_payable') </b>  {{ $generalSettings['business__currency'] }}</label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="total_purchase_amount" id="total_purchase_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
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
                                                        <label class="col-4"><b>@lang('menu.credit') A/C <span
                                                            class="text-danger">*</span></b> </label>
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
                                                        <label class=" col-4"><b>@lang('menu.total_due') </b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" class="form-control fw-bold" name="purchase_due" id="purchase_due" value="0.00" tabindex="-1">
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

     <!--Add Product Modal-->
     <div class="modal fade" id="addDescriptionModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg description_modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Description') }} <span id="product_name"></span></h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>@lang('menu.description') </strong></label>
                            <textarea name="product_description" id="product_description" class="form-control" cols="30" rows="10" placeholder="Description"></textarea>
                        </div>
                    </div>

                    <div class="form-group text-end mt-3">
                        <button type="submit" id="add_description" class="btn btn-sm btn-success float-end">@lang('menu.add')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Add Product Modal End-->
@endsection
@push('scripts')
    @include('purchases.partials.purchaseCreateJsScript')
    <script src="{{ asset('backend/asset/js/select2.min.js') }}"></script>
    <script>
        $('.select2').select2();
        
        $('select').on('select2:close', function (e) {

            var nextId = $(this).data('next');

            setTimeout(function () {

                if (nextId == 'warehouse_id' && $('#warehouse_id').val() == undefined) {

                    $('#invoice_id').focus().select();
                    return;
                }

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

                if ($(this).attr('id') == 'paying_amount' && ($('#paying_amount').val() == 0 ||  $('#paying_amount').val() == '' )) {

                    $('#save_and_print').focus().select();
                    return;
                }

                $('#'+nextId).focus().select();
            }
        });

        document.getElementById('supplier_id').focus();
    </script>
@endpush

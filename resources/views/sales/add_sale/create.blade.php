@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .select_area { position: relative; background: #ffffff; box-sizing: border-box; position: absolute; width: 88.3%; z-index: 9999999; padding: 0; left: 6%; display: none; border: 1px solid #706a6d; margin-top: 1px; border-radius: 0px; }

        .select_area ul { list-style: none; margin-bottom: 0; padding: 0px 2px; }

        .select_area ul li a { color: #000000; text-decoration: none; font-size: 11px; padding: 2px 2px; display: block; border: 1px solid lightgray; margin: 2px 0px; }

        .select_area ul li a:hover { background-color: #999396; color: #fff; }

        .selectProduct { background-color: #746e70 !important; color: #fff !important; }

        .input-group-text-sale { font-size: 7px !important; }

        b { font-weight: 500; font-family: Arial, Helvetica, sans-serif; }

        .border_red { border: 1px solid red !important; }

        #display_pre_due { font-weight: 600; }

        input[type=number]#quantity::-webkit-inner-spin-button,
        input[type=number]#quantity::-webkit-outer-spin-button { opacity: 1; margin: 0; }

        .select2-container .select2-selection--single .select2-selection__rendered { display: inline-block; width: 143px; }

        /*.select2-selection:focus {
                 box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
            } */
        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 { text-align: right; padding-right: 10px; }

        .checkbox_input_wrap { text-align: right; }

        .select2-selection:focus { box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%); color: #212529; background-color: #fff; border-color: #86b7fe; outline: 0; }

        .btn-sale { width: calc(50% - 4px); padding-left: 0; padding-right: 0; }

        .sale-item-sec { height: 200px; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
@endpush
@section('title', 'Add Sale - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cart-plus"></span>
                    <h6>{{ __('Add Sale') }}</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-1">
            <form id="add_sale_form" action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">

                <section>
                    <div class="sale-content">
                        <div class="row g-3">
                            <div class="col-md-9">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body p-1">
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Customer') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select name="customer_account_id" class="form-control select2" id="customer_account_id" data-next="invoice_id">
                                                                <option value="">{{ __('Select Supplier') }}</option>
                                                                @foreach ($customerAccounts as $customerAccount)
                                                                    <option data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" id="addCustomer"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error error_customer_account_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Invoice ID") }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="{{ __("Invoice ID") }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Sales Account") }} <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <select name="sale_account_id" class="form-control" id="sale_account_id">
                                                            @foreach ($saleAccounts as $saleAccount)
                                                                <option value="{{ $saleAccount->id }}">
                                                                    {{ $saleAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_sale_account_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Closing Bal.') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" id="closing_balance" class="form-control fw-bold" value="0.00" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Inv Schema --}}
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>{{ __("Date") }} <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="date" class="form-control add_input" data-name="Date" value="{{ date($generalSettings['business__date_format']) }}" autocomplete="off" id="date">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Price Group") }}</b></label>
                                                    <div class="col-8">
                                                        <select name="price_group_id" class="form-control" id="price_group_id">
                                                            <option value="">{{ __("Default Selling Price Group") }}</option>
                                                            @foreach ($priceGroups as $priceGroup)
                                                                <option {{ $generalSettings['sale__default_price_group_id'] == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Warehouse') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="warehouse_id" id="warehouse_id" data-next="date">
                                                            <option value="">{{ __('Select Warehouse') }}</option>
                                                            @foreach ($warehouses as $w)
                                                                @php
                                                                    $isGlobal = $w->is_global == 1 ? ' (' . __('Global Access') . ')' : '';
                                                                @endphp
                                                                <option value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code . $isGlobal }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Status') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select name="status" class="form-control" id="status">
                                                            <option value="">{{ __('Select Sale Status') }}</option>
                                                            @foreach (\App\Enums\SaleStatus::cases() as $saleStatus)
                                                                <option value="{{ $saleStatus->value }}">{{ $saleStatus->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_status"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body py-0">
                                        {{-- <div class="row">
                                            <div class="col-md-9">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="fw-bold">{{ __("Search Product") }}</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-barcode text-dark input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="search_product" class="form-control scanable" id="search_product" placeholder="{{ __("Product Search By Name/Code") }}" autocomplete="off" autofocus>
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

                                            <div class="col-md-3">
                                                <label class="col-form-label"></label>
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button p-1 m-0">@lang('menu.stock')</span>
                                                    </div>
                                                    <input type="text" readonly class="form-control text-success stock_quantity" autocomplete="off" id="stock_quantity" placeholder="Stock Quantity" tabindex="-1">
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="fw-bold">{{ __('Search Product') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __("Search Product By Name/Code") }}" autocomplete="off">
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

                                            <div class="hidden_fields d-none">
                                                <input type="hidden" id="e_unique_id">
                                                <input type="hidden" id="e_unit_cost_inc_tax">
                                                <input type="hidden" id="e_item_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
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
                                                <input {{ auth()->user()->can('edit_price_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_price_exc_tax" placeholder="{{ __("Price Exc. Tax") }}" value="0.00">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Discount') }}</label>
                                                <div class="input-group">
                                                    <input {{ auth()->user()->can('edit_discount_sale_screen')? '': 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_discount" placeholder="{{ __('Discount') }}" value="0.00">

                                                    <select id="e_discount_type" class="form-control">
                                                        <option value="1">{{ __("Fixed") }}(0.00)</option>
                                                        <option value="2">{{ __("Percentage") }}(%)</option>
                                                    </select>

                                                    <input type="hidden" id="e_discount_amount">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Vat/Tax') }}</label>
                                                <div class="input-group">
                                                    <select id="e_tax_ac_id" class="form-control">
                                                        <option data-product_tax_percent="0.00" value="">{{ __("No Vat/Tax") }}</option>
                                                        {{-- @foreach ($taxAccounts as $taxAccount)
                                                            <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach --}}
                                                    </select>

                                                    <select id="e_tax_type" class="form-control" tabindex="-1">
                                                        <option value="1">{{ __('Exclusive') }}</option>
                                                        <option value="2">{{ __('Inclusive') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2 align-items-end">
                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('IMEI/SL No./Other Info') }}</label>
                                                <input type="number" step="any" class="form-control fw-bold" id="e_descriptions" value="" placeholder="IMEI/SL No./Other Info.">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Subtotal') }}</label>
                                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                            </div>

                                            <div class="col-xl-3">

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
                                                                    <th class="text-start">{{ __("Product") }}</th>
                                                                    <th class="text-start">{{ __("Stock Location") }}</th>
                                                                    <th class="text-start">{{ __("Quantity") }}</th>
                                                                    <th class="text-start">{{ __("Unit") }}</th>
                                                                    <th class="text-start">{{ __("Price Inc. Tax") }}</th>
                                                                    <th class="text-start">{{ __("Subtotal") }}</th>
                                                                    <th class="text-start"><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_list"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-1">
                                    <div class="card-body p-1">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-secondary text-white resent-tn">{{ __('Recent Transaction') }}</button>
                                            <button value="save_and_print" class="btn btn-sm btn-primary text-white submit_button" data-status="2">{{ __("Draft") }}</button>
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
                                                        <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="{{ __("Shipment Details") }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Ship. Address') }} </b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" placeholder="{{ __("Shipment Address") }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Ship. Status') }} </b></label>
                                                    <div class="col-8">
                                                        <select name="shipment_status" class="form-control" id="shipment_status">
                                                            <option value="">{{ __("Shipment Status") }}</option>
                                                            <option value="1">{{ __("Ordered") }}</option>
                                                            <option value="2">{{ __('Packed') }}</option>
                                                            <option value="3">{{ __('Shipped') }}</option>
                                                            <option value="4">{{ __('Delivered') }}</option>
                                                            <option value="5">{{ __('Cancelled') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Delivered To') }} </b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" placeholder="{{ __('Delivered To') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Sales Note") }}</b></label>
                                                    <div class="col-8">
                                                        <input name="sale_note" type="text" class="form-control" id="sale_note" placeholder="{{ __("Sales Note") }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Payment Note") }}</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="payment_note" class="form-control" id="payment_note" placeholder="{{ __("Payment Note") }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <div class="row gx-2">
                                            <label class="col-md-5 text-end"><b>{{ __("Total Item") }}</b></label>
                                            <div class="col-md-7">
                                                <input readonly type="number" step="any" name="total_item" id="total_item" class="form-control fw-bold" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row gx-2">
                                            <label class="col-md-5 text-end"><b>{{ __("Total Qty") }}</b></label>
                                            <div class="col-md-7">
                                                <input readonly type="number" step="any" name="total_qty" id="total_qty" class="form-control fw-bold" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __("Net Total Amount") }}</b></label>
                                            <div class="col-md-7">
                                                <input readonly type="number" step="any" class="form-control fw-bold" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __("Order Discount") }}</b></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <select name="order_discount_type" class="form-control" id="order_discount_type">
                                                        <option value="1">{{ __("Fixed") }}(0.00)</option>
                                                        <option value="2">{{ __("Percentage") }}(%)</option>
                                                    </select>
                                                    <input name="order_discount" type="number" step="any" class="form-control fw-bold" id="order_discount" value="0.00">
                                                    <input name="order_discount_amount" step="any" type="number" class="d-hide" id="order_discount_amount" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __("Order Tax") }}</b></label>
                                            <div class="col-md-7">
                                                <select name="order_tax" class="form-control" id="order_tax"></select>
                                                <input type="number" step="any" class="d-hide" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __("Shipment Charge") }}</b></label>
                                            <div class="col-md-7">
                                                <input name="shipment_charge" type="number" step="any" class="form-control fw-bold" id="shipment_charge" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __("Total Invoice Amt.") }}</b></label>
                                            <div class="col-md-7">
                                                <input type="number" step="any" name="total_invoice_amount" id="total_invoice_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="payment_body">
                                            <div class="row g-2">
                                                <label class="col-md-5 text-end"><b>{{ __("Received Amt.") }} >></b></label>
                                                <div class="col-md-7">
                                                    <input type="number" step="any" name="received_amount" class="form-control fw-bold" id="received_amount" value="0.00" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="row g-2">
                                                <label class="col-md-5 text-end"><b>{{ __("Payment Method") }}</b></label>
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

                                            <div class="row g-2">
                                                <label class="col-md-5 text-end"><b>{{ __("Debit A/c") }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-md-7">
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

                                            <div class="row g-2">
                                                <label class="col-md-5 text-end"><b>{{ __("Curr. Balance") }}</b></label>
                                                <div class="col-md-7">
                                                    <input readonly type="number" step="any" class="form-control fw-bold text-danger" name="current_balance" id="current_balance" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex justify-content-end pt-3">
                                                <div class="btn-loading d-flex flex-wrap gap-2 w-100">
                                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                                    <button type="submit" id="quotation" class="btn btn-sale btn-info text-white submit_button" data-status="4" value="save_and_print">@lang('menu.quotation')</button>
                                                    <button type="submit" id="order" class="btn btn-sale btn-secondary text-white submit_button" data-status="3" value="save_and_print">{{ __('Order') }}</button>
                                                    <button type="submit" id="save_and_print" class="btn btn-sale btn-success submit_button" data-status="1" value="save_and_print">{{ __('Final & Print') }}</button>
                                                    <button type="submit" id="save" class="btn btn-sale btn-success submit_button" data-status="1" value="save">@lang('menu.final')</button>
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

    <!--Add Customer Modal-->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_customer')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_customer_modal_body"></div>
            </div>
        </div>
    </div>
    <!--Add Customer Modal-->

    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info">Samsung A30</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="update_selling_product" action="">
                        @if (auth()->user()->can('view_product_cost_is_sale_screed'))
                            <p>
                                <span class="btn btn-sm btn-primary d-hide" id="show_cost_section">
                                    <span>{{ $generalSettings['business__currency'] }}</span>
                                    <span id="unit_cost">1,200.00</span>
                                </span>
                                <span class="btn btn-sm btn-info text-white" id="show_cost_button">@lang('menu.cost')</span>
                            </p>
                        @endif

                        <div class="form-group">
                            <label> <strong>@lang('menu.quantity')</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input fw-bold" data-name="Quantity" id="e_quantity" placeholder="Quantity" tabindex="-1" />
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>@lang('menu.unit_price_exc_tax')</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" {{ auth()->user()->can('edit_price_sale_screen')? '': 'readonly' }} step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price" />
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if (auth()->user()->can('edit_discount_sale_screen'))
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>@lang('menu.discount_type')</strong> </label>
                                    <select class="form-control " id="e_unit_discount_type">
                                        <option value="2">@lang('menu.percentage')</option>
                                        <option value="1">@lang('menu.fixed')</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>@lang('menu.discount')</strong> </label>
                                    <input type="number" step="any" class="form-control fw-bold" id="e_unit_discount" value="0.00" />
                                    <input type="hidden" id="e_discount_amount" />
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.tax')</strong> </label>
                                <select class="form-control" id="e_unit_tax"></select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.tax_type')</strong> </label>
                                <select class="form-control" id="e_tax_type">
                                    <option value="1">@lang('menu.exclusive')</option>
                                    <option value="2">@lang('menu.exclusive')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>@lang('menu.sale_unit')</strong> </label>
                            <select class="form-control" id="e_unit"></select>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-sm btn-success">@lang('menu.update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal End-->

    <!--Add Product Modal-->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_product')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_product_body"></div>
            </div>
        </div>
    </div>
    <!--Add Product Modal End-->

    <!-- Recent transection list modal-->
    <div class="modal fade" id="recentTransModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Recent Transactions') }}</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn tab_active" href="{{ url('common/ajax/call/recent/sales/1') }}"><i class="fas fa-info-circle"></i> @lang('menu.final')</a>

                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn" href="{{ url('common/ajax/call/recent/quotations/1') }}"><i class="fas fa-scroll"></i>@lang('menu.quotation')</a>

                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn" href="{{ url('common/ajax/call/recent/drafts/1') }}"><i class="fas fa-shopping-bag"></i> @lang('menu.draft')</a>
                        </div>
                    </div>

                    <div class="tab_contant">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="data_preloader" id="recent_trans_preloader">
                                        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table modal-table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">@lang('menu.sl')</th>
                                                    <th class="text-start">@lang('menu.invoice_id')</th>
                                                    <th class="text-start">@lang('menu.customer')</th>
                                                    <th class="text-start">@lang('menu.total')</th>
                                                    <th class="text-start">@lang('menu.action')</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data-list" id="transection_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show stock modal-->
    <div class="modal fade" id="showStockModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="data_preloader mt-5" id="stock_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">@lang('menu.item_stocks')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="stock_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Show stock modal end-->
@endsection
@push('scripts')
    @include('sales.partials.addSaleCreateJsScript')
@endpush

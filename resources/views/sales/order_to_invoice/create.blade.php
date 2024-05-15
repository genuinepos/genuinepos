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
            font-size: 24px !important;
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

        .select2-container .select2-selection--single .select2-selection__rendered {
            width: 100%;
        }

        .btn-sale {
            width: calc(50% - 4px);
            padding-left: 0;
            padding-right: 0;
        }

        .sale-item-sec {
            height: 250px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
@endpush
@section('title', 'Sales Order To Invoice - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name g-0">
                <div class="col-md-7">
                    <div class="name-head">
                        <h6>{{ __('Sales Order To Invoice') }}</h6>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="row g-0">
                        <div class="col-md-10">
                            <div class="input-group">
                                <label class="col-4 offset-md-6"><b>{{ __('Print') }}</b></label>
                                <div class="col-2">
                                    <select id="select_print_page_size" class="form-control">
                                        @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                            <option {{ $generalSettings['print_page_size__add_sale_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button d-inline"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-1">
            <form id="add_sale_form" action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="status" value="1">
                <input type="hidden" name="print_page_size" id="print_page_size" value="{{ $generalSettings['print_page_size__add_sale_page_size'] }}">
                <section>
                    <div class="sale-content">
                        <div class="row g-1">
                            <div class="col-md-9">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body p-1">
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Order ID') }}</b></label>
                                                    <div class="col-8">
                                                        <input type="text" id="sales_order_id" class="form-control fw-bold" placeholder="{{ __('Search Order') }}" value="{{ $order?->order_id }}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Customer') }}</b></label>
                                                    <div class="col-8">
                                                        <input type="hidden" name="customer_account_id" id="customer_account_id" value="{{ $order?->customer_account_id }}">
                                                        <input readonly type="text" id="customer_name" class="form-control fw-bold" value="{{ $order?->customer?->name . '/' . $order?->customer?->phone }}" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Invoice ID') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control fw-bold" placeholder="{{ __('Invoice ID') }}" autocomplete="off" tabindex="-1">
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>{{ __('Date') }} <span class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <input required type="text" name="date" class="form-control" value="{{ date($generalSettings['business_or_shop__date_format']) }}" data-next="sale_account_id" autocomplete="off" id="date">
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
                                                                <option @selected($order?->sale_account_id == $saleAccount->id) value="{{ $saleAccount->id }}">
                                                                    {{ $saleAccount->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_sale_account_id"></span>
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
                                                <label class="fw-bold">{{ __('Select Ordered Product') }}</label>
                                                <select class="form-control select2" id="sales_order_product_id">
                                                    <option value="">{{ __('Select Product') }}</option>
                                                    @foreach ($order?->saleProducts ? $order?->saleProducts : [] as $orderedProduct)
                                                        <option value="{{ $orderedProduct->id }}" data-name="{{ $orderedProduct?->product?->name . ($orderedProduct?->variant ? '-' . $orderedProduct?->variant?->name : '') }}" data-p_id="{{ $orderedProduct->product_id }}" data-is_manage_stock="{{ $orderedProduct?->product?->is_manage_stock }}" data-v_id="{{ $orderedProduct?->variant_id }}" data-p_tax_ac_id="{{ $orderedProduct->tax_ac_id ? $orderedProduct->tax_ac_id : '' }}" data-tax_type="{{ $orderedProduct?->tax_type }}" data-is_show_emi_on_pos="{{ $orderedProduct?->product?->is_show_emi_on_pos }}" data-p_price_exc_tax="{{ $orderedProduct?->unit_price_exc_tax }}" data-p_discount="{{ $orderedProduct?->unit_discount }}" data-p_discount_type="{{ $orderedProduct?->unit_discount_type }}" data-p_discount_amount="{{ $orderedProduct?->unit_discount_amount }}" data-p_price_inc_tax="{{ $orderedProduct?->unit_price_inc_tax }}"
                                                            data-p_cost_inc_tax="{{ $orderedProduct?->unit_cost_inc_tax }}" data-p_ordered_quantity="{{ $orderedProduct?->ordered_quantity }}" data-p_delivered_quantity="{{ $orderedProduct?->delivered_quantity }}" data-p_left_quantity="{{ $orderedProduct?->left_quantity }}">
                                                            {{ $orderedProduct?->product?->name . ($orderedProduct?->variant ? '-' . $orderedProduct?->variant?->name : '') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="hidden_fields d-none">
                                                <input type="hidden" id="e_unique_id">
                                                <input type="hidden" id="e_unit_cost_inc_tax">
                                                <input type="hidden" id="e_product_name">
                                                <input type="hidden" id="e_product_id">
                                                <input type="hidden" id="e_variant_id">
                                                <input type="hidden" id="e_tax_amount">
                                                <input type="hidden" id="e_is_show_emi_on_pos">
                                                <input type="hidden" id="e_price_inc_tax">
                                                <input type="hidden" id="e_current_quantity">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Ordered Quantity') }}</label>
                                                <input readonly type="number" step="any" class="form-control fw-bold" id="e_ordered_quantity" placeholder="{{ __('Ordered Quantity') }}" value="0.00" tabindex="-1">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Left Quantity') }}</label>
                                                <input readonly type="number" step="any" class="form-control fw-bold text-danger" id="e_left_quantity" placeholder="{{ __('Left Quantity') }}" value="0.00" tabindex="-1">
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Deliver Quantity') }}</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control fw-bold w-60" id="e_quantity" placeholder="{{ __('Quantity') }}" value="0.00">
                                                    <select id="e_unit_id" class="form-control w-40">
                                                        <option value="">{{ __('Unit') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Unit Price (Exc. Tax)') }}</label>
                                                <input {{ auth()->user()->can('edit_price_sale_screen') ? '' : 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_price_exc_tax" placeholder="{{ __('Price Exc. Tax') }}" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2 align-items-end">
                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('Discount') }}</label>
                                                <div class="input-group">
                                                    <input {{ auth()->user()->can('edit_discount_sale_screen') ? '' : 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_discount" placeholder="{{ __('Discount') }}" value="0.00">

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

                                            <div class="col-xl-2 col-md-6">
                                                <label class="fw-bold">{{ __('IMEI/SL No./Other Info') }}</label>
                                                <input type="number" step="any" class="form-control fw-bold" id="e_descriptions" value="" placeholder="IMEI/SL No./Other Info.">
                                            </div>

                                            <div class="col-xl-2 col-md-6 warehouse_field">
                                                <label class="fw-bold">{{ __('Stock Location') }}</label>
                                                <select class="form-control" id="e_warehouse_id">
                                                    <option value="">{{ $branchName }}</option>
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

                                            {{-- <div class="col-xl-2 col-md-6 offset-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button p-1 m-0">{{ __('Stock') }}</span>
                                                    </div>

                                                    <input type="text" readonly class="form-control text-success fw-bold" autocomplete="off" id="stock_quantity" placeholder="{{ __('Stock Quantity') }}" tabindex="-1">
                                                </div>
                                            </div> --}}
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">{{ __('Stock Location') }}</th>
                                                                    <th class="text-start">{{ __('Product') }}</th>
                                                                    <th class="text-start">{{ __('Ordered Qty') }}</th>
                                                                    <th class="text-start">{{ __('Left Qty') }}</th>
                                                                    <th class="text-start">{{ __('Deliver Qty') }}</th>
                                                                    <th class="text-start">{{ __('Unit') }}</th>
                                                                    <th class="text-start">{{ __('Price Inc. Tax') }}</th>
                                                                    <th class="text-start">{{ __('Subtotal') }}</th>
                                                                    <th class="text-start"><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_product_list"></tbody>
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
                                                        <input type="text" name="shipment_details" class="form-control" id="shipment_details" data-next="shipment_address" placeholder="{{ __('Shipment Details') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Ship. Address') }} </b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" data-next="shipment_status" placeholder="{{ __('Shipment Address') }}">
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
                                                                <option value="{{ $shipmentStatus->value }}">{{ $shipmentStatus->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Delivered To') }} </b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" data-next="note" placeholder="{{ __('Delivered To') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Sales Note') }}</b></label>
                                                    <div class="col-8">
                                                        <input name="note" type="text" class="form-control" id="note" data-next="payment_note" placeholder="{{ __('Sales Note') }}">
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
                                                <input readonly type="number" step="any" name="total_item" id="total_item" class="form-control fw-bold" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row gx-2">
                                            <label class="col-md-5 text-end"><b>{{ __('Total Qty') }}</b></label>
                                            <div class="col-md-7">
                                                <input readonly type="number" step="any" name="total_qty" id="total_qty" class="form-control fw-bold" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __('Net Total Amount') }}</b></label>
                                            <div class="col-md-7">
                                                <input readonly type="number" step="any" class="form-control fw-bold" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __('Sale Discount') }}</b></label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <input name="order_discount" type="number" step="any" class="form-control fw-bold" id="order_discount" data-next="order_discount_type" value="0.00">
                                                    <input name="order_discount_amount" step="any" type="number" class="d-hide" id="order_discount_amount" value="0.00" tabindex="-1">

                                                    <select name="order_discount_type" class="form-control" id="order_discount_type" data-next="sale_tax_ac_id">
                                                        <option value="1">{{ __('Fixed') }}(0.00)</option>
                                                        <option value="2">{{ __('Percentage') }}(%)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __('Sale Tax') }}</b></label>
                                            <div class="col-md-7">
                                                <select name="sale_tax_ac_id" class="form-control" id="sale_tax_ac_id" data-next="shipment_charge">
                                                    <option data-order_tax_percent="0.00" value="">{{ __('No Vat/Tax') }}</option>
                                                    @foreach ($taxAccounts as $taxAccount)
                                                        <option {{ $generalSettings['add_sale__default_tax_ac_id'] == $taxAccount->id ? 'SELECTED' : '' }} data-order_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                            {{ $taxAccount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="number" step="any" class="d-none" name="order_tax_percent" id="order_tax_percent" value="0.00">
                                                <input type="number" step="any" class="d-none" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __('Shipment Charge') }}</b></label>
                                            <div class="col-md-7">
                                                <input name="shipment_charge" type="number" step="any" class="form-control fw-bold" id="shipment_charge" data-next="received_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __('Total Invoice Amt.') }}</b></label>
                                            <div class="col-md-7">
                                                <input type="number" step="any" name="total_invoice_amount" id="total_invoice_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
                                                <input type="number" step="any" name="sales_ledger_amount" id="sales_ledger_amount" class="d-none" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="payment_body">
                                            <div class="row g-2">
                                                <label class="col-md-5 text-end"><b>{{ __('Received Amt.') }} >></b></label>
                                                <div class="col-md-7">
                                                    <input type="number" step="any" name="received_amount" class="form-control big_amount_field fw-bold" id="received_amount" data-next="payment_method_id" value="0.00" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="row g-2">
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

                                            <div class="row g-2">
                                                <label class="col-md-5 text-end"><b>{{ __('Debit A/c') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-md-7">
                                                    <select name="account_id" class="form-control" id="account_id" data-next="final">
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
                                                <label class="col-md-5 text-end"><b>{{ __('Curr. Balance') }}</b></label>
                                                <div class="col-md-7">
                                                    <input readonly type="number" step="any" class="form-control fw-bold text-danger" name="current_balance" id="current_balance" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex justify-content-end pt-1">
                                                <div class="btn-loading d-flex flex-wrap gap-2 w-100">
                                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                                    <button type="submit" id="final_and_print" class="btn btn-sale btn-success submit_button" data-status="1" value="save_and_print">{{ __('Final & Print') }}</button>
                                                    <button type="submit" id="final" class="btn btn-sale btn-success submit_button" data-status="1" value="final">{{ __('Final') }}</button>
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
@endsection
@push('scripts')
    <script>
        var itemUnitsArray = [];
        var branch_id = "{{ auth()->user()->branch_id }}";
        var branch_name = "{{ $branchName }}";

        $(document).on('change', '#sales_order_product_id', function(e) {

            var product_name = $(this).find('option:selected').data('name');
            var product_id = $(this).find('option:selected').data('p_id');
            var variant_id = $(this).find('option:selected').data('v_id') ? $(this).find('option:selected').data('v_id') : 'noid';
            var is_manage_stock = $(this).find('option:selected').data('is_manage_stock');
            var unit_cost_inc_tax = $(this).find('option:selected').data('p_cost_inc_tax');
            var unit_price_exc_tax = $(this).find('option:selected').data('p_price_exc_tax');
            var unit_discount = $(this).find('option:selected').data('p_discount');
            var unit_discount_type = $(this).find('option:selected').data('p_discount_type');
            var unit_discount_amount = $(this).find('option:selected').data('p_discount_amount');
            var unit_price_inc_tax = $(this).find('option:selected').data('p_price_inc_tax');
            var tax_ac_id = $(this).find('option:selected').data('p_tax_ac_id') != null ? $(this).find('option:selected').data('p_tax_ac_id') : '';
            var tax_type = $(this).find('option:selected').data('tax_type');
            var is_show_emi_on_pos = $(this).find('option:selected').data('is_show_emi_on_pos');

            var ordered_quantity = $(this).find('option:selected').data('p_ordered_quantity');
            var delivered_quantity = $(this).find('option:selected').data('p_delivered_quantity');
            var left_quantity = $(this).find('option:selected').data('p_left_quantity');

            var url = "{{ route('general.product.search.check.product.discount.with.stock', ['productId' => ':product_id', 'variantId' => ':variant_id', 'priceGroupId' => 'no_id', 'branchId' => auth()->user()->branch_id]) }}"
            var route = url.replace(':product_id', product_id);
            route = route.replace(':variant_id', variant_id);

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(data) {

                    if ($.isEmptyObject(data.errorMsg)) {

                        if (is_manage_stock == 1) {

                            $('#stock_quantity').val(parseFloat(data.stock).toFixed(2));
                        }

                        if (is_show_emi_on_pos == 0) {

                            $('#e_descriptions').prop('readonly', true);
                        } else {

                            $('#e_descriptions').prop('readonly', false);
                        }

                        $('#e_product_name').val(product_name);
                        $('#e_product_id').val(product_id);
                        $('#e_variant_id').val(variant_id);
                        $('#e_ordered_quantity').val(parseFloat(ordered_quantity).toFixed(2)).focus().select();
                        // $('#e_left_quantity').val(parseFloat(left_quantity).toFixed(2)).focus().select();
                        $('#e_quantity').val(parseFloat(1).toFixed(2)).focus().select();
                        $('#e_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
                        $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
                        $('#e_discount_type').val(unit_discount_type);
                        $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
                        $('#e_tax_ac_id').val(tax_ac_id);
                        $('#e_tax_type').val(tax_type);
                        $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
                        $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);

                        $('#e_unit_id').empty();
                        $('#e_unit_id').append(
                            '<option value="' + data.unit.id + '" data-is_base_unit="1" data-unit_name="' + data.unit.name + '" data-base_unit_multiplier="1">' + data.unit.name + '</option>'
                        );

                        itemUnitsArray[product_id] = [{
                            'unit_id': data.unit.id,
                            'unit_name': data.unit.name,
                            'unit_code_name': data.unit.code_name,
                            'base_unit_multiplier': 1,
                            'multiplier_details': '',
                            'is_base_unit': 1,
                        }];

                        $('#add_item').html('Add');

                        calculateEditOrAddAmount();
                    } else {

                        toastr.error(data.errorMsg);
                    }
                }
            });
        });

        $('#add_item').on('click', function(e) {
            e.preventDefault();

            var sales_order_product_id = $('#sales_order_product_id').val();

            var e_unique_id = $('#e_unique_id').val();
            var e_product_name = $('#e_product_name').val();
            var e_product_id = $('#e_product_id').val();
            var e_variant_id = $('#e_variant_id').val();
            var e_unit_name = $('#e_unit_id').find('option:selected').data('unit_name');
            var e_unit_id = $('#e_unit_id').val();
            var e_ordered_quantity = $('#e_ordered_quantity').val() ? $('#e_ordered_quantity').val() : 0;
            var e_left_quantity = $('#e_left_quantity').val() ? $('#e_left_quantity').val() : 0;
            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
            var e_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;
            var e_discount_type = $('#e_discount_type').val();
            var e_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0;
            var e_tax_ac_id = $('#e_tax_ac_id').val();
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0;
            var e_tax_amount = $('#e_tax_amount').val() ? $('#e_tax_amount').val() : 0;
            var e_tax_type = $('#e_tax_type').val();
            var e_price_inc_tax = $('#e_price_inc_tax').val() ? $('#e_price_inc_tax').val() : 0;
            var e_subtotal = $('#e_subtotal').val() ? $('#e_subtotal').val() : 0;
            var e_unit_cost_inc_tax = $('#e_unit_cost_inc_tax').val() ? $('#e_unit_cost_inc_tax').val() : 0;
            var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
            var e_descriptions = $('#e_descriptions').val();
            var stock_quantity = $('#stock_quantity').val();

            var e_warehouse_id = $('#e_warehouse_id').val() ? $('#e_warehouse_id').val() : '';
            var warehouse_name = $('#e_warehouse_id').find('option:selected').data('w_name');

            if (parseFloat(e_left_quantity) < 0) {

                toastr.error("{{ __('Deliver Quantity must not be greater then left quantity.') }}");
                return;
            }

            var stock_location_name = '';
            if (e_warehouse_id) {

                stock_location_name = warehouse_name;
            } else {

                stock_location_name = branch_name;
            }

            if (e_quantity == '') {

                toastr.error("{{ __('Quantity field must not be empty.') }}");
                return;
            }

            if (e_product_id == '') {

                toastr.error("{{ __('Please select an item.') }}");
                return;
            }

            var route = '';
            if (e_variant_id != 'noid') {

                var url = "{{ route('general.product.search.variant.product.stock', [':product_id', ':variant_id', ':warehouse_id']) }}";
                route = url.replace(':product_id', e_product_id);
                route = route.replace(':variant_id', e_variant_id);
                route = route.replace(':warehouse_id', e_warehouse_id);
            } else {

                var url = "{{ route('general.product.search.single.product.stock', [':product_id', ':warehouse_id']) }}";
                route = url.replace(':product_id', e_product_id);
                route = route.replace(':warehouse_id', e_warehouse_id);
            }

            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                success: function(data) {

                    var status = $('#status').val();

                    if (status == 1 || status == '') {

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        var stockLocationMessage = e_warehouse_id ? "{{ __('in selected warehouse') }} " : "{{ __('in the Shop') }} ";
                        if (parseFloat(e_quantity) > parseFloat(data.stock)) {

                            toastr.error("{{ __('Current stock is') }} " + parseFloat(data.stock) + '/' + e_unit_name + stockLocationMessage);
                            return;
                        }
                    }

                    var uniqueIdForPreventDuplicateEntry = e_product_id + e_variant_id + e_warehouse_id;
                    var uniqueIdValue = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).val();

                    if (uniqueIdValue == undefined) {

                        var tr = '';



                        tr += '<tr id="select_product">';

                        tr += '<td class="text-start">';
                        tr += '<input type="hidden" name="warehouse_ids[]" id="warehouse_id" value="' + e_warehouse_id + '">';
                        tr += '<span id="stock_location_name">' + stock_location_name + '</span>';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span class="product_name">' + e_product_name + '</span>';
                        tr += '<input type="hidden" id="product_name" value="' + e_product_name + '">';
                        tr += '<input type="hidden" name="is_show_emi_on_pos" id="is_show_emi_on_pos" value="' + e_is_show_emi_on_pos + '">';
                        tr += '<input type="hidden" name="descriptions[]" id="descriptions" value="' + e_descriptions + '">';
                        tr += '<input type="hidden" id="sales_order_product_ids" value="' + sales_order_product_id + '">';
                        tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + e_product_id + '">';
                        tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + e_variant_id + '">';
                        tr += '<input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="' + e_tax_ac_id + '">';
                        tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + e_tax_type + '">';
                        tr += '<input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="' + e_tax_percent + '">';
                        tr += '<input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="' + parseFloat(e_tax_amount).toFixed(2) + '">';
                        tr += '<input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="' + e_discount_type + '">';
                        tr += '<input type="hidden" name="unit_discounts[]" id="unit_discount" value="' + e_discount + '">';
                        tr += '<input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="' + e_discount_amount + '">';
                        tr += '<input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="' + e_unit_cost_inc_tax + '">';
                        tr += '<input type="hidden" id="current_stock" value="' + stock_quantity + '">';
                        tr += '<input type="hidden" data-product_name="' + e_product_name + '" data-unit_name="' + e_unit_name + '" id="stock_limit" value="' + data.stock + '">';
                        tr += '<input type="hidden" class="unique_id" id="' + e_product_id + e_variant_id + e_warehouse_id + '" value="' + e_product_id + e_variant_id + e_warehouse_id + '">';
                        tr += '</td>';



                        tr += '<td class="text-start">';
                        tr += '<span id="span_ordered_quantity" class="fw-bold">' + parseFloat(e_ordered_quantity).toFixed(2) + '</span>';
                        tr += '<input type="hidden" id="ordered_quantity" value="' + parseFloat(e_ordered_quantity).toFixed(2) + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span id="span_left_quantity" class="text-danger fw-bold">' + parseFloat(e_left_quantity).toFixed(2) + '</span>';
                        tr += '<input type="hidden" id="ordered_quantity" value="' + parseFloat(e_left_quantity).toFixed(2) + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span id="span_quantity" class="fw-bold">' + parseFloat(e_quantity).toFixed(2) + '</span>';
                        tr += '<input type="hidden" name="quantities[]" id="quantity" value="' + parseFloat(e_quantity).toFixed(2) + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<b><span id="span_unit">' + e_unit_name + '</span></b>';
                        tr += '<input type="hidden" name="unit_ids[]" id="unit_id" value="' + e_unit_id + '">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<input type="hidden" name="unit_prices_exc_tax[]" id="unit_price_exc_tax" value="' + parseFloat(e_price_exc_tax).toFixed(2) + '">';
                        tr += '<input type="hidden" name="unit_prices_inc_tax[]" id="unit_price_inc_tax" value="' + parseFloat(e_price_inc_tax).toFixed(2) + '">';
                        tr += '<span id="span_unit_price_inc_tax" class="fw-bold">' + parseFloat(e_price_inc_tax).toFixed(2) + '</span>';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<span id="span_subtotal" class="fw-bold">' + parseFloat(e_subtotal).toFixed(2) + '</span>';
                        tr += '<input type="hidden" name="subtotals[]" id="subtotal" value="' + parseFloat(e_subtotal).toFixed(2) + '" tabindex="-1">';
                        tr += '</td>';

                        tr += '<td class="text-start">';
                        tr += '<a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';
                        tr += '</tr>';

                        var index = getProductLastIndex(e_product_id, e_variant_id);
                        var __index = index > 0 ? index : 0;
                        // $('#sale_product_list').append(tr);
                        // $('#sale_product_list  tbody tr').eq(__index).after(tr);
                        var $tableRows = $('.sale-product-table tbody tr');
                        if ($tableRows.length > 0) {
                            $tableRows.eq(__index).after(tr);
                        } else {
                            $('.sale-product-table tbody').append(tr);
                        }

                        clearEditItemFileds();
                        // calculateTotalAmount();
                        recalculateRunningLeftQty(e_product_id, e_variant_id);
                    } else {

                        var tr = $('#' + (e_unique_id ? e_unique_id : uniqueIdForPreventDuplicateEntry)).closest('tr');

                        tr.find('#product_name').val(e_product_name);
                        tr.find('#product_id').val(e_product_id);
                        tr.find('#variant_id').val(e_variant_id);
                        tr.find('#tax_ac_id').val(e_tax_ac_id);
                        tr.find('#tax_type').val(e_tax_type);
                        tr.find('#unit_tax_percent').val(parseFloat(e_tax_percent).toFixed(2));
                        tr.find('#unit_tax_amount').val(parseFloat(e_tax_amount).toFixed(2));
                        tr.find('#unit_discount_type').val(e_discount_type);
                        tr.find('#unit_discount').val(parseFloat(e_discount).toFixed(2));
                        tr.find('#unit_discount_amount').val(parseFloat(e_discount_amount).toFixed(2));
                        tr.find('#unit_cost_inc_tax').val(parseFloat(e_unit_cost_inc_tax).toFixed(2));
                        tr.find('#ordered_quantity').val(parseFloat(e_ordered_quantity).toFixed(2));
                        tr.find('#span_ordered_quantity').html(parseFloat(e_ordered_quantity).toFixed(2));
                        tr.find('#left_quantity').val(parseFloat(e_left_quantity).toFixed(2));
                        tr.find('#span_left_quantity').html(parseFloat(e_left_quantity).toFixed(2));
                        tr.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
                        tr.find('#span_quantity').html(parseFloat(e_quantity).toFixed(2));
                        tr.find('#span_unit').html(e_unit_name);
                        tr.find('#unit_id').val(e_unit_id);
                        tr.find('#unit_price_exc_tax').val(parseFloat(e_price_exc_tax).toFixed(2));
                        tr.find('#unit_price_inc_tax').val(parseFloat(e_price_inc_tax).toFixed(2));
                        tr.find('#span_unit_price_inc_tax').html(parseFloat(e_price_inc_tax).toFixed(2));
                        tr.find('#subtotal').val(parseFloat(e_subtotal).toFixed(2));
                        tr.find('#span_subtotal').html(parseFloat(e_subtotal).toFixed(2));
                        tr.find('#is_show_emi_on_pos').val(e_is_show_emi_on_pos);
                        tr.find('#descriptions').val(e_descriptions);
                        tr.find('#stock_limit').val(data.stock);
                        tr.find('#stock_limit').data('unit_name', e_unit_name);
                        tr.find('.unique_id').val(e_product_id + e_variant_id + e_warehouse_id);
                        tr.find('.unique_id').attr('id', e_product_id + e_variant_id + e_warehouse_id);
                        tr.find('#warehouse_id').val(e_warehouse_id);
                        tr.find('#stock_location_name').html(stock_location_name);

                        clearEditItemFileds();
                        // calculateTotalAmount();
                        recalculateRunningLeftQty(e_product_id, e_variant_id);
                    }

                    $('#add_item').html('Add');
                }
            })
        });

        $(document).on('click', '#select_product', function(e) {

            var tr = $(this);
            var unique_id = tr.find('#unique_id').val();
            var warehouse_id = tr.find('#warehouse_id').val();
            var stock_location_name = tr.find('#stock_location_name').html();
            var product_name = tr.find('#product_name').val();
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#variant_id').val();
            var tax_ac_id = tr.find('#tax_ac_id').val();
            var tax_type = tr.find('#tax_type').val();
            var unit_tax_amount = tr.find('#unit_tax_amount').val();
            var unit_discount_type = tr.find('#unit_discount_type').val();
            var unit_discount = tr.find('#unit_discount').val();
            var unit_discount_amount = tr.find('#unit_discount_amount').val();
            var unit_cost_inc_tax = tr.find('#unit_cost_inc_tax').val();
            var ordered_quantity = tr.find('#ordered_quantity').val();
            var quantity = tr.find('#quantity').val();
            var unit_id = tr.find('#unit_id').val();
            var unit_price_exc_tax = tr.find('#unit_price_exc_tax').val();
            var unit_price_inc_tax = tr.find('#unit_price_inc_tax').val();
            var subtotal = tr.find('#subtotal').val();
            var is_show_emi_on_pos = tr.find('#is_show_emi_on_pos').val();
            var descriptions = tr.find('#descriptions').val();
            var current_stock = tr.find('#current_stock').val();

            $('#e_unit_id').empty();

            itemUnitsArray[product_id].forEach(function(unit) {

                $('#e_unit_id').append(
                    '<option ' + (unit_id == unit.unit_id ? 'selected' : '') +
                    ' value="' + unit.unit_id + '" data-is_base_unit="' + unit.is_base_unit +
                    '" data-unit_name="' + unit.unit_name + '" data-base_unit_multiplier="' + unit
                    .base_unit_multiplier + '">' + unit.unit_name + unit.multiplier_details +
                    '</option>'
                );
            });

            if (is_show_emi_on_pos == 0) {

                $('#e_descriptions').prop('readonly', true);
            } else {

                $('#e_descriptions').prop('readonly', false);
            }

            $('#e_unique_id').val(unique_id);
            $('#e_warehouse_id').val(warehouse_id);
            $('#e_stock_location_name').val(stock_location_name);
            $('#e_product_name').val(product_name);
            $('#e_product_id').val(product_id);
            $('#e_variant_id').val(variant_id);
            $('#e_unit_id').val(unit_id);
            $('#e_ordered_quantity').val(parseFloat(ordered_quantity).toFixed(2));
            $('#e_current_quantity').val(parseFloat(quantity).toFixed(2));
            $('#e_quantity').val(parseFloat(quantity).toFixed(2)).focus().select();
            $('#e_price_exc_tax').val(parseFloat(unit_price_exc_tax).toFixed(2));
            $('#e_discount').val(parseFloat(unit_discount).toFixed(2));
            $('#e_discount_type').val(unit_discount_type);
            $('#e_discount_amount').val(parseFloat(unit_discount_amount).toFixed(2));
            $('#e_tax_ac_id').val(tax_ac_id);
            $('#e_tax_amount').val(parseFloat(unit_tax_amount).toFixed(2));
            $('#e_tax_type').val(tax_type);
            $('#e_price_inc_tax').val(parseFloat(unit_price_inc_tax).toFixed(2));
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
            $('#e_unit_cost_inc_tax').val(parseFloat(unit_cost_inc_tax).toFixed(2));
            $('#e_is_show_emi_on_pos').val(is_show_emi_on_pos);
            $('#e_descriptions').val(descriptions);
            $('#stock_quantity').val(parseFloat(current_stock).toFixed(2));

            calculateEditOrAddAmount();

            $('#add_item').html('Update');
        });

        function recalculateRunningLeftQty(productId, variantId) {

            var runningDeliverQty = 0;
            var totalDelivedQty = 0;
            var lastRunningDeliverQty = 0;
            var lastOrderedQty = 0;
            var lastTr = null;
            var totalTr = 0;
            $('#sale_product_list > tr').each(function(index, tr) {

                var rowProductId = $(tr).find('#product_id').val();
                var rowVariantId = $(tr).find('#variant_id').val();

                if (productId == rowProductId && variantId == rowVariantId) {

                    var currentDeliverQty = $(tr).find('#quantity').val() ? $(tr).find('#quantity').val() : 0;
                    var orderedQty = $(tr).find('#ordered_quantity').val() ? $(tr).find('#ordered_quantity').val() : 0;

                    totalDelivedQty += parseFloat(currentDeliverQty);

                    var runningLeftQty = parseFloat(orderedQty) - parseFloat(currentDeliverQty) - parseFloat(runningDeliverQty);
                    lastRunningDeliverQty = parseFloat(orderedQty) - parseFloat(currentDeliverQty) - parseFloat(runningDeliverQty);
                    lastOrderedQty = parseFloat(orderedQty);

                    $(tr).find('#left_quantity').val(parseFloat(runningLeftQty).toFixed(2));
                    // $(tr).find('#span_left_quantity').html(parseFloat(runningLeftQty).toFixed(2));
                    $(tr).find('#span_ordered_quantity').html('<i class="fa-solid fa-down-long text-dark"></i>');
                    // $(tr).find('#span_ordered_quantity').html('');
                    $(tr).find('#span_left_quantity').html('<i class="fa-solid fa-down-long text-dark"></i>');
                    // $(tr).find('#span_left_quantity').html('');
                    runningDeliverQty += parseFloat(currentDeliverQty);

                    lastTr = $(tr);
                    totalTr += 1;
                }
            });

            var extra = totalTr > 1 ? '<span class="text-dark"> (Total Del. : ' + parseFloat(totalDelivedQty).toFixed(2) + ')</span>' : '';

            lastTr.find('#span_ordered_quantity').html(parseFloat(lastOrderedQty).toFixed(2));
            lastTr.find('#span_left_quantity').html(parseFloat(lastRunningDeliverQty).toFixed(2) + extra);
        }

        function getProductLastIndex(productId, variantId) {

            var lastIndex = 0;
            $('#sale_product_list > tr').each(function(index, tr) {

                var rowProductId = $(tr).find('#product_id').val();
                var rowVariantId = $(tr).find('#variant_id').val();

                if (productId == rowProductId && variantId == rowVariantId) {

                    lastIndex = index;
                }
            });

            return lastIndex;
        }

        function calculateEditOrAddAmount() {

            var e_product_id = $('#e_product_id').val();
            var e_variant_id = $('#e_variant_id').val();
            var e_tax_percent = $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') ? $('#e_tax_ac_id').find('option:selected').data('product_tax_percent') : 0.00;
            var e_tax_type = $('#e_tax_type').val();
            var e_discount_type = $('#e_discount_type').val();
            var e_ordered_quantity = $('#e_ordered_quantity').val() ? $('#e_ordered_quantity').val() : 0;
            // var e_left_quantity = $('#e_left_quantity').val() ? $('#e_left_quantity').val() : 0;
            var e_current_quantity = $('#e_current_quantity').val() ? $('#e_current_quantity').val() : 0;
            var e_quantity = $('#e_quantity').val() ? $('#e_quantity').val() : 0;
            var e_price_exc_tax = $('#e_price_exc_tax').val() ? $('#e_price_exc_tax').val() : 0;
            var e_unit_discount = $('#e_discount').val() ? $('#e_discount').val() : 0;

            var quantity = 0;
            $('#sale_product_list > tr').each(function(index, tr) {

                var productId = $(tr).find('#product_id').val();
                var variantId = $(tr).find('#variant_id').val();

                if (productId == e_product_id && e_variant_id == variantId) {

                    var deliverQty = $(tr).find('#quantity').val() ? $(tr).find('#quantity').val() : 0;
                    quantity += parseFloat(deliverQty);
                }
            });

            // var leftQty = (parseFloat(e_ordered_quantity) - parseFloat(quantity) - parseFloat(e_quantity));
            var leftQty = (parseFloat(e_ordered_quantity) - parseFloat(quantity) - parseFloat(e_quantity)) + parseFloat(e_current_quantity);
            $('#e_left_quantity').val(parseFloat(leftQty).toFixed(2));

            var discount_amount = 0;
            if (e_discount_type == 1) {

                discount_amount = e_unit_discount;
            } else {

                discount_amount = (parseFloat(e_price_exc_tax) / 100) * parseFloat(e_unit_discount);
            }

            var unitPriceWithDiscount = parseFloat(e_price_exc_tax) - parseFloat(discount_amount);
            var taxAmount = parseFloat(unitPriceWithDiscount) / 100 * parseFloat(e_tax_percent);
            var unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);

            if (e_tax_type == 2) {

                var inclusiveTax = 100 + parseFloat(e_tax_percent);
                var calcTax = parseFloat(unitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
                taxAmount = parseFloat(unitPriceWithDiscount) - parseFloat(calcTax);
                unitPriceIncTax = parseFloat(unitPriceWithDiscount) + parseFloat(taxAmount);
            }

            $('#e_tax_amount').val(parseFloat(taxAmount).toFixed(2));
            $('#e_discount_amount').val(parseFloat(parseFloat(discount_amount)).toFixed(2));
            $('#e_price_inc_tax').val(parseFloat(parseFloat(unitPriceIncTax)).toFixed(2));

            var subtotal = parseFloat(unitPriceIncTax) * parseFloat(e_quantity);
            $('#e_subtotal').val(parseFloat(subtotal).toFixed(2));
        }

        function clearEditItemFileds() {

            $('#e_unique_id').val('');
            $('#e_product_name').val('');
            $('#e_product_id').val('');
            $('#e_variant_id').val('');
            $('#e_base_unit_name').val('');
            $('#e_ordered_quantity').val(parseFloat(0).toFixed(2));
            $('#e_left_quantity').val(parseFloat(0).toFixed(2));
            $('#e_quantity').val(parseFloat(0).toFixed(2));
            $('#e_current_quantity').val(parseFloat(0).toFixed(2));
            $('#e_price_exc_tax').val(parseFloat(0).toFixed(2));
            $('#e_discount_type').val(1);
            $('#e_discount').val(parseFloat(0).toFixed(2));
            $('#e_discount_amount').val(parseFloat(0).toFixed(2));
            $('#e_tax_ac_id').val('');
            $('#e_tax_amount').val(parseFloat(0).toFixed(2));
            $('#e_tax_type').val(1);
            $('#e_price_inc_tax').val(parseFloat(0).toFixed(2));
            $('#e_subtotal').val(parseFloat(0).toFixed(2));
            $('#e_unit_cost_inc_tax').val(0);
            $('#e_is_show_discription').val('');
            $('#stock_quantity').val(parseFloat(0).toFixed(2));
            $('#e_warehouse_id').val('');

            $("#sales_order_product_id").val('');
            $("#sales_order_product_id").select2("destroy");
            $("#sales_order_product_id").select2();
            $("#sales_order_product_id").focus();

            $('#add_item').html('Add');
        }

        $(document).on('click', '#remove_product_btn', function(e) {

            e.preventDefault();

            $(this).closest('tr').remove();

            // calculateTotalAmount();
            recalculateRunningLeftQty(e_product_id, e_variant_id);

            setTimeout(function() {

                clearEditItemFileds();
            }, 5);
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        $('#e_quantity').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#e_unit_id').focus();
                }
            }
        });

        $('#e_unit_id').on('change keypress click', function(e) {

            if (e.which == 0) {

                $('#e_price_exc_tax').focus().select();
            }

            calculateEditOrAddAmount();
        });

        $('#e_price_exc_tax').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '') {

                    $('#e_discount').focus().select();
                }
            }
        });

        $('#e_discount').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                if ($(this).val() != '' && $(this).val() > 0) {

                    $('#e_discount_type').focus();
                } else {

                    $('#e_tax_ac_id').focus();
                }
            }
        });

        $('#e_discount_type').on('change keypress click', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#e_tax_ac_id').focus();
            }
        });

        $('#e_tax_ac_id').on('change keypress click', function(e) {

            calculateEditOrAddAmount();
            var val = $(this).val();
            var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
            var status = $('#status').val();

            if (e.which == 0) {

                if (val) {

                    $('#e_tax_type').focus();
                } else {

                    if (e_is_show_emi_on_pos == 1) {

                        $('#e_descriptions').focus().select();
                    } else {

                        if (status == 1 || status == '') {

                            var warehouse = $('#e_warehouse_id').val();
                            if (warehouse != undefined) {

                                $('#e_warehouse_id').focus();
                            } else {

                                $('#add_item').focus();
                            }
                        } else {

                            $('#add_item').focus();
                        }
                    }
                }
            }
        });

        $('#e_tax_type').on('change keypress click', function(e) {

            calculateEditOrAddAmount();
            var e_is_show_emi_on_pos = $('#e_is_show_emi_on_pos').val();
            var status = $('#status').val();

            if (e.which == 0) {

                if (e_is_show_emi_on_pos == 1) {

                    $('#e_descriptions').focus().select();
                } else {

                    if (status == 1 || status == '') {

                        var warehouse = $('#e_warehouse_id').val();
                        if (warehouse != undefined) {

                            $('#e_warehouse_id').focus();
                        } else {

                            $('#add_item').focus();
                        }
                    } else {

                        $('#add_item').focus();
                    }
                }
            }
        });

        $('#e_descriptions').on('input keypress', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 13) {

                var warehouse = $('#e_warehouse_id').val();
                if (warehouse != undefined) {

                    $('#e_warehouse_id').focus();
                } else {

                    $('#add_item').focus();
                }
            }
        });

        $('#e_warehouse_id').on('change keypress click', function(e) {

            calculateEditOrAddAmount();

            if (e.which == 0) {

                $('#add_item').focus();
            }
        });
    </script>
@endpush

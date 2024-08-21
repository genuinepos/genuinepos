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
@section('title', 'Add Service Quotation - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name g-0">
                <div class="col-md-7">
                    <div class="name-head">
                        <h6>{{ __('Add Service Quotation') }}</h6>
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
            <form id="add_quotation_form" action="{{ route('services.quotations.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="status" id="status" value="{{ \App\Enums\SaleStatus::Quotation->value }}">
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
                                                    <label class="col-4"><b>{{ __('Customer') }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select name="customer_account_id" class="form-control select2" id="customer_account_id" data-next="date">
                                                                @foreach ($customerAccounts as $customerAccount)
                                                                    @php
                                                                        $accountType = $customerAccount->sub_sub_group_number == 6 ? '' : ' -(' . __('Supplier') . ')';
                                                                    @endphp
                                                                    <option data-default_balance_type="{{ $customerAccount->default_balance_type }}" data-sub_sub_group_number="{{ $customerAccount->sub_sub_group_number }}" data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone . $accountType }}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text {{ $generalSettings['subscription']->features['contacts'] == 0 || !auth()->user()->can('customer_add') ? 'disabled_element' : '' }} add_button" id="{{ $generalSettings['subscription']->features['contacts'] == 1 && auth()->user()->can('customer_add') ? 'addContact' : '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error error_customer_account_id"></span>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class="col-4"><b>{{ __('Closing Bal.') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" id="closing_balance" class="form-control fw-bold text-danger" value="0.00" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Quotation ID') }}</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="quotation_id" id="quotation_id" class="form-control fw-bold" placeholder="{{ __('Quotation ID') }}" autocomplete="off" tabindex="-1">
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
                                                                <option value="{{ $saleAccount->id }}">
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
                                                                <option {{ $generalSettings['add_sale__default_price_group_id'] == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
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
                                                <input {{ auth()->user()->can('edit_price_sale_screen') ? '' : 'readonly' }} type="number" step="any" class="form-control fw-bold" id="e_price_exc_tax" placeholder="{{ __('Price Exc. Tax') }}" value="0.00">
                                            </div>

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
                                <div class="form_element rounded m-0 pt-3">
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
                                                <input name="shipment_charge" type="number" step="any" class="form-control fw-bold" id="shipment_charge" data-next="save_and_print" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-md-5 text-end"><b>{{ __('Total Amount') }}</b></label>
                                            <div class="col-md-7">
                                                <input type="number" step="any" name="total_invoice_amount" id="total_invoice_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row justify-content-center mt-3 mb-3">
                                            <div class="col-12 d-flex justify-content-end pt-1">
                                                <div class="btn-loading d-flex flex-wrap gap-2 w-100">
                                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                                    <button type="submit" id="save_and_print" class="btn btn-sale btn-success submit_button" data-status="4" value="save_and_print">{{ __('Save & Print') }}</button>
                                                    <button type="submit" id="save" class="btn btn-sale btn-success submit_button" data-status="4" value="save">{{ __('Save') }}</button>
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

    @if ($generalSettings['subscription']->features['contacts'] == \App\Enums\BooleanType::True->value && auth()->user()->can('customer_add'))
        <div class="modal fade" id="addOrEditContactModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif

    @if ($generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value && auth()->user()->can('product_add'))
        <div class="modal fade" id="addQuickProductModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

        <div class="modal fade" id="unitAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="categoryAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="brandAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
        <div class="modal fade" id="warrantyAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    @endif
@endsection
@push('scripts')
    @include('services.quotations.js_partials.add_js')
@endpush

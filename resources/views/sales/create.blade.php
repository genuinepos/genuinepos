@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 88.3%;z-index: 9999999;padding: 0;left: 6%;display: none;border: 1px solid #706a6d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 0px 2px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 11px;padding: 2px 2px;display: block;border: 1px solid lightgray; margin: 2px 0px;}
        .select_area ul li a:hover {background-color: #999396;color: #fff;}
        .selectProduct {background-color: #746e70!important;color: #fff !important;}
        .input-group-text-sale {font-size: 7px !important;}
        b{font-weight: 500; font-family: Arial, Helvetica, sans-serif;}
        .border_red { border: 1px solid red!important; }
        #display_pre_due{font-weight: 600;}
        input[type=number]#quantity::-webkit-inner-spin-button,
        input[type=number]#quantity::-webkit-outer-spin-button {opacity: 1;margin: 0;}
        .select2-container .select2-selection--single .select2-selection__rendered {display: inline-block;width: 143px;}
        /*.select2-selection:focus {
             box-shadow: 0 0 5px 0rem rgb(90 90 90 / 38%);
        } */

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
            height: 237px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cart-plus"></span>
                    <h6>@lang('menu.add_sale')</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_sale_form" action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <section>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row g-3">
                            <div class="col-md-9">
                                <div class="form_element rounded mt-0 mb-3">

                                    <div class="element-body p-1">
                                        <div class="row g-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.customer') :</b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select name="customer_id" class="form-control select2" id="customer_id">
                                                                <option value="">{{ __('Walk-In-Customer') }}</option>
                                                                @foreach ($customers as $customer)
                                                                    <option data-customer_name="{{ $customer->name }}" data-customer_phone="{{ $customer->phone }}" value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                                @endforeach
                                                            </select>

                                                            <div>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" id="addCustomer">
                                                                        <i class="fas fa-plus-square text-dark"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.invoice_id') :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="If you keep this field empty, The invoice ID will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                                    <div class="col-8">
                                                        <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Invoice ID" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.sales_account') : <span
                                                        class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <select name="sale_account_id" class="form-control add_input"
                                                            id="sale_account_id" data-name="Sale A/C">
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
                                                    <label class="col-4"><b>@lang('menu.previous_due') :</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="number" step="any" class="form-control text-danger" id="display_pre_due" value="0.00" tabindex="-1">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Inv Schema --}}
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('menu.date') : <span
                                                        class="text-danger">*</span></b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="date" class="form-control add_input" data-name="Date" value="{{ date($generalSettings['business']['date_format']) }}" autocomplete="off" id="date">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.attachment') : <i data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice related any file.Ex: Scanned cheque, payment prove file etc. Max Attachment Size 2MB." class="fas fa-info-circle tp"></i></b></label>
                                                    <div class="col-8">
                                                        <input type="file" name="attachment" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.price_group') :</b></label>
                                                    <div class="col-8">
                                                        <select name="price_group_id" class="form-control"
                                                            id="price_group_id">
                                                            <option value="">@lang('menu.default_selling_price')</option>
                                                            @foreach ($price_groups as $pg)
                                                                <option {{ $generalSettings['sale']['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-4"> <b>@lang('menu.warehouse') :</b> </label>
                                                    <div class="col-8">
                                                        <input type="hidden" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : $generalSettings['business']['shop_name'].'(HO)' }}" id="branch_name">
                                                        <input type="hidden" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL' }}" id="branch_id">
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

                                            <div class="col-md-4">
                                                <div class="row g-1">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"> <b>@lang('menu.status'):<span class="text-danger">*</span></b></label>
                                                            <div class="col-8">
                                                                <select name="status" class="form-control add_input" data-name="Status"
                                                                    id="status">
                                                                    <option value="">@lang('menu.select_status')</option>
                                                                    @foreach (App\Utils\SaleUtil::saleStatus() as $key => $status)
                                                                        <option value="{{ $key }}">{{ $status }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error error_status"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>{{ __('I.Sch') }} :</b></label>
                                                            <div class="col-8">
                                                                <select name="invoice_schema" class="form-control"
                                                                    id="invoice_schema">
                                                                    <option value="">@lang('menu.none')</option>
                                                                    @foreach ($invoice_schemas as $inv_schema)
                                                                        <option value="{{$inv_schema->format == 2 ? date('Y') . '/' . $inv_schema->start_from : $inv_schema->prefix . $inv_schema->start_from }}">
                                                                            {{$inv_schema->format == 2 ? date('Y') . '/' . $inv_schema->start_from : $inv_schema->prefix . $inv_schema->start_from }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded mt-0 mb-3">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">@lang('menu.item_search')</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-barcode text-dark input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" id="search_product" placeholder="Search Product by product code(SKU) / Scan bar code" autocomplete="off" autofocus>
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
                                                    <input type="text" readonly class="form-control text-success stock_quantity"
                                                        autocomplete="off" id="stock_quantity"
                                                        placeholder="Stock Quantity" tabindex="-1">
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
                                                            <tbody id="sale_list"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-body p-1">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-success text-white show_stock">@lang('menu.show_stock')</button>
                                            <button type="button" class="btn btn-sm btn-secondary text-white resent-tn">{{ __('Recent Transaction') }}</button>
                                            <button value="save_and_print" class="btn btn-sm btn-primary text-white submit_button" data-status="2">@lang('menu.draft')</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <div class="row gx-2 gy-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>{{ __('Ship Details') }} :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="@lang('menu.shipment_details')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>{{ __('Ship Address') }} :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" placeholder="Shipment Address">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>{{ __('Ship Status') }} :</b></label>
                                                    <div class="col-8">
                                                        <select name="shipment_status" class="form-control" id="shipment_status">
                                                            <option value="">@lang('menu.shipment_status')</option>
                                                            <option value="1">@lang('menu.ordered')</option>
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
                                                    <label class=" col-4"><b>{{ __('Delivered To') }} :</b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" placeholder="{{ __('Delivered To') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('menu.sale_note')</b></label>
                                                    <div class="col-8">
                                                        <input name="sale_note" type="text" class="form-control" id="sale_note" placeholder="@lang('menu.sale_note')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>@lang('menu.payment_note') :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="payment_note" class="form-control" id="payment_note" placeholder="@lang('menu.payment_note')">
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
                                            <label class="col-sm-5 col-form-label">@lang('menu.total_item') :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" step="any" name="total_item" id="total_item" class="form-control" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.net_total') :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" step="any" class="form-control" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.discount'):</label>
                                            <div class="col-sm-3 col-6">
                                                <select name="order_discount_type" class="form-control" id="order_discount_type">
                                                    <option value="1">@lang('menu.fixed')</option>
                                                    <option value="2">@lang('menu.percentage')</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4 col-6">
                                                <input name="order_discount" type="number" step="any" class="form-control" id="order_discount" value="0.00">
                                                <input name="order_discount_amount" step="any" type="number" class="d-hide" id="order_discount_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.order_tax') :</label>
                                            <div class="col-sm-7">
                                                <select name="order_tax" class="form-control" id="order_tax"></select>
                                                <input type="number" step="any" class="d-hide" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.shipment_cost'):</label>
                                            <div class="col-sm-7">
                                                <input name="shipment_charge" type="number" step="any" class="form-control" id="shipment_charge" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.previous_due') :</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control text-danger" type="number" step="any" name="previous_due" id="previous_due" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <label class="col-sm-5 col-form-label">@lang('menu.total_payable'):</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control" type="number" step="any" name="total_payable_amount" id="total_payable_amount" value="0.00" tabindex="-1">
                                                <input class="d-hide" type="number" step="any" name="total_invoice_payable" id="total_invoice_payable" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="payment_body">
                                            <div class="row g-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.cash_receive'): >></label>
                                                <div class="col-sm-7">
                                                    <input type="number" step="any" name="paying_amount" class="form-control" id="paying_amount" value="0.00" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="row g-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.change') :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" name="change_amount" class="form-control" id="change_amount" value="0.00" tabindex="-1">
                                                </div>
                                            </div>

                                            <div class="row g-2">
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

                                            <div class="row g-2">
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

                                            <div class="row g-2">
                                                <label class="col-sm-5 col-form-label">@lang('menu.due') :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" class="form-control text-danger" name="total_due" id="total_due" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex justify-content-end pt-3">
                                                <div class="btn-loading d-flex flex-wrap gap-2">
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
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_customer_modal_body"></div>
            </div>
        </div>
    </div>
    <!--Add Customer Modal-->

    <!--Add Customer Opening Balance Modal-->
    <div class="modal fade" id="addCustomerOpeingBalanceModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
    aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_customer_opening_balance')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="add_customer_opening_balance" action="{{ route('contacts.customer.add.opening.balance') }}" method="POST">
                        @csrf
                        <input type="hidden" id="op_branch_id" name="branch_id">
                        <input type="hidden" id="op_customer_id" name="customer_id">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <p><strong>@lang('menu.customer') : </strong> <span class="op_customer_name"></span></p>
                                <p><strong>@lang('menu.phone_no') : </strong> <span class="op_customer_phone"></span></p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>@lang('menu.business_location') : </strong> <span class="op_branch_name"></span></p>
                            </div>

                            <div class="col-md-12 mt-2">
                                <label><b>@lang('menu.opening_balance') :</b> </label>
                                <input type="number" step="any" name="opening_balance" class="form-control" placeholder="@lang('menu.opening_balance')">
                            </div>

                            <div class="col-12 mt-2">
                                <div class="row">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="never_show_again" id="never_show_again" class="is_show_again">&nbsp;<b>@lang('menu.never_show_again')</b>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn op_loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button name="action" value="save" type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Add Customer Opening Balance Modal End-->

    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info">Samsung A30</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="update_selling_product" action="">
                        @if(auth()->user()->can('view_product_cost_is_sale_screed'))
                            <p>
                                <span class="btn btn-sm btn-primary d-hide" id="show_cost_section">
                                    <span>{{ $generalSettings['business']['currency'] }}</span>
                                    <span id="unit_cost">1,200.00</span>
                                </span>
                                <span class="btn btn-sm btn-info text-white" id="show_cost_button">@lang('menu.cost')</span>
                            </p>
                        @endif

                        <div class="form-group">
                            <label> <strong>@lang('menu.quantity')</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="Quantity" tabindex="-1"/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>@lang('menu.unit_price_exc_tax')</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" {{ auth()->user()->can('edit_price_sale_screen') ? '' : 'readonly' }} step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price" />
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if(auth()->user()->can('edit_discount_sale_screen'))
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>@lang('menu.discount_type')</strong> :</label>
                                    <select class="form-control " id="e_unit_discount_type">
                                        <option value="2">@lang('menu.percentage')</option>
                                        <option value="1">@lang('menu.fixed')</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>@lang('menu.discount')</strong> :</label>
                                    <input type="number" step="any" class="form-control " id="e_unit_discount" value="0.00"/>
                                    <input type="hidden" id="e_discount_amount"/>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>@lang('menu.tax')</strong> :</label>
                                <select class="form-control" id="e_unit_tax"></select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>@lang('menu.tax_type')</strong> :</label>
                                <select class="form-control" id="e_tax_type">
                                    <option value="1">@lang('menu.exclusive')</option>
                                    <option value="2">@lang('menu.exclusive')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong >@lang('menu.sale_unit')</strong> :</label>
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
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
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
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn tab_active" href="{{url('common/ajax/call/recent/sales/1')}}"><i class="fas fa-info-circle"></i> @lang('menu.final')</a>

                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn" href="{{url('common/ajax/call/recent/quotations/1')}}"><i class="fas fa-scroll"></i>@lang('menu.quotation')</a>

                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn" href="{{url('common/ajax/call/recent/drafts/1')}}"><i class="fas fa-shopping-bag"></i> @lang('menu.draft')</a>
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
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
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



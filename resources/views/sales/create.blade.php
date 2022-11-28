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
                    <h6>Add Sale</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_sale_form" action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <section>
                    <div class="form_element rounded mt-0 mb-3">

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label class=" col-4"><b>@lang('menu.customer') :</b> </label>
                                        <div class="col-8">
                                            <div class="input-group flex-nowrap">
                                                <select name="customer_id" class="form-control select2" id="customer_id">
                                                    <option value="">Walk-In-Customer</option>
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

                                    <div class="input-group mt-1">
                                        <label class="col-4"> <b>Warehouse :</b> </label>
                                        <div class="col-8">
                                            <input type="hidden" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}" id="branch_name">
                                            <input type="hidden" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL' }}" id="branch_id">
                                            <select name="warehouse_id" class="form-control" id="warehouse_id">
                                                <option value="">Select Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option data-w_name="{{ $warehouse->name.'/'.$warehouse->code }}" value="{{ $warehouse->id }}">
                                                        {{ $warehouse->name.'/'.$warehouse->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label class="col-4"><b>Invoice ID :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="If you keep this field empty, The invoice ID will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                        <div class="col-8">
                                            <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Invoice ID" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>Attachment : <i data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice related any file.Ex: Scanned cheque, payment prove file etc. Max Attachment Size 2MB." class="fas fa-info-circle tp"></i></b></label>
                                        <div class="col-8">
                                            <input type="file" name="attachment" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="col-4"> <b>Status : <span
                                            class="text-danger">*</span></b></label>
                                        <div class="col-8">
                                            <select name="status" class="form-control add_input" data-name="Status"
                                                id="status">
                                                <option value="">Select status</option>
                                                @foreach (App\Utils\SaleUtil::saleStatus() as $key => $status)
                                                    <option value="{{ $key }}">{{ $status }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_status"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class=" col-4"><b>@lang('menu.date') : <span
                                            class="text-danger">*</span></b></label>
                                        <div class="col-8">
                                            <input type="text" name="date" class="form-control add_input" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" autocomplete="off" id="date">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="col-6"><b>Inv. Schema :</b></label>
                                        <div class="col-6">
                                            <select name="invoice_schema" class="form-control"
                                                id="invoice_schema">
                                                <option value="">None</option>
                                                @foreach ($invoice_schemas as $inv_schema)
                                                    <option value="{{$inv_schema->format == 2 ? date('Y') . '/' . $inv_schema->start_from : $inv_schema->prefix . $inv_schema->start_from }}">
                                                        {{$inv_schema->format == 2 ? date('Y') . '/' . $inv_schema->start_from : $inv_schema->prefix . $inv_schema->start_from }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-6"><b>Previous Due :</b></label>
                                        <div class="col-6">
                                            <input readonly type="number" step="any" class="form-control text-danger" id="display_pre_due" value="0.00" tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="col-5"><b>Price Group :</b></label>
                                        <div class="col-7">
                                            <select name="price_group_id" class="form-control"
                                                id="price_group_id">
                                                <option value="">Default Selling Price</option>
                                                @foreach ($price_groups as $pg)
                                                    <option {{ json_decode($generalSettings->sale, true)['default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-5"><b>Sales A/C : <span
                                            class="text-danger">*</span></b></label>
                                        <div class="col-7">
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
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row g-3">
                            <div class="col-md-9">
                                <div class="form_element rounded mt-0 mb-3">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">Item Search</label>
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
                                                        <span class="input-group-text add_button p-1 m-0">Stock</span>
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
                                                                    <th class="text-start">Product</th>
                                                                    <th class="text-start">Stock Location</th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th>Unit</th>
                                                                    <th class="text-center">Price Inc.Tax</th>
                                                                    <th>SubTotal</th>
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

                                <div class="form_element rounded mt-0 mb-3">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>Ship Details :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="Shipment Details">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>Ship Address :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" placeholder="Shipment Address">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>Ship Status :</b></label>
                                                    <div class="col-8">
                                                        <select name="shipment_status" class="form-control" id="shipment_status">
                                                            <option value="">Shipment Status</option>
                                                            <option value="1">Ordered</option>
                                                            <option value="2">Packed</option>
                                                            <option value="3">Shipped</option>
                                                            <option value="4">Delivered</option>
                                                            <option value="5">Cancelled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>Delivered To :</b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" placeholder="Delivered To">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>Sale Note:</b></label>
                                                    <div class="col-8">
                                                        <input name="sale_note" type="text" class="form-control" id="sale_note" placeholder="Sale note">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class=" col-4"><b>Payment Note :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="payment_note" class="form-control" id="payment_note" placeholder="Payment note">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-success text-white show_stock">Show Stock</button>
                                            <button type="button" class="btn btn-sm btn-secondary text-white resent-tn">Recent Transection</button>
                                            <button value="save_and_print" class="btn btn-sm btn-primary text-white submit_button" data-status="2">Draft</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <div class="row mb-2">
                                            <label class="col-sm-5 col-form-label">Total Item :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" step="any" name="total_item" id="total_item" class="form-control" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5 col-form-label">Net Total :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" step="any" class="form-control" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5 col-form-label">Discount:</label>
                                            <div class="col-sm-3">
                                                <select name="order_discount_type" class="form-control" id="order_discount_type">
                                                    <option value="1">Fixed</option>
                                                    <option value="2">Percentage</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input name="order_discount" type="number" step="any" class="form-control" id="order_discount" value="0.00">
                                                <input name="order_discount_amount" step="any" type="number" class="d-hide" id="order_discount_amount" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5 col-form-label">Order Tax :</label>
                                            <div class="col-sm-7">
                                                <select name="order_tax" class="form-control" id="order_tax"></select>
                                                <input type="number" step="any" class="d-hide" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5 col-form-label">Shipment Cost:</label>
                                            <div class="col-sm-7">
                                                <input name="shipment_charge" type="number" step="any" class="form-control" id="shipment_charge" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5 col-form-label">Previous Due :</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control text-danger" type="number" step="any" name="previous_due" id="previous_due" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-sm-5 col-form-label">Total Payable:</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control" type="number" step="any" name="total_payable_amount" id="total_payable_amount" value="0.00" tabindex="-1">
                                                <input class="d-hide" type="number" step="any" name="total_invoice_payable" id="total_invoice_payable" value="0.00" tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="payment_body">
                                            <div class="row mb-2">
                                                <label class="col-sm-5 col-form-label">Cash Receive: >></label>
                                                <div class="col-sm-7">
                                                    <input type="number" step="any" name="paying_amount" class="form-control" id="paying_amount" value="0.00" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5 col-form-label">Change :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" name="change_amount" class="form-control" id="change_amount" value="0.00" tabindex="-1">
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-sm-5 col-form-label">Paid By :</label>
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

                                            <div class="row mb-2">
                                                <label class="col-sm-5 col-form-label">Debit A/C : <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <select name="account_id" class="form-control" id="account_id" data-name="Debit A/C">
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

                                            <div class="row mb-2">
                                                <label class="col-sm-5 col-form-label">Due :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" class="form-control text-danger" name="total_due" id="total_due" value="0.00" tabindex="-1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="btn-loading d-flex flex-wrap gap-2">
                                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>Loading . . .</span></button>
                                                    <button type="submit" id="quotation" class="btn btn-info text-white submit_button" data-status="4" value="save_and_print">Quotation</button>
                                                    <button type="submit" id="order" class="btn btn-secondary text-white submit_button" data-status="3" value="save_and_print">Order</button>
                                                    <button type="submit" id="save_and_print" class="btn btn-success submit_button" data-status="1" value="save_and_print">Final & Print</button>
                                                    <button type="submit" id="save" class="btn btn-success submit_button" data-status="1" value="save">Final</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Customer</h6>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Customer Opening Balance</h6>
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
                                <p><strong>Phone No. : </strong> <span class="op_customer_phone"></span></p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>Business Location : </strong> <span class="op_branch_name"></span></p>
                            </div>

                            <div class="col-md-12 mt-2">
                                <label><b>@lang('menu.opening_balance') :</b> </label>
                                <input type="number" step="any" name="opening_balance" class="form-control" placeholder="@lang('menu.opening_balance')">
                            </div>

                            <div class="col-12 mt-2">
                                <div class="row">
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="never_show_again" id="never_show_again" class="is_show_again">&nbsp;<b>Never Show Again.</b>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn op_loading_button d-hide"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">Close</button>
                                    <button name="action" value="save" type="submit" class="btn btn-sm btn-success">Save</button>
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
                                    <span>{{ json_decode($generalSettings->business, true)['currency'] }}</span>
                                    <span id="unit_cost">1,200.00</span>
                                </span>
                                <span class="btn btn-sm btn-info text-white" id="show_cost_button">Cost</span>
                            </p>
                        @endif

                        <div class="form-group">
                            <label> <strong>Quantity</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="Quantity" tabindex="-1"/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>Unit Price Exc.Tax</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" {{ auth()->user()->can('edit_price_sale_screen') ? '' : 'readonly' }} step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price" />
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if(auth()->user()->can('edit_discount_sale_screen'))
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>Discount Type</strong> :</label>
                                    <select class="form-control " id="e_unit_discount_type">
                                        <option value="2">Percentage</option>
                                        <option value="1">Fixed</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>Discount</strong> :</label>
                                    <input type="number" step="any" class="form-control " id="e_unit_discount" value="0.00"/>
                                    <input type="hidden" id="e_discount_amount"/>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>Tax</strong> :</label>
                                <select class="form-control" id="e_unit_tax"></select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>Tax Type</strong> :</label>
                                <select class="form-control" id="e_tax_type">
                                    <option value="1">Exclusive</option>
                                    <option value="2">Inclusive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>Sale Unit</strong> :</label>
                            <select class="form-control" id="e_unit"></select>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-sm btn-success">Update</button>
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
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
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
                    <h6 class="modal-title" id="exampleModalLabel">Recent Transections</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn tab_active" href="{{url('common/ajax/call/recent/sales/1')}}"><i class="fas fa-info-circle"></i> Final</a>

                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn" href="{{url('common/ajax/call/recent/quotations/1')}}"><i class="fas fa-scroll"></i>Quotation</a>

                            <a id="tab_btn" class="btn btn-sm btn-primary tab_btn" href="{{url('common/ajax/call/recent/drafts/1')}}"><i class="fas fa-shopping-bag"></i> Draft</a>
                        </div>
                    </div>

                    <div class="tab_contant">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="data_preloader" id="recent_trans_preloader">
                                        <h6><i class="fas fa-spinner"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table modal-table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">SL</th>
                                                    <th class="text-start">Invoice ID</th>
                                                    <th class="text-start">Customer</th>
                                                    <th class="text-start">Total</th>
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
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end">Close</button>
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
                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">Item Stocks</h6>
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



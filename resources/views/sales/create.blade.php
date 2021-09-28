@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 88.3%;z-index: 9999999;padding: 0;left: 6%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 12px;padding: 4px 3px;display: block;border: 1px solid lightgray; margin-top: 3px;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
        .selectProduct {background-color: #ab1c59;color: #fff !important;}
        .input-group-text-sale {font-size: 7px !important;}
        b{font-weight: 500; font-family: Arial, Helvetica, sans-serif;}
        #display_pre_due{font-weight: 800;}
        input[type=number]#quantity::-webkit-inner-spin-button, 
        input[type=number]#quantity::-webkit-outer-spin-button {opacity: 1;margin: 0;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_sale_form" action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Add Sale</h5>
                                        </div>

                                        <div class="col-6">
                                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Customer :</b> </label>
                                                <div class="col-8">
                                                    <div class="input-group width-60">
                                                        <select name="customer_id" class="form-control" id="customer_id">
                                                            <option value="0">Walk-In-Customer</option>
                                                            @foreach ($customers as $customer)
                                                                <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" id="addCustomer"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"> <b>B. Location :</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Invoice ID :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="If you keep this field empty, The invoice ID will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Invoice ID" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Attachment : <i data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice related any file.Ex: Scanned cheque, payment prove file etc. Max Attachment Size 2MB." class="fas fa-info-circle tp"></i></b></label>
                                                <div class="col-8">
                                                    <input type="file" name="attachment" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"> <b>Status : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-8">
                                                    <select name="status" class="form-control add_input" data-name="Status"
                                                        id="status">
                                                        <option value="">Select status</option>
                                                        <option value="1">Final</option>
                                                        <option value="2">Draft</option>
                                                        <option value="4">Quatation</option>
                                                    </select>
                                                    <span class="error error_status"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Date :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control datepicker"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" autocomplete="off" id="datepicker">
                                                        <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-6"><b>Inv. Schema :</b></label>
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
                                                <label for="inputEmail3" class="col-6 text-danger"><b>Previous Due :</b></label>
                                                <div class="col-6">
                                                    <input readonly type="number" step="any" class="form-control" id="display_pre_due" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-5"><b>Price Group :</b></label>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">Item Search</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-barcode text-dark"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable"
                                                            autocomplete="off" id="search_product"
                                                            placeholder="Search Product by product code(SKU) / Scan bar code" autofocus>
                                                        <div class="input-group-prepend">
                                                            <span id="add_product" class="input-group-text add_button"><i
                                                                    class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
    
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area">
                                                           
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="col-form-label"></label>
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text add_button p-1 m-0">Stock</span>
                                                    </div>
                                                    <input type="text" readonly class="form-control"
                                                        autocomplete="off" id="stock_quantity"
                                                        placeholder="Stock Quantity">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">Product</th>
                                                                    <th></th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th>Unit</th>
                                                                    <th class="text-center">Price Inc.Tax</th>
                                                                    <th>SubTotal</th>
                                                                    <th><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_list">
                                                          
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec mt-2">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Ship Details :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="Shipment Details">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Ship Address :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" placeholder="Shipment Address"> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Ship Status :</b></label>
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
                                                    <label for="inputEmail3" class=" col-4"><b>Delivered To :</b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" placeholder="Delivered To"> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Sale Note:</b></label>
                                                    <div class="col-8">
                                                        <input name="sale_note" type="text" class="form-control" id="sale_note" placeholder="Sale note">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Payment Note :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="payment_note" class="form-control" id="payment_note" placeholder="Payment note">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec mt-3">
                                    <div class="content-inner">
                                        <div class="row no-gutters">
                                            <ul class="list-unstyled add_sale_ex_btn">
                                                <li><button value="save_and_print" class="btn btn-sm btn-info text-white submit_button" data-status="4">Quotation</button></li>
                                                <li><button value="save_and_print" class="btn btn-sm btn-warning text-white submit_button" data-status="2">Draft</button></li>
                                                <li><button type="button" class="btn btn-sm btn-secondary text-white resent-tn">Recent Transection</button></li>
                                                <li><button type="button" class="btn btn-sm btn-success text-white show_stock">Show Stock</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="item-details-sec mb-3">
                                    <div class="content-inner">
                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Total Item :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" name="total_item" id="total_item" class="form-control" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Net Total :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" class="form-control" name="net_total_amount" id="net_total_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Discount:</label>
                                            <div class="col-sm-3">
                                                <select name="order_discount_type" class="form-control" id="order_discount_type">
                                                    <option value="1">Fixed</option>
                                                    <option value="2">Percentage</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input name="order_discount" type="number" class="form-control" id="order_discount" value="0.00"> 
                                                <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount" value="0.00"> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Order Tax :</label>
                                            <div class="col-sm-7">
                                                <select name="order_tax" class="form-control" id="order_tax">
                                                    
                                                </select>
                                                <input type="number" step="any" class="d-none" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Shipment Cost:</label>
                                            <div class="col-sm-7">
                                                <input name="shipment_charge" type="number" step="any" class="form-control" id="shipment_charge" value="0.00"> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Previous Due :</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control" type="number" step="any" name="previous_due" id="previous_due" value="0.00">
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Total Payable:</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control" type="number" step="any" name="total_payable_amount" id="total_payable_amount" value="0.00">
                                                <input class="d-none" type="number" step="any" name="total_invoice_payable" id="total_invoice_payable" value="0.00">
                                            </div>
                                        </div>
                                        <div class="payment_body">
                                            <div class="row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Cash Receive:</label>
                                                <div class="col-sm-7">
                                                    <input type="number" step="any" name="paying_amount" class="form-control" id="paying_amount" value="0.00">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Change :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" name="change_amount" class="form-control" id="change_amount" value="0.00">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Paid By :</label>
                                                <div class="col-sm-7">
                                                    <select name="payment_method" class="form-control" id="payment_method">
                                                        <option value="Cash">Cash</option>
                                                        <option value="Advanced">Advanced</option>
                                                        <option value="Cheque">Cheque</option>
                                                        <option value="Card">Card</option>
                                                        <option value="Bank-Transfer">Bank-Transter</option>
                                                        <option value="Other">Other</option>
                                                        <option value="Custom">Custom Field</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="payment_method d-none" id="Card">
                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Card No :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="card_no" id="card_no" placeholder="Card number">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Card Holder :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="card_holder_name" id="card_holder_name" placeholder="Card holder name">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">TrX Number :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="card_transaction_no" id="card_transaction_no" placeholder="Card transaction no">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Card Type :</label>
                                                    <div class="col-sm-7">
                                                        <select name="card_type" class="form-control"  id="p_card_type">
                                                            <option value="Credit-Card">Credit Card</option>  
                                                            <option value="Debit-Card">Debit Card</option> 
                                                            <option value="Visa">Visa Card</option> 
                                                            <option value="Master-Card">Master Card</option> 
                                                        </select>
                                                    </div>
                                                </div> 

                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Month :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control " name="month" id="month" placeholder="Month">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Year :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="year" id="year" placeholder="Year">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Secure ID :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="secure_code" id="secure_code" placeholder="Secure code">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="payment_method d-none" id="Cheque">
                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Cheque No :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="cheque_no" id="cheque_no" placeholder="Cheque number">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="payment_method d-none" id="Bank-Transfer">
                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">Account No :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account number">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="payment_method d-none" id="Custom">
                                                <div class="row">
                                                    <label for="inputEmail3" class="col-sm-5 col-form-label">TrX No :</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control " name="transaction_no" id="transaction_no" placeholder="Transaction number">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Pay Account :</label>
                                                <div class="col-sm-7">
                                                    <select name="account_id" class="form-control" id="account_id">
                                                        <option value="">None</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}">{{ $account->name .' (A/C: '.$account->account_number.')'}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="inputEmail3" class="col-sm-5 col-form-label">Due :</label>
                                                <div class="col-sm-7">
                                                    <input readonly type="number" step="any" class="form-control" name="total_due" id="total_due" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="submitBtn">
                                            <div class="row justify-content-center">
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i> <strong>Loading...</strong> </button>
                                                    <button type="submit" value="save_and_print" data-status="1" class="btn btn-sm btn-primary submit_button">Final & Print </button>
                                                    <button type="submit" value="save" data-status="1" class="btn btn-sm btn-primary submit_button">Final</button>
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
        <div class="modal-dialog four-col-modal" role="document">
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
                        <div class="form-group">
                            <label> <strong>Quantity</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="Quantity"/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>Unit Price Exc.Tax</strong> : <span class="text-danger">*</span></label>
                            <input type="number" step="any" {{ auth()->user()->permission->sale['edit_price_sale_screen'] == '1' ? '' : 'readonly' }} step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price"/>
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if (auth()->user()->permission->sale['edit_discount_sale_screen'] == '1')
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
                            <select class="form-control" id="e_unit">

                            </select>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="c-btn btn_blue float-end me-0">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <!-- Edit selling product modal End-->

    <!--Add Product Modal--> 
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_product_body">
                    <!--begin::Form-->
                </div>
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
                        <ul class="list-unstyled">
                            <li>
                                <a id="tab_btn" class="tab_btn tab_active text-white" href="{{route('sales.recent.sales')}}"><i class="fas fa-info-circle"></i> Final</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white" href="{{route('sales.recent.quotations')}}"><i class="fas fa-scroll"></i>Quotation</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white" href="{{route('sales.recent.drafts')}}"><i class="fas fa-shopping-bag"></i> Draft</a>
                            </li>
                        </ul>
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
                                                    <th class="text-start">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data-list" id="transection_list">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show stock modal-->
    <div class="modal fade" id="showStockModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content">
                <div class="data_preloader mt-5" id="stock_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">Item Stocks</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="stock_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- Show stock modal end-->
@endsection
@push('scripts')
    @include('sales.partials.addSaleCreateJsScript')
@endpush

@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute; width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
        .selectProduct{background-color: #ab1c59; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_purchase_form" action="{{ route('purchases.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Add Purchase</h5>
                                            </div>

                                            <div class="col-md-6">
                                                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><span
                                                    class="text-danger">*</span> <b>Supplier :</b></label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <select name="supplier_id" class="form-control add_input"
                                                            data-name="Supplier" id="supplier_id">
                                                            <option value="">Select Supplier</option>
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" 
                                                                id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                    <span class="error error_supplier_id"></span>
                                                </div>
                                            </div>

                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><span
                                                        class="text-danger">*</span> <b>Warehouse :</b> </label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable add_input"
                                                            name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                            <option value="">Select Warehouse</option>
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Invoice ID :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control">
                                                </div>
                                            </div>

                                            @if (json_decode($generalSettings->purchase, true)['is_enable_status'] == '1')
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Status :</b></label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable" name="purchase_status" id="purchase_status">
                                                            <option value="1">Received</option>
                                                            <option value="2">Pending</option>
                                                            <option value="3">Ordered</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><span
                                                        class="text-danger">*</span> <b>Branch :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{ auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code }}">
                                                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}" id="branch_id">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Date :</b></label>
                                                <div class="col-8">
                                                    <input type="date" name="date" class="form-control changeable"
                                                        value="{{ date('Y-m-d') }}" id="date">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Attachment :</b> </label>
                                                <div class="col-8">
                                                    <input type="file" class="form-control" name="attachment">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Pay Term :</b> </label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <input type="text" name="pay_term_number" class="form-control w-25"
                                                            id="pay_term_number">
                                                        <select name="pay_term" class="form-control w-75 changeable"
                                                            id="pay_term">
                                                            <option value="">Select Pay Term</option>
                                                            <option value="1">Days</option>
                                                            <option value="2">Months</option>
                                                        </select>
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

                <section>
                    <div class="sale-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="searching_area" style="position: relative;">
                                                    <label for="inputEmail3" class="col-form-label">Item Search</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="Search Product by product code(SKU) / Scan bar code" autofocus>
                                                        <div class="input-group-prepend">
                                                            <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="select_area">
                                                        {{-- <div class="remove_select_area_btn">X</div> --}}
                                                        <ul id="list" class="variant_list_area">
                                                            {{-- <li>
                                                                <a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="" data-v_id="" data-p_name="" data-p_tax_id="" data-unit="" data-tax_percent="" data-tax_amount="" data-v_code="" data-v_cost="'+variant.variant_cost+'" data-v_profit="'+variant.variant_profit+'" data-v_price="'+variant.variant_price+'" data-v_cost_with_tax="'+variant.variant_cost_with_tax+'"  data-v_name="'+variant.variant_name+'" href="#"><img style="width:30px; height:30px;" src=""> Samsung A30 (4GB, 64Gb) Price-510000 </a>
                                                            </li> --}}
                                                        </ul>
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
                                                                    <th>Product</th>
                                                                    <th>Quantity</th>
                                                                    <th>Unit Cost(Before Discount)</th>
                                                                    <th>Discount</th>
                                                                    <th>Unit Cost(Before Tax)</th>
                                                                    <th>SubTotal (Before Tax)</th>
                                                                    <th>Unit Tax</th>
                                                                    <th>Net Unit Cost</th>
                                                                    <th>Line Total</th>
                                                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                        <th>Profit Margin(%)</th>
                                                                        <th>Selling Price</th>
                                                                    @endif
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="purchase_list">
                                                               
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-3">
                            <div class="item-details-sec">
                                <div class="content-inner">
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Total Sales</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Prev Balance</label>
                                        <div class="col-sm-3">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Sales Return</label>
                                        <div class="col-sm-3">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Discount</label>
                                        <div class="col-sm-3">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Sub Total</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Cash Paid</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Cash/Ex. Retnd</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">New Balance</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">New Balance</label>
                                        <div class="col-sm-6">
                                            <textarea name="" class="form-control" id="" cols="3" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">New Balance</label>
                                        <div class="col-sm-6">
                                            <textarea name="" class="form-control" id="" cols="3" rows="2"></textarea>
                                        </div>
                                    </div>


                                    <div class="submitBtn">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="btn-bg">
                                                    <a href="" class="bg-parpal function-card">
                                                        <small>Save & Print</small>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="btn-bg">
                                                    <a href="" class="bg-parpal function-card">
                                                        <small>Save</small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                            
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Discount :</b></label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <select name="order_discount_type" class="form-control" id="order_discount_type">
                                                                <option value="1">Fixed(0.00)</option>
                                                                <option value="2">Percentage(%)</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <input name="order_discount" type="number" class="form-control" id="order_discount" value="0.00"> 
                                                        </div>
                                                        
                                                    </div>
                                                    <input name="order_discount_amount" type="number" step="any" class="d-none" id="order_discount_amount" value="0.00"> 
                                                </div>
                                            </div>

                                        
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><b>Tax :</b><span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select name="purchase_tax" class="form-control" id="purchase_tax">
                                                        <option value="">NoTax</option>
                                                    </select>
                                                    <input name="purchase_tax_amount" type="number" step="any" class="d-none" id="purchase_tax_amount" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Ship Cost :</b></label>
                                                <div class="col-8">
                                                    <input name="shipment_charge" type="number" class="form-control" id="shipment_charge" value="0.00"> 
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Ship Details :</b></label>
                                                <div class="col-8">
                                                    <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="Shipment Details"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Total Item :</b> </label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="0.00">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Order Note :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="purchase_note" id="purchase_note" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Net Total :</b>  {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control" value="0.00" >
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Payable :</b>  {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_purchase_amount" id="total_purchase_amount" class="form-control" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Paying :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input name="paying_amount" class="form-control" id="paying_amount" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><b>Pay Method :</b> </label>
                                                <div class="col-8">
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
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><b>Account :</b> </label>
                                                <div class="col-8">
                                                    <select name="account_id" class="form-control" id="account_id">
                                                        <option value="">None</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Total Due :</b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" class="form-control" name="purchase_due" id="purchase_due" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment_method d-none" id="Card">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Card No :</b>  </label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control" name="card_no" id="card_no" placeholder="Card number">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Holder :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control" name="card_holder_name" id="card_holder_name" placeholder="Card holder name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3 " class="col-4"><b>Trans No :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control" name="card_transaction_no" id="card_transaction_no" placeholder="Card transaction no">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Card Type :</b> </label>
                                                    <div class="col-8">
                                                        <select name="card_type" class="form-control"  id="p_card_type">
                                                            <option value="Credit-Card">Credit Card</option>  
                                                            <option value="Debit-Card">Debit Card</option> 
                                                            <option value="Visa">Visa Card</option> 
                                                            <option value="Master-Card">Master Card</option> 
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <label for="inputEmail3 mt-1" class=" col-4"><b>Month :</b>  </label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control " name="month" id="month" placeholder="Month">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Year :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control form-control-sm" name="year" id="year" placeholder="Year">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Secure ID :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control form-control-sm" name="secure_code" id="secure_code" placeholder="Secure code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment_method d-none" id="Cheque">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-2"><b>Cheque Number :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control" name="cheque_no" id="cheque_no" placeholder="Cheque number">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment_method d-none" id="Bank-Transfer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-2"><b>Account No :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account number">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment_method d-none" id="Custom">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-2"><b>Transaction No :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" class="form-control " name="transaction_no" id="transaction_no" placeholder="Transaction number">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-1"><b>Pay Note :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="payment_note" class="form-control" id="payment_note" placeholder="Payment note">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area py-3">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button class="btn btn-sm btn-primary submit_button float-end">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Supplier</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_supplier_modal_body">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div> 

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
                    
                </div>
            </div>
        </div>
    </div> 
    <!--Add Product Modal End-->
@endsection
@push('scripts')
    <script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
    <script>
        $('#payment_method').on('change', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });

        var suppliersArray = '';
        function setSuppliers(){
            $.ajax({
                url:"{{route('purchases.get.all.supplier')}}",
                success:function(suppliers){
                    suppliersArray = suppliers;
                    $.each(suppliers, function(key, val){
                        $('#supplier_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.phone+')'+'</option>');
                    });
                }
            });
        }
        setSuppliers();

        function setAccount(){
            $.ajax({
                url:"{{route('accounting.accounts.all.form.account')}}",
                success:function(accounts){
                    $.each(accounts, function (key, account) {
                        $('#account_id').append('<option value="'+account.id+'">'+ account.name +' (A/C: '+account.account_number+')'+' (Balance: '+account.balance+')'+'</option>');
                    });
                    $('#account_id').val({{ auth()->user()->branch ? auth()->user()->branch->default_account_id : '' }});
                }
            });
        }
        setAccount();

        $('#supplier_id').on('change', function () {
            document.getElementById('search_product').focus();
            var id = $(this).val(); 
            var supplier = suppliersArray.filter(function (supplier) {
                return supplier.id == id;
            });
            if (supplier[0].pay_term != null && supplier[0].pay_term_number != null) {
                $('#pay_term').val(supplier[0].pay_term);
                $('#pay_term_number').val(supplier[0].pay_term_number);
            }else{
                $('#pay_term').val('');
                $('#pay_term_number').val('');
            }
        });

        $('#addSupplier').on('click', function () {
            $.get("{{route('purchases.add.quick.supplier.modal')}}", function(data) {
                $('#add_supplier_modal_body').html(data);
                $('#addSupplierModal').modal('show');
            });
        });

        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
            // Set warehouse in form field
            function setWarehouses(){
                $.ajax({
                    url:"{{route('purchases.get.all.warehouse')}}",
                    async:true,
                    type:'get',
                    dataType: 'json',
                    success:function(warehouses){
                        $.each(warehouses, function(key, val){
                            $('#warehouse_id').append('<option value="'+val.id+'">'+ val.warehouse_name +' ('+val.warehouse_code+')'+'</option>');
                        });
                    }
                });
            }
            setWarehouses();
        @endif

        // Get all unite for form field
        var unites = [];
        function getUnites(){
            $.ajax({
                url:"{{route('purchases.get.all.unites')}}",
                success:function(units){
                    $.each(units, function(key, unit){
                        unites.push(unit.name); 
                    });
                }
            });
        }
        getUnites();

        var taxArray;
        function getTaxes(){
            $.ajax({
                url:"{{route('purchases.get.all.taxes')}}",
                async:false,
                success:function(taxes){
                    taxArray = taxes;
                    $.each(taxes, function(key, val){
                        $('#purchase_tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
                    });
                }
            });
        }
        getTaxes();

        function calculateTotalAmount(){
            var quantities = document.querySelectorAll('#quantity');
            var line_totals = document.querySelectorAll('#line_total');
            var total_item = 0;
            quantities.forEach(function(qty){
                    total_item += 1;
            });
            $('#total_item').val(parseFloat(total_item));

            //Update Net Total Amount 
            var netTotalAmount = 0;
            line_totals.forEach(function(line_total){
                netTotalAmount += parseFloat(line_total.value);
            });
            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

            // Update total purchase amount 
            var order_discount_amount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
            var purchaseTaxAmount = $('#purchase_tax_amount').val() ? $('#purchase_tax_amount').val() : 0;
            var shipment_charge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;
            
            var calcTotalPurchaseAmount = parseFloat(netTotalAmount) - parseFloat(order_discount_amount) + parseFloat(purchaseTaxAmount) + parseFloat(shipment_charge);

            $('#total_purchase_amount').val(parseFloat(calcTotalPurchaseAmount).toFixed(2));
            $('#paying_amount').val(parseFloat(calcTotalPurchaseAmount).toFixed(2));
            // Update purchase due
            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            var calcPurchaseDue = parseFloat(calcTotalPurchaseAmount) - parseFloat(payingAmount);
            $('#purchase_due').val(parseFloat(calcPurchaseDue).toFixed(2));
        }

        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $('#search_product').on('input', function(e) {
            $('.variant_list_area').empty();
            $('.select_area').hide();
            var product_code = $(this).val();
            delay(function() { searchProduct(product_code); }, 200); //sendAjaxical is the name of remote-command
        });

        function searchProduct(product_code){
            $('.variant_list_area').empty();
            $('.select_area').hide();
            $.ajax({
                url:"{{url('purchases/search/product')}}"+"/"+product_code,
                dataType: 'json',
                success:function(product){
                    if (!$.isEmptyObject(product.errorMsg)) {
                        toastr.error(product.errorMsg);
                        $('#search_product').val('');
                        return;
                    } 

                    if(!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product) || !$.isEmptyObject(product.namedProducts)){
                        $('#search_product').addClass('is-valid');
                        if(!$.isEmptyObject(product.product)){
                            var product = product.product;
                            if(product.product_variants.length == 0){
                                $('.select_area').hide();
                                $('#search_product').val('');
                                product_ids = document.querySelectorAll('#product_id');
                                var sameProduct = 0;
                                product_ids.forEach(function(input){
                                    if(input.value == product.id){
                                        sameProduct += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        // update same product qty 
                                        var presentQty = closestTr.find('#quantity').val();
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#quantity').val(updateQty);

                                        // update unit cost with discount
                                        unitCost = closestTr.find('#unit_cost').val();
                                        discount = closestTr.find('#unit_discount').val();
                                        var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
                                        var unitCostWithDiscount = closestTr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

                                        // update subtotal
                                        var calcSubTotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(updateQty); 
                                        var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));

                                        // update net unit cost
                                        var unit_tax = closestTr.find('#unit_tax').val();
                                        var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(unit_tax);
                                        var netUnitCost = closestTr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));
                                        
                                        // update line total
                                        var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(updateQty);
                                        var lineTotal = closestTr.find('#line_total').val(parseFloat(calcLineTotal));
                                        calculateTotalAmount();
                                        return;
                                    }
                                });

                                if(sameProduct == 0){
                                    var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                    var tax_amount = parseFloat(product.tax != null ? product.product_cost/100 * product.tax.tax_percent : 0);
                                    var tr = '';
                                    tr += '<tr class="text-center">';
                                    tr += '<td>';
                                    tr += '<span class="product_name">'+product.name+'</span><br>';
                                    tr += '<span class="product_code">('+product.product_code+')</span><br>';
                                    tr += '<span class="product_variant"></span>';  
                                    tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                    tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
                                    tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';
                                        unites.forEach(function(unit) {
                                        if (product.unit.name == unit) {
                                            tr += '<option SELECTED value="'+unit+'">'+unit+'</option>'; 
                                        }else{
                                            tr += '<option value="'+unit+'">'+unit+'</option>';   
                                        }
                                    })
                                    tr += '</select>';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="'+product.product_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
                                    @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                        tr += '<input name="lot_number[]" placeholder="Lot No" type="text" class="form-control mt-1" id="lot_number" value="">';
                                    @endif
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="0.00" required name="unit_discounts[]" type="text" class="form-control" id="unit_discount">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="'+product.product_cost+'" required name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="'+product.product_cost+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input realonly type="text" name="tax_percents[]" id="tax_percent" class="form-control" value="'+tax_percent+'">';
                                    tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]" id="unit_tax">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input type="hidden" value="'+product.product_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                                    tr += '<input value="'+product.product_cost_with_tax+'" name="net_unit_costs[]" type="text" class="form-control" id="net_unit_cost">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="'+product.product_cost_with_tax+'" type="text" name="linetotals[]" id="line_total" class="form-control">';
                                    tr += '</td>';

                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                        tr += '<td>';
                                        tr += '<input value="'+product.profit+'" type="text" name="profits[]" class="form-control" id="profit">';
                                        tr += '</td>';
                                    
                                        tr += '<td>';
                                        tr += '<input value="'+product.product_price+'" type="text" name="selling_prices[]" class="form-control" id="selling_price">';
                                        tr += '</td>';
                                    @endif

                                    tr += '<td class="text-start">';
                                    tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                                    tr += '</td>';
                                    
                                    tr += '</tr>';
                                    $('#purchase_list').prepend(tr); 
                                    calculateTotalAmount();  
                                }
                            }else{
                                //console.log(product); 
                                var li = "";
                                var imgUrl = "{{asset('public/uploads/product/thumbnail')}}";
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;
                                $.each(product.product_variants, function(key, variant){
                                    var tax_amount = parseFloat(product.tax != null ? variant.variant_cost/100 * product.tax.tax_percent : 0.00);
                                    var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price) ;
                                    li += '<li>';
                                    li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-v_cost="'+variant.variant_cost+'" data-v_profit="'+variant.variant_profit+'" data-v_price="'+variant.variant_price+'" data-v_cost_with_tax="'+variant.variant_cost_with_tax+'"  data-v_name="'+variant.variant_name+'" href="#"><img style="width:30px; height:30px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Unit Cost: '+variant.variant_cost_with_tax+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                    li +='</li>';
                                });
                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        }else if(!$.isEmptyObject(product.namedProducts)){
                            if(product.namedProducts.length > 0){
                                var li = "";
                                var imgUrl = "{{asset('public/uploads/product/thumbnail')}}";
                                var products = product.namedProducts; 
                                $.each(products, function (key, product) {
                                    var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;
                                    if (product.product_variants.length > 0) {
                                        $.each(product.product_variants, function(key, variant){
                                            var tax_amount = parseFloat(product.tax != null ? variant.variant_cost/100 * product.tax.tax_percent : 0.00);
                                            var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price) ;
                                            li += '<li class="mt-1">';
                                            li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-v_cost="'+variant.variant_cost+'" data-v_profit="'+variant.variant_profit+'" data-v_price="'+variant.variant_price+'" data-v_cost_with_tax="'+variant.variant_cost_with_tax+'"  data-v_name="'+variant.variant_name+'" href="#"><img style="width:30px; height:30px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Unit Cost: '+variant.variant_cost_with_tax+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                            li +='</li>';
                                        });
                                    }else{
                                        var tax_amount = parseFloat(product.tax != null ? product.product_cost/100 * product.tax.tax_percent : 0.00);
                                        var unitPriceIncTax = (parseFloat(product.product_price) / 100 * tax_percent) + parseFloat(product.product_price);
                                        li += '<li class="mt-1">';
                                        li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_amount="'+tax_amount+'" data-p_code="'+product.product_code+'" data-p_cost="'+product.product_cost+'" data-p_profit="'+product.profit+'" data-p_price="'+product.product_price+'" data-p_cost_with_tax="'+product.product_cost_with_tax+'" data-p_name="'+product.name+'" href="#"><img style="width:30px; height:30px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' ('+product.product_code+')'+' - Unit Cost: '+product.product_cost_with_tax+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                        li +='</li>';
                                    }
                                });
                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        }else if(!$.isEmptyObject(product.variant_product)){
                            $('.select_area').hide();
                            $('#search_product').val('');
                            var variant_product = product.variant_product;
                            console.log(variant_product); 
                            var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.percent : 0;
                            var tax_rate = parseFloat(variant_product.product.tax != null ? variant_product.variant_cost/100 * tax_percent : 0); 
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;
                            variant_ids.forEach(function(input){
                                if(input.value != 'noid'){
                                    if(input.value == variant_product.id){
                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        // update same product qty 
                                        var presentQty = closestTr.find('#quantity').val();
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#quantity').val(updateQty);

                                        // update unit cost with discount
                                        unitCost = closestTr.find('#unit_cost').val();
                                        discount = closestTr.find('#unit_discount').val();
                                        var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
                                        var unitCostWithDiscount = closestTr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

                                        // update subtotal
                                        var calcSubTotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(updateQty); 
                                        var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));

                                        // update net unit cost
                                        var unit_tax = closestTr.find('#unit_tax').val();
                                        var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(unit_tax);
                                        var netUnitCost = closestTr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));
                                        
                                        // update line total
                                        var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(updateQty);
                                        var lineTotal = closestTr.find('#line_total').val(parseFloat(calcLineTotal));
                                        calculateTotalAmount();
                                        return;
                                    }
                                }    
                            });
                           
                            if(sameVariant == 0){
                                var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.tax_percent : 0;
                                var tax_amount = parseFloat(variant_product.product.tax != null ? variant_product.variant_cost/100 * variant_product.product.tax.tax_percent : 0);
                                var tr = '';
                                tr += '<tr class="text-center">';
                                tr += '<td>';
                                tr += '<span class="product_name">'+variant_product.product.name+'</span><br>';
                                tr += '<span class="product_code">('+variant_product.variant_code+')</span><br>';
                                tr += '<span class="product_variant">('+variant_product.variant_name+')</span>';  
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
                                tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';
                                unites.forEach(function(unit) {
                                    if (variant_product.product.unit.name == unit) {
                                        tr += '<option SELECTED value="'+unit+'">'+unit+'</option>'; 
                                    }else{
                                        tr += '<option value="'+unit+'">'+unit+'</option>';   
                                    }
                                })
                                tr += '</select>';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="'+variant_product.variant_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
                                @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                                    tr += '<input name="lot_number[]" placeholder="Lot No" type="text" class="form-control mt-1" id="lot_number" value="">';
                                @endif
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="0.00" required name="unit_discounts[]" type="text" class="form-control" id="unit_discount">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="'+variant_product.variant_cost+'" required name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly value="'+variant_product.variant_cost+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input type="text"  name="tax_percents[]" id="tax_percent" class="form-control" id="unit_tax" value="'+tax_percent+'">';
                                tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]" id="unit_tax">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input type="hidden" value="'+variant_product.variant_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                                tr += '<input value="'+variant_product.variant_cost_with_tax+'" name="net_unit_costs[]" type="text" class="form-control" id="net_unit_cost">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly value="'+variant_product.variant_cost_with_tax+'" type="text" name="linetotals[]" id="line_total" class="form-control">';
                                tr += '</td>';

                                @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                    tr += '<td>';
                                    tr += '<input value="'+variant_product.variant_profit+'" type="text" name="profits[]" class="form-control" id="profit">';
                                    tr += '</td>';
                               
                                    tr += '<td>';
                                    tr += '<input value="'+variant_product.variant_price+'" type="text" name="selling_prices[]" class="form-control" id="selling_price">';
                                    tr += '</td>';
                                @endif

                                tr += '<td>';
                                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                                tr += '</td>';
                                
                                tr += '</tr>';
                                $('#purchase_list').prepend(tr);
                                calculateTotalAmount(); 
                            }    
                        }
                    }else{
                        $('#search_product').addClass('is-invalid');
                    }
                }
            });
        }

        // select single product and add purchase table
        var keyName = 1;
        function singleProduct(e){
            if (keyName == 13 || keyName == 1) {
                document.getElementById('search_product').focus();
            }
            $('.select_area').hide();
            $('#search_product').val('');

            var product_id = e.getAttribute('data-p_id');
            var product_name = e.getAttribute('data-p_name');
            var tax_percent = e.getAttribute('data-tax_percent');
            var product_unit = e.getAttribute('data-unit');
            var tax_id = e.getAttribute('data-p_tax_id') != null ? e.getAttribute('data-p_tax_id') : '';
            var tax_amount = e.getAttribute('data-tax_amount');
    
            var product_code = e.getAttribute('data-p_code');
            var product_cost = e.getAttribute('data-p_cost');
            var product_cost_with_tax  = e.getAttribute('data-p_cost_with_tax'); 
            var product_profit = e.getAttribute('data-p_profit');
            var product_price = e.getAttribute('data-p_price');
            product_ids = document.querySelectorAll('#product_id');
            var sameProduct = 0;
            product_ids.forEach(function(input){
                if(input.value == product_id){
                    sameProduct += 1;
                    var className = input.getAttribute('class');
                    // get closest table row for increasing qty and re calculate product amount
                    var closestTr = $('.'+className).closest('tr');
                    // update same product qty 
                    var presentQty = closestTr.find('#quantity').val();
                    var updateQty = parseFloat(presentQty) + 1;
                    closestTr.find('#quantity').val(updateQty);

                    // update unit cost with discount
                    unitCost = closestTr.find('#unit_cost').val();
                    discount = closestTr.find('#unit_discount').val();
                    var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
                    var unitCostWithDiscount = closestTr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

                    // update subtotal
                    var calcSubTotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(updateQty); 
                    var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));

                    // update net unit cost
                    var unit_tax = closestTr.find('#unit_tax').val();
                    var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(unit_tax);
                    var netUnitCost = closestTr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));
                    
                    // update line total
                    var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(updateQty);
                    var lineTotal = closestTr.find('#line_total').val(parseFloat(calcLineTotal));
                    calculateTotalAmount();
                    if (keyName == 9) {
                        closestTr.find('#quantity').focus();
                        closestTr.find('#quantity').select();
                        keyName = 1;
                    }
                    return;
                }
            });

            if(sameProduct == 0){
                var tr = '';
                tr += '<tr class="text-center">';
                tr += '<td>';
                tr += '<span class="product_name">'+product_name+'</span><br>';
                tr += '<span class="product_code">('+product_code+')</span><br>';
                tr += '<span class="product_variant"></span>';  
                tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
                tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';
                    unites.forEach(function(unit) {
                    if (product_unit == unit) {
                        tr += '<option SELECTED value="'+unit+'">'+unit+'</option>'; 
                    }else{
                        tr += '<option value="'+unit+'">'+unit+'</option>';   
                    }
                })
                tr += '</select>';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="'+product_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
                @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                    tr += '<input name="lot_number[]" placeholder="Lot No" type="text" class="form-control mt-1" id="lot_number" value="">';
                @endif
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="0.00" required name="unit_discounts[]" type="text" class="form-control" id="unit_discount">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="'+product_cost+'" required name="unit_costs_with_discount[]" type="text" class="form-control" id="unit_cost_with_discount">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="'+product_cost+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly type="text" name="tax_percents[]"  id="tax_percent" class="form-control" value="'+tax_percent+'">'
                tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]"   id="unit_tax">';
                ;
                tr += '</td>';

                tr += '<td>';
                tr += '<input type="hidden" value="'+product_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                tr += '<input value="'+product_cost_with_tax+'" name="net_unit_costs[]" type="text" class="form-control" id="net_unit_cost">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly value="'+product_cost_with_tax+'" type="text" name="linetotals[]" id="line_total" class="form-control">';
                tr += '</td>';

                @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                    tr += '<td>';
                    tr += '<input value="'+product_profit+'" type="text" name="profits[]" class="form-control" id="profit">';
                    tr += '</td>';
              
                    tr += '<td>';
                    tr += '<input value="'+product_price+'" type="text" name="selling_prices[]" class="form-control" id="selling_price">';
                    tr += '</td>';
                @endif

                tr += '<td class="text-start">';
                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                tr += '</td>';

                tr += '</tr>';
                $('#purchase_list').prepend(tr); 
                calculateTotalAmount();  
                if (keyName == 9) {
                    $("#quantity").select();
                    keyName = 1;
                }
            }
        }

         // select variant product and add purchase table
         function salectVariant(e){
            if (keyName == 13 || keyName == 1) {
                document.getElementById('search_product').focus();
            }
            
            $('.select_area').hide();
            $('#search_product').val("");
            var product_id = e.getAttribute('data-p_id');
            var product_name = e.getAttribute('data-p_name');
            var tax_percent = e.getAttribute('data-tax_percent');
            var product_unit = e.getAttribute('data-purchase_unit');
            var tax_id = e.getAttribute('data-p_tax_id') != null ? e.getAttribute('data-p_tax_id') : '';
            var tax_amount = e.getAttribute('data-tax_amount');
            var variant_id = e.getAttribute('data-v_id');
            var variant_name = e.getAttribute('data-v_name');
            var variant_code = e.getAttribute('data-v_code');
            var variant_cost = e.getAttribute('data-v_cost');
            var variant_cost_with_tax  = e.getAttribute('data-v_cost_with_tax'); 
            var variant_profit = e.getAttribute('data-v_profit');
            var variant_price = e.getAttribute('data-v_price');
            var variant_ids = document.querySelectorAll('#variant_id');
            var sameVariant = 0;
            variant_ids.forEach(function(input){
                console.log(input.value);
                if(input.value != 'noid'){
                    if(input.value == variant_id){
                        sameVariant += 1;
                        var className = input.getAttribute('class');
                        // get closest table row for increasing qty and re calculate product amount
                        var closestTr = $('.'+className).closest('tr');
                        // update same product qty 
                        var presentQty = closestTr.find('#quantity').val();
                        var updateQty = parseFloat(presentQty) + 1;
                        closestTr.find('#quantity').val(updateQty);

                        // update unit cost with discount
                        unitCost = closestTr.find('#unit_cost').val();
                        discount = closestTr.find('#unit_discount').val();
                        var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
                        var unitCostWithDiscount = closestTr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

                        // update subtotal
                        var calcSubTotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(updateQty); 
                        var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));

                        // update net unit cost
                        var unit_tax = closestTr.find('#unit_tax').val();
                        var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(unit_tax);
                        var netUnitCost = closestTr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));
                        
                        // update line total
                        var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(updateQty);
                        var lineTotal = closestTr.find('#line_total').val(parseFloat(calcLineTotal));
                        calculateTotalAmount();
                        if (keyName == 9) {
                            closestTr.find('#quantity').focus();
                            closestTr.find('#quantity').select();
                            keyName = 1;
                        }
                        return;
                    }
                }    
            });

            if(sameVariant == 0){
                var tr = '';
                tr += '<tr class="text-center">';
                tr += '<td>';
                tr += '<span class="product_name">'+product_name+'</span><br>';
                tr += '<span class="product_code">('+variant_code+')</span><br>';
                tr += '<span class="product_variant">('+variant_name+')</span>';  
                tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="1" required name="quantities[]" type="number" step="any" class="form-control" id="quantity">';
                tr += '<select name="unit_names[]" id="unit_name" class="form-control mt-1">';
                    unites.forEach(function(unit) {
                    if (product_unit == unit) {
                        tr += '<option SELECTED value="'+unit+'">'+unit+'</option>'; 
                    }else{
                        tr += '<option value="'+unit+'">'+unit+'</option>';   
                    }
                })
                tr += '</select>';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="'+variant_cost+'" required name="unit_costs[]" type="text" class="form-control" id="unit_cost">';
                @if (json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1')
                    tr += '<input name="lot_number[]" placeholder="Lot No" type="text" class="form-control mt-1" id="lot_number" value="">';
                @endif
                tr += '</td>';
                tr += '<td>';
                tr += '<input value="0.00" required name="unit_discounts[]" type="number" class="form-control" id="unit_discount">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input value="'+variant_cost+'" required name="unit_costs_with_discount[]" type="number" class="form-control" id="unit_cost_with_discount">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly value="'+variant_cost+'" required name="subtotals[]" type="number" class="form-control" id="subtotal">';
                tr += '</td>';

                tr += '<td>';
                
                tr += '<input readonly type="text" name="tax_percents[]"  id="tax_percent" class="form-control" value="'+tax_percent+'">';
                tr += '<input type="hidden" value="'+parseFloat(tax_amount).toFixed(2)+'" name="unit_taxes[]" type="text" id="unit_tax">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input type="hidden" value="'+variant_cost_with_tax+'" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax">';
                tr += '<input value="'+variant_cost_with_tax+'" name="net_unit_costs[]" type="number" class="form-control" id="net_unit_cost">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly value="'+variant_cost_with_tax+'" type="number" name="linetotals[]" id="line_total" class="form-control">';
                tr += '</td>';

                @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                    tr += '<td>';
                    tr += '<input value="'+variant_profit+'" type="text" name="profits[]" class="form-control" type="number" id="profit">';
                    tr += '</td>';
          
                    tr += '<td class="text-right">';
                    tr += '<input value="'+variant_price+'" type="number" name="selling_prices[]" class="form-control" id="selling_price">';
                    tr += '</td>';
                @endif

                tr += '<td class="text-start">';
                tr += '<a href="#" id="remove_product_btn" class="c-delete"><span class="fas fa-trash "></span></a>';
                tr += '</td>';
                
                tr += '</tr>';
                $('#purchase_list').prepend(tr); 
                calculateTotalAmount();
                if (keyName == 9) {
                    $("#quantity").select();
                    keyName = 1;
                }
            }
        }

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#quantity', function(){
            var qty = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            //Update subtotal 
            var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val();
            var calcSubtotal = parseFloat(unitCostWithDiscount) * parseFloat(qty);
            var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            
            //Update line total
            var netUnitCost = tr.find('#net_unit_cost').val();
            var calcLineTotal = parseFloat(netUnitCost) * parseFloat(qty);
            var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));
            // console.log(tr);
            calculateTotalAmount();
        });

        // Change tax percent and clculate row amount
        $(document).on('input', '#unit_cost', function(){
            var unitCost = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            
            // update unit cost with discount
            var discount = tr.find('#unit_discount').val();
            var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(discount);
            var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

            // update subtotal
            var quantity = tr.find('#quantity').val();
            var calcSubtotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(quantity);
            tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));

            // Update net unit cost
            var tax_percent = tr.find('#tax_percent').val();
            //Calc Unit tax
            var calcTaxAmount = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(tax_percent);
            tr.find('#unit_tax').val(parseFloat(calcTaxAmount).toFixed(2));
            var calcNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(calcTaxAmount);
            tr.find('#net_unit_cost').val(parseFloat(calcNetUnitCost).toFixed(2));

            // Calc unit inc 
            var unitCostIncTax = parseFloat(unitCost) + parseFloat(calcTaxAmount);
            tr.find('#unit_cost_inc_tax').val(parseFloat(unitCostIncTax).toFixed(2));
            // Update line total
            var calcLineTotal = parseFloat(calcNetUnitCost) * parseFloat(quantity);
            var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));

            // Update selling price
            var profit = tr.find('#profit').val();
            var calcProfit = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(profit) + parseFloat(calcUnitCostWithDiscount);
            var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));
            calculateTotalAmount();
        });

        // Input discount and clculate row amount
        $(document).on('input', '#unit_discount', function(){
            var unit_discount = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            //Update unit cost with discount 
            var unitCost = tr.find('#unit_cost').val();
            var calcUnitCostWithDiscount = parseFloat(unitCost) - parseFloat(unit_discount);
            var unitCostWithDiscount = tr.find('#unit_cost_with_discount').val(parseFloat(calcUnitCostWithDiscount).toFixed(2));

            // Update sub-total
            var quantity = tr.find('#quantity').val();
            var calcSubtotal = parseFloat(calcUnitCostWithDiscount) * parseFloat(quantity);
            var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));

            // Update net unit cost
            var tax_percent = tr.find('#tax_percent').val();
            // Calc unit tax
            var calcTaxAmount = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(tax_percent);
            tr.find('#unit_tax').val(parseFloat(calcTaxAmount).toFixed(2));
            var calsNetUnitCost = parseFloat(calcUnitCostWithDiscount) + parseFloat(calcTaxAmount);
            tr.find('#net_unit_cost').val(parseFloat(calsNetUnitCost).toFixed(2));

            // Update line total
            var calcLineTotal = parseFloat(calsNetUnitCost) * parseFloat(quantity);
            var lineTotal = tr.find('#line_total').val(parseFloat(calcLineTotal).toFixed(2));

            // Update profit 
            var profitMargin = tr.find('#profit').val();
            var calcProfit = parseFloat(calcUnitCostWithDiscount) / 100 * parseFloat(profitMargin) + parseFloat(calcUnitCostWithDiscount);
            var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));
            calculateTotalAmount();
        });

        $(document).on('blur', '#unit_discount', function(){
            if ($(this).val() == '') {
                $(this).val(parseFloat(0).toFixed(2));
            }
        });

        // Input profit margin and clculate row amount
        $(document).on('input', '#profit', function(){
            var profit = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
         
            // Update selling price
            var unit_cost = tr.find('#unit_cost').val();
            var unitCostWithDiscount = parseFloat(unit_cost) - parseFloat(profit);
            var calcProfit = parseFloat(unitCostWithDiscount)  /100 * parseFloat(profit) + parseFloat(unitCostWithDiscount);
            var sellingPrice = tr.find('#selling_price').val(parseFloat(calcProfit).toFixed(2));
            calculateTotalAmount();
        });

        $(document).on('blur', '#profit', function(){
            if ($(this).val() == '') {
                $(this).val(parseFloat(0).toFixed(2));
            }
        });

        // Input order discount and clculate total amount
        $(document).on('input', '#order_discount', function(){
            var orderDiscount = $(this).val() ? $(this).val() : 0;
            var orderDiscountType = $('#order_discount_type').val();
            var netTotalAmount = $('#net_total_amount').val();
            if (orderDiscountType == 1) {
                $('.label_order_discount_amount').html(parseFloat(orderDiscount).toFixed(2)); 
                $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2)); 
            }else{
                var calsOrderDiscount = parseFloat(netTotalAmount) / 100 * parseFloat(orderDiscount);
                $('.label_order_discount_amount').html(parseFloat(calsOrderDiscount).toFixed(2)); 
                $('#order_discount_amount').val(parseFloat(calsOrderDiscount).toFixed(2));
            }
            calculateTotalAmount();
        });

        // Input order discount type and clculate total amount
        $(document).on('change', '#order_discount_type', function(){
            var orderDiscountType = $(this).val() ? $(this).val() : 0;
            var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0.00;
            var netTotalAmount = $('#net_total_amount').val();
            if (orderDiscountType == 1) {
                $('.label_order_discount_amount').html(parseFloat(orderDiscount).toFixed(2)); 
                $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2)); 
            }else{
                var calsOrderDiscount = parseFloat(netTotalAmount) / 100 * parseFloat(orderDiscount);
                $('.label_order_discount_amount').html(parseFloat(calsOrderDiscount).toFixed(2)); 
                $('#order_discount_amount').val(parseFloat(calsOrderDiscount).toFixed(2));
            }
            calculateTotalAmount();
        });

         // Input shipment charge and clculate total amount
         $(document).on('input', '#shipment_charge', function(){
            calculateTotalAmount();
        });

        // chane purchase tax and clculate total amount
        $(document).on('change', '#purchase_tax', function(){
            var purchaseTax = $(this).val() ? $(this).val() : 0;
            var netTotalAmount = $('#net_total_amount').val();
            var calcPurchaseTaxAmount = parseFloat(netTotalAmount) / 100 * parseFloat(purchaseTax);
            $('#purchase_tax_amount').val(parseFloat(calcPurchaseTaxAmount).toFixed(2));
            calculateTotalAmount();
        });

        // Input paying amount and clculate due amount
        $(document).on('input', '#paying_amount', function(){
            var payingAmount = $(this).val() ? $(this).val() : 0;
            var total_purchase_amount = $('#total_purchase_amount').val() ? $('#total_purchase_amount').val() : 0;
            var calcDueAmount = parseFloat(total_purchase_amount) - parseFloat(payingAmount);
            $('#purchase_due').val(parseFloat(calcDueAmount).toFixed(2));
        });

        // // Dispose Select area 
        // $(document).on('click', '.remove_select_area_btn', function(e){
        //     e.preventDefault();
        //     $('.select_area').hide();
        // });

        // Remove product form purchase product list (Table) 
        $(document).on('click', '#remove_product_btn',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateTotalAmount();
            document.getElementById('search_product').focus();
        });

        //Add purchase request by ajax
        $('#add_purchase_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var inputs = $('.add_input');
                inputs.removeClass('is-invalid');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.','Some thing want wrong.'); 
                return;
            }
            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    $('.submit_button').prop('type', 'sumbit');
                    if(!$.isEmptyObject(data.errorMsg)){
                        toastr.error(data.errorMsg,'ERROR'); 
                        $('.loading_button').hide();
                    }else{
                        $('.loading_button').hide();
                        toastr.success(data); 
                        window.location = "{{route('purchases.index_v2')}}";
                    }
                }
            });
        });

        setInterval(function(){
            $('#search_product').removeClass('is-invalid');
        }, 500); 

        setInterval(function(){
            $('#search_product').removeClass('is-valid');
        }, 1000);

         // Add supplier by ajax
         $(document).on('submit', '#add_supplier_form', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.s_add_input');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_supplier_form')[0].reset();
                    $('.loading_button').hide();
                    $('#addSupplierModal').modal('hide');
                    $('#supplier_id').append('<option value="'+data.id+'">'+ data.name +' ('+data.phone+')'+'</option>');
                    $('#supplier_id').val(data.id);
                    document.getElementById('search_product').focus();
                }
            });
        });

        // Show add product modal with data
        $('#add_product').on('click', function () {
            $.ajax({
                url:"{{route('purchases.add.product.modal.view')}}",
                type:'get',
                success:function(data){
                    $('#add_product_body').html(data);
                    $('#addProductModal').modal('show');
                }
            });
        });

        var tax_percent = 0;
        $(document).on('change', '#add_tax_id',function() {
            var tax = $(this).val();
            if (tax) {
                var split = tax.split('-');
                tax_percent = split[1];
                console.log(split);
            }else{
                tax_percent = 0;
            }
        });

        function costCalculate() {
            console.log(tax_percent);
            var product_cost = $('#add_product_cost').val() ? $('#add_product_cost').val() : 0;
            var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent ? tax_percent : 0);
            var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
            $('#add_product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
            var profit = $('#add_profit').val() ? $('#add_profit').val() : 0;
            var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
            var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
            $('#add_product_price').val(parseFloat(product_price).toFixed(2));
        }

        $(document).on('input', '#add_product_cost',function() {
            console.log($(this).val());
            costCalculate();
        });

        $(document).on('change', '#add_tax_id', function() {
            costCalculate();
        });

        $(document).on('input', '#add_profit',function() {
            costCalculate();
        });

        // Add product by ajax
        $(document).on('submit', '#add_product_form',function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success('Successfully product is added.');
                    $.ajax({
                        url:"{{url('purchases/recent/product')}}"+"/"+data.id,
                        type:'get',
                        success:function(data){
                            $('.loading_button').hide();
                            $('#addProductModal').modal('hide');
                            $('#purchase_list').prepend(data); 
                            calculateTotalAmount();
                            document.getElementById('search_product').focus();
                        }
                    });
                },
                error: function(err) {
                    $('.loading_button').hide();
                    toastr.error('Please check again all form fields.', 'Some thing want wrong.');
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_add_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('change', '#add_category_id', function () {
            var category_id = $(this).val();
            $.ajax({
                url:"{{url('sales/get/all/sub/category')}}"+"/"+category_id,
                async:true,
                type:'get',
                dataType: 'json',
                success:function(subcate){
                    $('#add_child_category_id').empty();
                    $('#add_child_category_id').append('<option value="">Select Sub-Category</option>');
                    $.each(subcate, function(key, val){
                        $('#add_child_category_id').append('<option value="'+val.id+'">'+val.name+'</option>');
                    });
                }
            });
        });

        $(document).keypress(".scanable",function(event){
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();
            }
        });

        $('body').keyup(function(e){
            if (e.keyCode == 13 || e.keyCode == 9){  
                $(".selectProduct").click();
                $('#list').empty();
                keyName = e.keyCode;
            }
        });

        $('.changeable').on('change', function () {
            document.getElementById('search_product').focus();
        });

        document.getElementById('search_product').focus();
    </script>
@endpush

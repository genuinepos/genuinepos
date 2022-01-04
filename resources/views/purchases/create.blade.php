@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute; width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 11px; padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
        .selectProduct{background-color: #ab1c59; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        h6.collapse_table:hover {background: lightgray; padding: 3px; cursor: pointer;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_purchase_form" action="{{ route('purchases.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action" value="">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Add Purchase</h5>
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
                                                <label for="inputEmail3" class=" col-4"><span
                                                    class="text-danger">*</span> <b>Supplier :</b></label>
                                                <div class="col-8">
                                                    <div class="input-group">
                                                        <select name="supplier_id" class="form-control add_input"
                                                            data-name="Supplier" id="supplier_id">
                                                            <option value="">Select Supplier</option>
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                    <span class="error error_supplier_id"></span>
                                                </div>
                                            </div>

                                            @if (count($warehouses) > 0)
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><span
                                                        class="text-danger">*</span> <b>Warehouse :</b> </label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable add_input"
                                                            name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                            <option value="">Select Warehouse</option>
                                                            @foreach ($warehouses as $w)
                                                                <option value="{{ $w->id }}">{{ $w->warehouse_name.'/'.$w->warehouse_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Store Location :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" name="branch_id" class="form-control changeable" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}"/>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Invoice ID :</b> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If you keep this field empty, The Purchase Invoice ID will be generated automatically." class="fas fa-info-circle tp"></i></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Purchase Invoice ID" autocomplete="off">
                                                </div>
                                            </div>

                                            @if (json_decode($generalSettings->purchase, true)['is_enable_status'] == '1')
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Status :</b></label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable" name="purchase_status" id="purchase_status">
                                                            <option value="1">Purchase</option>
                                                            <option value="2">Pending</option>
                                                            <option value="3">Ordered</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><span
                                                        class="text-danger">*</span> <b>Store Location :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{ auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code }}">
                                                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}" id="branch_id">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>PUR./PO. Date:</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control changeable"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="datepicker" placeholder="dd-mm-yyyy" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Attachment :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice related any file.Ex: Scanned cheque, payment prove file etc. Max File Size 2MB." class="fas fa-info-circle tp"></i></label>
                                                <div class="col-8">
                                                    <input type="file" class="form-control" name="attachment">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Delivery Date :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="delivery_date" class="form-control changeable" id="delivery_date" placeholder="DD-MM-YYYY" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Pay Term :</b> </label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <input type="text" name="pay_term_number" class="form-control"
                                                            id="pay_term_number" placeholder="Number">
                                                        </div>
                                                        
                                                        <div class="col-7">
                                                            <select name="pay_term" class="form-control changeable"
                                                            id="pay_term">
                                                                <option value="">Pay Term</option>
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
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="Search Product by product code(SKU) / Scan bar code" autofocus>
                                                        @if (auth()->user()->permission->product['product_add'] == '1')
                                                            <div class="input-group-prepend">
                                                                <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark"></i></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
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
                                                                    <th>Unit Cost(BD <i data-bs-toggle="tooltip" data-bs-placement="right" title="Before Discount" class="fas fa-info-circle tp"></i>)</th>
                                                                    <th>Discount</th>
                                                                    <th>Unit Cost(BT <i data-bs-toggle="tooltip" data-bs-placement="right" title="Before Tax" class="fas fa-info-circle tp"></i>)</th>
                                                                    <th>SubTotal (BT <i data-bs-toggle="tooltip" data-bs-placement="right" title="Before Tax" class="fas fa-info-circle tp"></i>)</th>
                                                                    <th>Unit Tax</th>
                                                                    <th>Net Unit Cost</th>
                                                                    <th>Line Total</th>
                                                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                        <th>xMargin(%)</th>
                                                                        <th>Selling Price</th>
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
                                </div>
                            </div>
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
                                                        <option value="0.00">NoTax</option>
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
                                                <input readonly name="total_qty" type="number" step="any" class="d-none" id="total_qty" value="0.00">
                                                <label for="inputEmail3" class=" col-4"><b>Total Item :</b> </label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="0.00">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Order Note :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="purchase_note" id="purchase_note" class="form-control" value="" placeholder="Order Note.">
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

                <div class="submitBtn">
                    <div class="row justify-content-center">
                        <div class="col-12 text-end">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i> <strong>Loading...</strong> </button>
                            <button type="submit" value="1" class="btn btn-sm btn-primary submit_button">Save & Print </button>
                            <button type="submit" value="2" class="btn btn-sm btn-primary submit_button">Save</button>
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
                <div class="modal-body" id="add_supplier_modal_body"></div>
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
                <div class="modal-body" id="add_product_body"></div>
            </div>
        </div>
    </div>
    <!--Add Product Modal End-->

     <!--Add Product Modal-->
     <div class="modal fade" id="addDescriptionModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal description_modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Description <span id="product_name"></span></h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>Description :</strong></label>
                            <textarea name="product_description" id="product_description" class="form-control" cols="30" rows="10" placeholder="Description"></textarea>
                        </div>
                    </div>

                    <div class="form-group text-end mt-3">
                        <button type="submit" id="add_description" class="c-btn btn_blue float-end me-0">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Add Product Modal End-->
@endsection
@push('scripts')
    @include('purchases.partials.purchaseCreateJsScript')
@endpush

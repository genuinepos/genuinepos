<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>Genuine POS</title>
    <!-- Icon -->
    <link rel="shortcut icon" href="{{ asset('public/favicon.png') }}">

    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/fontawesome/css/all.css">
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link href="{{asset('public')}}/backend/css/reset.css" rel="stylesheet" type="text/css">
    <link href="{{asset('public')}}/backend/css/typography.css" rel="stylesheet" type="text/css">
    <link href="{{asset('public')}}/backend/css/body.css" rel="stylesheet" type="text/css">

    <link href="{{asset('public')}}/backend/css/form.css" rel="stylesheet" type="text/css">
    <link href="{{asset('public')}}/backend/css/gradient.css" rel="stylesheet" type="text/css">

    <!-- Calculator -->
    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/calculator.css') }}">
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/comon.css">
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/layout.css">
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/pos.css">
    <link href="{{asset('public')}}/assets/plugins/custom/toastrjs/toastr.min.css" rel="stylesheet"
    type="text/css"/>
    <link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('public')}}/backend/asset/css/style.css">
    <style> .btn-bg {padding: 2px!important;} </style>
    @stack('css')
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <!--Toaster.js js link-->
    <script src="{{ asset('public') }}/assets/plugins/custom/toastrjs/toastr.min.js"></script>
    <!--Toaster.js js link end-->
  
    <script src="{{asset('public')}}/backend/asset/js/bootstrap.bundle.min.js "></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.min.js"></script>
    <script src="{{asset('public')}}/assets/plugins/custom/Shortcuts-master/shortcuts.js"></script>
    <!--alert js link-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/digital_clock/digital_clock.js"></script>
    <script src="{{asset('public')}}/backend/asset/js/sale.exchange.js"></script>
</head>

<body>
    <form id="pos_submit_form" action="{{ route('sales.pos.store') }}" method="POST">
        @csrf
        <div class="pos-body">
            <div class="main-wraper">
                @yield('pos_content')
            </div>
        </div>

        <!--Add Payment modal-->
        <div class="modal fade in" id="otherPaymentMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog col-50-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="payment_heading">Choose Payment method</h6>
                        <a href="" class="close-btn" id="cancel_pay_mathod"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label><strong>Payment Method :</strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark"></i></span>
                                    </div>
                                    <select name="payment_method" class="form-control"  id="payment_method">
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                        <option value="Bkash">Bkash</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Advanced">Advanced</option>
                                        <option value="Bank-Transfer">Bank-Transfer</option>
                                        <option value="Other">Other</option>
                                        <option value="Custom">Custom Field</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label><strong>Payment Account :</strong> </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark"></i></span>
                                    </div>
                                    <select name="account_id" class="form-control"  id="account_id">
                                        <option value="">Select Accout</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="payment_method d-none" id="Card">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label><strong>Card Number :</strong> </label>
                                        <input type="text" class="form-control" name="card_no" id="p_card_no" placeholder="Card number">
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>Holder Name :</strong> </label>
                                        <input type="text" class="form-control" name="card_holder_name" id="p_card_holder_name" placeholder="Card holder name">
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>Transaction No :</strong> </label>
                                        <input type="text" class="form-control" name="card_transaction_no" id="p_card_transaction_no" placeholder="Card transaction no">
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>Card Type :</strong> </label>
                                        <select name="card_type" class="form-control"  id="p_card_type">
                                            <option value="Credit-Card">Credit Card</option>
                                            <option value="Debit-Card">Debit Card</option>
                                            <option value="Visa">Visa Card</option>
                                            <option value="Master-Card">Master Card</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <label><strong>Month :</strong> </label>
                                        <input type="text" class="form-control" name="month" id="p_month" placeholder="Month">
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>Year :</strong> </label>
                                        <input type="text" class="form-control" name="year" id="p_year" placeholder="Year">
                                    </div>

                                    <div class="col-md-3">
                                        <label><strong>Secure Code :</strong> </label>
                                        <input type="text" class="form-control" name="secure_code" id="p_secure_code" placeholder="Secure code">
                                    </div>
                                </div>
                            </div>

                            <div class="payment_method d-none" id="Cheque">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><strong>Cheque Number :</strong> </label>
                                        <input type="text" class="form-control" name="cheque_no" id="p_cheque_no" placeholder="Cheque number">
                                    </div>
                                </div>
                            </div>

                            <div class="payment_method d-none" id="Bank-Transfer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><strong>Account Number :</strong> </label>
                                        <input type="text" class="form-control" name="account_no" id="p_account_no" placeholder="Account number">
                                    </div>
                                </div>
                            </div>

                            <div class="payment_method d-none" id="Custom">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label><strong>Transaction No :</strong> </label>
                                        <input type="text" class="form-control" name="transaction_no" id="p_transaction_no" placeholder="Transaction number">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><strong> Payment Note :</strong></label>
                            <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3" placeholder="Note"></textarea>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <a href="" class="c-btn btn_blue me-0 float-end" id="submit_btn" data-button_type="1" data-action_id="1">Confirm (F10)</a>
                                <button type="button" class="c-btn btn_orange float-end" id="cancel_pay_mathod">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Add Payment modal End-->

        @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == '1')
        <!--Redeem Point modal-->
            <div class="modal fade" id="pointReedemModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog col-40-modal" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel">Reedem Point</h6>
                            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label><b>Available Point :</b> </label>
                                <input type="number" step="any" name="available_point" id="available_point" class="form-control" value="0" readonly>
                            </div>
        
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><b>Redeemed :</b> </label>
                                    <input type="number" step="any" name="total_redeem_point" id="total_redeem_point" class="form-control">
                                    <input type="number" step="any" name="pre_redeemed" id="pre_redeemed" class="d-none" value="0">
                                </div>
        
                                <div class="col-md-6">
                                    <label><b>Redeem Amount :</b> </label>
                                    <input type="number" step="any" name="redeem_amount" id="redeem_amount" class="form-control">
                                </div>
                            </div>
        
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                    <a href="#" class="c-btn btn_blue ms-1 float-end" id="redeem_btn">Redeem</a>
                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!--Redeem Point modal-->
    </form>

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
                                <a id="tab_btn" class="tab_btn tab_active text-white" href="{{url('sales/pos/recent/sales')}}"><i class="fas fa-info-circle"></i> Final</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white" href="{{url('sales/pos/recent/quotations')}}"><i class="fas fa-scroll"></i>Quotation</a>
                            </li>

                            <li>
                                <a id="tab_btn" class="tab_btn text-white" href="{{url('sales/pos/recent/drafts')}}"><i class="fas fa-shopping-bag"></i> Draft</a>
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
                                                    <th class="text-start">Reference/InvoiceId</th>
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
 <!-- Recent transection list modal end-->

    <!-- Hold invoice list modal -->
    <div class="modal fade" id="holdInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-40-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Hold Invoices</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table_area">
                                <div class="data_preloader" id="hold_invoice_preloader">
                                    <h6><i class="fas fa-spinner"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="hold_invoices">

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
    <!-- Hold invoice list modal End-->

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

    <!--Add Customer Modal-->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Customer</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_customer_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
    <!--Add Customer Modal-->

    <!-- Edit selling product modal-->
    <div class="modal fade" id="suspendedSalesModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content">
                <div class="data_preloader" id="suspend_preloader">
                    <h6><i class="fas fa-spinner"></i> Processing...</h6>
                </div>
                <div class="modal-header">
                    <h6 class="modal-title">Suspended Sales</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="suspended_sale_list">

                </div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal end-->

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
                    <form id="update_selling_product">
                        <div class="form-group mt-1">
                            <label> <strong>Quantity</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="Quantity" value=""/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>Unit Price Exc.Tax</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" {{ auth()->user()->permission->sale['edit_price_pos_screen'] == '1' ? '' : 'readonly' }} step="any" class="form-control form-control-sm edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price" value=""/>
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if (auth()->user()->permission->sale['edit_discount_pos_screen'] == '1')
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>Discount Type</strong>  :</label>
                                    <select class="form-control" id="e_unit_discount_type">
                                        <option value="2">Percentage</option>
                                        <option value="1">Fixed</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>Discount</strong>  :</label>
                                    <input type="number" class="form-control" id="e_unit_discount" value="0.00"/>
                                    <input type="hidden" id="e_discount_amount"/>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><strong>Tax</strong> :</label>
                                <select class="form-control" id="e_unit_tax">
    
                                </select>
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

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="c-btn btn_blue me-0 float-end">Update</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit selling product modal end-->

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

    <!-- Close Register modal -->
    <div class="modal fade" id="closeRegisterModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" id="close_register_content">

            </div>
        </div>
    </div>
    <!-- Close Register modal End-->

    <!-- Cash Register Details modal -->
    <div class="modal fade" id="cashRegisterDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" id="cash_register_details_content">

            </div>
        </div>
    </div>
    <!-- Cash Register Details modal End-->

    <!--Quick Cash receive modal-->
    <div class="modal fade in" id="cashReceiveMethod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog col-45-modal" role="document">
            <div class="modal-content modal-middle">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">Quick Cash Receive</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <div class="form-group row ">
                        <div class="col-md-6">
                            <div class="input-box-4 bg-dark">
                                <label class="text-white big_label"><strong>Total Payable :</strong> </label>
                                <input readonly type="text" class="form-control big_field" id="modal_total_payable" value="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-box-2 bg-info">
                                <label class="text-white big_label"><strong>Change :</strong></label>
                                <input type="text" class="form-control big_field text-info" id="modal_change_amount" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-6">
                            <div class="input-box bg-success">
                                <label class="text-white big_label"><strong>Cash Receive :</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="modal_paying_amount" class="form-control text-success big_field m-paying" id="modal_paying_amount" value="0" autofocus>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-box-3 bg-danger">
                                <label class="text-white big_label"><strong>Due :</strong> </label>
                                <input type="text" class="form-control text-danger big_field" id="modal_total_due" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <a href="" class="c-btn btn_blue ms-1 float-end" id="submit_btn" data-button_type="1" data-action_id="1">Cash (F10)</a>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end me-0">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Quick Cash receive modal End-->

    <!-- Exchange modal -->
    <div class="modal fade" id="exchangeModal"tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-60-modal" role="document">
            <div class="modal-content" id="exchange_body">
                <div class="modal-header">
                    <h6 class="modal-title">Exchange</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body">
                    <div class="form-area">
                        <form id="search_inv_form" action="{{ route('sales.pos.serc.ex.inv') }}" method="GET">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <input required type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Search invoice">
                                </div>

                                <div class="col-md-3">
                                    <input required type="text" name="customer_id" id="customer_id" class="form-control" placeholder="Search By customer">
                                </div>

                                <div class="col-md-3">
                                    <input required type="text" name="customer_phone" id="customer_phone" class="form-control" placeholder="Search By phone number">
                                </div>

                                <div class="col-md-2">
                                    <div class="btn_30_blue m-0">
                                        <a id="submit_form_btn" href="#"><i class="fas fa-plus-square"></i> Search</a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="preloader_area">
                            <div class="data_preloader" id="get_inv_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2" id="invoice_description">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Exchange modal End-->

    <!--Data delete form-->
    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <!--Data delete form end-->
    <script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
    <script>
        // Calculate total amount functionalitie
        function calculateTotalAmount(){
            var indexs = document.querySelectorAll('#index');
            indexs.forEach(function(index) {
                var className = index.getAttribute("class");
                var rowIndex = $('.' + className).closest('tr').index();
                $('.' + className).closest('tr').find('.serial').html(rowIndex + 1);
            });

            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item
            var total_item = 0;
            var total_qty = 0;
            quantities.forEach(function(qty){
                total_item += 1;
                total_qty += parseFloat(qty.value)
            });

            $('#total_item').val(parseFloat(total_item));
            $('.mb_total_item').val(parseFloat(total_item));
            $('#total_qty').val(parseFloat(total_qty).toFixed(2));
            $('.mb_total_qty').val(parseFloat(total_qty).toFixed(2));

            // Update Net total Amount
            var netTotalAmount = 0;
            subtotals.forEach(function(subtotal){
                netTotalAmount += parseFloat(subtotal.value);
            });

            $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

            if ($('#order_discount_type').val() == 2) {
                var orderDisAmount = parseFloat(netTotalAmount) /100 * parseFloat($('#order_discount').val() ? $('#order_discount').val() : 0);
                $('#order_discount_amount').val(parseFloat(orderDisAmount).toFixed(2));
            }else{
                var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0;
                $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
            }

            var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
            // Calc order tax amount
            var orderTax = $('#order_tax').val() ? $('#order_tax').val() : 0;
            var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTax) ;
            $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));

            // Update Total payable Amount
            var calcOrderTaxAmount = $('#order_tax_amount').val() ? $('#order_tax_amount').val() : 0;
            var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;
            var previousDue = $('#previous_due').val() ? $('#previous_due').val() : 0;

            var calcInvoicePayable = parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount) + parseFloat(calcOrderTaxAmount) + parseFloat(shipmentCharge);

            $('#total_invoice_payable').val(parseFloat(calcInvoicePayable).toFixed(2));
            var ex_inv_payable_amount = $('#ex_inv_payable_amount').val() ? $('#ex_inv_payable_amount').val() : 0;
            var ex_inv_paid = $('#ex_inv_paid').val() ? $('#ex_inv_paid').val() : 0;
            var exchange_item_total_price = $('#exchange_item_total_price').val() ? $('#exchange_item_total_price').val() : 0;

            var calcTotalPayableAmount = parseFloat(netTotalAmount) - 
            parseFloat(orderDiscountAmount) + 
            parseFloat(calcOrderTaxAmount) + 
            parseFloat(shipmentCharge) + 
            parseFloat(previousDue);
            
            $('#total_payable_amount').val(parseFloat(calcTotalPayableAmount).toFixed(2));
            //$('#paying_amount').val(parseFloat(calcTotalPayableAmount).toFixed(2));
            // Update purchase due
            var payingAmount = $('#paying_amount').val() ? $('#paying_amount').val() : 0;
            var changeAmount = parseFloat(payingAmount) - parseFloat(calcTotalPayableAmount);
            $('#change_amount').val(parseFloat(changeAmount >= 0 ? changeAmount : 0).toFixed(2));
            var calcTotalDue = parseFloat(calcTotalPayableAmount) - parseFloat(payingAmount);
            $('#total_due').val(parseFloat(calcTotalDue >= 0 ? calcTotalDue : 0).toFixed(2));
        }

        $(document).keypress(".scanable",function(event){
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();
            }
        });

        $('#payment_method').on('change', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });

        var tableRowIndex = 0;
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var parentTableRow = $(this).closest('tr');
            tableRowIndex = parentTableRow.index();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);           
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                            $('#recent_trans_preloader').show();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            // alert('Deleted canceled.')
                        } 
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    toastr.error(data);
                    var productTableRow = $('#transection_list tr:nth-child(' + (tableRowIndex + 1) + ')').remove();
                    $('#recent_trans_preloader').hide();
                    $('#suspendedSalesModal').modal('hide');
                    $('#holdInvoiceModal').modal('hide');
                }
            });
        });

        $('#cash_register_details').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url:"{{route('sales.cash.register.details')}}",
                type:'get',
                success:function(data){
                    $('#cash_register_details_content').html(data);
                    $('#cashRegisterDetailsModal').modal('show');
                }
            });
        });

        $('#close_register').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url:"{{route('sales.cash.register.close.modal.view')}}",
                type:'get',
                success:function(data){
                    $('#close_register_content').html(data);
                    $('#closeRegisterModal').modal('show');
                }
            });
        });

        $(document).on('click', '#pos_exit_button',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);           
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure, you want to exit?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            window.location = "{{ route('dashboard.dashboard') }}";
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            // alert('Deleted canceled.')
                        } 
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#search_inv_form',function(e){
            e.preventDefault();
            $('#get_inv_preloader').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({ 
                url:url,
                type:'get',
                data:request,
                success:function(data){
                    $('#get_inv_preloader').hide();
                    $('#invoice_description').empty();
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                    }else{
                        $('#invoice_description').html(data);
                    }
                }
            });
        });

        $('#submit_form_btn').on('click', function (e) {
            e.preventDefault();
            $('#search_inv_form').submit();
        });

        $('#exchange_btn').on('click', function (e) {
            e.preventDefault();
            $('#invoice_description').empty();$('#invoice_id').val('');
        });

        $('.calculator-bg__main button').prop('type','button');

        $('#reedem_point_button').on('click', function (e) {
            e.preventDefault();
            $('#pointReedemModal').modal('show');
        });
    </script>
    @stack('js')
</body>
</html>

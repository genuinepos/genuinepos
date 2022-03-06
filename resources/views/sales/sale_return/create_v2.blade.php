@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 88.3%;z-index: 9999999;padding: 0;left: 6%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 11px;padding: 4px 3px;display: block;border: 1px solid lightgray; margin-top: 3px;}
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
                                        <div class="col-8">
                                            <h6>Add Sale Return | <small class="text-muted">Save & Print = (Ctrl + Enter), Save = (Shift + Enter) </small></h6>
                                        </div>

                                        <div class="col-4">
                                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>Sale INV. ID :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="sale_invoice_id" id="sale_invoice_id" class="form-control" placeholder="Sale Invoice ID" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class=" col-4"> <b>B. Location :</b> </label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4"><b>Customer :</b> </label>
                                                <div class="col-8">
                                                    <select name="customer_id" class="form-control" id="customer_id">
                                                        <option value="">Walk-In-Customer</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4"> <b>Warehouse :</b> </label>
                                                <div class="col-8">
                                                    <select class="form-control changeable add_input"
                                                        name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                        <option value="">Select Warehouse</option>
                                                    </select>
                                                    <span class="error error_warehouse_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="col-4">
                                                    <b>Return Date : <span class="text-danger">*</span></b>
                                                </label>
                                                
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control add_input" data-name="Date"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" autocomplete="off" id="date">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label class="col-4">
                                                    <b>Return A/C : <span class="text-danger">*</span></b>
                                                </label>

                                                <div class="col-8">
                                                    <select name="sale_return_account_id" class="form-control add_input"
                                                        id="sale_return_account_id" data-name="Sale Return A/C">
                                                    </select>
                                                    <span class="error error_sale_return_account_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class=" col-4"><b>Return Invoice:</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Sale Return Invoice ID" autocomplete="off">
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
                                            <div class="col-md-4">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">Select Item</label>
                                                    <select disabled class="form-control" id="product">
                                                        <option value="">Select Item</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">Item Search</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-barcode text-dark input_f"></i>
                                                            </span>
                                                        </div>

                                                        <input type="text" name="search_product" class="form-control scanable" id="search_product" placeholder="Search Product by product code(SKU) / Scan bar code" autocomplete="off" autofocus>
                                                    
                                                    </div>
    
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
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
                                                                    <th class="text-center">Sold Price</th>
                                                                    <th class="text-center">Current Stock</th>
                                                                    <th class="text-center">Return Quantity</th>
                                                                    <th class="text-center">SubTotal</th>
                                                                    <th><i class="fas fa-minus text-dark"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="return_list"></tbody>
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
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('sales.partials.addSaleCreateJsScript')
@endpush



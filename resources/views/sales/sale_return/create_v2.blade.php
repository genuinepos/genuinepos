@extends('layout.master')
@push('stylesheets')
    <style>
        .data_preloader{top:2.3%}
         /* .search_area{position: relative;}  */
        /* .invoice_search_result{position: relative;} */
        /* Search Product area style */
        .selected_invoice {background-color: #645f61;color: #fff !important;}
        .invoice_search_result {position: absolute; width: 100%;border: 1px solid #E4E6EF;background: white;z-index: 1;padding: 3px;margin-top: 1px;}
        .invoice_search_result ul li {width: 100%;border: 1px solid lightgray;margin-top: 2px;}
        .invoice_search_result ul li a {color: #6b6262;font-size: 10px;display: block;padding: 0px 3px;}
        .invoice_search_result ul li a:hover {color: white;background-color: #ada9a9;}

        .search_result {position: absolute;width: 67%;border: 1px solid #E4E6EF;background: white;z-index: 1;padding: 3px;margin-top: 1px;}
        .selectProduct {background-color: #645f61;color: #fff !important;}
        .search_result ul li {width: 100%;border: 1px solid lightgray;margin-top: 2px;}
        .search_result ul li a {color: #6b6262;font-size: 10px;display: block;padding: 2px;}
        .search_result ul li a:hover {color: white;background-color: #ada9a9;}
        .element-body {overflow: initial!important;}
        /* Search Product area style end */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
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
                                                    <div style="position: relative;">
                                                        <input type="text" name="sale_invoice_id" id="sale_invoice_id" class="form-control" placeholder="Sale Invoice ID" autocomplete="off">
                                                        <input type="hidden" name="sale_id" id="sale_id" class="resetable" value="">

                                                        <div class="invoice_search_result d-none">
                                                            <ul id="invoice_list" class="list-unstyled">

                                                            </ul>
                                                        </div>
                                                    </div>
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

                <section class="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <input readonly name="total_qty" type="number" step="any" class="d-none" id="total_qty" value="0.00">
                                                        <label class="col-4"><b>Total Item :</b> </label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="0.00">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>Return Discount :</b></label>
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
                                                        <label class="col-4"><b>Return Tax :</b>
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="col-8">
                                                            <select name="return_tax" class="form-control" id="return_tax">
                                                                <option value="0.00">NoTax</option>
                                                            </select>
                                                            <input name="return_tax_amount" type="number" step="any" class="d-none" id="return_tax_amount" value="0.00">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>Net Total Amount :</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="net_total_amount" id="net_total_amount" class="form-control" value="0" placeholder="Net Total Amount">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>Return Note :</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="return_note" id="return_note" class="form-control" value="" placeholder="Return Note.">
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
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    
    <script>
        var ul = '';
        var selectObjClassName = '';

        $(document).on('click', '#sale_invoice_id', function () {
            
            ul = document.getElementById('invoice_list')
            selectObjClassName = 'selected_invoice';
            $(this).val('');
            $('#sale_id').val('');
        });

        $('#sale_invoice_id').on('input', function () {

            $('.invoice_search_result').hide();

            var invoice_id = $(this).val();

            if (invoice_id === '') {

                $('.invoice_search_result').hide();
                $('#sale_id').val('');;
                return;
            }

            $.ajax({
                url:"{{ url('common/ajax/call/search/final/sale/invoices') }}" + "/" +invoice_id,
                async:true,
                type:'get',
                success:function(data){

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.invoice_search_result').hide();
                    }else{

                        $('.invoice_search_result').show();
                        $('#invoice_list').html(data);
                    }
                }
            });
        });

        $(document).on('click', '#selected_invoice', function (e) {
            e.preventDefault();
            
            var sale_invoice_id = $(this).html();

            $('#sale_invoice_id').val(sale_invoice_id.trim());

            var sale_id = $(this).data('sale_id');

            $('#sale_id').val(sale_id);

            $('.invoice_search_result').hide();
        });

        $(document).on('keyup', 'body', function(e){

            console.log(window.event.which);

            if (e.keyCode == 13){  

                $(".selected_invoice").click();
                $('.invoice_search_result').hide();
                $('#list').empty();
            }
        });

        // $(document).on('click', 'body', function(e){

        //     setTimeout(function () {

        //         $('.search_result').hide();
        //         $('#list').empty();
        //     }, 200)
        // });

        
    </script>
    <script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.custom.js"></script>
@endpush





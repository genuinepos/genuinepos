@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_sale_return_form" action="{{ route('sales.returns.store', $saleId) }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Sale Return</h5>
                                        </div>

                                        <div class="col-6">
                                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <p class="m-0"><strong>Parent Sale </strong></p>
                                    <hr class="m-1">
                                    <div class="row">
                                        <div class="col-md-6">
                                           <p class="m-0"><strong>Invoice ID: </strong> <span class="sale_invoice_id">SI-14252-45525588</span> </p> 
                                           <p class="m-0"><strong>Date: </strong> <span class="sale_date">05-12-2020</span></p> 
                                        </div>
                                        <div class="col-md-6">
                                            <p class="m-0"><strong>Customer: </strong> <span class="sale_customer">Walk_in_customer</span> </p> 
                                            <p class="m-0 branch"><strong>Branch : </strong> <span class="sale_branch">Dhaka Branch - 145225</span></p>
                                            <p class="m-0 warehouse"><strong>Warehouse : </strong> <span class="sale_warehouse">Dhaka Branch - 145225</span></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Return Invoice ID :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" class="form-control" id="invoice_id" placeholder="Return Invoice ID">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-2"><b>Date : <span
                                                    class="text-danger">*</span></b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control add_input" id="date" autocomplete="off" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
                                                    <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-5"><b>Sales Return A/C : <span
                                                    class="text-danger">*</span></b></label>
                                                <div class="col-7">
                                                    <select name="sale_return_account_id" class="form-control add_input"
                                                        id="sale_return_account_id" data-name="Sale Return A/C">
                                                        @foreach ($saleReturnAccounts as $saleReturnAccount)
                                                            <option value="{{ $saleReturnAccount->id }}">
                                                                {{ $saleReturnAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_sale_return_account_id"></span>
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
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>Product Name</th>
                                                                    <th></th>
                                                                    <th>Unit Price</th>
                                                                    <th>Sold Quantity</th>
                                                                    <th>Return Quantity</th>
                                                                    <th>Return Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_return_list"></tbody>
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
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Return Discount:</label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <select name="return_discount_type" class="form-control" id="return_discount_type">
                                                                <option value="1">Fixed(0.00)</option>
                                                                <option value="2">Percentage(%)</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <input name="return_discount" type="number" class="form-control" id="return_discount" value="0.00"> 
                                                        </div>
                                                        
                                                    </div>
                                                    <input name="total_return_discount_amount" type="number" step="any" class="d-none" id="total_return_discount_amount" value="0.00"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Net Total :</label>
                                                <div class="col-8">
                                                    <input readonly type="number" name="net_total_amount" step="any" class="form-control" id="net_total_amount" value="0.00"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Total Return :</label>
                                                <div class="col-8">
                                                    <input readonly type="number" name="total_return_amount" step="any" class="form-control" id="total_return_amount" value="0.00"> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area py-2">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button class="btn btn-sm btn-primary float-end">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function getSaleReturn() {
        $.ajax({
            url:"{{route('sales.returns.get.sale', $saleId)}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(sale){

                $('.sale_invoice_id').html(sale.invoice_id); 
                $('.sale_date').html(sale.date); 
                $('.sale_customer').html(sale.customer ? sale.customer.name : "Walk-In-Customer"); 

                if (sale.branch != null) {

                    $('.sale_branch').html(sale.branch.name + ' - ' + sale.branch.branch_code);
                }else{

                    $('.branch').hide();
                }

                if (sale.warehouse != null) {

                    $('.sale_warehouse').html(sale.warehouse.warehouse_name + ' - ' + sale.warehouse.warehouse_code);
                }else{

                    $('.warehouse').hide();
                }

                $('#invoice_id').val(sale.sale_return != null ? sale.sale_return.invoice_id : '');

                $('#date').val(sale.sale_return != null ? sale.sale_return.date : '');

                $('#return_discount_type').val(sale.sale_return != null ? sale.sale_return.return_discount_type : 1);

                $('#return_discount').val(sale.sale_return != null ? sale.sale_return.return_discount : 0.00);
                
                $('.span_total_return_discount_amount').html(sale.sale_return != null ? sale.sale_return.return_discount_amount : 0.00);

                $('#total_return_discount_amount').val(sale.sale_return != null ? sale.sale_return.return_discount_amount : 0.00);

                if (sale.sale_return) {

                    $('#sale_return_account_id').val(sale.sale_return.sale_return_account_id );
                }
               
                if (sale.sale_return != null) {

                    $.each(sale.sale_return.sale_return_products, function (key, return_product) {
                        var tr = "";
                        tr += '<tr>';
                        tr += '<td colspan="2" class="text-start">';
                        tr += '<span class="product_name">'+return_product.sale_product.product.name+'</span>';
                        var variant = return_product.sale_product.variant ? ' ('+return_product.sale_product.variant.variant_name+')' : '';
                        
                        tr += '<span class="product_variant"><small><b>'+variant+'</b></small></span>'; 

                        var code = return_product.sale_product.variant ? return_product.sale_product.variant.variant_code : return_product.sale_product.product.product_code;
                        tr += '<span class="product_code"><small>('+code+')</small></span>';
                        tr += '<input value="'+return_product.sale_product_id+'" type="hidden" id="sale_product_id" name="sale_product_ids[]">';
                        tr += '</td>';
                        
                        tr += '<td class="text">';
                        tr += '<span class="span_unit_price">'+return_product.sale_product.unit_price_inc_tax+'</span>';
                        tr += '<input value="'+return_product.sale_product.unit_price_inc_tax+'" type="hidden" name="unit_prices[]" id="unit_price">';
                        tr += '</td>';

                        tr += '<td class="text">';
                            tr += '<input value="'+return_product.sale_product.unit+'" type="hidden" name="units[]" id="unit">';
                        tr += '<span class="span_sale_product_qty">'+return_product.sale_product.quantity+' ('+return_product.sale_product.unit+')'+'</span>';
                        tr += '<input value="'+return_product.sale_product.quantity+'" type="hidden" name="sale_qtys[]" id="sale_qty">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="'+return_product.return_qty+'" type="hidden" name="previous_return_quantitiess[]" id="previous_return_quantity">';
                        tr += '<input value="'+return_product.sale_product.unit+'" type="hidden" id="unit">';
                        tr += '<input value="'+return_product.return_qty+'" required name="return_quantities[]" type="text" class="form-control form-control-sm" id="return_quantity">';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<span class="span_return_subtotal">'+return_product.return_subtotal+'</span>';
                        tr += '<input value="'+return_product.return_subtotal+'"  name="return_subtotals[]" type="hidden" class="form-control form-control-sm" id="return_subtotal">';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#sale_return_list').append(tr);
                    });

                    calculateTotalAmount();
                }else{

                    $.each(sale.sale_products, function (key, sale_product) {

                        var tr = "";
                        tr += '<tr>';
                        tr += '<td colspan="2" class="text-start">';
                        tr += '<span class="product_name">'+sale_product.product.name+'</span>';
                        var variant = sale_product.variant ? ' ('+sale_product.variant.variant_name+')' : '';
                        tr += '<span class="product_variant"><small><b>'+variant+'</b></small></span>'; 
                        var code = sale_product.variant ? sale_product.variant.variant_code : sale_product.product.product_code;
                        tr += '<span class="product_code"><small>('+code+')</small></span>';
                        tr += '<input value="'+sale_product.id+'" type="hidden" id="sale_product_id" name="sale_product_ids[]">';
                        tr += '</td>';
                        
                        tr += '<td class="text">';
                        tr += '<span class="span_unit_price">'+sale_product.unit_price_inc_tax+'</span>';
                        tr += '<input value="'+sale_product.unit_price_inc_tax+'" type="hidden" name="unit_prices[]" id="unit_price">';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<input value="'+sale_product.unit+'" type="hidden" name="units[]" id="unit">';
                        tr += '<span class="span_sale_product_quantity">'+sale_product.quantity+' ('+sale_product.unit+')'+'</span>';
                        tr += '<input value="'+sale_product.quantity+'" type="hidden" name="sale_quantities[]" id="sale_quantity">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input value="0" type="hidden" name="previous_return_quantities[]" id="previous_return_quantity">';
                        tr += '<input value="'+sale_product.unit+'" type="hidden" id="unit">';
                        tr += '<input value="0.00" required name="return_quantities[]" type="text" class="form-control form-control-sm" id="return_quantity">';
                        tr += '</td>';

                        tr += '<td class="text">';
                        tr += '<span class="span_return_subtotal">0.00</span>';
                        tr += '<input value="0.00" name="return_subtotals[]" type="hidden" class="form-control form-control-sm" id="return_subtotal">';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#sale_return_list').append(tr);
                    });

                    calculateTotalAmount();
                }
            }
        });
    }
    getSaleReturn();

     // Calculate total amount functionalitie
     function calculateTotalAmount(){

        var quantities = document.querySelectorAll('#return_quantity');
        var subtotals = document.querySelectorAll('#return_subtotal');
  
        // Update Net total Amount
        var netTotalAmount = 0;
        subtotals.forEach(function(subtotal){

            netTotalAmount += parseFloat(subtotal.value);
        });

        $('.span_net_total_amount').html(parseFloat(netTotalAmount).toFixed(2));
        $('#net_total_amount').val(parseFloat(netTotalAmount).toFixed(2));

        
        // Update Total payable Amount
        var returnDiscountAmount = $('#total_return_discount_amount').val() ? $('#total_return_discount_amount').val() : 0;

        var calcTotalReturnAmount = parseFloat(netTotalAmount) - parseFloat(returnDiscountAmount);
        $('#total_return_amount').val(parseFloat(calcTotalReturnAmount).toFixed(2));
        $('.span_total_return_amount').html(parseFloat(calcTotalReturnAmount).toFixed(2));
    }

    // Input return discount and clculate total amount
    $(document).on('input', '#return_discount', function(){

        var returnDiscount = $(this).val() ? $(this).val() : 0;
        var returnDiscountType = $('#return_discount_type').val();
        var netTotalAmount = $('#net_total_amount').val();

        if (returnDiscountType == 1) {

            $('.span_total_return_discount_amount').html(parseFloat(returnDiscount).toFixed(2)); 
            $('#total_return_discount_amount').val(parseFloat(returnDiscount).toFixed(2)); 
            calculateTotalAmount();
        }else{

            var calsReturnDiscount = parseFloat(netTotalAmount) / 100 * parseFloat(returnDiscount);
            $('.span_total_return_discount_amount').html(parseFloat(calsReturnDiscount).toFixed(2)); 
            $('#total_return_discount_amount').val(parseFloat(calsReturnDiscount).toFixed(2));
            calculateTotalAmount();
        }
    });

    // Input return discount type and clculate total amount
    $(document).on('change', '#return_discount_type', function(){

        var returnDiscountType = $(this).val() ? $(this).val() : 0;
        var returnDiscount = $('#return_discount').val() ? $('#return_discount').val() : 0.00;
        var netTotalAmount = $('#net_total_amount').val();

        if (returnDiscountType == 1) {

            $('.span_total_return_discount_amount').html(parseFloat(returnDiscount).toFixed(2)); 
            $('#total_return_discount_amount').val(parseFloat(returnDiscount).toFixed(2)); 
            calculateTotalAmount();
        } else {

            var calsReturnDiscount = parseFloat(netTotalAmount) / 100 * parseFloat(returnDiscount);
            $('.span_total_return_discount_amount').html(parseFloat(calsReturnDiscount).toFixed(2)); 
            $('#total_return_discount_amount').val(parseFloat(calsReturnDiscount).toFixed(2));
            calculateTotalAmount();
        }
    });

    // Return Quantity increase or dicrease and clculate row amount
    $(document).on('input', '#return_quantity', function(){

        var return_quantity = $(this).val() ? $(this).val() : 0;

        if (parseFloat(return_quantity) >= 0) {

            var tr = $(this).closest('tr');
            var previousReturnQty = tr.find('#previous_return_quantity').val();
            var unit = tr.find('#unit').val();
            var limit = tr.find('#sale_quantity').val();
            var qty_limit = parseFloat(previousReturnQty) + parseFloat(limit);

            if(parseInt(return_quantity) > parseInt(qty_limit)){

                alert('Only '+limit+' '+unit+' is available.');
                $(this).val(parseFloat(limit).toFixed(2));
                var unitPrice = tr.find('#unit_price').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                tr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
            }else{

                var unitPrice = tr.find('#unit_price').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(return_quantity);
                tr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount(); 
            }
        }
    });

    //Add purchase request by ajax
    $('#add_sale_return_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');
      
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                $('.error').html('');

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg,'ERROR'); 
                    $('.loading_button').hide();
                }else {

                    $('.loading_button').hide();
                    toastr.success('Successfully sale return is addedd.'); 
                    $(data).printThis({
                        debug: false,                   
                        importCSS: true,                
                        importStyle: true,          
                        loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",                      
                        removeInline: false, 
                        printDelay: 1000, 
                        header: null,        
                    });
                }
            },error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');
                
                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.'); 
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing want wrong.'); 

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    
    new Litepicker({
        singleMode: true,
        element: document.getElementById('date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>
@endpush

@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
        .selectProduct{background-color: #ab1c59; color: #fff!important;}
    </style>
    <link rel="stylesheet" href="{{ asset('public') }}/backend/asset/css/bootstrap-datepicker.min.css">
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_purchase_return_form" action="{{ route('purchases.returns.supplier.return.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Add Purchase Return</h5> 
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
                                                <label for="inputEmail3" class=" col-4"><b>Supplier :</b><span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select name="supplier_id" class="form-control add_input"
                                                        data-name="Supplier" id="supplier_id">
                                                        <option value="">Select Supplier</option>
                                                        @foreach ($suppliers as $sup)
                                                            <option value="{{$sup->id}}">{{ $sup->name.' ('.$sup->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                    <span class="error error_supplier_id"></span>
                                                </div>
                                            </div>

                                            @if (count($warehouses) > 0)
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Warehouse :</b><span
                                                        class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable add_input"
                                                            name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                            <option value="">Select Warehouse</option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name.'/'.$warehouse->warehouse_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else 
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Location :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'] }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Invoice ID :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Invoice ID">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>Date :</b> <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control datepicker changeable"
                                                        value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}" id="date">
                                                     <span class="error error_date"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>Attachment :</b></label>
                                                <div class="col-8">
                                                    <input type="file" class="form-control" name="attachment">
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
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="Search Product by product code(SKU) / Scan bar code">
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area">
                                                          
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
                                                                    <th>Unit Price</th>
                                                                    <th>Current Stock</th>
                                                                    <th>Return Quantity</th>
                                                                    <th>Return Subtotal</th>
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="purchase_return_list">
                                                               
                                                            </tbody>
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
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4">Tax:<span class="text-danger">*</span></label>
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
                                                <label><strong> Tax Amount (+) : </strong></label> <span class="label_purchase_tax_amount"> 0.00</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4 text-center">Net Total Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="total_return_amount" type="number" step="any" id="total_return_amount" class="form-control" value="0.00" >
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
                            <button class="btn btn-sm btn-success float-end">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
    <script src="{{ asset('public') }}/backend/asset/js/bootstrap-date-picker.min.js"></script>
    <script>
        function getTaxes(){
            $.ajax({
                url:"{{ route('purchases.get.all.taxes') }}",
                async:false,
                type:'get',
                dataType: 'json',
                success:function(taxes){
                    $('#purchase_tax').append('<option value="">No Tax</option>');
                    $.each(taxes, function(key, val){
                        $('#purchase_tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
                    });
                }
            });
        }
        getTaxes();
      
        // add purchase product by searching product code
        $('#search_product').on('input', function(e){
            $('.variant_list_area').empty();
            $('.select_area').hide();
            var product_code = $(this).val();
            var warehouse_id = $('#warehouse_id').val();
            var branch_id = $('#branch_id').val();
            if(warehouse_id == ""){
                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }
            $.ajax({
                @if (count($warehouses) > 0) 
                    url:"{{url('purchases/returns/search/product')}}"+"/"+product_code+"/"+warehouse_id,
                @else
                    url:"{{url('purchases/returns/search/product/in/branch')}}"+"/"+product_code,  
                @endif
                dataType: 'json',
                success:function(product){
                    if(!$.isEmptyObject(product.errorMsg)){
                        toastr.error(product.errorMsg); 
                        return;
                    }
                    var qty_limit = product.qty_limit;
                    if(!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product)){
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
                                        var presentQty = closestTr.find('#return_quantity').val() ? closestTr.find('#return_quantity').val() : 0;
                                        var qty_limit = closestTr.find('#qty_limit').val();
                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){
                                            alert('Quantity Limit is - '+qty_limit+' '+product.unit.name);
                                            return;
                                        }

                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));
                                        
                                        //Update Subtotal
                                        var unitCost = closestTr.find('#unit_cost').val();
                                        var calcSubtotal = parseFloat(unitCost) * parseFloat(updateQty);
                                        closestTr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                        closestTr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                        calculateTotalAmount();
                                        return;
                                    }
                                });

                                if(sameProduct == 0){
                                    var tr = '';
                                    tr += '<tr>';
                                    tr += '<td class="text">';
                                    tr += '<span class="product_name">'+product.name+'</span>';
                                    tr += '<span class="product_variant"></span>'; 
                                    tr += '<span class="product_code">'+' ('+product.product_code+')'+'</span>';
                                  ;
                                    tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                    tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
        
                                    tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                    tr += '<input  name="units[]" type="hidden" id="unit" value="'+product.unit.name+'">';
                                    tr += '</td>';

                                    tr += '<td class="text">';
                                    tr += '<b><span class="span_unit_cost">'+product.product_cost_with_tax+'</span></b>'; 
                                    tr += '<input  name="unit_costs[]" type="hidden" id="unit_cost" value="'+product.product_cost_with_tax+'">';
                                    tr += '</td>';

                                    tr += '<td class="text"><span class="span_warehouse_stock">'+qty_limit+' ('+product.unit.name+')'+'</span></td>';
                                   
                                    tr += '<td>';
                                    tr += '<input value="1.00" required name="return_quantities[]" type="text" class="form-control" id="return_quantity">';
                                    tr += '</td>';
                                    
                                    tr += '<td class="text">';
                                    tr += '<strong><span class="span_return_subtotal">'+product.product_cost_with_tax+' </span></strong>'; 
                                    tr += '<input value="'+product.product_cost_with_tax+'" readonly name="return_subtotals[]" type="hidden"  id="return_subtotal">';
                                    tr += '</td>';
                                    
                                    tr += '<td>';
                                    tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                    tr += '</td>';
                                    tr += '</tr>';
                                    $('#purchase_return_list').append(tr);
                                    calculateTotalAmount();
                                }
                            }else{
                                var li = "";
                                $.each(product.product_variants, function(key, variant){
                                    li += '<li>';
                                    li += '<a class="select_variant_product" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-unit="'+product.unit.name+'" data-v_code="'+variant.variant_code+'" data-v_cost="'+variant.variant_cost_with_tax+'" data-v_name="'+variant.variant_name+'" href="#">'+product.name+' ('+variant.variant_name+')'+'</a>';
                                    li +='</li>';
                                });
                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        }else if(!$.isEmptyObject(product.variant_product)){
                            $('.select_area').hide();
                            $('#search_product').val('');
                            var variant_product = product.variant_product;
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;
                            variant_ids.forEach(function(input){
                                if(input.value != 'noid'){
                                    if(input.value == variant_product.id){
                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        var presentQty = closestTr.find('#return_quantity').val() ? closestTr.find('#return_quantity').val() : 0;
                                        var qty_limit = closestTr.find('#qty_limit').val();
                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){
                                            alert('Quantity Limit is - '+qty_limit+' '+variant_product.product.unit.name);
                                            return;
                                        }
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));
                                        //Update Subtotal
                                        var unitPrice = closestTr.find('#unit_cost').val();
                                        var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                        closestTr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                        closestTr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                        calculateTotalAmount();
                                        return;
                                    }
                                }    
                            });
                           
                            if(sameVariant == 0){
                                var tr = '';
                                tr += '<tr>';
                                tr += '<td colspan="2" class="text">';
                                tr += '<span class="product_name">'+variant_product.product.name+'</span>';
                                tr += '<span class="product_variant">'+' -'+variant_product.variant_name+'- '+'</span>'; 
                                tr += '<span class="product_code">'+'('+variant_product.variant_code+')'+'</span>';
                             
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                
                                tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                tr += '<input  name="units[]" type="hidden" id="unit" value="'+variant_product.product.unit.name+'">';
                                tr += '</td>';

                                tr += '<td class="text">';
                                tr += '<span class="span_unit_cost">'+variant_product.variant_cost_with_tax+'</span>'; 
                                tr += '<input readonly name="unit_costs[]" type="hidden" id="unit_cost" value="'+variant_product.variant_cost_with_tax+'">';
                                tr += '</td>';

                                tr += '<td class="text"><span class="span_warehouse_stock">'+qty_limit+' ('+variant_product.product.unit.name+')'+'</span></td>';

                                tr += '<td>';
                                tr += '<input value="1.00" required name="return_quantities[]" type="text" class="form-control text-center form-control-sm" id="return_quantity">';
                                tr += '</td>';

                                tr += '<td class="text">';
                                tr += '<strong><span class="span_return_subtotal">'+variant_product.variant_cost_with_tax+'</span></strong>'; 
                                tr += '<input value="'+variant_product.variant_cost_with_tax+'"  name="return_subtotals[]" type="hidden" id="return_subtotal">';
                                tr += '</td>';

                                tr += '<td class="text-center">';
                                tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#purchase_return_list').append(tr);
                                calculateTotalAmount();
                            }    
                        }
                    }else{
                        $('#search_product').addClass('is-invalid');
                    }
                }
            });
        });

        // select variant product and add purchase table
        $(document).on('click', '.select_variant_product', function(e){
            e.preventDefault();
            $('.select_area').hide();
            var warehouse_id = $('#warehouse_id').val();
            var branch_id = $('#branch_id').val();
            var product_id = $(this).data('p_id');
            var product_name = $(this).data('p_name');
            var product_unit = $(this).data('unit');
            var variant_id = $(this).data('v_id');
            var variant_name = $(this).data('v_name');
            var variant_code = $(this).data('v_code');
            var variant_cost = $(this).data('v_cost');

            if(warehouse_id == ""){
                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }
            $.ajax({
                @if (count($warehouses) > 0) 
                    url:"{{url('purchases/returns/check/warehouse/variant/qty/')}}"+"/"+product_id+"/"+variant_id+"/"+warehouse_id, 
                @else
                    url:"{{url('purchases/returns/check/branch/variant/qty')}}"+"/"+product_id+"/"+variant_id,
                @endif
                type:'get',
                dataType: 'json',
                success:function(branchVariantQty){
                    if($.isEmptyObject(branchVariantQty.errorMsg)){
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
                                    var presentQty = closestTr.find('#return_quantity').val() ? closestTr.find('#return_quantity').val() : 0;
                                    var qty_limit = closestTr.find('#qty_limit').val();
                                    if(parseFloat(qty_limit) === parseFloat(presentQty)){
                                        alert('Quantity Limit is - '+qty_limit+' '+product_unit);
                                        return;
                                    }
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#return_quantity').val(parseFloat(updateQty).toFixed(2));
                                    
                                    //Update Subtotal
                                    var unitCost = closestTr.find('#unit_cost').val();
                                    var calcSubtotal = parseFloat(unitCost) * parseFloat(updateQty);
                                    closestTr.find('#return_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_return_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    return;
                                }
                            }    
                        });

                        if(sameVariant == 0){
                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="text">';
                            tr += '<span class="product_name">'+product_name+'</span>';
                            tr += '<span class="product_variant">'+' -'+variant_name+'- '+'</span>'; 
                            tr += '<span class="product_code">'+'('+variant_code+')'+'</span>';
                            tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                            tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
                            tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                            tr += '</td>';
                            tr += '<input type="hidden" id="qty_limit" value="'+branchVariantQty+'">';
                            tr += '</td>';

                            tr += '<td class="text">';
                            tr += '<span class="span_unit_cost">'+variant_cost+'</span>'; 
                            tr += '<input name="unit_costs[]" type="hidden" id="unit_cost" value="'+variant_cost+'">';
                            tr += '</td>';

                            tr += '<td class="text"><span class="span_warehouse_stock">'+branchVariantQty+' ('+product_unit+')'+'</span></td>';

                            tr += '<td>';
                            tr += '<input value="1.00" required name="return_quantities[]" type="text" class="form-control form-control-sm" id="return_quantity">';
                            tr += '</td>';

                            tr += '<td class="text>';
                            tr += '<strong><span class="span_return_subtotal">'+variant_cost+'</span></strong>'; 

                            tr += '<input value="'+variant_cost+'" readonly name="return_subtotals[]" type="hidden" id="return_subtotal">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#purchase_return_list').append(tr);
                            calculateTotalAmount();
                        }
                    }else{
                        toastr.warning(branchVariantQty.errorMsg);   
                    }
                }
            });
        });

             // Calculate total amount functionalitie
        function calculateTotalAmount(){
            var subtotals = document.querySelectorAll('#return_subtotal');
            // Update Net total Amount
            var netTotalAmount = 0;
            subtotals.forEach(function(subtotal){
                netTotalAmount += parseFloat(subtotal.value);
            });

            var purchaseTaxAmount = $('#purchase_tax_amount').val() ? $('#purchase_tax_amount').val() : 0; 
            var calcTotalReturnAmount = parseFloat(netTotalAmount) + parseFloat(purchaseTaxAmount);
            $('.span_total_return_amount').html(parseFloat(calcTotalReturnAmount).toFixed(2));
            $('#total_return_amount').val(parseFloat(calcTotalReturnAmount).toFixed(2));
        }


        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#return_quantity', function(){
            var qty = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            //Update subtotal 
            var qty_limit = tr.find('#qty_limit').val();
            var unit = tr.find('#unit').val();
            if(parseInt(qty) > parseInt(qty_limit)){
                alert('Quantity Limit Is - '+qty_limit+' '+unit);
                $(this).val(qty_limit);
                var unitCost = tr.find('#unit_cost').val();
                var calcReturnSubtotal = parseFloat(unitCost) * parseFloat(qty_limit);
                tr.find('#return_subtotal').val(parseFloat(calcReturnSubtotal).toFixed(2));
                tr.find('.span_return_subtotal').html(parseFloat(calcReturnSubtotal).toFixed(2));
                calculateTotalAmount();  
                return;
            }else{
                var unitCost = tr.find('#unit_cost').val();
                var calcReturnSubtotal = parseFloat(unitCost) * parseFloat(qty);
                tr.find('#return_subtotal').val(parseFloat(calcReturnSubtotal).toFixed(2));
                tr.find('.span_return_subtotal').html(parseFloat(calcReturnSubtotal).toFixed(2));
                calculateTotalAmount();
            } 
        });

        // chane purchase tax and clculate total amount
        $(document).on('change', '#purchase_tax', function(){
            var purchaseTax = $(this).val() ? $(this).val() : 0;
            var totalReturnAmount = $('#total_return_amount').val();
            var calcPurchaseTaxAmount = parseFloat(totalReturnAmount) / 100 * parseFloat(purchaseTax);
            $('.label_purchase_tax_amount').html(parseFloat(calcPurchaseTaxAmount).toFixed(2));
            $('#purchase_tax_amount').val(parseFloat(calcPurchaseTaxAmount).toFixed(2));
            calculateTotalAmount();
        });

        // Dispose Select area 
        $(document).on('click', '.remove_select_area_btn', function(e){
            e.preventDefault();
            $('.select_area').hide();
        });

        // Remove product form purchase product list (Table) 
        $(document).on('click', '#remove_product_btn',function(e){
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateTotalAmount();
        });

        //Add purchase request by ajax
        $('#add_purchase_return_form').on('submit', function(e){
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

            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    if(!$.isEmptyObject(data.errorMsg)){
                        toastr.error(data.errorMsg,'ERROR'); 
                        $('.loading_button').hide();
                    }else{
                        $('.loading_button').hide();
                        toastr.success(data); 
                        window.location = "{{ route('purchases.returns.index') }}";
                    }
                }
            });
        });

        // Automatic remove searching product is found signal 
        setInterval(function(){
            $('#search_product').removeClass('is-invalid');
        }, 500); 

        setInterval(function(){
            $('#search_product').removeClass('is-valid');
        }, 1000);

        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '' ;
        _expectedDateFormat = dateFormat.replace('d', 'dd');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
        $('.datepicker').datepicker({format: _expectedDateFormat});
    </script>
@endpush

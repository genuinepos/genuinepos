@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
<br><br><br>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <!-- Golmenu area -->
            <div class="menu_popup_area">
               
            </div>
            <!-- Golmenu area end-->
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3 style="color: #32325d"> Generate Barcode</h3>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left"></i> Back</a>
                    </div>
                </div>

                <div class="card card-custom">
                    <form id="multiple_completed_form" action="{{ route('barcode.multiple.genereate.completed') }}" method="post">
                        @csrf
                    </form>
                    <!--begin::Form-->
                    <form id="generate_barcode_form" action="" method="POST">
                        @csrf
                        <div class="card-body">
                        <input type="hidden" id="business_name" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                            <div class="form-group row">
                                <div class="col-md-8 offset-2">
                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" name="search_product" class="form-control form-control-sm" autocomplete="off" id="search_product" placeholder="Search Product by Product name / Product code(SKU)">
                                    </div>
                                    <div class="select_area">
                                        <div class="remove_select_area_btn">X</div>
                                        <ul class="product_dropdown_list">
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="barcode_product_table_area">
                                <div class="table_heading">
                                    <p class="p-0 m-0"><strong>Recent Purchased Product List</strong></p>
                                </div>
                                <div class="table_area">
                                <div class="data_preloader d-none"> <h6><i class="fas fa-spinner"></i> Processing...</h6></div>
                                <table class="table table-sm">
                                    <thead>
                                        <tr class="bg-primary text-white text-center">
                                            <th>Product</th>
                                            <th>Supplier</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="barcode_product_list">
                                    
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5"><a href="" class="btn btn-sm btn-success multiple_completed"> Generate Completed All</a></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            </div>

                            <div class="extra_label">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_price" class="checkbox" id="is_price" value="1"> &nbsp; Price Price. &nbsp;  </h6> 
                                        </div>
                                        <div class="col-2">
                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_product_name" class="checkbox"  id="is_product_name" value="1"> &nbsp; Product Name &nbsp; </h6>
                                        </div>
                                        <div class="col-2">
                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_product_variant" class="checkbox" id="is_product_variant" value="0"> &nbsp; Product Variant &nbsp; </h6>
                                        </div>
                                        <div class="col-2">                                            
                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_tax" class="checkbox" id="is_tax" value="0"> &nbsp; Product Tax &nbsp; </h6>
                                    </div>
                                    <div class="col-2">

                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_business_name" class="checkbox" id="is_business_name" value="0"> &nbsp; Business Name &nbsp; </h6>
                                    </div>
                                    <div class="col-2">

                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_supplier_name" class="checkbox" id="is_supplier_name" value="0"> &nbsp; Supplier Prefix &nbsp; </h6>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn loading_button btn-sm d-none"><i class="fas fa-spinner"></i> <strong>Loading</strong> </button>
                                    <button type="submit" class="btn btn-success submit_button btn-sm">Generate</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                   </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div><br><br>

    <div class="barcodes d-none">
        <!-- <div class="barcode_conatiner">
            <div class="product_name">
                <h6>Product Name</h6>
            </div>
            <div class="row barcode_row">
                <div class="col-md-3">
                    <svg id="barcode"></svg>
                </div>
            </div>
        </div> -->
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('public') }}/assets/plugins/custom/barcode/JsBarcode.all.min.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/printme/jquery-printme.min.js"></script>
    <script>
        $('.multiple_completed').hide();
        // Get all supplier products
        function getSupplierProduct(){
            $('.data_preloader').show();
            $.ajax({
                url:"{{route('barcode.supplier.get.products')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(suppliers){
                    $('.data_preloader').hide();
                    $.each(suppliers, function (key, supplier) {
                        console.log(supplier.supplier_products.length);
                        if (supplier.supplier_products.length > 0) {
                            $('.multiple_completed').show();
                            $.each(supplier.supplier_products, function (key, product) {
                                var tax = product.product.tax != null ? product.product.tax.tax_percent : 0.00;
                                var tr = '';
                                tr += '<tr>';
                                tr += '<td>';
                                tr += '<span class="span_product_name">'+product.product.name+'</span>';  
                                if (product.product_variant_id != null) {
                                    tr += '<span class="span_variant_name">'+' - '+product.variant.variant_name+'</span>';
                                }else{
                                    tr += '<span class="span_product_code"></span>';
                                } 
                                  
                                if (product.product_variant_id != null) {
                                    tr += '<span class="span_product_code">'+' ('+product.variant.variant_code+')'+'</span>';
                                }else{
                                    tr += '<span class="span_product_code">'+' ('+product.product.product_code+')'+'</span>';
                                }
                                
                                var variant_id = product.product_variant_id != null ? product.product_variant_id : null;
                                tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+product.product.id+supplier.id+variant_id+'" id="product_id" value="'+product.product.id+'">';
                                tr += '<input type="hidden" name="product_name" id="product_name" value="'+product.product.name+'">';
                                tr += '<input type="hidden" class="'+key+'" id="recents" value="1">';
                                if (product.product_variant_id != null) {
                                    tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="'+product.variant.id+'">';
                                    tr += '<input type="hidden" name="product_variant" id="product_variant" value="'+product.variant.variant_name+'">';
                                    tr += '<input type="hidden" class="productCode-'+product.variant.variant_code+'" name="product_code" id="product_code" value="'+product.variant.variant_code+'">';
                                    tr += '<input type="hidden" name="product_price" id="product_price" value="'+product.variant.variant_price+'">';
                                } else {
                                    tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="noid">';
                                    tr += '<input type="hidden" name="product_variant" id="product_variant" value="">';
                                    tr += '<input type="hidden" class="productCode-'+product.product.product_code+'" name="product_code" id="product_code" value="'+product.product.product_code+'">';
                                    var priceIncTax = parseFloat(product.product.product_price) /100 * parseFloat(tax) + parseFloat(product.product.product_price);
                                    tr += '<input type="hidden" name="product_price" id="product_price" value="'+priceIncTax+'">'; 
                                }
                                
                                tr += '<input type="hidden" name="product_tax" id="product_tax" value="'+tax+'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<span class="span_supplier_name">'+ supplier.name +'</span>';
                                tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+supplier.id+'">';
                                tr += '<input type="hidden" name="supplier_name" id="supplier_name" value="'+supplier.prefix+'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input type="number" name="left_qty" class="form-control form-control-sm" id="left_qty" value="'+product.label_qty+'">';
                                tr += '<input type="hidden" name="barcode_type" id="barcode_type" value="'+product.product.barcode_type+'">';
                                tr += '</td>';
                                tr += '<td class="text-center">';
                                tr += '<a href="#" class="btn btn-sm btn-success completed_btn">Generate Completed</a>';
                                tr += '<a href="#" class="btn btn-sm btn-danger remove_btn float-right ml-1">X</a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#barcode_product_list').append(tr);
                            });
                        }
                    });
                }
            });
        }
        getSupplierProduct();

        // Generate barcode 
        $('#generate_barcode_form').on('submit', function(e){
            e.preventDefault();
            var productCodes = document.querySelectorAll('#product_code');
            var index = 0;
            $('.barcodes').empty(); 
            productCodes.forEach(function(productCode){
                var getClass = productCode.getAttribute('class');
                //console.log(productCode.value);
                var closestTr = $('.'+getClass).closest('tr');
                var barcodeQty = closestTr.find('#left_qty').val();
                //console.log(barcodeQty);
                var productPrice = closestTr.find('#product_price').val();
                //console.log(productPrice);
                var productName = closestTr.find('#product_name').val();
                console.log();
                var productVariant = closestTr.find('#product_variant').val() ? ' - ' + closestTr.find('#product_variant').val() : '';
                var productSupplier = closestTr.find('#supplier_name').val();
                var productTax = closestTr.find('#product_tax').val(); 
                var barcode_type = closestTr.find('#barcode_type').val();
                //console.log(productName);
                var barcode = '';
                    barcode +='<div class="barcode_conatiner">';
                        barcode += '<div class="product_name">';
                            barcode +='<h6><b>'+productName+productVariant+'<b><hr class="p-0 m-0"></h6>';
                        barcode += '</div>';
                        barcode += '<div class="col-md-12">';
                            barcode += '<div class="row barcode_row-'+index+'">';
                            
                            barcode += '</div>';   
                        barcode += '</div>';
                    barcode += '</div>';

                    $('.barcodes').append(barcode);

                    barCodeCol = '';
                    var codeLength = productCode.value.length;
                    console.log(codeLength);
                    var col = codeLength > 11 ? 'col-md-3' : 'col-md-2 px-2';
                    barCodeCol += '<div class="'+col+' text-center mt-1">';
                    var is_business_name = $('#is_business_name');
                    var business_name = $('#business_name').val();
                    if(is_business_name.is(':CHECKED', true)){
                        barCodeCol += '<div class="info">';
                            barCodeCol += '<small class="barcode_business_name m-0 p-0"><b>'+business_name+'</b></small>';
                        barCodeCol += '</div>';
                    }

                    barCodeCol += '<svg class="main_barcode" id="barcode-'+index+'"></svg>';

                    var is_name = $('#is_product_name');
                    if(is_name.is(':CHECKED', true)){
                        barCodeCol += '<div class="info">';
                            barCodeCol += '<small class="barcode_product_name m-0 p-0">'+productName+'</small>';
                        barCodeCol += '</div>';
                    }

                    var is_variant = $('#is_product_variant');
                    if(is_variant.is(':CHECKED', true)){
                        barCodeCol += '<div class="info">';
                            barCodeCol += '<small class="barcode_product_variant m-0 p-0">'+productVariant+'</small>';
                        barCodeCol += '</div>';
                    }

                    var is_price = $('#is_price');
                    var is_tax = $('#is_tax');
                    var tax = ''
                    if(is_tax.is(':CHECKED', true)){
                        tax = ' + '+parseFloat(productTax).toFixed(2) +'%'
                    } 

                    if(is_price.is(':CHECKED', true)){
                        barCodeCol += '<div class="barcode_product_price info">';
                            barCodeCol += '<small >Price :  '+"{{ json_decode($generalSettings->business, true)['currency'] }} "+productPrice+tax+'</small>';
                        barCodeCol += '</div>';
                    } 

                    var is_supplier_name = $('#is_supplier_name');
                    if(is_supplier_name.is(':CHECKED', true)){
                        barCodeCol += '<div class="barcode_product_supplier info">';
                            barCodeCol += '<small > :'+productSupplier+'</small>';
                        barCodeCol += '</div>';
                    }
                    barCodeCol += '</div>'; 

                    $('.barcode_row-'+index).html(barCodeCol); 

                    $('#barcode-'+index).JsBarcode(productCode.value,{
                        format:barcode_type,
                        width:1,
                        height:40,
                        fontSize:10,
                        textMargin:-1,
                        margin: 5,
                    }); 

                    var this_barcode = $('.barcode_row-'+index).html();
                    for(i = 0; i < barcodeQty - 1; i++){
                        $('.barcode_row-'+index).append(this_barcode); 
                    }
                index++;   
            });
            $(".barcodes").printMe({ "path": ["{{asset('public/assets/css/print/barcode.print.css')}}"] });
        });

        // Generate confirm request send by ajax
        $(document).on('click', '.completed_btn',function (e) {
            e.preventDefault();
            var tr = $(this).closest('tr');
            var product_id = tr.find('#product_id').val();
            var variant_id = tr.find('#product_variant_id').val();
            var supplier_id = tr.find('#supplier_id').val();
            var label_qty = tr.find('#left_qty').val();
            console.log(product_id);

            $.ajax({
                url:"{{route('barcode.genereate.completed')}}",
                type:'get',
                data: {
                    product_id,variant_id,supplier_id,label_qty
                },
                success:function(data){
                    console.log(data);
                    if (!$.isEmptyObject(data.successMsg)) {
                        toastr.success(data.successMsg);
                        tr.remove();
                    }
                }
            });
        });

        // Searcha product 
        $('#search_product').on('input', function () {
           var searchKeyWord = $(this).val();
           $('.product_dropdown_list').empty();
           $('.select_area').hide();
           $.ajax({
                url:"{{url('product/barcode/search/product')}}"+"/"+searchKeyWord,
                type:'get',
                success:function(products){
                    console.log(products);
                    $('.product_dropdown_list').empty();
                        if (products.length > 0) {
                            $.each(products, function(key, product){
                            li = '';
                            li += '<li>';
                            li += '<a class="select_product" data-p_id="'+product.id+'" href="#">'+product.name+' - '+product.product_code+'</a>';
                            li +='</li>';
                            if (product.product_purchased_variants.length > 0) {
                                $.each(product.product_purchased_variants, function (key, product_variant) {
                                    li += '<li>';
                                    li += '<a class="select_variant_product" data-p_id="'+product_variant.product_id+'" data-v_id="'+product_variant.id+'" href="#">'+product.name+' - '+product_variant.variant_name+' - '+'('+product_variant.variant_code+')'+'</a>';
                                    li +='</li>';
                                });
                            }
                        });
                        $('.product_dropdown_list').append(li);
                        $('.select_area').show();
                    }else{
                        $('.select_area').hide();
                    }
                }
            });
        });

        //Get Seleled product requested by ajax 
        $(document).on('click', '.select_product', function(e) {
            e.preventDefault();
            var product_id = $(this).data('p_id');
            $('.select_area').hide();
            $('#search_product').val('');
            $.ajax({
                url:"{{url('product/barcode/get/selected/product/')}}"+"/"+product_id,
                type:'get',
                success:function(supplierProducts){
                    var productIds = document.querySelectorAll('#product_id');
                    var rows = [
                        {
                            productPrefix : 78555858,
                        },
                    ];
                    productIds.forEach(function (product_id) {
                        var productId = product_id.value;
                        var className = product_id.getAttribute('class');
                        console.log('Class_name-'+className);
                        var tr = $('.'+className).closest('tr');
                        var supplier_id = tr.find('#supplier_id').val();
                        //console.log('supplier_id-'+supplier_id);
                        var variant_id = tr.find('#product_variant_id').val() != 'noid' ? tr.find('#product_variant_id').val() : null;
                        console.log('variant_id-'+variant_id);
                        rows.push({
                            productPrefix : supplier_id+productId+variant_id,
                        });
                    });

                    $.each(supplierProducts, function (key, sProduct) {
                        var createPrefix = sProduct.supplier_id+''+sProduct.product_id+''+sProduct.product_variant_id;
                        console.log('prefix-'+createPrefix);
                        var sameProduct = rows.filter(function (row) {
                           return row.productPrefix == createPrefix; 
                        });

                        if (sameProduct.length == 0) {
                            var tr = '';
                            tr += '<tr>';
                            tr += '<td>';
                            tr += '<span class="span_product_name">'+sProduct.product.name+'</span>';  
                            if (sProduct.product_variant_id != null) {
                                tr += '<span class="span_variant_name">'+' - '+sProduct.variant.variant_name+'</span>';
                            }else{
                                tr += '<span class="span_product_code"></span>';
                            } 

                            if (sProduct.product_variant_id != null) {
                                    tr += '<span class="span_product_code">'+' ('+sProduct.variant.variant_code+')'+'</span>';
                            }else{
                                tr += '<span class="span_product_code">'+' ('+sProduct.product.product_code+')'+'</span>';
                            }

                            var variant_id = sProduct.product_variant_id != null ? sProduct.product_variant_id : null;
                            tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+sProduct.product.id+sProduct.supplier_id+variant_id+'" id="product_id" value="'+sProduct.product.id+'">';
                            tr += '<input type="hidden" name="product_name" id="product_name" value="'+sProduct.product.name+'">';

                            if (sProduct.product_variant_id != null) {
                                tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="'+sProduct.variant.id+'">';
                                tr += '<input type="hidden" name="product_variant" id="product_variant" value="'+sProduct.variant.variant_name+'">';
                                tr += '<input type="hidden" class="productCode-'+sProduct.variant.variant_code+'" name="product_code" id="product_code" value="'+sProduct.variant.variant_code+'">';
                                tr += '<input type="hidden" name="product_price" id="product_price" value="'+sProduct.variant.variant_price+'">';
                            } else {
                                tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="noid">';
                                tr += '<input type="hidden" name="product_variant" id="product_variant" value="">';
                                tr += '<input type="hidden" class="productCode-'+sProduct.product.product_code+'" name="product_code" id="product_code" value="'+sProduct.product.product_code+'">';
                                
                                tr += '<input type="hidden" name="product_price" id="product_price" value="'+sProduct.product.product_price+'">'; 
                            }
                            var tax = sProduct.product.tax != null ? sProduct.product.tax.tax_percent : 0.00 ;
                            tr += '<input type="hidden" name="product_tax" id="product_tax" value="'+tax+'">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span class="span_supplier_name">'+ sProduct.supplier.name +'</span>';
                            tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+sProduct.supplier_id+'">';
                            tr += '<input type="hidden" name="supplier_name" id="supplier_name" value="'+sProduct.supplier.name+'">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input type="number" name="left_qty" class="form-control form-control-sm" id="left_qty" value="'+1+'">';
                            tr += '<input type="hidden" name="barcode_type" id="barcode_type" value="'+sProduct.product.barcode_type+'">';
                            tr += '</td>';
                            tr += '<td class="text-center">';
                            tr += '<a href="#" class="btn btn-sm btn-danger remove_btn float-right ml-1">X</a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#barcode_product_list').append(tr);
                        }
                    });
                }
            });
        });

        //Get Seleled product requested by ajax 
        $(document).on('click', '.select_variant_product', function(e) {
            e.preventDefault();
            var product_id = $(this).data('p_id');
            var variant_id = $(this).data('v_id');
            $.ajax({
                url:"{{url('product/barcode/get/selected/product/variant')}}"+"/"+product_id+"/"+variant_id,
                type:'get',
                success:function(supplierProducts){
                    console.log(supplierProducts);
                    var productIds = document.querySelectorAll('#product_id');
                    var rows = [
                        {
                            productPrefix : 78555858,
                        },
                    ];
                    productIds.forEach(function (product_id) {
                        var productId = product_id.value;
                        var className = product_id.getAttribute('class');
                        console.log('Class_name-'+className);
                        var tr = $('.'+className).closest('tr');
                        var supplier_id = tr.find('#supplier_id').val();
                        //console.log('supplier_id-'+supplier_id);
                        var variant_id = tr.find('#product_variant_id').val() != 'noid' ? tr.find('#product_variant_id').val() : null;
                        console.log('variant_id-'+variant_id);
                        rows.push({
                            productPrefix : supplier_id+productId+variant_id,
                        });
                    });

                    $.each(supplierProducts, function (key, sProduct) {
                        var createPrefix = sProduct.supplier_id+''+sProduct.product_id+''+sProduct.product_variant_id;
                        console.log('prefix-'+createPrefix);
                        var sameProduct = rows.filter(function (row) {
                           return row.productPrefix == createPrefix; 
                        });

                        if (sameProduct.length > 0) {
                           alert('This variant is exists in barcode table.'); 
                           return;
                        }
                        if (sameProduct.length == 0) {
                            var tr = '';
                            tr += '<tr>';
                            tr += '<td>';
                            tr += '<span class="span_product_name">'+sProduct.product.name+'</span>';  
                            if (sProduct.product_variant_id != null) {
                                tr += '<span class="span_variant_name">'+' - '+sProduct.variant.variant_name+'</span>';
                            }else{
                                tr += '<span class="span_product_code"></span>';
                            } 

                            if (sProduct.product_variant_id != null) {
                                    tr += '<span class="span_product_code">'+' ('+sProduct.variant.variant_code+')'+'</span>';
                            }else{
                                tr += '<span class="span_product_code">'+' ('+sProduct.product.product_code+')'+'</span>';
                            }
                            
                            var variant_id = sProduct.product_variant_id != null ? sProduct.product_variant_id : null;
                            tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+sProduct.product.id+sProduct.supplier_id+variant_id+'" id="product_id" value="'+sProduct.product.id+'">';
                            tr += '<input type="hidden" name="product_name" id="product_name" value="'+sProduct.product.name+'">';
                            var tax = sProduct.product.tax != null ? sProduct.product.tax.tax_percent : 0.00 ;
                            if (sProduct.product_variant_id != null) {
                                tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="'+sProduct.variant.id+'">';
                                tr += '<input type="hidden" name="product_variant" id="product_variant" value="'+sProduct.variant.variant_name+'">';
                                tr += '<input type="hidden" class="productCode-'+sProduct.variant.variant_code+'" name="product_code" id="product_code" value="'+sProduct.variant.variant_code+'">';
                                price_inc_tax = parseFloat(sProduct.variant.variant_price) / 100 * parseFloat(tax) + parseFloat(sProduct.variant.variant_price);
                                tr += '<input type="hidden" name="product_price" id="product_price" value="'+parseFloat(price_inc_tax).toFixed(2)+'">';
                            } else {
                                tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="noid">';
                                tr += '<input type="hidden" name="product_variant" id="product_variant" value="">';
                                tr += '<input type="hidden" class="productCode-'+sProduct.product.product_code+'" name="product_code" id="product_code" value="'+sProduct.product.product_code+'">';
                                
                                var price_inc_tax = parseFloat(sProduct.product.product_price) / 100 * parseFloat(tax) + parseFloat(sProduct.product.product_price);
                                tr += '<input type="hidden" name="product_price" id="product_price" value="'+parseFloat(price_inc_tax).toFixed(2) +'">'; 
                            }
                            
                            tr += '<input type="hidden" name="product_tax" id="product_tax" value="'+tax+'">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<span class="span_supplier_name">'+ sProduct.supplier.name +'</span>';
                            tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+sProduct.supplier_id+'">';
                            tr += '<input type="hidden" name="supplier_name" id="supplier_name" value="'+sProduct.supplier.name+'">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input type="number" name="left_qty" class="form-control form-control-sm" id="left_qty" value="'+1+'">';
                            tr += '<input type="hidden" name="barcode_type" id="barcode_type" value="'+sProduct.product.barcode_type+'">';
                            tr += '</td>';
                            tr += '<td class="text-center">';
                            tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ml-1">X</a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#barcode_product_list').append(tr);
                        }
                    });
                }
            });
        });

        // Dispose Select area 
        $(document).on('click', '.remove_select_area_btn', function(e){
            e.preventDefault();
            $('.select_area').hide();
        });

        // Generate confirm request send by ajax
        $(document).on('click', '.remove_btn',function (e) {
            e.preventDefault();
            var tr = $(this).closest('tr').remove(); 
        })

         // Show sweet alert for multiple generate completed
         $(document).on('click', '.multiple_completed',function(e){
            e.preventDefault();
            $('#action').val('multipla_deactive');
            swal({
                title: "Are you sure ?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) { 
                    $('#multiple_completed_form').submit();
                } else {
                    swal("Your imaginary file is safe!");
                }
            });
        });

        //Multiple generate completed requested by ajax
        $(document).on('submit', '#multiple_completed_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            console.log(url);
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    if(!$.isEmptyObject(data.errorMsg)){
                        toastr.error(data.errorMsg, 'Attention');
                    }else{
                        toastr.success(data, 'SUCCESS');
                        $('.multiple_completed').remove();
                        var allRecents = document.querySelectorAll('#recents');
                        allRecents.forEach(function (recent) {
                            var className = recent.getAttribute('class');
                            $('.'+className).closest('tr').remove();
                        });
                    }
                }
            });
        });
        
        $(document).on('change','.all',function(){
            console.log('OK');
            if($(this).is(':CHECKED', true)){
                $('.data_id').prop('checked', true); 
            }else{
                $('.data_id').prop('checked', false);  
            }
        });
     
    </script>
@endpush 
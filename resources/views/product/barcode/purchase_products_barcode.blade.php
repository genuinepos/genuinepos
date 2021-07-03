@extends('layout.master')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <!-- Golmenu area -->
            <div class="menu_popup_area">
                <div class="menu_close">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="" id="close_popup_btn" class="ml-4"><i class="fas fa-times text-muted"></i></a>
                        </div>
                    </div>
                </div>
                <div class="menu_list_area">
                    
                </div>
            </div>
            <!-- Golmenu area end-->
            <div class="container">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title">Generate Barcode Label ee</h3>
                    </div>
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
                                    <p class="p-0 m-0"><strong>Purchased Product List </strong></p>
                                </div>
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
                                </table>
                            </div>

                            <div class="extra_label">
                                <div class="form-group">
                                    <div class="row">
                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_price" class="form-control form-control-sm" id="is_price" value="1"> &nbsp; Price Price. &nbsp;  </h6> 
    
                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_product_name" class="form-control form-control-sm"  id="is_product_name" value="1"> &nbsp; Product Name &nbsp; </h6>
    
                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_product_variant" class="form-control form-control-sm" id="is_product_variant" value="0"> &nbsp; Product Variant &nbsp; </h6>

                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_tax" class="form-control form-control-sm" id="is_tax" value="0"> &nbsp; Product Tax &nbsp; </h6>

                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_business_name" class="form-control form-control-sm" id="is_business_name" value="0"> &nbsp; Business Name &nbsp; </h6>

                                        &nbsp;&nbsp;&nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input checked type="checkbox" name="is_supplier_name" class="form-control form-control-sm" id="is_supplier_name" value="0"> &nbsp; Supplier Prefix &nbsp; </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn loading_button btn-sm"><i class="fas fa-spinner"></i> <strong>Loading</strong> </button>
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
    </div>

    <div class="barcodes d-none">
        {{-- <div class="barcode_conatiner">
            <div class="product_name">
                <h6>Product Name</h6>
            </div>
            <div class="row barcode_row">
                <div class="col-md-3">
                    <svg id="barcode"></svg>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('public') }}/assets/plugins/custom/barcode/JsBarcode.all.min.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/printme/jquery-printme.min.js"></script>
    <script>
        // Get all Purchase product products
        console.log('IN');
        function getPurchaseProducts(){
            $('.data_preloader').show();
            $.ajax({
                url:"{{route('barcode.get.purchase.products', $purchaseId)}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(purchaseProducts){
                    console.log(purchaseProducts);
                    $.each(purchaseProducts, function (key, purchaseProduct) {
                        var tr = '';
                        tr += '<tr>';
                        tr += '<td>';
                        tr += '<span class="span_product_name">'+purchaseProduct.product.name+'</span>';  
                        if (purchaseProduct.product_variant_id != null) {
                            tr += '<span class="span_variant_name">'+' - '+purchaseProduct.variant.variant_name+'</span>';
                        }else{
                            tr += '<span class="span_product_code"></span>';
                        } 
                            
                        if (purchaseProduct.product_variant_id != null) {
                            tr += '<span class="span_product_code">'+' ('+purchaseProduct.variant.variant_code+')'+'</span>';
                        }else{
                            tr += '<span class="span_product_code">'+' ('+purchaseProduct.product.product_code+')'+'</span>';
                        }
                        
                        var variant_id = purchaseProduct.product_variant_id != null ? purchaseProduct.product_variant_id : null;
                        tr += '<input type="hidden" name="product_ids[]" class="productPrefix-'+purchaseProduct.product.id+purchaseProduct.purchase.supplier_id+variant_id+'" id="product_id" value="'+purchaseProduct.product.id+'">';
                        tr += '<input type="hidden" name="product_name" id="product_name" value="'+purchaseProduct.product.name+'">';

                        if (purchaseProduct.product_variant_id != null) {
                            tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="'+purchaseProduct.product_variant_id+'">';
                            tr += '<input type="hidden" name="product_variant" id="product_variant" value="'+purchaseProduct.variant.variant_name+'">';
                            tr += '<input type="hidden" class="productCode-'+purchaseProduct.variant.variant_code+'" name="product_code" id="product_code" value="'+purchaseProduct.variant.variant_code+'">';
                            var price_inc_tax = parseFloat(purchaseProduct.selling_price) / 100 * parseFloat(purchaseProduct.unit_tax_percent) + parseFloat(purchaseProduct.selling_price);
                            tr += '<input type="hidden" name="product_price" id="product_price" value="'+parseFloat(price_inc_tax).toFixed(2) +'">';
                        } else {
                            tr += '<input type="hidden" name="product_variant_ids[]" id="product_variant_id" class="variantId-" value="noid">';
                            tr += '<input type="hidden" name="product_variant" id="product_variant" value="">';
                            tr += '<input type="hidden" class="productCode-'+purchaseProduct.product.product_code+'" name="product_code" id="product_code" value="'+purchaseProduct.product.product_code+'">';
                            var price_inc_tax = parseFloat(purchaseProduct.selling_price) / 100 * parseFloat(purchaseProduct.unit_tax_percent) + parseFloat(purchaseProduct.selling_price);
                            tr += '<input type="hidden" name="product_price" id="product_price" value="'+parseFloat(price_inc_tax).toFixed(2)+'">'; 
                        }
                        
                        tr += '<input type="hidden" name="product_tax" id="product_tax" value="'+purchaseProduct.unit_tax_percent+'">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<span class="span_supplier_name">'+ purchaseProduct.purchase.supplier.name +'</span>';
                        tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="'+purchaseProduct.purchase.supplier.id+'">';
                        tr += '<input type="hidden" name="supplier_name" id="supplier_name" value="'+purchaseProduct.purchase.supplier.prefix+'">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<input type="number" name="left_qty" class="form-control form-control-sm" id="left_qty" value="'+purchaseProduct.quantity+'">';
                        tr += '<input type="hidden" name="barcode_type" id="barcode_type" value="'+purchaseProduct.product.barcode_type+'">';
                        tr += '</td>';
                        tr += '<td class="text-center">';
                        tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ml-1">X</a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#barcode_product_list').append(tr);
                    });
                }
            });
        }
        getPurchaseProducts();

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
                            tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ml-1">X</a>';
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
        });
    </script>
@endpush 
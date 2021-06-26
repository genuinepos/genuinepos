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
                                        <input type="text" name="search_product" class="form-control " autocomplete="off" id="search_product" placeholder="Search Product by Product name / Product code(SKU)">
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
                                <table class="table modal-table table-sm">
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
                                tr += '<input type="number" name="left_qty" class="form-control " id="left_qty" value="'+product.label_qty+'">';
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
    </script>
@endpush 
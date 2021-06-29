@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 88.3%;z-index: 9999999;padding: 0;left: 6%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
        .selectProduct {background-color: #ab1c59;color: #fff !important;}
        .input-group-text-sale {font-size: 7px !important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_sale_form" action="{{ route('sales.update', $saleId) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Edit Sale</h5>
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
                                                <label for="inputEmail3" class=" col-4"><b>Customer :</b> </label>
                                                <div class="col-8">
                                                    <div class="input-group width-60">
                                                        <input readonly type="text" value="" id="customer_name" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><span
                                                        class="text-danger">*</span> <b>Warehosue :</b></label>
                                                    <div class="col-8">
                                                        <select name="warehouse_id" data-name="Warehouse"
                                                            class="form-control add_input" id="warehouse_id">
                                                            @foreach ($warehouses as $warehouse)
                                                                <option {{ $sale->warehouse_id == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name.' - '.$warehouse->warehouse_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                        <input type="hidden" name="warehouse_id" id="req_warehouse_id"
                                                            value="">
                                                    </div>
                                                </div>
                                            @else
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><span
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
                                                <label for="inputEmail3" class="col-4"><b>Invoice ID :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><b>Attachment :</b>
                                                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice related any file.Ex: Scanned cheque, payment prove file etc." class="fas fa-info-circle tp"></i></label>
                                                <div class="col-8">
                                                    <input type="file" name="attachment" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4">Status : <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select name="status" class="form-control add_input" data-name="Status"
                                                        id="status">
                                                        <option value="">Select status</option>
                                                        <option value="1">Final</option>
                                                        <option value="2">Draft</option>
                                                        <option value="4">Quatation</option>
                                                    </select>
                                                    <span class="error error_status"></span>
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><b>Price Group :</b></label>
                                                <div class="col-8">
                                                    <select name="price_group_id" class="form-control"
                                                        id="price_group_id">
                                                        <option value="">Default Selling Price</option>
                                                        @foreach ($price_groups as $pg)
                                                            <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Sale Date :</label>
                                                <div class="col-8">
                                                    <input type="date" name="date" class="form-control"
                                                        value="{{ date('Y-m-d', strtotime($sale->date)) }}">
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
                            <div class="col-md-9">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="searching_area" style="position: relative;">
                                                <label for="inputEmail3" class="col-form-label">Item Search</label>
                                                <div class="input-group ">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-barcode text-dark"></i></span>
                                                    </div>
                                                    <input type="text" name="search_product" class="form-control scanable"
                                                        autocomplete="off" id="search_product"
                                                        placeholder="Search Product by product code(SKU) / Scan bar code" autofocus>
                                                    <div class="input-group-prepend">
                                                        <span id="add_product" class="input-group-text add_button"><i
                                                                class="fas fa-plus-square text-dark"></i></span>
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

                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">Product</th>
                                                                    <th></th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th>Unit</th>
                                                                    <th class="text-center">Price Inc.Tax</th>
                                                                    <th>SubTotal</th>
                                                                    <th><i class="fas fa-trash-alt text-danger"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="sale_list">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="item-details-sec mt-2 payment_body">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Ship Details :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="Shipment Details">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Ship Address :</b></label>
                                                    <div class="col-8">
                                                        <input name="shipment_address" type="text" class="form-control" id="shipment_address" placeholder="Shipment Address"> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Ship Status :</b></label>
                                                    <div class="col-8">
                                                        <select name="shipment_status" class="form-control" id="shipment_status">
                                                            <option value="">Shipment Status</option>
                                                            <option value="1">Ordered</option>
                                                            <option value="2">Packed</option>
                                                            <option value="3">Shipped</option>
                                                            <option value="4">Delivered</option>
                                                            <option value="5">Cancelled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class=" col-4"><b>Delivered To :</b></label>
                                                    <div class="col-8">
                                                        <input name="delivered_to" type="text" class="form-control" id="delivered_to" placeholder="Delivered To"> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="item-details-sec mb-3">
                                    <div class="content-inner">
                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Total Item :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" name="total_item" id="total_item" class="form-control" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Net Total :</label>
                                            <div class="col-sm-7">
                                                <input readonly type="number" class="form-control" name="net_total_amount" id="net_total_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Discount:</label>
                                            <div class="col-sm-3">
                                                <select name="order_discount_type" class="form-control" id="order_discount_type">
                                                    <option value="1">Fixed</option>
                                                    <option value="2">Percentage</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input name="order_discount" type="number" class="form-control" id="order_discount" value="0.00"> 
                                                <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount" value="0.00"> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Order Tax :</label>
                                            <div class="col-sm-7">
                                                <select name="order_tax" class="form-control" id="order_tax">
                                                    
                                                </select>
                                                <input type="number" step="any" class="d-none" name="order_tax_amount" id="order_tax_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Shipment Cost:</label>
                                            <div class="col-sm-7">
                                                <input name="shipment_charge" type="number" class="form-control" id="shipment_charge" value="0.00"> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Previous Due :</label>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="number" step="any" name="previous_due" id="previous_due" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Total Payable:</label>
                                            <div class="col-sm-7">
                                                <input readonly class="form-control" type="number" step="any" name="total_payable_amount" id="total_payable_amount" value="0.00">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="inputEmail3" class="col-sm-5 col-form-label">Sale Note:</label>
                                            <div class="col-sm-7">
                                                <input name="sale_note" type="text" class="form-control" id="sale_note" placeholder="Sale note">
                                            </div>
                                        </div>

                                        <div class="submitBtn">
                                            <div class="row justify-content-center">
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i> <strong>Loading...</strong> </button>
                                                    <button type="submit" class="btn btn-sm btn-primary submit_button">Save Change </button>
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
   
    <!-- Edit selling product modal-->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info">Samsung A30 - Black-4GB-64GB - (black-4gb-64gb-85554687)</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="update_selling_product" action="">
                        <div class="form-group">
                            <label> <strong>Quantity</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" step="any" readonly class="form-control edit_input" data-name="Quantity" id="e_quantity" placeholder="Quantity"/>
                            <span class="error error_e_quantity"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label> <strong>Unit Price Exc.Tax</strong>  : <span class="text-danger">*</span></label>
                            <input type="number" step="any" {{ auth()->user()->permission->sale['edit_price_sale_screen'] == '1' ? '' : 'readonly' }} step="any" class="form-control edit_input" data-name="Unit price" id="e_unit_price" placeholder="Unit price"/>
                            <span class="error error_e_unit_price"></span>
                        </div>

                        @if (auth()->user()->permission->sale['edit_discount_sale_screen'] == '1')
                            <div class="form-group row mt-1">
                                <div class="col-md-6">
                                    <label><strong>Discount Type</strong>  :</label>
                                    <select class="form-control " id="e_unit_discount_type">
                                        <option value="2">Percentage</option>
                                        <option value="1">Fixed</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><strong>Discount</strong>  :</label>
                                    <input type="number" step="any" class="form-control " id="e_unit_discount" value="0.00"/>
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
                                <label><strong>Tax Type</strong>  :</label>
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

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="c-btn btn_blue float-end me-0">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 
    <!-- Edit selling product modal End-->

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
@endsection
@push('scripts')
    <script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
    <script>
        // Get all price group
        var price_groups = '';
        function getPriceGroupProducts(){
            $.ajax({
                url:"{{route('sales.product.price.groups')}}",
                success:function(data){
                    price_groups = data;
                }
            });
        }
        getPriceGroupProducts();

        // Get all unite for form field
        var unites = [];
        function getUnites(){
            $.get("{{ route('purchases.get.all.unites') }}", function(units) {
                $.each(units, function(key, unit){
                    unites.push(unit.name); 
                });
            });
        }
        getUnites();

        // Get all taxes for form field
        var taxArray;
        function getTaxes(){
            $.get("{{ route('purchases.get.all.taxes') }}", function(taxes) {
                taxArray = taxes;
                $('#order_tax').append('<option value="0.00">No Tax</option>');
                $.each(taxes, function(key, val){
                    $('#order_tax').append('<option value="'+val.tax_percent+'">'+val.tax_name+'</option>');
                });
            });
        }
        getTaxes();

        // Calculate total amount functionalitie
        function calculateTotalAmount(){
            var quantities = document.querySelectorAll('#quantity');
            var subtotals = document.querySelectorAll('#subtotal');
            // Update Total Item
            var total_item = 0;
            quantities.forEach(function(qty){
                total_item += 1;
            });

            $('#total_item').val(parseFloat(total_item));

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
                var orderDiscount = $('#order_discount').val() ? $('#order_discount').val() : 0
                $('#order_discount_amount').val(parseFloat(orderDiscount).toFixed(2));
            }
            
            // Calc order tax amount
            var orderDiscountAmount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
            var orderTax = $('#order_tax').val() ? $('#order_tax').val() : 0;
            var calcOrderTaxAmount = (parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount)) / 100 * parseFloat(orderTax) ;
            $('#order_tax_amount').val(parseFloat(calcOrderTaxAmount).toFixed(2));
            
            // Update Total payable Amount
            var shipmentCharge = $('#shipment_charge').val() ? $('#shipment_charge').val() : 0;
            var calcTotalPayableAmount = parseFloat(netTotalAmount) - parseFloat(orderDiscountAmount) + parseFloat(calcOrderTaxAmount) + parseFloat(shipmentCharge);
            $('#total_payable_amount').val(parseFloat(calcTotalPayableAmount).toFixed(2));
        }

        var delay = (function() {
            var timer = 0;
            return function(callback, ms) {
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();

        var unique_index = 0;
        $('#search_product').on('input', function(e) {
            $('.variant_list_area').empty();
            $('.select_area').hide();
            var product_code = $(this).val();
            var warehouse_id = $('#warehouse_id').val();
            var branch_id = $('#branch_id').val();
            var role = $('#role').val();
            if(warehouse_id == ""){
                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }
            delay(function() { searchProduct(product_code, branch_id, warehouse_id); }, 200); //sendAjaxical is the name of remote-command
        });

        // add Sale product by searching product code
        function searchProduct(product_code, branch_id){
            var price_group_id = $('#price_group_id').val();
            $('.variant_list_area').empty();
            $('.select_area').hide();
            var warehouse_id = $('#warehouse_id').val();
            if(warehouse_id == ""){
                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }

            $.ajax({
                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) 
                    url:"{{ url('sales/search/product/in/warehouse') }}"+"/"+product_code+"/"+warehouse_id,
                @else
                    url:"{{ url('sales/search/product') }}"+"/"+product_code+"/"+branch_id,
                @endif
                dataType: 'json',
                success:function(product){
                    if(!$.isEmptyObject(product.errorMsg)){
                        toastr.error(product.errorMsg); 
                        $('#search_product').val("");
                        return;
                    }
                    var qty_limit = product.qty_limit;
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
                                        var presentQty = closestTr.find('#quantity').val();
                                        var previousQty = closestTr.find('#previous_quantity').val();
                                        var limit = closestTr.find('#qty_limit').val()
                                        var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){
                                            alert('Quantity exceeds stock quantity!');
                                            return;
                                        }
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                        
                                        //Update Subtotal
                                        var unitPrice = closestTr.find('#unit_price').val();
                                        var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                        closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                        closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                        calculateTotalAmount();
                                        productTable();
                                        return;
                                    }
                                });

                                if(sameProduct == 0){
                                    var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                    var price = 0;
                                    var __price = price_groups.filter(function (value) {
                                        return value.price_group_id == price_group_id && value.product_id == product.id;
                                    });

                                    if (__price.length != 0) {
                                        price = __price[0].price ? __price[0].price : product.product_price;
                                    } else {
                                        price = product.product_price;
                                    }
                                    var tax_amount = parseFloat(price / 100 * tax_percent);
                                    var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                                    if (product.tax_type == 2) {
                                        var inclusiveTax = 100 + parseFloat(tax_percent)
                                        var calcAmount = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                        tax_amount = parseFloat(price) - parseFloat(calcAmount);
                                        var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                                    }
                                    var tr = '';
                                    tr += '<tr>';
                                    tr += '<td colspan="2" class="text-start">';
                                    tr += '<a href="#" class="text-success" id="edit_product">';
                                    tr += '<span class="product_name">'+ product.name +'</span>';
                                    tr += '<span class="product_variant"></span>'; 
                                    tr += '<span class="product_code">'+ ' ('+product.product_code+')' +'</span>';
                                    tr += '</a><br/><input type="'+ (product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here.">';
                                    tr += '<input value="'+ product.id +'" type="hidden" class="productId-'+ product.id +'" id="product_id" name="product_ids[]">';
                                    tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                                    tr += '<input value="'+ product.tax_type +'" type="hidden" id="tax_type">';
                                    tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+ tax_percent +'">';
                                    tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+ parseFloat(tax_amount).toFixed(2) +'">';
                                    tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                    tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                    tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                    tr += '<input value="'+ product.product_cost_with_tax +'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                                    tr += '<input type="hidden" id="previous_quantity" value="0">';
                                    tr += '<input type="hidden" id="qty_limit" value="'+ qty_limit +'">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<div class="input-group">';
                                    tr += '<div class="input-group-prepend">';
                                    tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                                    tr += '</div>';
                                    tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                                    tr += '<div class="input-group-prepend">';
                                    tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                                    tr += '</div>';
                                    tr += '</div>';
                                    tr += '</td>';
                                    tr += '<td class="text">';
                                    tr += '<span class="span_unit">'+ product.unit.name +'</span>'; 
                                    tr += '<input  name="units[]" type="hidden" id="unit" value="'+ product.unit.name +'">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input readonly name="unit_prices_exc_tax[]" type="hidden"  id="unit_price_exc_tax" value="'+ parseFloat(price).toFixed(2) +'">';
                                    tr += '<input readonly name="unit_prices[]" type="text" class="form-control form-control-sm text-center" id="unit_price" value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'">';
                                    tr += '</td>';

                                    tr += '<td class="text text-center">';
                                    tr += '<strong><span class="span_subtotal">'+ parseFloat(unitPriceIncTax).toFixed(2) +' </span></strong>'; 
                                    tr += '<input value="'+ parseFloat(unitPriceIncTax).toFixed(2) +'" readonly name="subtotals[]" type="hidden"  id="subtotal">';
                                    tr += '</td>';
                                    tr += '<td class="text-center">';
                                    tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                    tr += '</td>';
                                    tr += '</tr>';
                                    $('#sale_list').append(tr);
                                    calculateTotalAmount(); 
                                    productTable();  
                                }
                            }else{
                                var li = "";
                                var imgUrl = "{{ asset('public/uploads/product/thumbnail') }}";
                                var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;
                                $.each(product.product_variants, function(key, variant){
                                    var tax_amount = parseFloat(variant.variant_price/100 * tax_percent);
                                    var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price);
                                    if (product.tax_type == 2) {
                                       var inclusiveTax = 100 + parseFloat(tax_percent);
                                       var calcTax = parseFloat(variant.variant_price) / parseFloat(inclusiveTax) * 100;
                                       var __tax_amount = parseFloat(variant.variant_price) - parseFloat(calcTax);
                                        unitPriceIncTax = parseFloat(variant.variant_price) + parseFloat(__tax_amount);
                                        tax_amount = __tax_amount;
                                    }
                                    li += '<li class="mt-1">';
                                    li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'" data-tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-v_price="'+variant.variant_price+'" data-v_name="'+variant.variant_name+'" data-v_cost_inc_tax="'+variant.variant_cost_with_tax+'" href="#"><img style="width:30px; height:30px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                    li += '</li>';
                                });
                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        }else if(!$.isEmptyObject(product.variant_product)){
                            $('.select_area').hide();
                            $('#search_product').val('');
                            var variant_product = product.variant_product;
                            var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.tax_percent : 0;
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;
                            variant_ids.forEach(function(input){
                                if(input.value != 'noid'){
                                    if(input.value == variant_product.id){
                                        sameVariant += 1;
                                        var className = input.getAttribute('class');
                                        // get closest table row for increasing qty and re calculate product amount
                                        var closestTr = $('.'+className).closest('tr');
                                        var presentQty = closestTr.find('#quantity').val();
                                        var previousQty = closestTr.find('#previous_quantity').val();
                                        var limit = closestTr.find('#qty_limit').val()
                                        var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                                        if(parseFloat(qty_limit) == parseFloat(presentQty)){
                                            alert('Quantity exceeds stock quantity!');
                                            return;
                                        }
                                        var updateQty = parseFloat(presentQty) + 1;
                                        closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                        
                                        //Update Subtotal
                                        var unitPrice = closestTr.find('#unit_price').val();
                                        var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                        closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                        closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                        calculateTotalAmount();
                                        productTable();
                                        return;
                                    }
                                }    
                            });
                           
                            if(sameVariant == 0){
                                var price = 0;
                                var __price = price_groups.filter(function (value) {
                                    return value.price_group_id == price_group_id && value.product_id == variant_product.product.id && value.variant_id == variant_product.id;
                                });

                                if (__price.length != 0) {
                                    price = __price[0].price ? __price[0].price : variant_product.variant_price;
                                } else {
                                    price = variant_product.variant_price;
                                }
                                var tax_amount = parseFloat(price / 100 * tax_percent);
                                var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                                if (variant_product.product.tax_type == 2) {
                                    var inclusiveTax = 100 + parseFloat(tax_percent)
                                    var calcAmount = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                    tax_amount = parseFloat(price) - parseFloat(calcAmount);
                                    var unitPriceIncTax = parseFloat(price) + parseFloat(tax_amount);
                                }
                                var tr = '';
                                tr += '<tr>';
                                tr += '<td colspan="2" class="text-start">';
                                tr += '<a href="#" class="text-success" id="edit_product">';
                                tr += '<span class="product_name">'+variant_product.product.name+'</span>';
                                tr += '<span class="product_variant">'+' -'+variant_product.variant_name+'- '+'</span>'; 
                                tr += '<span class="product_code">'+'('+variant_product.variant_code+')'+'</span>';
                                tr += '</a><br/><input type="'+(variant_product.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here.">';
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                tr += '<input value="'+variant_product.product.tax_type+'" type="hidden" id="tax_type">';
                                tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                                tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                                tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                                tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                                tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                                tr += '<input value="'+variant_product.variant_cost_with_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                                tr += '<input type="hidden" id="previous_quantity" value="0">';
                                tr += '<input type="hidden" id="qty_limit" value="'+qty_limit+'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<div class="input-group">';
                                tr += '<div class="input-group-prepend">';
                                tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                                tr += '</div>';
                                tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center form-control-sm" id="quantity">';
                                tr += '<div class="input-group-prepend">';
                                tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                                tr += '</div>';
                                tr += '</div>';
                                tr += '</td>';
                                tr += '<td class="text">';
                                tr += '<span class="span_unit">'+variant_product.product.unit.name+'</span>'; 
                                tr += '<input  name="units[]" type="hidden" id="unit" value="'+variant_product.product.unit.name+'">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+parseFloat(price).toFixed(2)+'" id="unit_price_exc_tax">';
                                tr += '<input readonly name="unit_prices[]" type="text" class="form-control form-control-sm text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2) +'">';
                                tr += '</td>';

                                tr += '<td class="text text-center">';
                                tr += '<strong><span class="span_subtotal">'+parseFloat(unitPriceIncTax).toFixed(2)+'</span></strong>'; 
                                tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                                tr += '</td>';

                                tr += '<td class="text-center">';
                                tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                                tr += '</td>';
                                tr += '</tr>';
                                $('#sale_list').append(tr);
                                calculateTotalAmount();
                                productTable();
                            }    
                        }else if (!$.isEmptyObject(product.namedProducts)) {
                            if(product.namedProducts.length > 0){
                                var li = "";
                                var imgUrl = "{{asset('public/uploads/product/thumbnail')}}";
                                var products = product.namedProducts; 
                                $.each(products, function (key, product) {
                                    var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                    if (product.product_variants.length > 0) {
                                        $.each(product.product_variants, function(key, variant){
                                            var tax_amount = parseFloat(variant.variant_price/100 * tax_percent);
                                            var unitPriceIncTax = (parseFloat(variant.variant_price) / 100 * tax_percent) + parseFloat(variant.variant_price);
                                            if (product.tax_type == 2) {
                                                var inclusiveTax = 100 + parseFloat(tax_percent);
                                                var calcTax = parseFloat(variant.variant_price) / parseFloat(inclusiveTax) * 100;
                                                var __tax_amount = parseFloat(variant.variant_price) - parseFloat(calcTax);
                                                unitPriceIncTax = parseFloat(variant.variant_price) + parseFloat(__tax_amount);
                                                tax_amount = __tax_amount;
                                            }
                                            li += '<li>';
                                            li += '<a class="select_variant_product" onclick="salectVariant(this); return false;" data-p_id="'+product.id+'" data-v_id="'+variant.id+'" data-p_name="'+product.name+'" data-p_tax_id="'+product.tax_id+'" data-unit="'+product.unit.name+'" data-tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'" data-tax_amount="'+tax_amount+'" data-v_code="'+variant.variant_code+'" data-description="'+product.is_show_emi_on_pos+'" data-v_price="'+variant.variant_price+'" data-v_name="'+variant.variant_name+'" data-v_cost_inc_tax="'+variant.variant_cost_with_tax+'" href="#"><img style="width:30px; height:30px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' - '+variant.variant_name+' ('+variant.variant_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                            li +='</li>';
                                        });
                                    }else{
                                        var tax_amount = parseFloat(product.product_price/100 * tax_percent);
                                        var unitPriceIncTax = (parseFloat(product.product_price) / 100 * tax_percent) + parseFloat(product.product_price);
                                        if (product.tax_type == 2) {
                                            var inclusiveTax = 100 + parseFloat(tax_percent);
                                            var calcTax = parseFloat(product.product_price) / parseFloat(inclusiveTax) * 100;
                                            var __tax_amount = parseFloat(product.product_price) - parseFloat(calcTax);
                                            unitPriceIncTax = parseFloat(product.product_price) + parseFloat(__tax_amount);
                                            tax_amount = __tax_amount;
                                        }

                                        li += '<li>';
                                        li += '<a class="select_single_product" onclick="singleProduct(this); return false;" data-p_id="'+product.id+'" data-p_name="'+product.name+'" data-unit="'+product.unit.name+'" data-p_code="'+product.product_code+'" data-p_price_exc_tax="'+product.product_price+'" data-description="'+product.is_show_emi_on_pos+'" data-p_tax_percent="'+tax_percent+'" data-tax_type="'+product.tax_type+'"  data-p_tax_amount="'+tax_amount+'" data-p_cost_inc_tax="'+product.product_cost_with_tax+'" href="#"><img style="width:30px; height:30px;" src="'+imgUrl+'/'+product.thumbnail_photo+'"> '+product.name+' ('+product.product_code+')'+' - Price: '+parseFloat(unitPriceIncTax).toFixed(2)+'</a>';
                                        li +='</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        }
                    }else{
                        $('#search_product').addClass('is-invalid');
                        $('#search_product').val('');
                        toastr.error('Product not found.', 'Failed'); 
                    }
                }
            });
        }

        // select single product and add stock adjustment table
        var keyName = '';
        function singleProduct(e){
            var price_group_id = $('#price_group_id').val();
            $('.select_area').hide();
            $('#search_product').val('');
            if (keyName == 13 || keyName == 1) {
                document.getElementById('search_product').focus();
            }

            var warehouse_id = $('#warehouse_id').val();
            var branch_id = $('#branch_id').val();
            var product_id = e.getAttribute('data-p_id');
            var product_name = e.getAttribute('data-p_name');
            var product_code = e.getAttribute('data-p_code');
            var product_unit = e.getAttribute('data-unit');
            var product_cost_inc_tax = e.getAttribute('data-p_cost_inc_tax');
            var product_price_exc_tax = e.getAttribute('data-p_price_exc_tax');
            var p_tax_percent = e.getAttribute('data-p_tax_percent');
            var p_tax_amount = e.getAttribute('data-p_tax_amount');
            var p_tax_type = e.getAttribute('data-tax_type');
            var description = e.getAttribute('data-description');
            if(warehouse_id == ""){
                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }

            $.ajax({
                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) 
                    url:"{{ url('sales/check/single/product/stock/in/warehouse') }}"+"/"+product_id+"/"+warehouse_id,
                @else
                    url:"{{ url('sales/check/single/product/stock/') }}"+"/"+product_id+"/"+branch_id,
                @endif
                async:true,
                type:'get',
                dataType: 'json',
                success:function(singleProductQty){
                    if($.isEmptyObject(singleProductQty.errorMsg)){
                        var product_ids = document.querySelectorAll('#product_id');
                        var sameProduct = 0;
                        product_ids.forEach(function(input){
                            if(input.value == product_id){
                                sameProduct += 1;
                                var className = input.getAttribute('class');
                                // get closest table row for increasing qty and re calculate product amount
                                var closestTr = $('.'+className).closest('tr');
                                var presentQty = closestTr.find('#quantity').val();
                                var qty_limit = closestTr.find('#qty_limit').val();
                                if(parseFloat(qty_limit)  === parseFloat(presentQty)){
                                    alert('Quantity exceeds stock quantity!');
                                    return;
                                }
                                var updateQty = parseFloat(presentQty) + 1;
                                closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                
                                //Update Subtotal
                                var unitPrice = closestTr.find('#unit_price').val();
                                var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);

                                closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
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
                            var price = 0;
                            var __price = price_groups.filter(function (value) {
                                return value.price_group_id == price_group_id && value.product_id == product_id;
                            });

                            if (__price.length != 0) {
                                price = __price[0].price ? __price[0].price : product_price_exc_tax;
                            } else {
                                price = product_price_exc_tax;
                            }

                            var tr = '';
                            tr += '<tr>';
                            tr += '<td colspan="2" class="text-start">';
                            tr += '<a href="#" class="text-success" id="edit_product">';
                            tr += '<span class="product_name">'+product_name+'</span>';
                            tr += '<span class="product_variant"></span>'; 
                            tr += '<span class="product_code">'+' ('+product_code+')'+'</span>';
                            tr += '</a><br/><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here.">';
                            tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                            tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';
                            tr += '<input value="'+p_tax_type+'" type="hidden" id="tax_type">';
                            tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+p_tax_percent+'">';
                            tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(p_tax_amount).toFixed(2)+'">';
                            tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                            tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                            tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                            tr += '<input value="'+product_cost_inc_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                            tr += '<input type="hidden" id="previous_quantity" value="0">';
                            tr += '<input type="hidden" id="qty_limit" value="'+singleProductQty+'">';

                            tr += '</td>';

                            tr += '<td>';
                            tr += '<div class="input-group">';
                            tr += '<div class="input-group-prepend">';
                            tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                            tr += '</div>';
                            tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                            tr += '<div class="input-group-prepend">';
                            tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                            tr += '</div>';
                            tr += '</div>';
                            tr += '</td>';
                            tr += '<td class="text">';
                            tr += '<b><span class="span_unit">'+product_unit+'</span></b>'; 
                            tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                            tr += '</td>';
                            tr += '<td>';

                            tr += '<input readonly name="unit_prices_exc_tax[]" type="hidden" id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'">';

                            var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(p_tax_percent) + parseFloat(price);
                            if (p_tax_type == 2) {
                                var inclusiveTax = 100 + parseFloat(p_tax_percent);
                                var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                                unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                            }

                            tr += '<input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'">';
                            tr += '</td>';
                            tr += '<td class="text text-center">';
                            tr += '<strong><span class="span_subtotal"> '+parseFloat(unitPriceIncTax).toFixed(2)+' </span></strong>'; 
                            tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                            tr += '</td>';
                            tr += '<td class="text-center">';
                            tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#sale_list').prepend(tr);
                            calculateTotalAmount(); 
                            productTable();  
                            if (keyName == 9) {
                                $("#quantity").select();
                                keyName = 1;
                            }
                        }
                    }else{
                        toastr.error(singleProductQty.errorMsg);   
                    }
                }
            });
        }

        // select variant product and add purchase table
        function salectVariant(e){
            var price_group_id = $('#price_group_id').val();
            if (keyName == 13 || keyName == 1) {
                document.getElementById('search_product').focus();
            }
            
            $('.select_area').hide();
            $('#search_product').val("");
            $('#selectVairantModal').modal('hide');
            var warehouse_id = $('#warehouse_id').val();
            var branch_id = $('#branch_id').val();
            var product_id = e.getAttribute('data-p_id');
            var product_name = e.getAttribute('data-p_name');
            var tax_percent = e.getAttribute('data-tax_percent');
            var product_unit = e.getAttribute('data-unit');
            var tax_id = e.getAttribute('data-p_tax_id');
            var tax_type = e.getAttribute('data-tax_type');
            var tax_amount = e.getAttribute('data-tax_amount');
            var variant_id = e.getAttribute('data-v_id');
            var variant_name = e.getAttribute('data-v_name');
            var variant_code = e.getAttribute('data-v_code');
            var variant_cost_inc_tax = e.getAttribute('data-v_cost_inc_tax');
            var variant_price = e.getAttribute('data-v_price');
            var description = e.getAttribute('data-description');

            if(warehouse_id == ""){
                $('#search_product').val("");
                alert('Warehouse field must not be empty.');
                return;
            }

            $.ajax({
                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) 
                    url:"{{ url('sales/check/warehouse/variant/qty') }}"+"/"+product_id+"/"+variant_id+"/"+warehouse_id,
                @else
                    url:"{{ url('sales/check/branch/variant/qty/') }}"+"/"+product_id+"/"+variant_id+"/"+branch_id,
                @endif
                async:true,
                type:'get',
                dataType: 'json',
                success:function(branchVariantQty){
                    if($.isEmptyObject(branchVariantQty.errorMsg)){
                        var variant_ids = document.querySelectorAll('#variant_id');
                        var sameVariant = 0;
                        variant_ids.forEach(function(input){
                            if(input.value != 'noid'){
                                if(input.value == variant_id){
                                    sameVariant += 1;
                                    var className = input.getAttribute('class');
                                    // get closest table row for increasing qty and re calculate product amount
                                    var closestTr = $('.'+className).closest('tr');
                                    var presentQty = closestTr.find('#quantity').val();
                            
                                    var previousQty = closestTr.find('#previous_quantity').val();
                                    var limit = closestTr.find('#qty_limit').val()
                                    var qty_limit = parseFloat(previousQty) + parseFloat(limit);

                                    if(parseFloat(qty_limit)  === parseFloat(presentQty)){
                                        alert('Quantity exceeds stock quantity!');
                                        return;
                                    }
                                    var updateQty = parseFloat(presentQty) + 1;
                                    closestTr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
                                    
                                    //Update Subtotal
                                    var unitPrice = closestTr.find('#unit_price').val();
                                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(updateQty);
                                    closestTr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                                    closestTr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                                    calculateTotalAmount();
                                    productTable();
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
                            var price = 0;
                            var __price = price_groups.filter(function (value) {
                                return value.price_group_id == price_group_id && value.product_id == product_id && value.variant_id == variant_id;
                            });

                            if (__price.length != 0) {
                                price = __price[0].price ? __price[0].price : variant_price;
                            } else {
                                price = variant_price;
                            }
                            var tr = '';
                            tr += '<tr>';
                            tr += '<td colspan="2" class="text-start">';
                            tr += '<a href="#" class="text-success" id="edit_product">';
                            tr += '<span class="product_name">'+product_name+'</span>';
                            tr += '<span class="product_variant">'+' -'+variant_name+'- '+'</span>'; 
                            tr += '<span class="product_code">'+'('+variant_code+')'+'</span>';
                            tr += '</a><br/><input type="'+(description == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here.">';
                            tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                            tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
                            tr += '<input value="'+tax_type+'" type="hidden" id="tax_type">';
                            tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+tax_percent+'">';
                            tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+parseFloat(tax_amount).toFixed(2)+'">';
                            tr += '<input value="1" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                            tr += '<input value="0.00" name="unit_discounts[]" type="hidden" id="unit_discount">';
                            tr += '<input value="0.00" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                            tr += '<input value="'+variant_cost_inc_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                            tr += '<input type="hidden" id="previous_quantity" value="0">';
                            tr += '<input type="hidden" id="qty_limit" value="'+branchVariantQty+'">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<div class="input-group">';
                            tr += '<div class="input-group-prepend">';
                            tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                            tr += '</div>';
                            tr += '<input value="1.00" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                            tr += '<div class="input-group-prepend">';
                            tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                            tr += '</div>';
                            tr += '</div>';
                            tr += '</td>';
                            tr += '<td class="text">';
                            tr += '<span class="span_unit">'+product_unit+'</span>'; 
                            tr += '<input  name="units[]" type="hidden" id="unit" value="'+product_unit+'">';
                            tr += '</td>';
                            tr += '<td>';

                            tr += '<input name="unit_prices_exc_tax[]" type="hidden" id="unit_price_exc_tax" value="'+parseFloat(price).toFixed(2)+'">';
                            var unitPriceIncTax = parseFloat(price) / 100 * parseFloat(tax_percent) + parseFloat(price);
                            if (tax_type == 2) {
                                var inclusiveTax = 100 + parseFloat(tax_percent);
                                var calcTax = parseFloat(price) / parseFloat(inclusiveTax) * 100;
                                var __tax_amount = parseFloat(price) - parseFloat(calcTax);
                                unitPriceIncTax = parseFloat(price) + parseFloat(__tax_amount);
                            }
                            tr += '<input readonly name="unit_prices[]" type="text" class="form-control form-control-sm text-center" id="unit_price" value="'+parseFloat(unitPriceIncTax).toFixed(2)+'">';
                            tr += '</td>';
                            tr += '<td class="text text-center">';
                            tr += '<strong><span class="span_subtotal">'+parseFloat(unitPriceIncTax).toFixed(2)+'</span></strong>'; 
                            tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                            tr += '</td>';
                            tr += '<td class="text-center">';
                            tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                            tr += '</td>';
                            tr += '</tr>';
                            $('#sale_list').append(tr);
                            calculateTotalAmount();
                            productTable();
                            if (keyName == 9) {
                                $("#quantity").select();
                                keyName = 1;
                            }
                        }
                    }else{
                        toastr.warning(branchVariantQty.errorMsg);   
                    }
                }
            });
        }

        // Quantity increase or dicrease and clculate row amount
        $(document).on('input', '#quantity', function(){
            var qty = $(this).val() ? $(this).val() : 0;
            if (parseFloat(qty) >= 0) {
                var tr = $(this).closest('tr');
                var previousQty = tr.find('#previous_quantity').val();
                var limit = tr.find('#qty_limit').val()
                var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                if(parseInt(qty) > parseInt(qty_limit)){
                    alert('Quantity exceeds stock quantity!');
                    $(this).val(parseFloat(qty_limit).toFixed(2));
                    var unitPrice = tr.find('#unit_price').val();
                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                    tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                    tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                    calculateTotalAmount();  
                    productTable();
                    return;
                }
                var unitPrice = tr.find('#unit_price').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount(); 
                productTable(); 
            }
        });

        $(document).on('blur', '#quantity', function(){
            var qty = $(this).val() ? $(this).val() : 0;
            if (qty < 0) {
                $(this).val(0);
            }
            if (parseFloat(qty) >= 0) {
                var tr = $(this).closest('tr');
                var previousQty = tr.find('#previous_quantity').val();
                var limit = tr.find('#qty_limit').val()
                var qty_limit = parseFloat(previousQty) + parseFloat(limit);
                if(parseInt(qty) > parseInt(qty_limit)){
                    alert('Quantity exceeds stock quantity!');
                    $(this).val(parseFloat(qty_limit).toFixed(2));
                    var unitPrice = tr.find('#unit_price').val();
                    var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty_limit);
                    tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                    tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                    calculateTotalAmount();  
                    productTable();
                    return;
                }
                var unitPrice = tr.find('#unit_price').val();
                var calcSubtotal = parseFloat(unitPrice) * parseFloat(qty);
                tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                tr.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount(); 
                productTable(); 
            }
        });

        // Input order discount and clculate total amount
        $(document).on('input', '#order_discount', function(){
            calculateTotalAmount();
            productTable();
        });

        // Input order discount type and clculate total amount
        $(document).on('change', '#order_discount_type', function(){
            calculateTotalAmount();
            productTable();
        });

        // Input shipment charge and clculate total amount
        $(document).on('input', '#shipment_charge', function(){
            calculateTotalAmount();
        });

        // chane purchase tax and clculate total amount
        $(document).on('change', '#order_tax', function(){
            calculateTotalAmount();
            productTable();
        });

        // Input paying amount and clculate due amount
        $(document).on('input', '#paying_amount', function(){
            var payingAmount = $(this).val() ? $(this).val() : 0;
            var total_purchase_amount = $('#total_payable_amount').val() ? $('#total_payable_amount').val() : 0;
            var calcDueAmount = parseFloat(total_purchase_amount) - parseFloat(payingAmount);
            $('.label_total_due').html(parseFloat(calcDueAmount).toFixed(2));
            $('#total_due').val(parseFloat(calcDueAmount).toFixed(2));
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
            productTable();
        });

        // Show selling product's update modal
        var tableRowIndex = 0;
        $(document).on('click', '#edit_product', function(e) {
            e.preventDefault();
            var parentTableRow = $(this).closest('tr');
            tableRowIndex = parentTableRow.index();
            var quantity = parentTableRow.find('#quantity').val();
            var product_name = parentTableRow.find('.product_name').html();
            var product_variant = parentTableRow.find('.product_variant').html();
            var product_code = parentTableRow.find('.product_code').html();
            var unit_price_exc_tax = parentTableRow.find('#unit_price_exc_tax').val();
            var unit_tax_percent = parentTableRow.find('#unit_tax_percent').val();
            var unit_tax_amount = parentTableRow.find('#unit_tax_amount').val();
            var unit_tax_type = parentTableRow.find('#tax_type').val();
            var unit_discount_type = parentTableRow.find('#unit_discount_type').val();
            var unit_discount = parentTableRow.find('#unit_discount').val();
            var unit_discount_amount = parentTableRow.find('#unit_discount_amount').val();
            var product_unit = parentTableRow.find('#unit').val();
            // Set modal heading
            var heading = product_name + (product_variant ? product_variant : '') + product_code;
            $('#product_info').html(heading);
            
            $('#e_quantity').val(parseFloat(quantity).toFixed(2));
            $('#e_unit_price').val(parseFloat(unit_price_exc_tax).toFixed(2));
            $('#e_unit_discount_type').val(unit_discount_type);
            $('#e_unit_discount').val(unit_discount);
            $('#e_discount_amount').val(unit_discount_amount);
            $('#e_unit_tax').empty();
            $('#e_unit_tax').append('<option value="0.00">No Tax</option>');
            taxArray.forEach(function (tax) {
                if (tax.tax_percent == unit_tax_percent) {
                    $('#e_unit_tax').append('<option SELECTED value="'+tax.tax_percent+'">'+tax.tax_name+'</option>');
                }else{
                    $('#e_unit_tax').append('<option value="'+tax.tax_percent+'">'+tax.tax_name+'</option>');
                }
            });
            $('#e_tax_type').val(unit_tax_type);
            $('#e_unit').empty();
            unites.forEach(function (unit) {
                if (unit == product_unit) {
                    $('#e_unit').append('<option SELECTED value="'+unit+'">'+unit+'</option>');
                }else{
                    $('#e_unit').append('<option value="'+unit+'">'+unit+'</option>');
                }
            });

            $('#editProductModal').modal('show');
        });

        // Calculate unit discount
        $('#e_unit_discount').on('input', function () {
            var discountValue = $(this).val() ? $(this).val() : 0.00;
            if ($('#e_unit_discount_type').val() == 1) {
                $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
            }else{
               var unit_price = $('#e_unit_price').val();
               var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
               $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
            }
        });

        // change unit discount type var productTableRow 
        $('#e_unit_discount_type').on('change', function () {
            var type = $(this).val();
            var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
            if (type == 1) {
                $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
            }else {
               var unit_price = $('#e_unit_price').val();
               var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
               $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
            }
        });

        //Update Selling producdt
        $('#update_selling_product').on('submit', function (e) {
            e.preventDefault();
            var inputs = $('.edit_input');
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
                return;
            }

            var e_quantity = $('#e_quantity').val();
            var e_unit_price = $('#e_unit_price').val();
            var e_unit_discount_type = $('#e_unit_discount_type').val();
            var e_unit_discount = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
            var e_unit_discount_amount = $('#e_discount_amount').val() ? $('#e_discount_amount').val() : 0.00;
            var e_unit_tax_percent = $('#e_unit_tax').val() ? $('#e_unit_tax').val() : 0.00;
            var e_unit_tax_type = $('#e_tax_type').val();
            var e_unit = $('#e_unit').val();

            var productTableRow = $('#sale_list tr:nth-child(' + (tableRowIndex + 1) + ')');
            // calculate unit tax 
            productTableRow.find('.span_unit').html(e_unit);
            productTableRow.find('#unit').val(e_unit);
            productTableRow.find('#unit').val(e_unit);
            productTableRow.find('#quantity').val(parseFloat(e_quantity).toFixed(2));
            productTableRow.find('#unit_price_exc_tax').val(parseFloat(e_unit_price).toFixed(2));
            productTableRow.find('#unit_discount_type').val(e_unit_discount_type);
            productTableRow.find('#unit_discount').val(parseFloat(e_unit_discount).toFixed(2));
            productTableRow.find('#unit_discount_amount').val(parseFloat(e_unit_discount_amount).toFixed(2));

            var calcUnitPriceWithDiscount = parseFloat(e_unit_price) - parseFloat(e_unit_discount_amount);
            var calsUninTaxAmount = parseFloat(calcUnitPriceWithDiscount) / 100 * parseFloat(e_unit_tax_percent);
            if (e_unit_tax_type == 2) {
                var inclusiveTax = 100 + parseFloat(e_unit_tax_percent);
                var calc = parseFloat(calcUnitPriceWithDiscount) / parseFloat(inclusiveTax) * 100;
                calsUninTaxAmount = parseFloat(calcUnitPriceWithDiscount) - parseFloat(calc);
            }
            productTableRow.find('#unit_tax_percent').val(parseFloat(e_unit_tax_percent).toFixed(2));
            productTableRow.find('#tax_type').val(e_unit_tax_type);
            productTableRow.find('#unit_tax_amount').val(parseFloat(calsUninTaxAmount).toFixed(2));

            var calcUnitPriceIncTax = parseFloat(calcUnitPriceWithDiscount) + parseFloat(calsUninTaxAmount);
        
            productTableRow.find('#unit_price').val(parseFloat(calcUnitPriceIncTax).toFixed(2));

            var calcSubtotal = parseFloat(calcUnitPriceIncTax) * parseFloat(e_quantity);
            productTableRow.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
            productTableRow.find('.span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            $('#editProductModal').modal('hide');
            calculateTotalAmount();
        });

        // change unit price
        $('#e_unit_price').on('input', function () {
            var unit_price = $(this).val() ? $(this).val() : 0.00;
            var discountValue = $('#e_unit_discount').val() ? $('#e_unit_discount').val() : 0.00;
            if ($('#e_unit_discount_type').val() == 1) {
                $('#e_discount_amount').val(parseFloat(discountValue).toFixed(2));
            }else{
               var calcUnitDiscount = parseFloat(unit_price) / 100 * parseFloat(discountValue);
               $('#e_discount_amount').val(parseFloat(calcUnitDiscount).toFixed(2));
            }
        });

        //Add purchase request by ajax
        $('#edit_sale_form').on('submit', function(e){
            e.preventDefault();
            var totalItem = $('#total_item').val();
            if (parseFloat(totalItem) == 0) {
                toastr.error('Product table is empty.','Some thing want wrong.'); 
                return;
            }
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
                        toastr.success(data.successMsg); 
                        window.location = "{{route('sales.index2')}}";
                    }
                }
            });
        });

        // Decrease qty
        $(document).on('click', '.decrease_qty_btn', function (e) {
            e.preventDefault();
            var tr = $(this).closest('tr');
            var presentQty = tr.find('#quantity').val();
            var updateQty = parseFloat(presentQty) - 1;
            if (updateQty < 0) {
                updateQty = parseFloat(0).toFixed(2);
            }
            tr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
            tr.find('#quantity').addClass('.form-control:focus');
            tr.find('#quantity').blur();
        });

         // Iecrease qty
         $(document).on('click', '.increase_qty_btn', function (e) {
            e.preventDefault();
            var tr = $(this).closest('tr');
            var presentQty = tr.find('#quantity').val();
            var updateQty = parseFloat(presentQty) + 1;
            tr.find('#quantity').val(parseFloat(updateQty).toFixed(2));
            tr.find('#quantity').addClass('.form-control:focus');
            tr.find('#quantity').blur();
        })
        // Automatic remove searching product is found signal 

        setInterval(function(){
            $('#search_product').removeClass('is-invalid');
        }, 500); 

        setInterval(function(){
            $('#search_product').removeClass('is-valid');
        }, 1000);

        // Disable branch field after add the products 
        function productTable(){
            var totalItem = $('#total_item').val() ? $('#total_item').val() : 0;
            if(parseFloat(totalItem) > 0){
                $('#warehouse_id').prop('disabled', true);
            }else{
                $('#warehouse_id').prop('disabled', false);
            }
        }

        $('.submit_button').on('click', function () {
            var value = $(this).val();
            $('#action').val(value); 
        });
        
        $('#warehouse_id').on('change', function(e){
            e.preventDefault();
            var warehouse_id = $(this).val();
            $('#req_warehouse_id').val(warehouse_id);
        });

        // Get editable data by ajax
        function getEditableSale(){
            $.ajax({
                url:"{{route('sales.get.editable.sale', $saleId)}}",
                type:'get',
                dataType: 'json',
                success:function(sale){
                    var qty_limits = sale.qty_limits;
                    var sale = sale.sale;
                    $('#invoice_id').val(sale.invoice_id);
                    $('#branch_id').val(sale.branch_id);
                    $('#req_warehouse_id').val(sale.warehouse_id);
                    $('#status').val(sale.status);
                    $('#date').val(sale.date);
                    $('#pay_term').val(sale.pay_term);
                    $('#pay_term_number').val(sale.pay_term_number);
                    $('#customer_name').val(sale.customer_id == null ? 'Walk-In-Customer' : sale.customer.name + '('+sale.customer.phone+')');
                    $('#order_discount_type').val(sale.order_discount_type);
                    $('#order_discount').val(sale.order_discount);
         
                    $('#order_discount_amount').val(sale.order_discount_amount);
                    $('#order_tax').val(sale.order_tax_percent);
                    $('#order_tax_amount').val(sale.order_tax_amount);
                    $('#shipment_details').val(sale.shipment_details);
                    $('#shipment_address').val(sale.shipment_address);
                    $('#shipment_charge').val(sale.shipment_charge);
                    $('#shipment_status').val(sale.shipment_status);
                    $('#delivered_to').val(sale.delivered_to);
                    $('#sale_note').val(sale.sale_note);

                    $.each(sale.sale_products, function (key, product) {
                        var tr = '';
                        tr += '<tr>';
                        tr += '<td colspan="2" class="text-start">';
                        tr += '<a href="#" class="text-success" id="edit_product">';
                        tr += '<span class="product_name">'+product.product.name+'</span>';
                        var variant = product.product_variant_id != null ? ' -'+product.variant.variant_name+'- ' : '';
                        tr += '<span class="product_variant">'+variant+'</span>'; 
                        var code = product.product_variant_id != null ? product.variant.variant_code : product.product.product_code;
                        tr += '<span class="product_code">'+'('+code+')'+'</span>';
                        tr += '</a><br/><input type="'+(product.product.is_show_emi_on_pos == 1 ? 'text' : 'hidden')+'" name="descriptions[]" class="form-control scanable mb-1" placeholder="IMEI, Serial number or other informations here." value="'+(product.description ? product.description : '')+'">';
                        tr += '<input value="'+product.product_id+'" type="hidden" class="productId-'+product.product_id+'" id="product_id" name="product_ids[]">';

                        if (product.product_variant_id != null) {
                            tr += '<input value="'+product.product_variant_id+'" type="hidden" class="variantId-'+product.product_variant_id+'" id="variant_id" name="variant_ids[]">'; 
                        }else{
                            tr += '<input value="noid" type="hidden" class="variantId-" id="variant_id" name="variant_ids[]">';  
                        }   

                        tr += '<input type="hidden" id="tax_type" value="'+product.product.tax_type+'">';

                        tr += '<input name="unit_tax_percents[]" type="hidden" id="unit_tax_percent" value="'+product.unit_tax_percent+'">';
                        tr += '<input name="unit_tax_amounts[]" type="hidden" id="unit_tax_amount" value="'+product.unit_tax_amount+'">';
                        tr += '<input value="'+product.unit_discount_type+'" name="unit_discount_types[]" type="hidden" id="unit_discount_type">';
                        tr += '<input value="'+product.unit_discount+'" name="unit_discounts[]" type="hidden" id="unit_discount">';
                        tr += '<input value="'+product.unit_discount_amount+'" name="unit_discount_amounts[]" type="hidden" id="unit_discount_amount">';
                        tr += '<input value="'+product.unit_cost_inc_tax+'" name="unit_costs_inc_tax[]" type="hidden" id="unit_cost_inc_tax">';
                        tr += '<input type="hidden" id="previous_quantity" value="'+product.quantity+'">';
                        tr += '<input type="hidden" id="qty_limit" value="'+qty_limits[key]+'">';
                        tr += '</td>';

                        tr += '<td>';
                        tr += '<div class="input-group">';
                        tr += '<div class="input-group-prepend">';
                        tr += '<a href="#" class="input-group-text input-group-text-sale decrease_qty_btn"><i class="fas fa-minus text-danger"></i></a>';
                        tr += '</div>';
                        tr += '<input value="'+product.quantity+'" required name="quantities[]" type="number" step="any" class="form-control text-center" id="quantity">';
                        tr += '<div class="input-group-prepend">';
                        tr += '<a href="#" class="input-group-text input-group-text-sale increase_qty_btn "><i class="fas fa-plus text-success "></i></a>';
                        tr += '</div>';
                        tr += '</div>';
                        tr += '</td>';
                        tr += '<td class="text">';
                        tr += '<span class="span_unit">'+product.unit+'</span>'; 
                        tr += '<input  name="units[]" type="hidden" id="unit" value="'+product.unit+'">';
                        tr += '</td>';
                        tr += '<td>';
                        
                        tr += '<input name="unit_prices_exc_tax[]" type="hidden" value="'+product.unit_price_exc_tax+'" id="unit_price_exc_tax">';
                        tr += '<input readonly name="unit_prices[]" type="text" class="form-control text-center" id="unit_price" value="'+product.unit_price_inc_tax+'">';
                        tr += '</td>';
                        tr += '<td class="text text-center">';
                        tr += '<strong><span class="span_subtotal">'+product.subtotal+'</span></strong>'; 
                        tr += '<input value="'+product.subtotal+'" readonly name="subtotals[]" type="hidden" id="subtotal">';
                        tr += '</td>';
                        tr += '<td class="text-center">';
                        tr += '<a href="" id="remove_product_btn" class=""><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
                        tr += '</td>';
                        tr += '</tr>';
                        $('#sale_list').append(tr);
                    });
                    calculateTotalAmount();
                    productTable();
                }
            });
        }
        getEditableSale();

        $('#add_product').on('click', function () {
            var warehouse_id = $('#warehouse_id').val();
            if (warehouse_id == '') {
                toastr.error("Warehouse field must not be empty.");
                return;
            }
            $.ajax({
                url:"{{ route('sales.add.product.modal.view') }}",
                type:'get',
                success:function(data){
                    $('#add_product_body').html(data);
                    $('#addProductModal').modal('show');
                }
            });
        });

        var tax_percent = 0;
        $(document).on('change', '#sale_tax_id',function() {
            var tax = $(this).val();
            if (tax) {
                var split = tax.split('-');
                tax_percent = split[1];
            }else{
                tax_percent = 0;
            }
        });

        function costCalculate() {
            var product_cost = $('#sale_product_cost').val() ? $('#sale_product_cost').val() : 0;
            var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent ? tax_percent : 0);
            var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
            $('#sale_product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
            var profit = $('#sale_profit').val() ? $('#sale_profit').val() : 0;
            var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
            var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
            $('#sale_product_price').val(parseFloat(product_price).toFixed(2));
        }

        $(document).on('input', '#sale_product_cost',function() {
            $('.os_unit_costs_exc_tax').val(parseFloat($(this).val()).toFixed(2));
            costCalculate();
        });

        $(document).on('change', '#sale_tax_id', function() {
            costCalculate();
        });

        $(document).on('input', '#sale_profit',function() {
            costCalculate();
        });

        // Reduce empty opening stock qty field
        $(document).on('blur', '#os_quantity', function () {
           if ($(this).val() == '') {
               $(this).val(parseFloat(0).toFixed(2));
           } 
        });

        // Reduce empty opening stock unit cost field
        $(document).on('blur', '#os_unit_cost_exc_tax', function () {
            if ($(this).val() == '') {
                $(this).val(parseFloat(0).toFixed(2));
            } 
        });

        $(document).on('input', '#os_quantity', function () {
            var qty = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            var unit_cost_exc_tax = tr.find('#os_unit_cost_exc_tax').val() ? tr.find('#os_unit_cost_exc_tax').val() : 0;
            var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
            tr.find('.os_span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            tr.find('#os_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
        });

        $(document).on('input', '#os_unit_cost_exc_tax', function () {
            var unit_cost_exc_tax = $(this).val() ? $(this).val() : 0;
            var tr = $(this).closest('tr');
            var qty = tr.find('#os_quantity').val() ? tr.find('#os_quantity').val() : 0;
            var calcSubtotal = parseFloat(qty) * parseFloat(unit_cost_exc_tax);
            tr.find('.os_span_subtotal').html(parseFloat(calcSubtotal).toFixed(2));
            tr.find('#os_subtotal').val(parseFloat(calcSubtotal).toFixed(2));
        });

        // Add product by ajax
        $(document).on('submit', '#add_product_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var branch_id = $('#branch_id').val() ? $('#branch_id').val() : null;
            var warehouse_id = $('#warehouse_id').val() ? $('#warehouse_id').val() : null;
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success('Successfully product is added.');
                    $.ajax({
                        url:"{{url('sales/get/recent/product')}}"+"/"+branch_id+"/"+warehouse_id+"/"+data.id,
                        type:'get',
                        success:function(data){
                            $('.loading_button').hide();
                            $('#addProductModal').modal('hide');
                            if (!$.isEmptyObject(data.errorMsg)) {
                                toastr.error(data.errorMsg);
                            }else{
                                $('#sale_list').prepend(data);
                                calculateTotalAmount();
                                productTable();
                            }
                        }
                    });
                },
                error: function(err) {
                    $('.loading_button').hide();
                    toastr.error('Please check again all form fields.', 'Some thing want wrong.');
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_sale_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('change', '#sale_category_id', function () {
            var category_id = $(this).val();
            $.ajax({
                url:"{{url('sales/get/all/sub/category')}}"+"/"+category_id,
                type:'get',
                dataType: 'json',
                success:function(subcate){
                    $('#sale_child_category_id').empty();
                    $('#sale_child_category_id').append('<option value="">Select Sub-Category</option>');
                    $.each(subcate, function(key, val){
                        $('#sale_child_category_id').append('<option value="'+val.id+'">'+val.name+'</option>');
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

        $(document).on('mouseenter', '#list>li>a',function () {
            $('#list>li>a').removeClass('selectProduct');
            $(this).addClass('selectProduct');
        });
    </script>
@endpush

<style>
    .search_item_area{position: relative;}
    .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 100%;z-index: 9999999;padding: 0;left: 0%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
    .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
    .select_area ul li a {color:#464343;text-decoration: none;font-size: 12px;padding: 2px 3px;display: block;line-height: 15px;border: 1px solid #968e92;font-weight: 500;}
    .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
    .selectProduct {background-color: #ab1c59;color: #fff !important;}
    .text-info {color: #0795a5!important;}
</style>

<div class="head-pos">
    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
    <input type="hidden" name="branch_id" id="branch_id" value="{{ $sale->branch_id }}">
    <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ $sale->warehouse_id }}">
    <input type="hidden" name="action" id="action" value="">
    <nav class="pos-navigation">
        <div class="col-lg-5 col-sm-12 col-12 nav-left-sec">
            <div class="col-lg-4 col-sm-12 col-12 logo-sec">
                <div class="pos-logo">
                    <img src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}" alt="">
                </div>
            </div>
            
            <div class="col-lg-8 col-sm-12 col-12 address">
                <p class="store-name">
                    {{ json_decode($generalSettings->business, true)['shop_name'] }} (HEAD OFFICE)
                </p>
                <p class="address-name">
                    @if ($sale->branch)
                        {{ $sale->branch->name.'-'.$sale->branch->branch_code }}
                        {{ $sale->branch->city ? ','.$sale->branch->city : ''}}
                        {{ $sale->branch->state ? ','.$sale->branch->state : ''}}
                        {{ $sale->branch->country ? ','.$sale->branch->country : ''}}
                    @else 
                        {{ json_decode($generalSettings->business, true)['address'] }}
                    @endif
                    
                </p>
                <small class="login-user-name">
                    <span class="text-highlight">Loggedin :</span> {{ $sale->admin ? $sale->admin->prefix.' '.$sale->admin->name.' '.$sale->admin->last_name : 'N/A' }} 
                    <span>
                        <span class="text-highlight">C.Register :</span>
                        @if ($sale->admin)
                            @if ($sale->admin->role_type == 1)
                                Super-Admin
                            @elseif($sale->admin->role_type == 2)
                                Admin
                            @else 
                                {{ $sale->admin->role->name }}
                            @endif
                        @endif
                    </span>
                </small>
            </div>  
           
        </div>
        <div class="col-lg-7 col-sm-12 col-12 input-buttob-sec">
            <div class="input-section">
                <div class="row">
                    <div class="input-sec col-lg-8">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="input-group mb-1">
                                    <input readonly type="text" class="form-control form-select" value="{{ $sale->customer ? $sale->customer->name.' ('.$sale->customer->phone.')' : 'Walk-In-Customer' }}">
                                </div>

                                <div class="search_item_area">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" name="search_product" class="form-control" id="search_product" placeholder="Scan/Search Items by SKU/Barcode" autofocus>
                                        <div class="input-group-append add_button" id="add_product">
                                            <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                        </div>
                                    </div>
    
                                    <div class="select_area">
                                        <ul id="list" class="variant_list_area">
                                           
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 input-value-sec">
                                <div class="input-group  mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">Point</span>
                                    </div>
                                    <input type="text" class="form-control">
                                    <!-- =============================== -->

                                    <div class="input-group-prepend ms-1">
                                        <span class="input-group-text valus">USD</span>
                                    </div>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="input-group col-6">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">SQ</span>
                                    </div>
                                    <input type="text" class="form-control" id="stock_quantity">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 btn-section">
                        <div class="date">
                            <p>3-11-2021 <span>6:58 AM</span></p>
                        </div>

                        <div class="btn-sec">
                            <a href="{{ route('sales.pos.suspended.list') }}" class="pos-btn status" id="suspends"><i class="fas text-warning fa-pause"></i></a>
                            <a href="" class="pos-btn"><span class="fas fa-calculator"></span></a>
                            {{-- <a href="" class="pos-btn"><span class="fas fa-briefcase"></span></a>
                            <a href="" class="pos-btn text-danger"><span class="fas fa-times"></span></a> --}}
                            <a href="" class="pos-btn"><span class="fas fa-bell"></span></a>
                            <a href="" class="pos-btn" id="pos_exit_button"><span class="fas fa-backward"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
<script>
    $('#add_product').on('click', function () {
        $.ajax({
            url:"{{route('sales.add.product.modal.view')}}",
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
            console.log(split);
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
        console.log($(this).val());
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

    $(document).on('change', '#sale_category_id', function () {
        var category_id = $(this).val();
        $.ajax({
            url:"{{url('sales/get/all/sub/category')}}"+"/"+category_id,
            async:true,
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
                    url:"{{url('sales/pos/get/recent/product')}}"+"/"+branch_id+"/"+warehouse_id+"/"+data.id,
                    type:'get',
                    success:function(data){
                        console.log(data);
                        $('.loading_button').hide();
                        $('#addProductModal').modal('hide');
                        if (!$.isEmptyObject(data.errorMsg)) {
                            toastr.error(data.errorMsg);
                        }else{
                            $('#product_list').prepend(data);
                            calculateTotalAmount();
                        }
                    }
                });
            },
            error: function(err) {
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.', 'Some thing want wrong.');
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    //console.log(key);
                    $('.error_sale_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('click', '#suspends',function (e) {
        e.preventDefault();
        allSuspends();
    });

    function allSuspends() {
        $('#suspendedSalesModal').modal('show');
        $('#suspend_preloader').show();
        $.ajax({
            url:"{{ route('sales.pos.suspended.list') }}",
            async:true,
            success:function(data){
                $('#suspended_sale_list').html(data);
                $('#suspend_preloader').hide();
            }
        });
    }
</script>
<!-- Pos Header End-->
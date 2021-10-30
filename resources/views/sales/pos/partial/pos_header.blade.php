<style>
    .search_item_area{position: relative;}
    .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 100%;z-index: 9999999;padding: 0;left: 0%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
    .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
    .select_area ul li a {color:#464343;text-decoration: none;font-size: 12px;padding: 2px 3px;display: block;line-height: 15px;border: 1px solid #968e92;font-weight: 400;}
    .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
    .selectProduct {background-color: #ab1c59;color: #fff !important;}
    .text-info {color: #0795a5!important;}
</style>

<div class="head-pos">
    <input type="hidden" name="action" id="action" value="">
    <input type="text" class="d-none" name="ex_sale_id" id="ex_sale_id" value="">
    <input type="hidden" name="cash_register_id" value="{{ $openedCashRegister->id }}">
    <input type="text" class="d-none" name="button_type" id="button_type" value="0">
    <input type="hidden" id="store_url" value="{{ route('sales.pos.store') }}">
    <input type="hidden" id="exchange_url" value="{{ route('sales.pos.exchange.confirm') }}">
    <nav class="pos-navigation">
        <div class="col-lg-4 col-sm-3 col-xs-3 nav-left-sec">
            <div class="col-lg-4 col-sm-12 col-12 logo-sec">
                <div class="pos-logo">
                    @if (auth()->user()->branch)
                        @if (auth()->user()->branch->logo != 'default.png')
                            <img style="height: 40px; width:100px;" src="{{ asset('public/uploads/branch_logo/' . auth()->user()->branch->logo) }}">
                        @else 
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{ auth()->user()->branch->name }}</span>
                        @endif
                    @else  
                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                            <img style="height: 40px; width:100px;" src="{{ asset('public/uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                        @else 
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                        @endif
                    @endif
                </div>
            </div>
            
            <div class="col-lg-8 col-sm-12 col-12 address">
                @if ($openedCashRegister->branch)
                    <p class="store-name">
                        {{ $openedCashRegister->branch->name.'-'.$openedCashRegister->branch->branch_code }}
                    </p>
                    <p class="address-name">
                        {{ $openedCashRegister->branch->city ? $openedCashRegister->branch->city.', ' : ''}}
                        {{ $openedCashRegister->branch->state ? $openedCashRegister->branch->state.', ' : ''}}
                        {{ $openedCashRegister->branch->country ? ', '.$openedCashRegister->branch->country : ''}}
                    </p>
                @else 
                    <p class="store-name">
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} <b>(HO)</b>
                    </p>
                    <p class="address-name">
                        {{ Str::limit(json_decode($generalSettings->business, true)['address'], 45) }}
                    </p>
                @endif
                <small class="login-user-name">
                    <span class="text-highlight">Loggedin : </span> {{ auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }}.
                    <span> 
                        <span class="text-highlight">C.Register : </span> 
                        @if ($openedCashRegister->admin)
                            @if ($openedCashRegister->admin->role_type == 1)
                                Super-Admin.
                            @elseif($openedCashRegister->admin->role_type == 2)
                                Admin.
                            @else 
                                {{ $openedCashRegister->admin->role->name }}.
                            @endif
                        @endif
                    </span>
                    <span> <span class="text-highlight">Cash Counter : </span> {{ $openedCashRegister->cash_counter ? $openedCashRegister->cash_counter->counter_name : 'N/A' }}.</span>
                </small>
            </div>  
        </div>
        
        <div class="col-lg-8 col-sm-9 col-12 input-buttob-sec">
            <div class="input-section">
                <div class="row">
                    <div class="input-sec col-sm-8 col-12">
                        <div class="row">
                            <div class="col-lg-7 col-12 sm-input-sec-w">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <select name="customer_id" class="form-control form-select" id="customer_id">
                                        <option value="">Walk-In-Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append add_button" id="addCustomer">
                                        <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                    </div>
                                </div>

                                <div class="search_item_area">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" name="search_product" class="form-control" id="search_product" placeholder="Scan/Search Items by SKU/Barcode" autofocus autocomplete="off">
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
                                @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == '1')
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text valus">Point</span>
                                        </div>
                                        <input readonly type="number" step="any" class="form-control" name="earned_point" id="earned_point">
                                        <!-- =============================== -->

                                        <div class="input-group-prepend ms-1">
                                            <span class="input-group-text valus"> = {{ json_decode($generalSettings->business, true)['currency'] }}</span>
                                        </div>
                                        <input readonly type="text" class="form-control" id="trial_point_amount">
                                    </div>
                                @endif
                                

                                <div class="input-group col-6">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">SQ</span>
                                    </div>
                                    <input type="text" class="form-control" id="stock_quantity">

                                    <div class="input-group-prepend ms-1">
                                        <select name="price_group_id" class="form-control" id="price_group_id">
                                            <option value="">Default Selling Price</option>
                                            @foreach ($price_groups as $pg)
                                                <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4 btn-section">
                        <div class="date">
                            <p>{{ date('d-m-Y') }} <span id="time">6:58 AM</span></p>
                        </div>

                        <div class="btn-sec">
                            <a href="" class="pos-btn status" id="suspends"><i class="fas text-warning fa-pause"></i></a>
                            <a href="" class="pos-btn mr-1" data-bs-toggle="modal" data-bs-target="#calculatorModal">
                                <span class="fas fa-calculator"></span>
                            </a>
                            <div class="modal" id="calculatorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modail-body" id="calculator">
                                        <div class="calculator-bg">
                                            <div class="calculator-bg__main">
                                                <div class="calculator-bg__main__screen">
                                                    <div class="calculator-bg__main__screen__first"></div>
                                                    <div class="calculator-bg__main__screen__second">0</div>
                                                </div>
                                                <button class="calculator-bg__main__ac">AC</button>
                                                <button class="calculator-bg__main__del">DEL</button>
                                                <button class="calculator-bg__main__operator">/</button>
                                                <button class="calculator-bg__main__num">7</button>
                                                <button class="calculator-bg__main__num">8</button>
                                                <button class="calculator-bg__main__num">9</button>
                                                <button class="calculator-bg__main__operator">x</button>
                                                <button class="calculator-bg__main__num">4</button>
                                                <button class="calculator-bg__main__num">5</button>
                                                <button class="calculator-bg__main__num">6</button>
                                                <button class="calculator-bg__main__operator">+</button>
                                                <button class="calculator-bg__main__num">1</button>
                                                <button class="calculator-bg__main__num">2</button>
                                                <button class="calculator-bg__main__num">3</button>
                                                <button class="calculator-bg__main__operator">-</button>
                                                <button class="calculator-bg__main__num decimal">.</button>
                                                <button class="calculator-bg__main__num">0</button>
                                                <button class="calculator-bg__main__result">=</button>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            @if (auth()->user()->permission->register['register_view'] == '1')
                                <a href="#" class="pos-btn text-info" id="cash_register_details" title="Register Details"><i class="fas fa-cash-register"></i></a>
                            @endif

                            @if (auth()->user()->permission->register['register_close'] == '1')
                                <a href="#" class="pos-btn text-danger" id="close_register" title="Close Register"><span class="fas fa-times"></span></a>
                            @endif
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
    // Get all price group
    var price_groups = '';
    function getPriceGroupProducts(){
        $.ajax({
            url:"{{ route('sales.product.price.groups') }}",
            success:function(data){
                price_groups = data;
            }
        });
    }
    getPriceGroupProducts();
 
    var rp_settings = {
        enable_rp : "{{ json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] }}",
        redeem_amount_per_unit_rp : "{{ json_decode($generalSettings->reward_poing_settings, true)['redeem_amount_per_unit_rp'] }}",
        min_order_total_for_redeem : "{{ json_decode($generalSettings->reward_poing_settings, true)['min_order_total_for_redeem'] }}",
        min_redeem_point : "{{ json_decode($generalSettings->reward_poing_settings, true)['min_redeem_point'] }}",
        max_redeem_point : "{{ json_decode($generalSettings->reward_poing_settings, true)['max_redeem_point'] }}",
    }
    $('#customer_id').on('change', function () {
        var customerId = $(this).val();
        $('#previous_due').val(parseFloat(0).toFixed(2));
        $('#earned_point').val(0);
        $('#pre_redeemed').val(0);

        var pre_redeemed_amount = $('#pre_redeemed_amount').val() ? $('#pre_redeemed_amount').val() : 0;
        var order_discount = $('#order_discount').val() ? $('#order_discount').val() : 0;
        var calcDiscount = parseFloat(order_discount) - parseFloat(pre_redeemed_amount);
        $('#order_discount').val(parseFloat(calcDiscount).toFixed(2));
        $('#order_discount_amount').val(parseFloat(calcDiscount).toFixed(2));
        $('#pre_redeemed_amount').val(0);
        var url = "{{ url('sales/customer_info') }}"+'/'+customerId;
        $.get(url, function(data) {
            $('#previous_due').val(data.total_sale_due);
            $('#earned_point').val(data.point);
            if (rp_settings.enable_rp == '1') {
                var __point_amount = parseFloat(data.point) * parseFloat(rp_settings.redeem_amount_per_unit_rp);
                $('#trial_point_amount').val(parseFloat(__point_amount).toFixed(2));
            }
            calculateTotalAmount();
        });
        calculateTotalAmount();
        document.getElementById('search_product').focus();
    });

    $(document).on('click', '#reedem_point_button', function (e) {
        e.preventDefault();
        if ($('#customer_id').val()) {
            var earned_point = $('#earned_point').val() ? $('#earned_point').val() : 0;
            $('#available_point').val(parseFloat(earned_point));
            $('#redeem_amount').val('');
            $('#total_redeem_point').val('')
            $('#pointReedemModal').modal('show');
        }else{
            toastr.error('Select customer first.');
            return;
        }
    });

    $(document).on('input', '#total_redeem_point', function () {
        var redeeming_point = $(this).val();
        var __point_amount = parseFloat(redeeming_point) * parseFloat(rp_settings.redeem_amount_per_unit_rp);
        $('#redeem_amount').val(parseFloat(__point_amount));
    });

    $(document).on('click', '#redeem_btn',function(e) {
        e.preventDefault();
        var available_point = $('#available_point').val() ? $('#available_point').val() : 0;
        var total_redeem_point = $('#total_redeem_point').val() ? $('#total_redeem_point').val() : 0;
        var redeem_amount = $('#redeem_amount').val() ? $('#redeem_amount').val() : 0;
        if (parseFloat(total_redeem_point) > parseFloat(available_point)) {
            toastr.error('Only '+available_point+' points is available.');
            return;
        }

        var total_invoice_payable = $('#total_invoice_payable').val();
        if (rp_settings.min_order_total_for_redeem && total_invoice_payable < parseFloat(rp_settings.min_order_total_for_redeem)) {
            toastr.error('Minimum order amount is '+rp_settings.min_order_total_for_redeem+' to redeem the points.'); 
            return;
        }

        if (rp_settings.min_redeem_point && parseFloat(total_redeem_point) < parseFloat(rp_settings.min_redeem_point)) {
            toastr.error('Minimum redeem points is '+rp_settings.min_redeem_point);
            return; 
        }
        
        if (rp_settings.max_redeem_point && parseFloat(total_redeem_point) > parseFloat(rp_settings.max_redeem_point)) {
            toastr.error('Maximum redeem points is '+rp_settings.max_redeem_point); 
            return;
        }

        var order_discount = $('#order_discount').val() ? $('#order_discount').val() : 0;
        var order_discount_amount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
        var calcDiscount = parseFloat(order_discount_amount) + parseFloat(redeem_amount);
        $('#order_discount').val(parseFloat(calcDiscount).toFixed(2));
        $('#order_discount_amount').val(parseFloat(calcDiscount).toFixed(2));
        var earned_point = $('#earned_point').val() ? $('#earned_point').val() : 0;
        var calcLastPoint = parseFloat(earned_point) - parseFloat(total_redeem_point);
        $('#earned_point').val(parseFloat(calcLastPoint));
        var calcLastAmount = parseFloat(calcLastPoint) * parseFloat(parseFloat(rp_settings.redeem_amount_per_unit_rp));
        $('#trial_point_amount').val(parseFloat(calcLastAmount).toFixed(2));
        var pre_redeemed = $('#pre_redeemed').val() ? $('#pre_redeemed').val() : 0;
        var calcPreRedeemPoint = parseFloat(pre_redeemed) + parseFloat(total_redeem_point);
        $('#pre_redeemed').val(parseFloat(calcPreRedeemPoint));
        var pre_redeemed_amount = $('#pre_redeemed_amount').val() ? $('#pre_redeemed_amount').val() : 0;
        var calcPreRedeemAmount = parseFloat(pre_redeemed_amount) + parseFloat(redeem_amount);
        $('#pre_redeemed_amount').val(parseFloat(calcPreRedeemAmount).toFixed(2));
        calculateTotalAmount();
        $('#pointReedemModal').modal('hide');
    });

    $('#addCustomer').on('click', function () {
        $.get("{{route('sales.pos.add.quick.customer.modal')}}", function(data) {
            $('#add_customer_modal_body').html(data);
            $('#addCustomerModal').modal('show');
        });
    });

    // Add customer by ajax
    $(document).on('submit', '#add_customer_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.c_add_input');
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
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                toastr.success(data);
                $('#add_customer_form')[0].reset();
                $('.loading_button').hide();
                $('#addCustomerModal').modal('hide');
                $('#customer_id').append('<option value="'+data.id+'">'+ data.name +' ('+data.phone+')'+'</option>');
                $('#customer_id').val(data.id);
                console.log(parseFloat(data.total_sale_due).toFixed(2));
                $('#previous_due').val(parseFloat(data.total_sale_due).toFixed(2));
                calculateTotalAmount();
            }
        });
    });

    $('#add_product').on('click', function() {
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
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success('Successfully product is added.');
                $.ajax({
                    url:"{{url('sales/pos/get/recent/product')}}"+"/"+data.id,
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
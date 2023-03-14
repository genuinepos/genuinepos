<style>
    .search_item_area {position: relative;}
    .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 100%;z-index: 9999999;padding: 0;left: 0%;display: none;border: 1px solid var(--main-color);margin-top: 1px;border-radius: 0px;}
    .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
    .select_area ul li a {color: #464343;text-decoration: none;font-size: 12px;padding: 2px 3px;display: block;line-height: 15px;border: 1px solid #968e92;font-weight: 400;}
    .select_area ul li a:hover {background-color: #999396;color: #fff;}
    .selectProduct {background-color: #746e70!important;color: #fff !important;}
    .text-info {color: #0795a5 !important;}
    .pos-logo img {
        height: auto;
        width: 70%;
    }
</style>

<div class="head-pos">
    <input type="hidden" name="action" id="action" value="">
    <input type="text" class="d-hide" name="ex_sale_id" id="ex_sale_id" value="">
    <input type="hidden" name="cash_register_id" value="{{ $openedCashRegister->id }}">
    <input type="hidden" name="sale_account_id" value="{{ $openedCashRegister->sale_account_id }}">
    <input type="text" class="d-hide" name="button_type" id="button_type" value="0">
    <input type="hidden" id="store_url" value="{{ route('sales.pos.store') }}">
    <input type="hidden" id="exchange_url" value="{{ route('sales.pos.exchange.confirm') }}">
    <nav class="pos-navigation">
        <div class="col-lg-9 nav-left-sec">
            <div class="row g-1 align-items-center">
                <div class="col-xl-5 col-lg-3">
                    <div class="row g-1">
                        <div class="col-xl-4 logo-sec">
                            <div class="pos-logo d-flex justify-content-center">
                                @if (auth()->user()->branch)
                                    @if (auth()->user()->branch->logo != 'default.png')
                                        <img
                                        src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}">
                                        {{-- src="{{ asset(config('speeddigit.app_logo')) }}"> --}}
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{
                                        auth()->user()->branch->name }}</span>
                                    @endif
                                @else
                                    @if ($generalSettings['business__business_logo'] != null)
                                    <img
                                        {{-- src="{{ asset(config('speeddigit.app_logo'))}}" --}}
                                        src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}"
                                        alt="logo" class="logo__img">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{
                                        $generalSettings['business__shop_name'] }}</span>
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
                                    {{ $generalSettings['business__shop_name'] }}
                                </p>
                                <p class="address-name">
                                    {{ Str::limit($generalSettings['business__address'], 45) }}
                                </p>
                            @endif
                            <small class="login-user-name">
                                <span class="text-highlight">{{ __('Loggedin') }} </span> {{ auth()->user()->prefix.' '.auth()->user()->name.'
                                '.auth()->user()->last_name }}.
                                <span>
                                    <span class="text-highlight">{{ __('C.Register') }} </span>
                                    @if ($openedCashRegister->admin)
                                        @if ($openedCashRegister->admin->role_type == 1)
                                            Super-Admin.
                                        @elseif($openedCashRegister->admin->role_type == 2)
                                            Admin.
                                        @else
                                            {{ $openedCashRegister->admin?->roles()?->first()?->name }}.
                                        @endif
                                    @endif
                                </span>
                                <span> <span class="text-highlight">@lang('menu.cash_counter') </span> {{ $openedCashRegister->cash_counter ?
                                    $openedCashRegister->cash_counter->counter_name : 'N/A' }}.</span>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-9">
                    <div class="input-sec">
                        <div class="row g-1">
                            <div class="col-lg-6 col-12 sm-input-sec-w">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <select name="customer_id" class="form-control" id="customer_id">
                                        <option value="">{{ __('Walk-In-Customer') }}</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')'
                                            }}</option>
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
                                        <input type="text" name="search_product" class="form-control"
                                            id="search_product" placeholder="Scan/Search Items by SKU/Barcode" autofocus
                                            autocomplete="off">
                                        @if (auth()->user()->can('product_add'))
                                            <div class="input-group-append add_button" id="add_product">
                                                <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="select_area">
                                        <ul id="list" class="variant_list_area"></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 input-value-sec">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">Point</span>
                                    </div>
                                    <input readonly type="number" step="any" class="form-control" name="earned_point"
                                        id="earned_point" tabindex="-1">

                                    <div class="input-group-prepend ms-1">
                                        <span class="input-group-text valus"> = {{
                                            $generalSettings['business__currency'] }}</span>
                                    </div>
                                    <input readonly type="text" class="form-control" id="trial_point_amount" tabindex="-1">
                                </div>


                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">SQ</span>
                                    </div>
                                    <input readonly type="text" class="form-control" id="stock_quantity" tabindex="-1">

                                    <div class="input-group-prepend ms-1">
                                        <select name="price_group_id" class="form-control" id="price_group_id">
                                            <option value="">@lang('menu.default_selling_price')</option>
                                            @foreach ($price_groups as $pg)
                                            <option value="{{ $pg->id }}">{{ $pg->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-9 col-12 input-buttob-sec">
            <div class="input-section">
                <div class="btn-section">
                    <div class="date">
                        <p>{{ date('d-m-Y') }} <span id="time">6:58 AM</span></p>
                    </div>

                    <div class="btn-sec">
                        {{-- Shortcut Manual --}}
                        <a href="#" class="pos-btn position-relative" id="readDocument" title="Shortcut button list" tabindex="-1">
                            <i class="fas fa-file-alt"></i>
                            <div class="position-absolute doc">
                                <ul class="p-2 pt-3">
                                    <li>
                                        <span class="icon shortcut">F2</span>
                                        <span class="desc">Save as draft</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">F3</span>
                                        <span class="desc">View stock</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">Ctrl+Q</span>
                                        <span class="desc">Quick setup</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">Ctrl+C</span>
                                        <span class="desc">Copy Element</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">Ctrl+V</span>
                                        <span class="desc">Paste Copied</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">F2</span>
                                        <span class="desc">Save as draft</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">F2</span>
                                        <span class="desc">Save as draft</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">F2</span>
                                        <span class="desc">Save as draft</span>
                                    </li>
                                </ul>
                            </div>
                        </a>

                        <a href="#" class="pos-btn status" id="suspends" title="Suspended Invoice" tabindex="-1">
                            <i class="fas text-warning fa-pause"></i>
                        </a>

                        <a href="#" class="pos-btn" data-bs-toggle="modal" data-bs-target="#calculatorModal" tabindex="-1">
                            <span class="fas fa-calculator"></span>
                        </a>

                        <a href="#" class="pos-btn" id="hard_reload" tabindex="-1">
                            <span class="fas fa-redo-alt"></span>
                        </a>

                        {{-- <a href="#" class="pos-btn d-hide d-md-block" id="hard_reload" title="Hard Reload">
                            <span class="fas fa-redo-alt"></span>
                        </a> --}}

                        <div class="modal" id="calculatorModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
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

                        @if (auth()->user()->can('register_view'))
                            <a href="#" class="pos-btn text-info" id="cash_register_details" title="Register Details" tabindex="-1"><i
                                class="fas fa-cash-register"></i></a>
                        @endif

                        @if (auth()->user()->can('register_close'))
                            <a href="#" class="pos-btn text-danger" id="close_register" title="Close Register" tabindex="-1">
                                <span class="fas fa-times"></span></a>
                        @endif

                        <a href="#" class="pos-btn" tabindex="-1">
                            <span class="fas fa-bell"></span>
                        </a>

                        <a href="#" class="pos-btn" id="pos_exit_button" tabindex="-1">
                            <span class="fas fa-backward"></span>
                        </a>
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
        enable_rp : "{{ $generalSettings['reward_point_settings__enable_cus_point'] }}",
        redeem_amount_per_unit_rp : "{{ $generalSettings['reward_point_settings__redeem_amount_per_unit_rp'] }}",
        min_order_total_for_redeem : "{{ $generalSettings['reward_point_settings__min_order_total_for_redeem'] }}",
        min_redeem_point : "{{ $generalSettings['reward_point_settings__min_redeem_point'] }}",
        max_redeem_point : "{{ $generalSettings['reward_point_settings__max_redeem_point'] }}",
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

        // var url = "{{ url('common/ajax/call/customer_info') }}"+'/'+customerId;
        var url = "{{ route('contacts.customer.amounts.branch.wise', ':customerId') }}";
        var route = url.replace(':customerId', customerId);

        $.get(route, function(data) {

            $('#previous_due').val(data.total_sale_due);

            if (rp_settings.enable_rp == '1') {

                $('#earned_point').val(data.point);
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
        if (rp_settings.enable_rp == '1') {

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
        }else{

            toastr.error('Reaward pointing system is disabled.');
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

        $.get("{{ route('sales.pos.add.quick.customer.modal') }}", function(data) {

            $('#add_customer_modal_body').html(data);
            $('#addCustomerModal').modal('show');
        });
    });

    @if (auth()->user()->can('product_add'))

        $('#add_product').on('click', function() {
            $.ajax({
                url:"{{ route('sales.add.product.modal.view') }}",
                type:'get',
                success:function(data){

                    $('#add_product_body').html(data);
                    $('#addProductModal').modal('show');
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
                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                    $('.error').html('');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_sale_' + key + '').html(error[0]);
                    });
                }
            });
        });
    @endif

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

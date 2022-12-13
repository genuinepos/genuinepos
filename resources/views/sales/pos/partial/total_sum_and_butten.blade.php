<style>
    .cash_receive_input{
      background-color: var(--white-color);
      border: 1px solid #ced4da;
      letter-spacing: -3px!important;
      padding: 0px 3px 0px 0px!important;
      font-weight: 700!important;
    }
</style>

<div class="col-lg-3">
    <div class="pos-right-inner">
        <div class="check-out-wraper">
            <div class="function-sec">
                <div class="row g-xxl-3 g-xl-2 g-lg-1">
                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_draft'] == '1')
                                    data-button_type="0"
                                    data-action_id="2"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Creating draft is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('menu.draft')<p>F2</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_quotation'] == '1')
                                    data-action_id="4"
                                    data-button_type="0"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Creating quotaion is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('menu.quotation')<p>F4</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card" id="exchange_btn" data-bs-toggle="modal" data-bs-target="#exchangeModal" tabindex="-1">
                                @lang('menu.exchange')<p>F6</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card" id="show_stock" tabindex="-1">@lang('menu.stock')<p>Alt+C</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '1')
                                    data-button_type="0"
                                    data-action_id="5"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Hold invoice is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">@lang('menu.hold_invoices')<p>F8</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '1')
                                    id="pick_hold_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Hold invoice is disabled in POS.');
                                    "
                                @endif
                                class="function-card" tabindex="-1">{{ __('Pick Hold') }} <p>F9</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="{{ route('settings.general.index') }}" class="function-card" tabindex="-1">
                                Setup <p>Ctrl+Q</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#"
                                @if (json_decode($generalSettings->pos, true)['is_enabled_suspend'] == '1')
                                    data-button_type="0"
                                    data-action_id="6"
                                    id="submit_btn"
                                @else
                                    onclick="
                                        event.preventDefault();
                                        toastr.error('Suspend is disabled in POS.');
                                    "
                                @endif
                                class="function-card function-card-danger" tabindex="-1">{{ __('Suspend') }}<p>Alt+A</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="btn-bg">
                            <a href="#" class="function-card function-card-danger" onclick="cancel(); return false;" tabindex="-1">
                                Cancel <p>Ctrl+M</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapper_input_btn">
                <div class="checkout-input-sec">
                    <div class="row g-1">
                        <label for="inputEmail3" class="col-sm-3 col-4 col-form-label text-white"><b>@lang('menu.total'):</b></label>
                        <div class="col-sm-9 col-8">
                            <input readonly type="number" class="form-control pos-amounts" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                        </div>
                    </div>

                    @if (json_decode($generalSettings->pos, true)['is_enabled_discount'] == '1')
                        <div class="row g-1">
                            <label class="col-sm-3 col-4 col-form-label text-white">@lang('menu.discount'):</label>
                            <div class="col-sm-9 col-8">

                                <div class="row g-2">
                                    <div class="col-6">
                                        <select name="order_discount_type" id="order_discount_type" class="form-control pos-amounts">
                                            <option value="1">@lang('menu.fixed')(0.00)</option>
                                            <option value="2">Percent(%)</option>
                                        </select>
                                        {{-- <input name="order_discount_type" class="form-control" id="order_discount_type" value="1"> --}}
                                    </div>

                                    <div class="col-6">
                                        <input name="order_discount" type="number" step="any" class="form-control pos-amounts" id="order_discount" value="0.00">
                                    </div>
                                </div>

                                <input name="order_discount_amount" type="number" class="d-hide" id="order_discount_amount"
                                    value="0.00" tabindex="-1">
                            </div>
                        </div>
                    @else
                        <input name="order_discount" type="hidden" id="order_discount" value="0.00" tabindex="-1">
                        <input name="order_discount_amount" type="number" class="d-hide" id="order_discount_amount"
                            value="0.00" tabindex="-1">
                        <input name="order_discount_type" class="d-hide" id="order_discount_type" value="1">
                    @endif

                    @if (json_decode($generalSettings->pos, true)['is_enabled_order_tax'] == '1')
                        <div class="row g-1">
                            <label class="col-sm-3 col-4 col-form-label text-white">Vat/Tax:</label>
                            <div class="col-sm-9 col-8">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <select name="order_tax" class="form-control pos-amounts" id="order_tax"></select>
                                    </div>

                                    <div class="col-6">
                                        <input type="number" class="form-control pos-amounts" name="order_tax_amount" id="order_tax_amount"
                                        value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <input name="order_tax" type="hidden" id="order_tax" value="0.00" tabindex="-1">
                        <input type="hidden" name="order_tax_amount" id="order_tax_amount" value="0.00" tabindex="-1">
                    @endif

                    <div class="row g-1">
                        <label class="col-sm-3 col-4 col-form-label text-white">{{ __('Pre. Due') }}:</label>

                        <div class="col-sm-9 col-8">
                            <input readonly class="form-control pos-amounts" type="number" step="any" name="previous_due"
                                id="previous_due" value="0.00" tabindex="-1">
                        </div>

                        <label class="col-sm-3 col-4 col-form-label text-white">Payable:</label>
                        <div class="col-sm-9 col-8">
                            <input readonly class="form-control pos-amounts" type="number" step="any"
                                name="total_payable_amount" id="total_payable_amount" value="0.00" tabindex="-1">

                            <input class="d-hide" type="number" step="any" name="total_invoice_payable"
                                id="total_invoice_payable" value="0.00" tabindex="-1">
                        </div>
                    </div>

                    <div class="row g-1">
                        <label class="col-sm-6 col-5 col-form-label text-white">@lang('menu.cash_receive'):</label>
                        <div class="col-sm-6 col-7">
                            {{-- <input type="number" step="any" name="paying_amount" id="paying_amount" value="0"
                                class="form-control pos-amounts" autocomplete="off"> --}}

                            <div class="input-group">
                                <span class="input-group-text cash_receive_input">>></span>
                                <input type="number" step="any" name="paying_amount" id="paying_amount" value="0"
                                class="form-control pos-amounts input_i" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="row g-1">
                        <label class="col-sm-6 col-5 col-form-label text-white">@lang('menu.change_amount'):</label>
                        <div class="col-sm-6 col-7">
                            <input readonly type="text" name="change_amount" id="change_amount" value="0.00"
                                class="form-control pos-amounts" tabindex="-1">
                        </div>
                    </div>

                    <div class="row g-1">
                        <label class="col-sm-6 col-5 col-form-label text-danger"><b>@lang('menu.due') :</b></label>
                        <div class="col-sm-6 col-7">
                            <input type="text" readonly name="total_due" id="total_due" value="0.00"
                                class="form-control pos-amounts text-danger" tabindex="-1">
                        </div>
                    </div>
                </div>

                <div class="sub-btn-sec">
                    <div class="row g-xxl-3 g-1">
                        <div class="col-lg-4 col-6 m-order-2">
                            <div class="btn-bg mb-xxl-1 mb-xl-1">
                                <a href="#" class=" btn-pos"
                                    @if (json_decode($generalSettings->pos, true)['is_enabled_credit_full_sale'] == '1')
                                        data-button_type="0"
                                        id="full_due_button"
                                    @else
                                        onclick="
                                            event.preventDefault();
                                            toastr.error('Full credit sale is disabled.');
                                        "
                                    @endif
                                    tabindex="-1">@lang('menu.credit_sale')</a>
                            </div>

                            <div class="btn-bg">
                                <a href="#" class="btn-pos" id="reedem_point_button" tabindex="-1">@lang('menu.reedem_oint')</a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6 btn-bottom m-order-3">
                            <div class="btn-bg">
                                <a href="#" class="function-card other_payment_method" tabindex="-1">
                                    <span>@lang('menu.other_method')</span>
                                    <p>Ctrl+B</p>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-4 btn-bottom">
                            <div class="btn-bg">
                                <a href="#" class="function-card cash-btn" id="submit_btn" data-button_type="1"
                                    data-action_id="1" tabindex="-1">
                                    <span>@lang('menu.cash') </span>
                                    <p>F10</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var actionMessage = 'Data inserted Successfull.';

    $('#pos_submit_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');
        $('.submit_preloader').show();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                $('.loading_button').hide();
                $('.submit_preloader').hide();

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg,'Attention');
                    return;
                }else if(data.suspendMsg){

                    toastr.success(data.suspendMsg);
                    afterSubmitForm();
                    document.getElementById('search_product').focus();
                }else if(data.holdInvoiceMsg){

                    toastr.success(data.holdInvoiceMsg);
                    afterSubmitForm();
                    document.getElementById('search_product').focus();
                }else {

                    toastr.success(actionMessage);
                    afterSubmitForm();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                    document.getElementById('search_product').focus();
                }
            },error: function(err) {

                $('.loading_button').hide();
                $('.submit_preloader').hide();
                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500) {

                    toastr.error('Server error. Please contact the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    toastr.error(error[0]);
                });
            }
        });
    });

    @if (json_decode($generalSettings->pos, true)['is_enabled_hold_invoice'] == '1')
        //Key shorcut for pic hold invoice
        shortcuts.add('f9',function() {
            $('#hold_invoice_preloader').show();
            pickHoldInvoice();
        });

        // Pick hold invoice
        $(document).on('click', '#pick_hold_btn',function (e) {
            e.preventDefault();
            $('#hold_invoice_preloader').show();
            pickHoldInvoice();
        });

        function pickHoldInvoice() {
            $('#holdInvoiceModal').modal('show');
            $.ajax({
                url:"{{url('sales/pos/pick/hold/invoice/')}}",
                type:'get',
                success:function(data){
                    $('#hold_invoices').html(data);
                    $('#hold_invoice_preloader').hide();
                }
            });
        }
    @endif

    function showStock() {
        $('#stock_preloader').show();
        $('#showStockModal').modal('show');
        $.ajax({
            url:"{{route('sales.pos.branch.stock')}}",
            type:'get',
            success:function(data){
                $('#stock_modal_body').html(data);
                $('#stock_preloader').hide();
            }
        });
    }

    $(".cat-button").on("click", function(){
        $(this).addClass("active");
        $(this).siblings().removeClass("active");
    });

    var width = $(".function-sec .btn-bg").width();
    $(".function-sec .btn-bg").height(width / 1.2);
    if($(window).width() >= 992) {
        $(".function-sec .btn-bg").height(width / 1.4);
    }
    if($(window).width() >= 1200) {
        $(".function-sec .btn-bg").height(width / 1.6);
    }
</script>

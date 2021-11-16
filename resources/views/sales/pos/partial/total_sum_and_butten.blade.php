<div class="col-lg-3 p-1">
    <div class="pos-right-inner">
        <div class="check-out-woaper">
            <div class="function-sec">
                <div class="row">
                    @if (json_decode($generalSettings->pos, true)['is_disable_draft'] == '0')
                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="" class="bg-orange function-card" data-button_type="0" data-action_id="2"
                                id="submit_btn">
                                Draft
                                <p>F2</p>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if (json_decode($generalSettings->pos, true)['is_disable_quotation'] == '0')
                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="" class="bg-orange function-card" id="submit_btn" data-button_type="0"
                                data-action_id="4">
                                Quotation
                                <p>F4</p>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="" class="bg-orange function-card" id="exchange_btn" data-bs-toggle="modal"
                                data-bs-target="#exchangeModal">
                                Exchange
                                <p>F6</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="" class="bg-gren function-card" id="show_stock">
                                Stock
                                <p>Alt+C</p>
                            </a>
                        </div>
                    </div>

                    @if (json_decode($generalSettings->pos, true)['is_disable_hold_invoice'] == '0')
                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="" class="bg-gren function-card" id="submit_btn" data-button_type="0"
                                data-action_id="5">
                                Hold Invoice
                                <p>F8</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#" class="bg-gren function-card" id="pick_hold_btn">
                                Pick Hold
                                <p>F9</p>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="{{ route('settings.general.index') }}" class="bg-swit function-card">
                                Setup
                                <p>Ctrl+Q</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="" class="bg-swit function-card" id="submit_btn" data-button_type="0"
                                data-action_id="6">
                                Suspend
                                <p>Alt+A</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 px-2 py-1">
                        <div class="btn-bg">
                            <a href="#" class="bg-swit function-card" onclick="cancel(); return false;">
                                Cancel
                                <p>Ctrl+M</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper_input_btn">
                <div class="checkout-input-sec">

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white"><b>Total:</b></label>
                        <div class="col-sm-9">
                            <input readonly type="number" class="form-control sp-input" name="net_total_amount"
                                id="net_total_amount" value="0.00">
                        </div>
                    </div>

                    @if (json_decode($generalSettings->pos, true)['is_disable_order_tax'] == '0')
                    <div class="row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Vat/Tax:</label>
                        <div class="col-sm-9 ">
                            <select name="order_tax" class="form-control" id="order_tax">

                            </select>
                            <input type="number" class="d-none" name="order_tax_amount" id="order_tax_amount"
                                value="0.00">
                        </div>
                    </div>
                    @else
                    <input name="order_tax" type="hidden" id="order_tax" value="0.00">
                    <input type="hidden" name="order_tax_amount" id="order_tax_amount" value="0.00">
                    @endif

                    @if (json_decode($generalSettings->pos, true)['is_disable_discount'] == '0')
                    <div class="row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Discount:</label>
                        <div class="col-sm-9 ">
                            <input name="order_discount" type="number" step="any" class="form-control"
                                id="order_discount" value="0.00">
                            <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount"
                                value="0.00">
                            <input name="order_discount_type" class="d-none" id="order_discount_type" value="1">
                        </div>
                    </div>
                    @else
                    <input name="order_discount" type="hidden" id="order_discount" value="0.00">
                    <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount"
                        value="0.00">
                    <input name="order_discount_type" class="d-none" id="order_discount_type" value="1">
                    @endif

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Pre. Due:</label>
                        <div class="col-sm-9 ">
                            <input readonly class="form-control" type="number" step="any" name="previous_due"
                                id="previous_due" value="0.00" autocomplete="off">
                        </div>
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Payable:</label>
                        <div class="col-sm-9 ">
                            <input readonly class="form-control sp-input" type="number" step="any"
                                name="total_payable_amount" id="total_payable_amount" value="0.00">
                            <input class="d-none" type="number" step="any" name="total_invoice_payable"
                                id="total_invoice_payable" value="0.00">
                        </div>
                    </div>

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-6 col-form-label text-white">Cash Receive:</label>
                        <div class="col-sm-6 ">
                            <input type="number" step="any" name="paying_amount" id="paying_amount" value="0"
                                class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-6 col-form-label text-white">Change Amount:</label>
                        <div class="col-sm-6 ">
                            <input readonly type="text" name="change_amount" id="change_amount" value="0.00"
                                class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <label for="inputEmail3" class="col-sm-6 col-form-label text-danger"><b>Due :</b></label>
                        <div class="col-sm-6 ">
                            <input type="text" readonly name="total_due" id="total_due" value="0.00"
                                class="form-control sp-input text-danger">
                        </div>
                    </div>
                </div>

                <div class="sub-btn-sec">
                    <div class="row">
                        <div class="col-lg-4 col-12 p-1 pb-1">
                            @if (json_decode($generalSettings->pos, true)['is_show_credit_sale_button'] == '1')
                            <div class="btn-bg mb-1">
                                <a href="#" class="bg-orange btn-pos" data-button_type="0" id="full_due_button"><i
                                        class="fas fa-check"></i> Credit Sale</a>
                            </div>
                            @endif

                            @if (json_decode($generalSettings->reward_poing_settings, true)['enable_cus_point'] == '1')
                            <div class="btn-bg">
                                <a href="#" class="bg-parpal btn-pos" id="reedem_point_button">Reedem Point</a>
                            </div>
                            @endif
                        </div>

                        <div class="col-lg-4 col-6 p-1 pb-0 btn-bottom">
                            <div class="btn-bg">
                                <a href="#" class="bg-parpal function-card other_payment_method">
                                    <small><i class="fas fa-credit-card"></i> Other Method</small>
                                    <p>Ctrl+B</p>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-4 col-6 p-1 pb-0 btn-bottom">
                            <div class="btn-bg">
                                <a href="" class="bg-parpal function-card" id="submit_btn" data-button_type="1"
                                    data-action_id="1">
                                    <small><i class="far fa-money-bill-alt"></i> Cash</small>
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
    var defaultAccount = "{{ auth()->user()->branch ? auth()->user()->branch->default_account_id : $openedCashRegister->account_id }}";

    var actionMessage = 'Data inserted Successfull.';
    $('#pos_submit_form').on('submit', function(e){
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
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'Attention');
                    $('.submit_preloader').hide();
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
                        loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });
                    document.getElementById('search_product').focus();
                }
            }
        });
    });

    @if (json_decode($generalSettings->pos, true)['is_disable_hold_invoice'] == '0')
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
</script>

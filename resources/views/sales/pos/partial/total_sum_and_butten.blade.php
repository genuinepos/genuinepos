<div class="col-lg-3 p-1 pb-0">
    <div class="pos-right-inner">
        <div class="check-out-woaper">
            <div class="function-sec">
                <div class="row">
                    @if (json_decode($generalSettings->pos, true)['is_disable_draft'] == '0')
                        <div class="col-4 p-1">
                            <div class="btn-bg">
                                <a href="" class="bg-orange function-card" data-button_type="0" data-action_id="2" id="submit_btn">
                                    <small>Draft</small>
                                    <p>F2</p>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (json_decode($generalSettings->pos, true)['is_disable_quotation'] == '0')
                        <div class="col-4 p-1">
                            <div class="btn-bg">
                                <a href="" class="bg-orange function-card" id="submit_btn" data-button_type="0" data-action_id="4">
                                    <small>Quotation</small>
                                    <p>F4</p>
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="col-4 p-1">
                        <div class="btn-bg">
                            <a href="" class="bg-orange function-card" id="exchange_btn" data-bs-toggle="modal" data-bs-target="#exchangeModal">
                                <small>Exchange</small>
                                <p>F6</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 p-1">
                        <div class="btn-bg">
                            <a href="" class="bg-gren function-card" id="show_stock">
                                <small>Stock</small>
                                <p>Alt+C</p>
                            </a>
                        </div>
                    </div>

                    @if (json_decode($generalSettings->pos, true)['is_disable_hold_invoice'] == '0')
                        <div class="col-4 p-1">
                            <div class="btn-bg">
                                <a href="" class="bg-gren function-card" id="submit_btn" data-button_type="0" data-action_id="5">
                                    <small>Hold Invoice</small>
                                    <p>F8</p>
                                </a>
                            </div>
                        </div>

                        <div class="col-4 p-1">
                            <div class="btn-bg">
                                <a href="#" class="bg-gren function-card" id="pick_hold_btn">
                                    <small>Pick Hold</small>
                                    <p>F9</p>
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="col-4 p-1">
                        <div class="btn-bg">
                            <a href="{{ route('settings.general.index') }}" class="bg-swit function-card">
                                <small>Setup</small>
                                <p>Ctrl+Q</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 p-1">
                        <div class="btn-bg">
                            <a href="" class="bg-swit function-card" id="submit_btn" data-button_type="0" data-action_id="6">
                                <small>Suspend</small>
                                <p>Alt+A</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 p-1">
                        <div class="btn-bg">
                            <a href="#" class="bg-swit function-card" onclick="cancel(); return false;">
                                <small>Cancel</small>
                                <p>Ctrl+M</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="checkout-input-sec">

                <div class="row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label text-white"><b>Total:</b></label>
                    <div class="col-sm-9">
                        <input readonly type="number" class="form-control sp-input" name="net_total_amount" id="net_total_amount" value="0.00">
                    </div>
                </div>

                @if (json_decode($generalSettings->pos, true)['is_disable_order_tax'] == '0')
                    <div class="row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Vat/Tax:</label>
                        <div class="col-sm-9 ">
                            <select name="order_tax" class="form-control" id="order_tax">
                                                        
                            </select>
                            <input type="number" class="d-none" name="order_tax_amount" id="order_tax_amount" value="0.00">
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
                            <input name="order_discount" type="number" step="any" class="form-control" id="order_discount" value="0.00"> 
                            <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount" value="0.00">
                            <input name="order_discount_type" class="d-none" id="order_discount_type" value="1">
                        </div>
                    </div>
                @else
                    <input name="order_discount" type="hidden" id="order_discount" value="0.00"> 
                    <input name="order_discount_amount" type="number" class="d-none" id="order_discount_amount" value="0.00">
                    <input name="order_discount_type" class="d-none" id="order_discount_type" value="1">
                @endif

                <div class="row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Pre. Due:</label>
                    <div class="col-sm-9 ">
                        <input readonly class="form-control" type="number" step="any" name="previous_due" id="previous_due" value="0.00">
                    </div>
                    <label for="inputEmail3" class="col-sm-3 col-form-label text-white">Payable:</label>
                    <div class="col-sm-9 ">
                        <input readonly class="form-control sp-input" type="number" step="any" name="total_payable_amount" id="total_payable_amount" value="0.00">
                        <input class="d-none" type="number" step="any" name="total_invoice_payable" id="total_invoice_payable" value="0.00">
                    </div>
                </div>

                <div class="row">
                    <label for="inputEmail3" class="col-sm-6 col-form-label text-white">Cash Receive:</label>
                    <div class="col-sm-6 ">
                        <input type="text" name="paying_amount" id="paying_amount" value="0.00" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <label for="inputEmail3" class="col-sm-6 col-form-label text-white">Change Amount:</label>
                    <div class="col-sm-6 ">
                        <input readonly type="text" name="change_amount" id="change_amount" value="0.00" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <label for="inputEmail3" class="col-sm-6 col-form-label text-danger"><b>Due :</b></label>
                    <div class="col-sm-6 ">
                        <input type="text" readonly name="total_due" id="total_due" value="0.00" class="form-control sp-input text-danger">
                    </div>
                </div>
            </div>

            <div class="sub-btn-sec">
                <div class="row">
                    <div class="col-4 p-1">
                        @if (json_decode($generalSettings->pos, true)['is_show_credit_sale_button'] == '1')
                            <div class="btn-bg">
                                <a href="#" class="bg-orange btn-pos" data-button_type="0" id="full_due_button"><i class="fas fa-check"></i> Credit Sale</a>
                            </div>
                        @endif

                        @if (json_decode($generalSettings->pos, true)['is_show_partial_sale_button'] == '1')
                            <div class="btn-bg">
                                <a href="" class="bg-parpal btn-pos">Reedem Point</a>
                            </div>
                        @endif
                    </div>

                    <div class="col-4 p-1">
                        <div class="btn-bg">
                            <a href="#" class="bg-parpal function-card other_payment_method">
                                <small><i class="fas fa-credit-card"></i> Other Method</small>
                                <p>Ctrl+B</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-4 p-1">
                        <div class="btn-bg">
                            <a href="" class="bg-parpal function-card" id="submit_btn" data-button_type="1" data-action_id="1">
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

<script>
    var defaultAccount = "{{ auth()->user()->branch ? auth()->user()->branch->default_account_id : $openedCashRegister->account_id }}";
    $(document).on('click', '#submit_btn', function (e) {
        e.preventDefault();
        var action = $(this).data('action_id');
        var button_type = $(this).data('button_type');
        if (action == 1) {
            actionMessage = 'Successfully sale is created.';
        }else if (action == 2) {
            actionMessage = 'Successfully draft is created.';
        }else if (action == 4) {
            actionMessage = 'Successfully quotation is created.';
        }
        $('#action').val(action);
        $('#button_type').val(button_type);
        $('#b').val(action);
        $('#pos_submit_form').submit();
    });

    $(document).on('click', '#full_due_button', function (e) {
        e.preventDefault();
        fullDue();
    });

    function fullDue() {
        var total_payable_amount = $('#total_payable_amount').val();
        $('#paying_amount').val(parseFloat(0).toFixed(2));
        $('#change_amount').val(- parseFloat(total_payable_amount).toFixed(2));
        $('#total_due').val(parseFloat(total_payable_amount).toFixed(2));
        $('#action').val(1);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    }

    var actionMessage = 'Successfull data inserted.';
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
                console.log(data);
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'Attention'); 
                    $('.submit_preloader').hide();
                    return;
                }else if(data.suspendMsg){
                    toastr.success(data.suspendMsg);
                    $('.payment_method').hide();
                    $('#pos_submit_form')[0].reset();
                    $('#product_list').empty();
                    calculateTotalAmount();
                    $('.modal').modal('hide');
                    $('.submit_preloader').hide();
                    document.getElementById('search_product').focus();
                }else if(data.holdInvoiceMsg){
                    toastr.success(data.holdInvoiceMsg);
                    $('.payment_method').hide();
                    $('#pos_submit_form')[0].reset();
                    $('#product_list').empty();
                    calculateTotalAmount();
                    $('.modal').modal('hide');
                    $('.submit_preloader').hide();
                    document.getElementById('search_product').focus();
                }else {
                    $('.modal').modal('hide');
                    $('#pos_submit_form')[0].reset();
                    $('.payment_method').hide();
                    toastr.success(actionMessage);
                    $('#product_list').empty();
                    calculateTotalAmount();
                    $('.submit_preloader').hide();
                    $('#account_id').val(defaultAccount);
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

    function cancel() {
        $('#product_list').empty();
        $('.payment_method').hide();
        $('#pos_submit_form')[0].reset();
        calculateTotalAmount();
        $('#account_id').val(defaultAccount);
        toastr.error('Sale is cancelled.');
        document.getElementById('search_product').focus();
    }

    //Key shorcut for cancel
    shortcuts.add('ctrl+m',function() { 
        cancel();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f2',function() { 
        $('#action').val(2);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f4',function() { 
        $('#action').val(4);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f8',function() { 
        $('#action').val(5);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f10',function() { 
        $('#action').val(1);
        $('#button_type').val(1);
        $('#pos_submit_form').submit();
    });

    $('.other_payment_method').on('click', function (e) {
       e.preventDefault();
        $('#otherPaymentMethod').modal('show');
    });

    $(document).on('click', '#cancel_pay_mathod', function (e) {
        e.preventDefault();
        console.log('cancel_pay_mathod');
        $('#payment_method').val('Cash');
        $('.payment_method').hide();
        $('#account_id').val(defaultAccount);
        $('#otherPaymentMethod').modal('hide');
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('ctrl+b',function() { 
        $('#otherPaymentMethod').modal('show');
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+g',function() { 
        fullDue();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+s',function() {
        var total_payable = $('#total_payable_amount').val();
        var paying_amount = $('#paying_amount').val();
        var change = $('#change_amount').val();
        var due = $('#total_due').val();
        $('#modal_total_payable').val(parseFloat(total_payable).toFixed(2));
        $('#modal_paying_amount').val(parseFloat(paying_amount).toFixed(2));
        $('#modal_change_amount').val(parseFloat(change).toFixed(2));
        $('#modal_total_due').val(parseFloat(due).toFixed(2));
        $('#cashReceiveMethod').modal('show');
        setTimeout(function (){
            $('#modal_paying_amount').focus();
            $('#modal_paying_amount').select();
        }, 500);
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+a',function() { 
        $('#action').val(6);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('ctrl+q',function() { 
        window.location = "{{ route('settings.general.index') }}";
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+z',function() { 
        allSuspends();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+v',function() { 
        document.getElementById('search_product').focus();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+x',function() { 
        showRecentTransectionModal();
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

    $(document).on('click', '#show_stock',function (e) {
       e.preventDefault();
       showStock();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+c',function() { 
        showStock();
    });

    function showStock() {
        $('#stock_preloader').show();
        $('#showStockModal').modal('show');
        var warehouse_id = $('#warehouse_id').val() ? $('#warehouse_id').val() : null;
        $.ajax({
            url:"{{route('sales.pos.branch.stock')}}",
            data:{warehouse_id},
            type:'get',
            success:function(data){
                $('#stock_modal_body').html(data);
                $('#stock_preloader').hide();
            }
        });
    }
</script>
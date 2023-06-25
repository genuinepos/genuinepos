<script>
    $(document).on('click', '#submit_btn', function (e) {
        e.preventDefault();

        var action = $(this).data('action_id');
        if (action == 1) {
            actionMessage = 'Successfully sale is created.';
        }else if (action == 2) {
            actionMessage = 'Successfully draft is created.';
        }else if (action == 3) {
            actionMessage = 'Successfully challan is created.';
        }else if (action == 4) {
            actionMessage = 'Successfully quotation is created.';
        }

        $('#action').val(action);
        $('#pos_submit_form').submit();
    });

    $(document).on('click', '#full_due_button', function (e) {
        e.preventDefault();
        fullDue();
    });

    var actionMessage = 'Successfull data is inserted.';
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

                    toastr.error(data.errorMsg,'ERROR');
                    $('.submit_preloader').hide();
                    return;
                }else if(data.suspendMsg){

                    toastr.success(data.suspendMsg);
                    window.location = "{{route('sales.pos.create')}}";
                }else if(data.holdInvoiceMsg){
                    
                    toastr.success(data.holdInvoiceMsg);
                    window.location = "{{route('sales.pos.create')}}";
                }else {

                    $('.modal').modal('hide');
                    toastr.success(actionMessage);
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });

                    setTimeout(function () {
                        window.location = "{{ url()->previous(); }}";
                    }, 2000);
                }
            }
        });
    });

    function cancel() {
        toastr.error('Sale is cancelled.');
        window.location = "{{route('sales.pos.create')}}";
    }

    //Key shorcut for cancel
    shortcuts.add('ctrl+m',function() {
        cancel();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f2',function() {
        $('#action').val(2);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f4',function() {
        $('#action').val(4);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f8',function() {
        $('#action').val(5);
        $('#pos_submit_form').submit();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f10',function() {
        $('#action').val(1);
        $('#pos_submit_form').submit();
    });

    $('.other_payment_method').on('click', function (e) {
       e.preventDefault();
        $('#otherPaymentMethod').modal('show');
    });

    $(document).on('click', '#cancel_pay_mathod', function (e) {
        e.preventDefault();
        $('#payment_method').val('Cash');
        $('.payment_method').hide();
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
        $('#paying_amount').select();
        $('#paying_amount').focus();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+a',function() {
        $('#action').val(6);
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
    shortcuts.add('alt+x',function() {
        showRecentTransectionModal();
    });

    @if ($generalSettings['pos__is_enabled_hold_invoice'] == '1')
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
        $.ajax({
            url:"{{route('sales.pos.branch.stock')}}",
            type:'get',
            success:function(data){
                $('#stock_modal_body').html(data);
                $('#stock_preloader').hide();
            }
        });
    }

    function getEditSaleProducts() {
        $.ajax({
            url:"{{route('sales.pos.invoice.products', $sale->id)}}",
            success:function(invoiceProducts){
                $('#product_list').append(invoiceProducts);
                calculateTotalAmount();
            }
        });
    }
    getEditSaleProducts();

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

    var windowHeight = $(window).height();
    $('.set-height').height(windowHeight - 304 + 'px');
</script>

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

    var windowHeight = $(window).height();
    $('.set-height').height(windowHeight - 304 + 'px');
</script>

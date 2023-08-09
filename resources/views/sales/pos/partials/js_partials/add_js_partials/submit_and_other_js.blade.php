<script>
    var actionMessage = 'Data inserted Successfull.';

    $(document).on('click', '#submit_btn', function (e) {
        e.preventDefault();

        var action = $(this).data('action_id');
        var button_type = $(this).data('button_type');

        if (action == 1) {

            actionMessage = 'Sale created Successfully.';
        } else if (action == 2) {

            actionMessage = 'Draft created successfully.';
        } else if (action == 4) {

            actionMessage = 'Quotation created Successfully.';
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
        var total_receivable_amount = $('#total_receivable_amount').val();
        $('#received_amount').val(parseFloat(0).toFixed(2));
        $('#change_amount').val(- parseFloat(total_receivable_amount).toFixed(2));
        $('#total_due').val(parseFloat(total_receivable_amount).toFixed(2));
        $('#action').val(1);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    }

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

    // After submitting form successfully this function will be executed.
    function afterSubmitForm() {

        $('.modal').modal('hide');
        $('#pos_submit_form')[0].reset();
        $('.payment_method').hide();
        $('#product_list').empty();
        calculateTotalAmount();
        $('.submit_preloader').hide();
        var store_url = $('#store_url').val();
        $('#pos_submit_form').attr('action', store_url);
        activeSelectedItems();
    }

    $(document).keypress(".scanable", function (event) {
        if (event.which == '10' || event.which == '13') {
            event.preventDefault();
        }
    });

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

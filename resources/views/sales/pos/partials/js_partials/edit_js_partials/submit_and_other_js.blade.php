<script>
    $(document).on('click', '#submit_btn', function(e) {
        e.preventDefault();

        var action = $(this).data('action_id');
        if (action == 1) {
            actionMessage = 'Successfully sale is created.';
        } else if (action == 2) {
            actionMessage = 'Successfully draft is created.';
        } else if (action == 3) {
            actionMessage = 'Successfully challan is created.';
        } else if (action == 4) {
            actionMessage = 'Successfully quotation is created.';
        }

        $('#action').val(action);
        $('#pos_submit_form').submit();
    });

    var actionMessage = 'Successfull data is inserted.';
    $('#pos_submit_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');
        $('.submit_preloader').show();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.loading_button').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    $('.submit_preloader').hide();
                    return;
                } else if (data.suspendMsg) {

                    toastr.success(data.suspendMsg);
                    window.location = "{{ route('sales.pos.create') }}";
                } else if (data.holdInvoiceMsg) {

                    toastr.success(data.holdInvoiceMsg);
                    window.location = "{{ route('sales.pos.create') }}";
                } else {

                    $('.modal').modal('hide');
                    toastr.success(actionMessage);

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });

                    setTimeout(function() {
                        window.location = "{{ url()->previous() }}";
                    }, 2000);
                }
            }
        });
    });

    function getEditSaleProducts() {
        $.ajax({
            url: "{{ route('sales.pos.invoice.products', $sale->id) }}",
            success: function(invoiceProducts) {
                $('#product_list').append(invoiceProducts);
                calculateTotalAmount();
            }
        });
    }
    getEditSaleProducts();

    $(".cat-button").on("click", function() {
        $(this).addClass("active");
        $(this).siblings().removeClass("active");
    });

    var width = $(".function-sec .btn-bg").width();
    $(".function-sec .btn-bg").height(width / 1.2);

    if ($(window).width() >= 992) {
        $(".function-sec .btn-bg").height(width / 1.4);
    }

    if ($(window).width() >= 1200) {

        $(".function-sec .btn-bg").height(width / 1.6);
    }

    var windowHeight = $(window).height();
    $('.set-height').height(windowHeight - 304 + 'px');
</script>

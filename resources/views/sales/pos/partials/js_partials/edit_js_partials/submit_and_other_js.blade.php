<script>
    function saveAndPrintSuccessMsg() {

        var status = $('status').val();

        var actionMessage = 'Data inserted Successfull.';
        if (status == 1) {

            actionMessage = "{{ __('Sale Update Successfully.') }}";
        } else if (status == 2) {

            actionMessage = "{{ __('Draft Update successfully.') }}";
        } else if (status == 4) {

            actionMessage = "{{ __('Quotation Update Successfully.') }}";
        }

        return actionMessage;
    }

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.pos_submit_btn').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.pos_submit_btn', function() {

        var btnType = $(this).attr('id');
        if (btnType == 'credit_and_final') {

            $('#is_full_credit_sale').val(1);
        } else {

            $('#is_full_credit_sale').val(0);
        }

        var value = $(this).val();
        $('#status').val(value);

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#pos_submit_form').on('submit', function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');
        $('.submit_preloader').show();

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.submit_preloader').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'Attention');
                    return;
                } else if (data.suspendMsg) {

                    toastr.success(data.suspendMsg);
                    window.location = "{{ url()->previous() }}";
                } else if (data.holdInvoiceMsg) {

                    toastr.success(data.holdInvoiceMsg);
                    window.location = "{{ url()->previous() }}";
                } else {

                    var msg = saveAndPrintSuccessMsg();

                    toastr.success(msg);

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
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.submit_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact the support team.') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    toastr.error(error[0]);
                });
            }
        });

        if (isAjaxIn == false) {

            isAllowSubmit = true;
        }
    });

    document.onkeyup = function() {

        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.ctrlKey && e.which == 13) { // Ctrl + Enter

            $('#final').click();
            return false;
        } else if (e.which == 27) { //Esc

            $('.variant_list_area').empty();
            $('.select_area').hide();
            $('.modal').modal('hide');
            return false;
        }
    }

    $(document).on('click', function(e) {

        if ($(e.target).closest(".select_area").length === 0) {

            $('.select_area').hide();
            $('#list').empty();
        }
    });

    $(document).on('click enter', '#final_and_quick_cash_receive', function(e) {

        $('#final').click();
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        // var status = $('#status').val();
        var nextId = $(this).data('next');

        if (e.which == 13) {
            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

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

<script>
    function saveAndPrintSuccessMsg() {

        var status = $('status').val();

        var actionMessage = 'Data inserted Successfull.';
        if (status == 1) {

            actionMessage = "{{ __('Sale created Successfully.') }}";
        } else if (status == 2) {

            actionMessage = "{{ __('Draft created successfully.') }}";
        } else if (status == 4) {

            actionMessage = "{{ __('Quotation created Successfully.') }}";
        }

        return actionMessage;
    }

    $(document).on('click', '#credit_and_final', function(e) {
        fullDue();
    });

    function fullDue() {
        var total_receivable_amount = $('#total_receivable_amount').val();
        $('#received_amount').val(parseFloat(0).toFixed(2));
        $('#change_amount').val(parseFloat(0).toFixed(2));
        $('#current_balance').val(parseFloat(total_receivable_amount).toFixed(2));
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

        var allTr = $('#product_list').find('tr');

        var pass = true;
        allTr.each(function(index, value) {

            var check = __chackStockLimitation($(this), index);

            if (check == false) {
                pass = false;
                return;
            }
        });

        if (pass == false) {

            $('.submit_preloader').hide();
            $('.loading_button').hide();
            return;
        }

        isAjaxIn = false;
        isAllowSubmit = false;
        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: request,
            async: true,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.submit_preloader').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'Attention');
                    return;
                } else if (data.suspendedInvoiceMsg) {

                    toastr.success(data.suspendedInvoiceMsg);
                    afterSubmitForm();
                    document.getElementById('search_product').focus();
                } else if (data.holdInvoiceMsg) {

                    toastr.success(data.holdInvoiceMsg);
                    afterSubmitForm();
                    document.getElementById('search_product').focus();
                } else {

                    var msg = saveAndPrintSuccessMsg();

                    toastr.success(msg);

                    afterSubmitForm();

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 1000,
                        header: null,
                    });

                    document.getElementById('search_product').focus();
                }

                var jobCardId = $('#job_card_id').val() ? $('#job_card_id').val() : '';


                if (jobCardId) {
                    setTimeout(function() {
                        window.location = "{{ route('services.job.cards.index') }}";
                    }, 2000);
                }
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;
                $('.loading_button').hide();
                $('.submit_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
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
        }
    }

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

        $("#customer_account_id").select2("destroy");
        $("#customer_account_id").select2();
        activeSelectedItems();

        @if ($saleScreenType == 3)
            $('#check_list_area').empty();

            $("#brand_id").select2("destroy");
            $("#brand_id").select2();

            $("#device_id").select2("destroy");
            $("#device_id").select2();

            $("#device_model_id").select2("destroy");
            $("#device_model_id").select2();

            $("#status_id").select2("destroy");
            $("#status_id").select2();
        @endif

        getSalesVoucherNo();
    }

    function getSalesVoucherNo() {

        var url = "{{ route('sales.helper.invoice.or.id') }}";
        var route = url.replace(':status', status);

        $.ajax({
            url: route,
            type: 'get',
            success: function(data) {

                $('#invoice_id').html(data);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    }

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

    var index = 0;

    function __chackStockLimitation(tr, index) {

        var quantity = tr.find('#quantity').val() ? tr.find('#quantity').val() : 0;

        var current_stock = tr.find('#current_stock').val();

        var productName = tr.find('#current_stock').data('product_name');
        var unitName = tr.find('#current_stock').data('unit_name');

        if (parseFloat(quantity) > parseFloat(current_stock)) {

            toastr.error("{{ __('Serial No: ') }} " + (index + 1) + ',' + " {{ __('Product Name: ') }}" + productName + " {{ __('Only ') }}" + current_stock + '/' + unitName + " {{ __('is available in this store/company.') }}");
            return false;
        }

        return true;
    }

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

<script>

    //Key shortcut for draft
    shortcuts.add('f2', function () {

        $('#draft').click();
        $('#draft_disabled').click();
    });

    //Key shortcut for quotation
    // shortcuts.add('f4', function () {

    //     $('#quotation').click();
    //     $('#quotation_disabled').click();
    // });

    shortcuts.add('alt+q', function () {

        $('#quotation').click();
        $('#quotation_disabled').click();
    });

    //Key shortcut for hold invoice
    shortcuts.add('f8', function () {

        $('#hold_invoice').click();
        $('#hold_invoice_disabled').click();
    });

    //Key shortcut for credit and final
    shortcuts.add('alt+a', function () {

        $('#credit_and_final').click();
        $('#credit_sale_disabled').click();
    });

    //Key shortcut for Final
    // shortcuts.add('f10', function () {

    //     $('#final').click();
    // });

    //Key shortcut for all payment method
    shortcuts.add('ctrl+b', function () {

        $('#otherPaymentMethod').modal('show');
        setTimeout(function () {

            $('#payment_method_id').focus();
        }, 500);
    });

    //Key shortcut for credit sale
    shortcuts.add('alt+g', function () {

        fullDue();
    });

    //Key shortcut for pic hold invoice
    shortcuts.add('alt+z', function () {

        $('#suspendedInvoiceBtn').click();
    });

    //Key shortcut for focus search product input
    shortcuts.add('alt+v', function () {

        document.getElementById('search_product').focus();
    });

    //Key shortcut for show recent transactions
    shortcuts.add('alt+x', function () {

        showRecentTransectionModal();
    });

    //Key shortcut for quick payment
    shortcuts.add('alt+s', function () {

        var total_receivable_amount = $('#total_receivable_amount').val();
        var received_amount = $('#received_amount').val();
        var change = $('#change_amount').val();
        var current_balance = $('#current_balance').val();

        $('#modal_total_receivable').val(parseFloat(total_receivable_amount).toFixed(2));
        $('#modal_received_amount').val(parseFloat(received_amount).toFixed(2));
        $('#modal_change_amount').val(parseFloat(change).toFixed(2));
        $('#modal_total_due').val(parseFloat(current_balance).toFixed(2));
        $('#cashReceiveMethod').modal('show');

        setTimeout(function () {

            $('#modal_received_amount').focus();
            $('#modal_received_amount').select();
        }, 500);
    });

    //Key shortcut for show current stock
    shortcuts.add('alt+c', function () {

        $('#showStockBtn').click();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f9',function() {

        $('#pick_hold_btn').click();
    });

    //Key shortcut for cancel
    shortcuts.add('ctrl+m', function () {

        cancel();
    });

    shortcuts.add('alt+r', function () {

        $('#received_amount').focus().select();
    });

    shortcuts.add('f6', function () {

        $('#exchange').click();
    });

    // shortcuts.add('alt+q', function () {

    //     $('#order_discount').focus().select();
    // });

    $(document).on('keyup', '#product_list', function (e) {

        var e = e || window.event; // for IE to cover IEs window event-object

        if(e.which == 46) { // Ctrl + Enter

            var preTr = $('.active_tr').prev();
            var next = $('.active_tr').next();
            $('.active_tr #remove_product_btn').click();

            if (preTr.length > 0) {

                $(preTr).find('#edit_product_link').focus();
            }else if (next.length > 0) {

                $(next).find('#edit_product_link').focus();
            }

            return false;
        }
    });
</script>

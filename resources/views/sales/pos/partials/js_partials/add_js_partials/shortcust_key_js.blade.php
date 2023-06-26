<script>

    //Key shortcut for pic hold invoice
    shortcuts.add('f2', function () {

        $('#action').val(2);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    });

    //Key shortcut for pic hold invoice
    shortcuts.add('f4', function () {

        $('#action').val(4);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    });

    //Key shortcut for pic hold invoice
    shortcuts.add('f8', function () {

        $('#action').val(5);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    });

    //Key shortcut for pic hold invoice
    shortcuts.add('f10', function () {

        $('#action').val(1);
        $('#button_type').val(1);
        $('#pos_submit_form').submit();
    });

    //Key shortcut for all payment method
    shortcuts.add('ctrl+b', function () {

        $('#otherPaymentMethod').modal('show');
    });

    //Key shortcut for credit sale
    shortcuts.add('alt+g', function () {

        fullDue();
    });

    //Key shortcut for pic hold invoice
    shortcuts.add('alt+a', function () {

        $('#action').val(6);
        $('#button_type').val(0);
        $('#pos_submit_form').submit();
    });

    //Key shortcut for pic hold invoice
    shortcuts.add('alt+z', function () {

        allSuspends();
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
        var due = $('#total_due').val();

        $('#modal_total_receivable').val(parseFloat(total_receivable_amount).toFixed(2));
        $('#modal_received_amount').val(parseFloat(received_amount).toFixed(2));
        $('#modal_change_amount').val(parseFloat(change).toFixed(2));
        $('#modal_total_due').val(parseFloat(due).toFixed(2));
        $('#cashReceiveMethod').modal('show');

        setTimeout(function () {

            $('#modal_received_amount').focus();
            $('#modal_received_amount').select();
        }, 500);
    });

    //Key shortcut for show current stock
    shortcuts.add('alt+c', function () {

        showStock();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f9',function() {

        $('#hold_invoice_preloader').show();
        pickHoldInvoice();
    });

    //Key shortcut for cancel
    shortcuts.add('ctrl+m', function () {

        cancel();
    });
</script>

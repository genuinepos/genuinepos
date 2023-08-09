<script>

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

    shortcuts.add('ctrl+m',function() {
        cancel();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('alt+c',function() {
        showStock();
    });

    //Key shorcut for pic hold invoice
    shortcuts.add('f9',function() {

        $('#hold_invoice_preloader').show();
        pickHoldInvoice();
    });
</script>

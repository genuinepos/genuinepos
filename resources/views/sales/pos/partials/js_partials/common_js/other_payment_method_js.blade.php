<script>
    $('.other_payment_method').on('click', function(e) {
        e.preventDefault();
        $('#otherPaymentMethod').modal('show');

        setTimeout(function() {

            $('#payment_method_id').focus().select();
        }, 500);
    });

    $(document).on('click', '#cancel_pay_mathod', function(e) {
        e.preventDefault();

        var firstPaymentMethod = $("#payment_method_id option:first").val()
        $("#payment_method_id").val(firstPaymentMethod);
        var accountId = $("#payment_method_id").find('option:selected').data('account_id');
        setMethodAccount(accountId);

        $('#otherPaymentMethod').modal('hide');
    });

    $('#payment_method_id').on('change', function() {

        var accountId = $(this).find('option:selected').data('account_id');
        setMethodAccount(accountId);
    });

    function setMethodAccount(accountId) {

        if (accountId) {

            $('#account_id').val(accountId);

            if ($('#account_id').val() == null) {

                $('#account_id option:first-child').prop("selected", true);
            }
        } else if (accountId === '') {

            $('#account_id option:first-child').prop("selected", true);
            return;
        }
    }

    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));
</script>

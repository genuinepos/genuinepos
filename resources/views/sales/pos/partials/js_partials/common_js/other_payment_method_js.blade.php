<script>

    $('.other_payment_method').on('click', function (e) {

        e.preventDefault();
        $('#otherPaymentMethod').modal('show');

        setTimeout(function() {

            $('#payment_method_id').focus().select();
        }, 500);
    });

    $(document).on('click', '#cancel_pay_mathod', function (e) {
        e.preventDefault();

        $('#payment_method_id option').prop('selected', function() {
            return this.defaultSelected;
        });

        $('#otherPaymentMethod').modal('hide');
    });

    $('#payment_method_id').on('change', function () {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#account_id').val(account_id);
        }else if(account_id === ''){

            // $('#account_id option:first-child').prop("selected", true);
            return;
        }
    }

    setMethodAccount($('#payment_method_id').find('option:selected').data('account_id'));
</script>

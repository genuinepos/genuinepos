<script>

    $('.other_payment_method').on('click', function (e) {

        e.preventDefault();
        $('#otherPaymentMethod').modal('show');
    });

    $(document).on('click', '#cancel_pay_mathod', function (e) {
        e.preventDefault();

        $('#payment_method_id option').prop('selected', function() {
            return this.defaultSelected;
        });

        $('#otherPaymentMethod').modal('hide');
    });
</script>

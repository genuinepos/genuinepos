<script>
    $('#sale_type').on('change', function(e) {

        var saleType = $(this).val();

        $('#installment_btn').addClass('d-none');
        $('#received_amount').prop('readonly', false);

        if (saleType == 2) {

            $('#received_amount').prop('readonly', true);
            $('#installment_btn').removeClass('d-none');
        }
    });
</script>

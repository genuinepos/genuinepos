<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script>
    $(document).ready(function() {

        $('.minimum_purchase_class').on('click', function() {

            var toggleValue = $(this).hasClass('active') ? 0 : 1;
            $('#minimum_purchase_input').val(toggleValue);

            if (toggleValue == 1) {

                $("#is_minimum_purchase_id").show();
                $('#minimum_purchase_amount').prop('required', true);
            } else {

                $("#is_minimum_purchase_id").hide();
                $('#minimum_purchase_amount').prop('required', false);
            }
        });

        $('.maximum_usage_class').on('click', function() {

            var toggleValue = $(this).hasClass('active') ? 0 : 1;
            $('#maximum_usage_input').val(toggleValue);

            if (toggleValue == 1) {

                $("#is_maximum_usage_id").show();
                $('#no_of_usage').prop('required', true);
            } else {

                $("#is_maximum_usage_id").hide();
                $('#no_of_usage').prop('required', false);
            }
        });

        $('#generate_code').on('click', function() {

            let code = Math.random().toString(36).substring(2, 7).toUpperCase();
            $("#code").val(code);
        });

        $(function() {

            $("#start_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#end_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    });
</script>

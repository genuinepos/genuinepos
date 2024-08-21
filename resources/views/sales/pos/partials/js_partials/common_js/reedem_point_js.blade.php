<script>
    var rpayment_settings = {
        enable_rp : "{{ $generalSettings['reward_point_settings__enable_cus_point'] }}",
        redeem_amount_per_unit_rp : "{{ $generalSettings['reward_point_settings__redeem_amount_per_unit_rp'] }}",
        min_order_total_for_redeem : "{{ $generalSettings['reward_point_settings__min_order_total_for_redeem'] }}",
        min_redeem_point : "{{ $generalSettings['reward_point_settings__min_redeem_point'] }}",
        max_redeem_point : "{{ $generalSettings['reward_point_settings__max_redeem_point'] }}",
    }

    $(document).on('click', '#reedem_point_button', function (e) {
        e.preventDefault();

        var exchangSaleId = $('#ex_sale_id').val();
        if (exchangSaleId) {

            toastr.error("{{ __('Reedem pointing system is not allowed when exchange is going on.') }}");
            return;
        }

        if (rpayment_settings.enable_rp == '1') {

            if ($('#customer_account_id').val()) {

                var pre_redeemed = $('#pre_redeemed').val() ? $('#pre_redeemed').val() : 0;
                var pre_redeemed_amount = $('#pre_redeemed_amount').val() ? $('#pre_redeemed_amount').val() : 0;

                var earned_point = $('#earned_point').val() ? $('#earned_point').val() : 0;
                $('#available_point').val(parseFloat(earned_point));

                $('#redeem_amount').val(parseFloat(pre_redeemed));
                $('#total_redeem_point').val(parseFloat(pre_redeemed_amount))
                $('#pointReedemModal').modal('show');

                setTimeout(function() {

                    $('#total_redeem_point').focus().select();
                }, 500);
            }else{

                toastr.error("{{ __('Please select a customer first.') }}");
                return;
            }
        }else{

            toastr.error("{{ __('Reaward pointing system is disabled.') }}");
        }
    });

    $(document).on('input', '#total_redeem_point', function () {

        var redeeming_point = $(this).val();
        var __point_amount = parseFloat(redeeming_point) * parseFloat(rpayment_settings.redeem_amount_per_unit_rp);
        $('#redeem_amount').val(parseFloat(__point_amount));
    });

    $(document).on('click', '#redeem_btn',function(e) {
        e.preventDefault();

        var available_point = $('#available_point').val() ? $('#available_point').val() : 0;
        var total_redeem_point = $('#total_redeem_point').val() ? $('#total_redeem_point').val() : 0;
        var redeem_amount = $('#redeem_amount').val() ? $('#redeem_amount').val() : 0;

        if (parseFloat(total_redeem_point) > parseFloat(available_point)) {

            toastr.error("{{ __('Only ') }}" + available_point + "{{ __('points is available.') }}");
            return;
        }

        var total_invoice_payable = $('#total_invoice_payable').val();
        if (rpayment_settings.min_order_total_for_redeem && total_invoice_payable < parseFloat(rpayment_settings.min_order_total_for_redeem)) {

            toastr.error("{{ __('Minimum sale amount is') }} "+rpayment_settings.min_order_total_for_redeem+"{{ __('to redeem the points.') }}");
            return;
        }

        if (rpayment_settings.min_redeem_point && parseFloat(total_redeem_point) < parseFloat(rpayment_settings.min_redeem_point)) {

            toastr.error("{{ __('Minimum redeem points is') }} "+rpayment_settings.min_redeem_point);
            return;
        }

        if (rpayment_settings.max_redeem_point && parseFloat(total_redeem_point) > parseFloat(rpayment_settings.max_redeem_point)) {

            toastr.error("{{ __('Maximum redeem points is') }} "+rpayment_settings.max_redeem_point);
            return;
        }

        var order_discount = $('#order_discount').val() ? $('#order_discount').val() : 0;
        var order_discount_amount = $('#order_discount_amount').val() ? $('#order_discount_amount').val() : 0;
        var calcDiscount = parseFloat(order_discount_amount) + parseFloat(redeem_amount);
        $('#order_discount').val(parseFloat(calcDiscount).toFixed(2));
        $('#order_discount_amount').val(parseFloat(calcDiscount).toFixed(2));
        var earned_point = $('#earned_point').val() ? $('#earned_point').val() : 0;
        var calcLastPoint = parseFloat(earned_point) - parseFloat(total_redeem_point);
        $('#earned_point').val(parseFloat(calcLastPoint));
        var calcLastAmount = parseFloat(calcLastPoint) * parseFloat(parseFloat(rpayment_settings.redeem_amount_per_unit_rp));
        $('#trial_point_amount').val(parseFloat(calcLastAmount).toFixed(2));
        var pre_redeemed = $('#pre_redeemed').val() ? $('#pre_redeemed').val() : 0;
        $('#pre_redeemed').val(parseFloat(total_redeem_point));
        $('#pre_redeemed_amount').val(parseFloat(redeem_amount).toFixed(2));
        calculateTotalAmount();
        $('#pointReedemModal').modal('hide');
    });
</script>

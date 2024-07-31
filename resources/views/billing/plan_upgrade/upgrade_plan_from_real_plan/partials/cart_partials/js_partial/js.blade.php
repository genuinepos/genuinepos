<script>
    $(document).on('click', '.single-nav', function() {

        var tabId = $(this).attr('data-tab');
        $('.single-tab').removeClass('active');
        // $('.' + tabId).addClass('active');
        $('.' + tabId).addClass('active');
        $('#' + tabId).addClass('active');
    });

    $('.single-payment-card .panel-body').hide();
    $('.single-payment-card .panel-header').on('click', function() {
        $(this).siblings().slideDown(300);
        $(this).parent().siblings().find('.panel-body').slideUp(300);
        $(this).find('input[type=checkbox]').prop('checked', true);
        $(this).parent().siblings().find('.panel-header').find('input[type=checkbox]').prop('checked', false);
    });
</script>

<script>
    $(document).on('submit', '#plan_upgrade_form', function(e) {
        e.preventDefault();

        let url = $(this).attr('action');
        var request = $(this).serialize();

        $('#submit_button').addClass('d-none');
        $('#loading_button').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'POST',
            data: request,
            success: function(res) {

                $('#submit_button').removeClass('d-none');
                $('#loading_button').addClass('d-none');

                $('.single-nav').removeClass('active');
                $('.single-tab').removeClass('active');
                $('#stepThreeTab').addClass('active');

                window.location = "{{ route('software.service.billing.index') }}";
            },
            error: function(err) {

                $('#submit_button').removeClass('d-none');
                $('#loading_button').addClass('d-none');

                if (err.status == 0) {

                    toastr.error('Net Connection Error.');
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }else if (err.status == 419) {

                    toastr.error("{{ __('CSRF token mismatch') }}");
                    return;
                }
            }
        });
    });
</script>

<script>
    $(document).on('click', '#togglePriceAdjustmentDetails', function(e) {
        e.preventDefault();
        $('#priceAdjustmentDetailsTable').toggle();
    });

    $(document).on('click', '#remove_applied_coupon', function(e) {
        e.preventDefault();
        var discount = $('#discount').val();
        var totalPayable = $('#total_payable').val();
        $('#coupon_code').val('');
        $('#coupon_id').val('');
        $('#coupon_success_msg').hide();
        $('#coupon_code_applying_area').show();

        var currentTotalPayable = parseFloat(totalPayable) + parseFloat(discount);
        $('#total_payable').val(parseFloat(currentTotalPayable).toFixed(0));
        $('.span_total_payable').html(bdFormat(parseFloat(currentTotalPayable).toFixed(0)));
        $('#discount').val(0);
        $('.span_discount').html(parseFloat(0).toFixed(2));
    });

    $(document).on('click', '#applyCouponBtn', function(e) {
        e.preventDefault();

        var coupon_code = $('#coupon_code').val();
        var total_payable = $('#total_payable').val();
        if (coupon_code == '') {

            toastr.error("{{ __('Please enter a valid coupon code.') }}");
            return;
        }

        $('#applyCouponBtn').hide();
        $('#applyCouponLodingBtn').show();
        var url = "{{ route('check.coupon.code') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                coupon_code,
                total_payable
            },
            success: function(data) {

                $('#applyCouponBtn').show();
                $('#applyCouponLodingBtn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#applied_coupon_code').html(data.code);
                $('#coupon_id').val(data.id);
                var discountPercent = data.percent;
                $('#discount_percent').val(data.percent);
                $('#coupon_success_msg').show();
                $('#coupon_code_applying_area').hide();
                var totalPayable = $('#total_payable').val();

                var discount = ((parseFloat(totalPayable) / 100) * parseFloat(discountPercent));
                $('#discount').val(parseFloat(discount));
                $('.span_discount').html('(' + data.percent + '%=' + bdFormat(parseFloat(discount).toFixed(0)) + ')');

                var currentTotalPayable = parseFloat(totalPayable) - parseFloat(discount);
                $('#total_payable').val(parseFloat(currentTotalPayable).toFixed(0));
                $('.span_total_payable').html(bdFormat(parseFloat(currentTotalPayable).toFixed(0)));

                toastr.success("{{ __('Coupon is applied successfully.') }}");
            },
            error: function(err) {

                $('#applyCouponBtn').show();
                $('#applyCouponLodingBtn').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });
</script>

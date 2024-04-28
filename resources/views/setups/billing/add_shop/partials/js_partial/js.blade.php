<script>
    /*---------------------------
    Tab Change Function
    ---------------------------*/
    $('.single-nav').on('click', function() {

        var tabId = $(this).attr('data-tab');

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

    /*---------------------------
    Product Quantity
    ---------------------------*/
    $('.quantity').each(function() {

        var spinner = jQuery(this),
            input = spinner.find('input[type="number"]'),
            btnUp = spinner.find('.quantity-up'),
            btnDown = spinner.find('.quantity-down'),
            min = input.attr('min'),
            max = input.attr('max');

        btnUp.on('click', function() {

            var oldValue = parseFloat(input.val());
            if (oldValue >= max) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue + 1;
            }

            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
            calculateCartAmount();
        });

        btnDown.on('click', function() {

            var oldValue = parseFloat(input.val());
            if (oldValue <= min) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue - 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
            calculateCartAmount();

        });

        calculateCartAmount();
    });

    $(document).on('change', '#shop_price_period', function() {

        $('.shop_price_period_label').removeClass('bg-danger');
        $(this).closest('label').addClass('bg-danger');

        var shop_price_period = $(this).val();
        var shop_price_per_month = $('#shop_price_per_month').val() ? $('#shop_price_per_month').val() : 0;
        var shop_price_per_year = $('#shop_price_per_year').val() ? $('#shop_price_per_year').val() : 0;
        var shop_lifetime_price = $('#shop_lifetime_price').val() ? $('#shop_lifetime_price').val() : 0;

        if (shop_price_period == 'month') {

            $('#period_count_header').html('Months');
            $('#shop_price').val(parseFloat(shop_price_per_month));
            $('#span_shop_price').html(bdFormat(shop_price_per_month));
            $('.shop_price_period_count').removeClass('d-none');
            $('#fixed_shop_price_period_text').html('');
        } else if (shop_price_period == 'year') {

            $('#period_count_header').html('Years');
            $('#shop_price').val(parseFloat(shop_price_per_year));
            $('#span_shop_price').html(bdFormat(shop_price_per_year));
            $('.shop_price_period_count').removeClass('d-none');
            $('#fixed_shop_price_period_text').html('');
        } else if (shop_price_period == 'lifetime') {

            $('#period_count_header').html('Years');
            $('#shop_price').val(parseFloat(shop_lifetime_price));
            $('#span_shop_price').html(bdFormat(shop_lifetime_price));
            $('.shop_price_period_count').addClass('d-none');
            $('#fixed_shop_price_period_text').removeClass('d-none');
            $('#fixed_shop_price_period_text').html('Lifetime');
        }

        calculateCartAmount();
    });

    function calculateCartAmount() {

        var shop_price_period = $('#shop_price_period:checked').val() ? $('#shop_price_period:checked').val() : 0;
        var shop_price = $('#shop_price').val() ? $('#shop_price').val() : 0;
        var increase_shop_count = $('#increase_shop_count').val() ? $('#increase_shop_count').val() : 0;
        var discount = $('#discount').val() ? $('#discount').val() : 0;
        var shop_price_period_count = $('#shop_price_period_count').val() ? $('#shop_price_period_count').val() : 0;
        var __shop_price_period_count = shop_price_period == 'month' || shop_price_period == 'year' ? parseFloat(shop_price_period_count) : 1;

        var shop_subtotal = (parseFloat(shop_price) * parseFloat(increase_shop_count)) * parseFloat(__shop_price_period_count);
        $('#shop_subtotal').val(parseFloat(shop_subtotal).toFixed(2));
        $('#span_shop_subtotal').html(bdFormat(shop_subtotal));

        var netTotal = parseFloat(shop_subtotal);
        $('#net_total').val(parseFloat(netTotal));
        $('.span_net_total').html(bdFormat(netTotal));

        $('#discount').val(parseFloat(discount).toFixed());
        var totalPayableAmount = parseFloat(shop_subtotal) - parseFloat(discount)

        $('.span_shop_increase_shop_count').html(parseInt(increase_shop_count));
        $('#total_payable').val(parseFloat(totalPayableAmount));
        $('.span_total_payable').html(bdFormat(totalPayableAmount));
    }
</script>

<script>
    $(document).on('submit', '#add_shop_form', function(e) {
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

                    toastr.error('Net Connetion Error.');
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });
</script>

<script>
    $(document).on('click', '#remove_applied_coupon', function(e) {
        e.preventDefault();
        var discount = $('#discount').val();
        var totalPayable = $('#total_payable').val();
        $('#coupon_code').val('');
        $('#coupon_id').val('');
        $('#coupon_success_msg').hide();
        $('#coupon_code_applying_area').show();

        var currentTotalPayable = parseFloat(totalPayable) + parseFloat(discount);
        $('#total_payable').val(parseFloat(currentTotalPayable));
        $('.span_total_payable').html(bdFormat(currentTotalPayable));
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
            data: { coupon_code, total_payable },
            success: function(data) {

                console.log(data);
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
            }, error: function(err) {

                $('#applyCouponBtn').show();
                $('#applyCouponLodingBtn').hide();
                if (err.status == 0) {

                    toastr.error('Net Connetion Error.');
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });
</script>

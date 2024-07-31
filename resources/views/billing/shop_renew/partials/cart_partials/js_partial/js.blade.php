<script>
    /*---------------------------
    Tab Change Function
    ---------------------------*/
    $('.single-nav').on('click', function(e) {
        e.preventDefault();
        var tabId = $(this).attr('data-tab');
        var planId = $('#plan_id').val();

        if (tabId == 'stepOneTab' && planId == '') {

            toastr.error('Please select a plan first.');
            return;
        }

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

    $(document).on('click', '.shop_period_qty_up', function() {

        var tr = $(this).closest('tr');

        var shopPricePeriodCount = tr.find('#shop_price_period_count');
        var oldValue = parseInt(shopPricePeriodCount.val());
        shopPricePeriodCount.val(parseInt(oldValue + 1))

        calculateCartRowAmountForShop($(this).closest('tr'));
        calculateCartAmount();
    });

    $(document).on('click', '.shop_period_qty_down', function() {

        var tr = $(this).closest('tr');
        var shopPricePeriodCount = tr.find('#shop_price_period_count');

        var oldValue = parseInt(shopPricePeriodCount.val());
        var min = shopPricePeriodCount.attr('min');
        var newValue = oldValue - 1;

        if (newValue >= parseInt(min)) {

            shopPricePeriodCount.val(parseInt(oldValue - 1));
            calculateCartRowAmountForShop($(this).closest('tr'));
        }

        calculateCartAmount();
    });

    $(document).on('click', '.business_period_up_btn', function() {

        var tr = $(this).closest('tr');
        var businessPricePeriodCount = tr.find('#business_price_period_count');

        var oldValue = parseInt(businessPricePeriodCount.val());
        var min = businessPricePeriodCount.attr('min');

        businessPricePeriodCount.val(parseInt(oldValue + 1));
        calculateCartRowAmountForBusiness($(this).closest('tr'));

        calculateCartAmount();
    });

    $(document).on('click', '.business_period_down_btn', function() {

        var tr = $(this).closest('tr');
        var businessPricePeriodCount = tr.find('#business_price_period_count');

        var oldValue = parseInt(businessPricePeriodCount.val());
        var min = businessPricePeriodCount.attr('min');
        var newValue = oldValue - 1;

        if (newValue >= parseInt(min)) {

            businessPricePeriodCount.val(parseInt(oldValue - 1));
            calculateCartRowAmountForBusiness($(this).closest('tr'));
        }

        calculateCartAmount();
    });

    $(document).on('change', '#shop_price_period', function() {

        var tr = $(this).closest('tr');

        var shop_price_period = $(this).val();
        var shop_price_per_month = $('#shop_price_per_month').val() ? $('#shop_price_per_month').val() : 0;
        var shop_price_per_year = $('#shop_price_per_year').val() ? $('#shop_price_per_year').val() : 0;
        var shop_lifetime_price = $('#shop_lifetime_price').val() ? $('#shop_lifetime_price').val() : 0;

        if (shop_price_period == 'month') {

            tr.find('#shop_price').val(parseFloat(shop_price_per_month));
            tr.find('#span_shop_price').html(bdFormat(shop_price_per_month));
            tr.find('.shop_price_period_count').removeClass('d-none');
            tr.find('#fixed_shop_price_period_text').html('');
        } else if (shop_price_period == 'year') {

            tr.find('#shop_price').val(parseFloat(shop_price_per_year));
            tr.find('#span_shop_price').html(bdFormat(shop_price_per_year));
            tr.find('.shop_price_period_count').removeClass('d-none');
            tr.find('#fixed_shop_price_period_text').html('');
        } else if (shop_price_period == 'lifetime') {

            tr.find('#shop_price').val(parseFloat(shop_lifetime_price));
            tr.find('#span_shop_price').html(bdFormat(shop_lifetime_price));
            tr.find('.shop_price_period_count').addClass('d-none');
            tr.find('#shop_price_period_count').val(1);
            tr.find('#fixed_shop_price_period_text').removeClass('d-none');
            tr.find('#fixed_shop_price_period_text').html('Lifetime');
        }

        calculateCartRowAmountForShop($(this).closest('tr'));
        calculateCartAmount();
    });

    $(document).on('change', '#business_price_period', function() {

        var tr = $(this).closest('tr');
        var business_price_period = $(this).val();
        var business_price_per_month = $('#business_price_per_month').val() ? $('#business_price_per_month').val() : 0;
        var business_price_per_year = $('#business_price_per_year').val() ? $('#business_price_per_year').val() : 0;
        var business_lifetime_price = $('#business_lifetime_price').val() ? $('#business_lifetime_price').val() : 0;

        if (business_price_period == 'month') {

            tr.find('#business_price').val(parseFloat(business_price_per_month));
            tr.find('#span_business_price').html(bdFormat(business_price_per_month));
            tr.find('.business_price_period_count').removeClass('d-none');
            tr.find('#fixed_business_price_period_text').html('');
        } else if (business_price_period == 'year') {

            tr.find('#business_price').val(parseFloat(business_price_per_year));
            tr.find('#span_business_price').html(bdFormat(business_price_per_year));
            tr.find('.business_price_period_count').removeClass('d-none');
            tr.find('#fixed_business_price_period_text').html('');
        } else if (business_price_period == 'lifetime') {

            tr.find('#business_price').val(parseFloat(business_lifetime_price));
            tr.find('#span_business_price').html(bdFormat(business_lifetime_price));
            tr.find('.business_price_period_count').addClass('d-none');
            tr.find('#business_price_period_count').val(1);
            tr.find('#fixed_business_price_period_text').removeClass('d-none');
            tr.find('#fixed_business_price_period_text').html('Lifetime');
        }

        calculateCartRowAmountForBusiness($(this).closest('tr'));
        calculateCartAmount();
    });

    $(document).on('click', '#remove_btn', function(e) {

        e.preventDefault();
        $(this).closest('tr').remove();
        calculateCartAmount();
    });

    function calculateCartRowAmountForBusiness(tr) {

        var businessPrice = tr.find('#business_price').val();
        var businessPricePeriodCount = tr.find('#business_price_period_count').val();
        var businessSubtotal = parseFloat(businessPrice) * parseFloat(businessPricePeriodCount);
        tr.find('#business_subtotal').val(parseFloat(businessSubtotal).toFixed(0));
        tr.find('#span_business_subtotal').html(bdFormat(parseFloat(businessSubtotal).toFixed(0)));
    }

    function calculateCartRowAmountForShop(tr) {

        var shopPrice = tr.find('#shop_price').val();
        var shopPricePeriodCount = tr.find('#shop_price_period_count').val();
        var shopSubtotal = parseFloat(shopPrice) * parseFloat(shopPricePeriodCount);
        tr.find('#shop_subtotal').val(parseFloat(shopSubtotal).toFixed(0));
        tr.find('#span_shop_subtotal').html(bdFormat(parseFloat(shopSubtotal).toFixed(0)));
    }

    function calculateCartAmount() {

        var shopSubtotals = document.querySelectorAll('#shop_subtotal');
        var businessSubtotal = $('#business_subtotal').val() ? $('#business_subtotal').val() : 0;
        var discount = $('#discount').val() ? $('#discount').val() : 0;

        var netTotal = 0;
        shopSubtotals.forEach(function(val) {

            netTotal += parseFloat(val.value);
        });

        var netTotal = parseFloat(netTotal) + parseFloat(businessSubtotal);
        $('#net_total').val(parseFloat(netTotal).toFixed(0));
        $('.span_net_total').html(bdFormat(parseFloat(netTotal).toFixed(0)));

        var totalPayableAmount = parseFloat(netTotal) - parseFloat(discount)

        $('#total_payable').val(parseFloat(totalPayableAmount).toFixed(0));
        $('.span_total_payable').html(bdFormat(parseFloat(totalPayableAmount).toFixed(0)));
    }

    calculateCartAmount();
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
                $('#discount').val(parseFloat(discount).toFixed(0));
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

                    toastr.error('Net Connection Error.');
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
    $(document).on('submit', '#shop_renew_form', function(e) {
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
            }, error: function(err) {

                $('#submit_button').removeClass('d-none');
                $('#loading_button').addClass('d-none');

                if (err.status == 0) {

                    toastr.error('Net Connection Error.');
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                console.log(err.responseJSON);
                console.log(Object.values(err.responseJSON.errors)[0]);
                console.log(Object.values(err.responseJSON.errors)[0]);
                toastr.error(err.responseJSON.errors);
                toastr.error(Object.values(err.responseJSON.errors)[0]);
            }
        });
    });
</script>

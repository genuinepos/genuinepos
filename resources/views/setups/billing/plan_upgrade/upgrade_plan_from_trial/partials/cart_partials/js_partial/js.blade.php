<script>
    /*---------------------------
    Tab Change Function
    ---------------------------*/
    $('.single-nav').on('click', function() {

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

    /*---------------------------
    Product Quantity
    ---------------------------*/
    $('.quantity').each(function() {

        var isTrialPlan = $('#is_trial_plan').val();
        var spinner = jQuery(this),
            input = spinner.find('input[type="number"]'),
            btnUp = spinner.find('.quantity-up'),
            btnDown = spinner.find('.quantity-down'),
            min = input.attr('min'),
            max = input.attr('max');

        btnUp.on('click', function() {

            if (isTrialPlan == 0 || isTrialPlan == undefined) {

                var oldValue = parseFloat(input.val());
                if (oldValue >= max) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue + 1;
                }

                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
                calculateCartAmount();
            }
        });

        btnDown.on('click', function() {

            if (isTrialPlan == 0 || isTrialPlan == undefined) {

                var oldValue = parseFloat(input.val());
                if (oldValue <= min) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue - 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
                calculateCartAmount();
            }
        });

        calculateCartAmount();
    });

    $(document).on('click', '.business_period_down_btn', function() {

        var isTrialPlan = $('#is_trial_plan').val();
        if (isTrialPlan == 0 || isTrialPlan == undefined) {

            var business_price_period_count = $('#business_price_period_count');

            var newVal = 0;
            var oldValue = parseInt(business_price_period_count.val());
            var min = $('#business_price_period_count').attr('min');
            if (oldValue <= min) {
                newVal = oldValue;
            } else {
                newVal = oldValue - 1;
            }

            business_price_period_count.val(parseInt(newVal))
            calculateCartAmount();
        }
    });

    $(document).on('click', '.business_period_up_btn', function() {

        var isTrialPlan = $('#is_trial_plan').val();
        if (isTrialPlan == 0 || isTrialPlan == undefined) {

            var business_price_period_count = $('#business_price_period_count');

            var newVal = 0;
            var oldValue = parseInt(business_price_period_count.val());
            var max = $('#business_price_period_count').attr('max');
            if (oldValue >= max) {
                newVal = oldValue;
            } else {
                newVal = oldValue + 1;
            }

            business_price_period_count.val(parseInt(newVal))
            calculateCartAmount();
        }
    });

    $(document).on('change', '#shop_price_period', function() {

        $('.shop_price_period_label').removeClass('bg-danger');
        $(this).closest('label').addClass('bg-danger');

        var isTrialPlan = $('#is_trial_plan').val();
        if (isTrialPlan == 0 || isTrialPlan == undefined) {

            var shop_price_period = $(this).val();
            var shop_price_per_month = $('#shop_price_per_month').val() ? $('#shop_price_per_month').val() : 0;
            var shop_price_per_year = $('#shop_price_per_year').val() ? $('#shop_price_per_year').val() : 0;
            var shop_lifetime_price = $('#shop_lifetime_price').val() ? $('#shop_lifetime_price').val() : 0;

            if (shop_price_period == 'month') {

                $('#period_count_header').html('Months');
                $('#plan_price').val(parseFloat(shop_price_per_month));
                $('#span_plan_price').html(bdFormat(shop_price_per_month));
                $('.shop_price_period_count').removeClass('d-none');
                $('#fixed_shop_price_period_text').html('');
            } else if (shop_price_period == 'year') {

                $('#period_count_header').html('Years');
                $('#plan_price').val(parseFloat(shop_price_per_year));
                $('#span_plan_price').html(bdFormat(shop_price_per_year));
                $('.shop_price_period_count').removeClass('d-none');
                $('#fixed_shop_price_period_text').html('');
            } else if (shop_price_period == 'lifetime') {

                $('#period_count_header').html('Years');
                $('#plan_price').val(parseFloat(shop_lifetime_price));
                $('#span_plan_price').html(bdFormat(shop_lifetime_price));
                $('.shop_price_period_count').addClass('d-none');
                $('#fixed_shop_price_period_text').removeClass('d-none');
                $('#fixed_shop_price_period_text').html('Lifetime');
            }

            calculateCartAmount();
        }
    });

    $(document).on('change', '#business_price_period', function() {

        var isTrialPlan = $('#is_trial_plan').val();

        if (isTrialPlan == 0 || isTrialPlan == undefined) {

            var business_price_period = $(this).val();
            var business_price_per_month = $('#business_price_per_month').val() ? $('#business_price_per_month').val() : 0;
            var business_price_per_year = $('#business_price_per_year').val() ? $('#business_price_per_year').val() : 0;
            var business_lifetime_price = $('#business_lifetime_price').val() ? $('#business_lifetime_price').val() : 0;

            if (business_price_period == 'month') {

                $('#business_price').val(parseFloat(business_price_per_month));
                $('#span_business_price').html(bdFormat(business_price_per_month));
                $('.business_price_period_count').removeClass('d-none');
                $('#business_fixed_price_period_text').html('');
            } else if (business_price_period == 'year') {

                $('#business_price').val(parseFloat(business_price_per_year));
                $('#span_business_price').html(bdFormat(business_price_per_year));
                $('.business_price_period_count').removeClass('d-none');
                $('#business_fixed_price_period_text').html('');
            } else if (business_price_period == 'lifetime') {

                $('#business_price').val(parseFloat(business_lifetime_price));
                $('#span_business_price').html(bdFormat(business_lifetime_price));
                $('.business_price_period_count').addClass('d-none');
                $('#business_fixed_price_period_text').removeClass('d-none');
                $('#business_fixed_price_period_text').html('Lifetime');
            }

            calculateCartAmount();
        }
    });

    function calculateCartAmount() {

        var isTrialPlan = $('#is_trial_plan').val();

        if (isTrialPlan == 0 || isTrialPlan == undefined) {

            var shop_price_period = $('#shop_price_period:checked').val() ? $('#shop_price_period:checked').val() : 0;
            var business_price_period = $('#business_price_period').val() ? $('#business_price_period').val() : 0;
            var plan_price = $('#plan_price').val() ? $('#plan_price').val() : 0;
            var business_price = $('#business_price').val() ? $('#business_price').val() : 0;
            var shop_count = $('#shop_count').val() ? $('#shop_count').val() : 0;
            var discount = $('#discount').val() ? $('#discount').val() : 0;
            var shop_price_period_count = $('#shop_price_period_count').val() ? $('#shop_price_period_count').val() : 0;
            var business_price_period_count = $('#business_price_period_count').val() ? $('#business_price_period_count').val() : 0;
            var __shop_price_period_count = shop_price_period == 'month' || shop_price_period == 'year' ? parseFloat(shop_price_period_count) : 1;
            var __business_price_period_count = business_price_period == 'month' || business_price_period == 'year' ? parseFloat(business_price_period_count) : 1;

            var shop_subtotal = (parseFloat(plan_price) * parseFloat(shop_count)) * parseFloat(__shop_price_period_count);
            $('#shop_subtotal').val(parseFloat(shop_subtotal).toFixed(2));
            $('#span_shop_subtotal').html(bdFormat(shop_subtotal));
            var businessSubtotal = (parseFloat(business_price) * parseFloat(__business_price_period_count));
            $('#business_subtotal').val(parseFloat(businessSubtotal).toFixed(2));
            $('#span_business_subtotal').html(bdFormat(businessSubtotal));

            var netTotal = parseFloat(shop_subtotal) + parseFloat(businessSubtotal);
            $('#net_total').val(parseFloat(netTotal));
            $('.span_net_total').html(bdFormat(netTotal));

            $('#discount').val(parseFloat(discount).toFixed());
            var totalPayableAmount = parseFloat(shop_subtotal) + parseFloat(businessSubtotal) - parseFloat(discount)

            $('.span_total_shop_count').html(parseInt(shop_count));
            $('#total_payable').val(parseFloat(totalPayableAmount));
            $('.span_total_payable').html(bdFormat(totalPayableAmount));
        }
    }

    $(document).on('change', '#has_business', function() {

        addBusiness($(this));
    });

    function addBusiness(hasbusiness) {

        var planId = $('#plan_id').val();
        var shop_price_period = $('#shop_price_period:checked').val() ? $('#shop_price_period:checked').val() : 0;
        var has_lifetime_period = $('#has_lifetime_period').val() ? $('#has_lifetime_period').val() : 0;
        var businessPricePerMonth = $('#business_price_per_month').val() ? $('#business_price_per_month').val() : 0;
        var businessPricePerYear = $('#business_price_per_year').val() ? $('#business_price_per_year').val() : 0;
        var businessLifetimePrice = $('#business_lifetime_price').val() ? $('#business_lifetime_price').val() : 0;

        var initialBusinessPrice = 0;
        if (shop_price_period == 'month') {

            initialBusinessPrice = parseFloat(businessPricePerMonth);
        } else if (shop_price_period == 'year') {

            initialBusinessPrice = parseFloat(businessPricePerYear);
        } else if (shop_price_period == 'lifetime') {

            initialBusinessPrice = parseFloat(businessLifetimePrice);
        }

        if (hasbusiness.is(':checked') == true) {

            if (planId == '') {

                toastr.error("{{ __('Please select a plan first.') }}");
                hasbusiness.prop('checked', false);
                return;
            }

            var html = '';
            html += '<tr id="add_business_tr">';
            html += '<td>'+"{{ __('Multi Store Management System') }}"+'</td>';
            html += '<td>';
            html += '<input type="hidden" name="business_price" id="business_price" value="' + parseFloat(initialBusinessPrice).toFixed(2) + '">';
            html += '<span class="price-txt">'+"{{ $planPriceCurrency }}"+' <span id="span_business_price">' + bdFormat(initialBusinessPrice) + '</span></span>';
            html += '</td>';

            html += '<td class="text-start">';
            html += '<label>Period</label>';
            html += '<select name="business_price_period" class="form-control" id="business_price_period">';
            html += '<option ' + (shop_price_period == 'month' ? "SELECTED" : '') + ' value="month">'+"{{ __('Monthly') }}"+'</option>';
            html += '<option ' + (shop_price_period == 'year' ? "SELECTED" : '') + ' value="year">'+"{{ __('Yearly') }}"+'</option>';

            if (has_lifetime_period == 1) {

                html += '<option ' + (shop_price_period == 'lifetime' ? "SELECTED" : '') + ' value="lifetime">'+"{{ __('Lifetime') }}"+'</option>';
            }

            html += '</select>';
            html += '</td>';

            html += '<td>';
            html += '<label>'+"{{ __('Period Count') }}"+'</label>';
            html += '<div class="product-count cart-product-count business_price_period_count ' + (shop_price_period == 'lifetime' ? "d-none" : '') + '">';
            html += '<div class="quantity rapper-quantity">';
            html += '<input readonly name="business_price_period_count" id="business_price_period_count" type="number" min="1" step="1" value="1">';
            html += '<div class="quantity-nav">';
            html += '<div class="quantity-button quantity-down business_period_down_btn">';
            html += '<i class="fa-solid fa-minus"></i>';
            html += '</div>';
            html += '<div class="quantity-button quantity-up business_period_up_btn">';
            html += '<i class="fa-solid fa-plus"></i>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div id="business_fixed_price_period_text">' + (shop_price_period == 'lifetime' ? "Lifetime" : '') + '</div>';
            html += '</td>';

            html += '<td>';
            html += '<input type="hidden" name="business_subtotal" id="business_subtotal" value="' + parseFloat(initialBusinessPrice).toFixed(2) + '">';
            html += '<span class="price-txt">'+"{{ $planPriceCurrency }}"+' <span id="span_business_subtotal">' + bdFormat(initialBusinessPrice) + '</span></span>';
            html += '</td>';
            html += '</tr>';

            $('#plan_price_table').append(html);
            calculateCartAmount();
        } else {

            $('#add_business_tr').remove();
            calculateCartAmount();
        }
    }

    addBusiness($('#has_business'));
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

                window.location = "{{ route('dashboard.index') }}";
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
                $('.span_discount').html('(' + data.percent + '%=' + bdFormat(discount) + ')');

                var currentTotalPayable = parseFloat(totalPayable) - parseFloat(discount);
                $('#total_payable').val(parseFloat(currentTotalPayable));
                $('.span_total_payable').html(bdFormat(currentTotalPayable));

                toastr.success("{{ __('Coupon is applied successfully.') }}");
            },
            error: function(err) {

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

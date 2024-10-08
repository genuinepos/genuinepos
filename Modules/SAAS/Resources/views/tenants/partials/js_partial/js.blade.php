<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('backend/js/number-bdt-formater.js') }}"></script>

<script>
    $('table').addClass('table table-striped');
    $('.ck-container ul').addClass('list-group');
    $('.ck-container ul li').addClass('list-group-item');

    // Domain Check
    var typingTimer; //timer identifier
    var doneTypingInterval = 800; //time in ms, 5 seconds for example
    var $input = $('#domain');

    //on keyup, start the countdown
    $input.on('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown
    $input.on('keydown', function() {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    var isAvailable = false;

    function doneTyping() {
        $('#domainPreview').html(`<span class="">🔍Checking availability...<span>`);
        var domain = $('#domain').val();
        if (domain == '') {

            $('#domainPreview').html('');
            return;
        }

        $.ajax({
            url: "{{ route('saas.domain.checkAvailability') }}",
            type: 'GET',
            data: {
                domain: domain
            },
            success: function(res) {

                if (res.isAvailable == true) {

                    isAvailable = true;
                    $('#domainPreview').html(`<span class="text-success">✔ Domain is available<span>`);
                } else if (res.isAvailable == false) {

                    isAvailable = false;
                    $('#domainPreview').html(`<span class="text-danger">❌ Domain is not available<span>`);
                }
            },
            error: function(err) {

                isAvailable = false;
                $('#domainPreview').html(`<span class="text-danger">❌ Domain is not available<span>`);
            }
        });
    }

    $(document).on('submit', '#tenantStoreForm', function(e) {
        e.preventDefault();

        let url = $('#tenantStoreForm').attr('action');
        $('#response-message').removeClass('d-none');
        var request = $(this).serialize();

        if (isAvailable == false) {

            toastr.error('Domain is not available');
            $('#domainPreview').html(`<span class="text-danger">❌ Domain is not available<span>`);
            $('#response-message').addClass('d-none');
            return;
        }

        $('#timespan').text(0);
        var myInterval = setInterval(function() {
            let currentValue = parseInt($('#timespan').text() || 0);
            $('#timespan').text(currentValue + 1);
        }, 1000);

        $.ajax({
            url: url,
            type: 'POST',
            data: request,
            success: function(res) {

                $('.error').html('');
                $('#response-message-text').addClass('text-success');
                $('#response-message-text').text("{{ __('Successfully created! Redirecting you to the list') }}");
                window.location = "{{ route('saas.tenants.index') }}";
            },
            error: function(err) {

                clearInterval(myInterval);
                $('.error').html('');
                $('#response-message').addClass('d-none');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change', '#plan_id', function() {

        var planId = $(this).val();
        var isTrialPlan = $(this).find('option:selected').data('is_tral_plan');
        var url = "{{ route('saas.plans.single.by.id', ':planId') }}";
        var route = url.replace(':planId', planId);

        var shopPricePeriod = $('#shop_price_period:checked').val();

        if ((shopPricePeriod == '' || shopPricePeriod == undefined) && isTrialPlan == 0) {

            toastr.error('Please select a price period first');
            $(this).val('');
            return;
        }

        if (planId == '') {
            return;
        }

        $.ajax({
            url: route,
            type: 'get',
            success: function(plan) {

                var pricePerMonth = parseFloat(planPriceIfLocationIsBd(plan.price_per_month)).toFixed(0);
                var pricePerYear = parseFloat(planPriceIfLocationIsBd(plan.price_per_year)).toFixed(0);
                var lifetimePrice = parseFloat(planPriceIfLocationIsBd(plan.lifetime_price)).toFixed(0);
                var businessPricePerMonth = parseFloat(planPriceIfLocationIsBd(plan.business_price_per_month)).toFixed(0);
                var businessPricePerYear = parseFloat(planPriceIfLocationIsBd(plan.business_price_per_year)).toFixed(0);
                var businessLifetimePrice = parseFloat(planPriceIfLocationIsBd(plan.business_lifetime_price)).toFixed(0);

                var shopPrice = 0;
                var businessPricePeriod = 0;

                if (shopPricePeriod == 'month') {
                    shopPrice = pricePerMonth;
                } else if (shopPricePeriod == 'year') {
                    shopPrice = pricePerYear;
                } else if (shopPricePeriod == 'lifetime') {
                    shopPrice = lifetimePrice;
                }

                if (shopPricePeriod == 'month') {
                    businessPricePeriod = businessPricePerMonth;
                } else if (shopPricePeriod == 'year') {
                    businessPricePeriod = businessPricePerYear;
                } else if (shopPricePeriod == 'lifetime') {
                    businessPricePeriod = businessLifetimePrice;
                }

                $('#add_business_tr').remove();
                $('#has_business').prop('checked', false);
                // $('#shop_price_period').prop('checked', true);
                $('#is_trial_plan').val(plan.is_trial_plan);
                $('#shop_price_per_month').val(pricePerMonth);
                $('#shop_price_per_year').val(pricePerYear);
                $('#shop_lifetime_price').val(lifetimePrice);
                $('#shop_price').val(shopPrice);
                $('#business_price_per_month').val(businessPricePerMonth);
                $('#business_price_per_year').val(businessPricePerYear);
                $('#business_lifetime_price').val(businessLifetimePrice);
                $('#span_shop_price').html(bdFormat(shopPrice));
                $('#shop_count').val(plan.is_trial_plan == 1 ? plan.trial_shop_count : 1);
                $('#shop_price_period_count').val(1);
                $('#shop_subtotal').val(shopPrice);
                $('#span_shop_subtotal').html(bdFormat(shopPrice));
                $('.span_total_shop_count').html(1);
                $('#net_total').val(shopPrice);
                $('.span_net_total').html(bdFormat(shopPrice));
                $('.span_total_shop_count').html(plan.is_trial_plan == 1 ? plan.trial_shop_count : 1);
                $('#total_payable').val(shopPrice);
                $('.span_total_payable').html(bdFormat(shopPrice));

                if (plan.is_trial_plan == 1) {

                    $('.shop_price_period_count').addClass('d-none');
                    $('#shop_fixed_price_period_text').removeClass('d-none');
                    $('#shop_fixed_price_period_text').html(plan.trial_days + ' days');
                    $('#payment_status').prop('required', false);
                    $('.payment-section').addClass('d-none');
                    $('#discount_percent').val(0);
                    $('#discount').val(0);
                    $('.span_discount ').html('0%=0.00');
                } else {

                    $('.shop_price_period_count').removeClass('d-none');
                    $('#shop_fixed_price_period_text').html('');
                    $('#shop_fixed_price_period_text').addClass('d-none');
                    $('#payment_status').prop('required', true);
                    $('.payment-section').removeClass('d-none');
                }

                calculateCartAmount();
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('change', '#payment_status', function() {

        $('.repayment_field').addClass('d-none');
        $('#repayment_date').prop('required', false);
        $('.payment_details_field').addClass('d-none');

        var paymentStatus = $(this).val();

        var isTrialPlan = $('#is_trial_plan').val();
        if (isTrialPlan == 0) {

            if (paymentStatus == 1) {

                $('.repayment_field').addClass('d-none');
                $('#repayment_date').prop('required', false);
                $('.payment_details_field').removeClass('d-none');
            } else if (paymentStatus == 0) {

                $('.repayment_field').removeClass('d-none');
                $('#repayment_date').prop('required', true);
                $('.payment_details_field').addClass('d-none');
            }
        } else {

            $('#repayment_date').prop('required', false);
        }
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('repayment_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY',
    });
</script>

<script>
    $(document).on('click', '.single-nav', function() {

        var tabId = $(this).attr('data-tab');
        var planId = $('#plan_id').val();

        if (tabId == 'stepTwoTab' && planId == '') {

            toastr.error('Please select a plan first.');
            return;
        }

        var tabId = $(this).attr('data-tab');
        $('.single-tab').removeClass('active');
        // $('.' + tabId).addClass('active');
        $('.' + tabId).addClass('active');
        $('#' + tabId).addClass('active');
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
            calculateCartAmount();
        }
    });

    $(document).on('change', '#shop_price_period', function() {

        var isTrialPlan = $('#is_trial_plan').val();
        if (isTrialPlan == 0 || isTrialPlan == undefined) {

            var shop_price_period = $(this).val();
            var shop_price_per_month = $('#shop_price_per_month').val() ? $('#shop_price_per_month').val() : 0;
            var shop_price_per_year = $('#shop_price_per_year').val() ? $('#shop_price_per_year').val() : 0;
            var shop_lifetime_price = $('#shop_lifetime_price').val() ? $('#shop_lifetime_price').val() : 0;

            if (shop_price_period == 'month') {

                $('#period_count_header').html('Months');
                $('#shop_price').val(parseFloat(shop_price_per_month));
                $('#span_shop_price').html(bdFormat(parseFloat(shop_price_per_month).toFixed(0)));
                $('.shop_price_period_count').removeClass('d-none');
                $('#shop_fixed_price_period_text').html('');
            } else if (shop_price_period == 'year') {

                $('#period_count_header').html('Years');
                $('#shop_price').val(parseFloat(shop_price_per_year));
                $('#span_shop_price').html(bdFormat(parseFloat(shop_price_per_year).toFixed(0)));
                $('.shop_price_period_count').removeClass('d-none');
                $('#shop_fixed_price_period_text').html('');
            } else if (shop_price_period == 'lifetime') {

                $('#period_count_header').html('Years');
                $('#shop_price').val(parseFloat(shop_lifetime_price));
                $('#span_shop_price').html(bdFormat(parseFloat(shop_lifetime_price).toFixed(0)));
                $('.shop_price_period_count').addClass('d-none');
                $('#shop_fixed_price_period_text').removeClass('d-none');
                $('#shop_fixed_price_period_text').html('Lifetime');
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
                $('#span_business_price').html(bdFormat(parseFloat(business_price_per_month).toFixed(0)));
                $('.business_price_period_count').removeClass('d-none');
                $('#business_fixed_price_period_text').html('');
            } else if (business_price_period == 'year') {

                $('#business_price').val(parseFloat(business_price_per_year));
                $('#span_business_price').html(bdFormat(parseFloat(business_price_per_year).toFixed(0)));
                $('.business_price_period_count').removeClass('d-none');
                $('#business_fixed_price_period_text').html('');
            } else if (business_price_period == 'lifetime') {

                $('#business_price').val(parseFloat(business_lifetime_price));
                $('#span_business_price').html(bdFormat(parseFloat(business_lifetime_price).toFixed(0)));
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
            var shop_price = $('#shop_price').val() ? $('#shop_price').val() : 0;
            var business_price = $('#business_price').val() ? $('#business_price').val() : 0;
            var shop_count = $('#shop_count').val() ? $('#shop_count').val() : 0;
            var discount_percent = $('#discount_percent').val() ? $('#discount_percent').val() : 0;
            var shop_price_period_count = $('#shop_price_period_count').val() ? $('#shop_price_period_count').val() : 0;
            var business_price_period_count = $('#business_price_period_count').val() ? $('#business_price_period_count').val() : 0;
            var __shop_price_period_count = shop_price_period == 'month' || shop_price_period == 'year' ? parseFloat(shop_price_period_count) : 1;
            var __business_price_period_count = business_price_period == 'month' || business_price_period == 'year' ? parseFloat(business_price_period_count) : 1;

            var shop_subtotal = (parseFloat(shop_price) * parseFloat(shop_count)) * parseFloat(__shop_price_period_count);
            $('#shop_subtotal').val(parseFloat(shop_subtotal).toFixed(0));
            $('#span_shop_subtotal').html(bdFormat(parseFloat(shop_subtotal).toFixed(0)));
            var businessSubtotal = (parseFloat(business_price) * parseFloat(__business_price_period_count));
            $('#business_subtotal').val(parseFloat(businessSubtotal).toFixed(0));
            $('#span_business_subtotal').html(bdFormat(parseFloat(businessSubtotal).toFixed(0)));

            var netTotal = parseFloat(shop_subtotal) + parseFloat(businessSubtotal);
            $('#net_total').val(parseFloat(netTotal));
            $('.span_net_total').html(bdFormat(parseFloat(netTotal).toFixed(0)));

            // var discount = ((parseFloat(netTotal) / 100) * parseFloat(discount_percent));
            // $('#discount').val(parseFloat(discount));
            // $('.span_discount').html('(' + discount_percent + '%=' + bdFormat(parseFloat(discount).toFixed(0)) + ')');

            var discount = $('#discount').val() ? $('#discount').val() : 0;
            var discountPercent = (parseFloat(discount) / parseFloat(netTotal)) * 100;
            var __discountPercent = discountPercent ? discountPercent : 0;
            $('#discount_percent').val(parseFloat(__discountPercent).toFixed(2));
            $('.span_discount').html(parseFloat(__discountPercent).toFixed(2) + '%=' + bdFormat(parseFloat(discount).toFixed(0)));

            var totalPayableAmount = parseFloat(shop_subtotal) + parseFloat(businessSubtotal) - parseFloat(discount)
            $('.span_total_shop_count').html(parseInt(shop_count));
            $('#total_payable').val(parseFloat(totalPayableAmount));
            $('.span_total_payable').html(bdFormat(parseFloat(totalPayableAmount).toFixed(0)));
        }
    }

    $(document).on('input', '#discount', function() {
        calculateCartAmount();
    });

    /*---------------------------
    Payment Method Dropdown
    ---------------------------*/
    $('.single-payment-card .panel-body').hide();
    $('.single-payment-card .panel-header').on('click', function() {
        $(this).siblings().slideDown(300);
        $(this).parent().siblings().find('.panel-body').slideUp(300);
        $(this).find('input[type=checkbox]').prop('checked', true);
        $(this).parent().siblings().find('.panel-header').find('input[type=checkbox]').prop('checked', false);
    });

    $(document).on('change', '#has_business', function() {

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

        if ($(this).is(':checked') == true) {

            if (planId == '') {

                toastr.error('Please select a plan first.');
                $(this).prop('checked', false);
                return;
            }

            var html = '';
            html += '<tr id="add_business_tr">';
            html += '<td style="width: 30%;">' + "{{ __('Back Office') }}" + '</td>';
            html += '<td>';
            html += '<input type="hidden" name="business_price" id="business_price" value="' + parseFloat(initialBusinessPrice).toFixed(0) + '">';
            html += '<span class="price-txt">' + "{{ $planPriceCurrency }}" + ' <span id="span_business_price">' + bdFormat(parseFloat(initialBusinessPrice).toFixed(0)) + '</span></span>';
            html += '</td>';

            html += '<td class="text-start">';
            html += '<label>Period</label>';
            html += '<select name="business_price_period" class="form-control form-control-sm" id="business_price_period">';
            html += '<option ' + (shop_price_period == 'month' ? "SELECTED" : '') + ' value="month">Monthly</option>';
            html += '<option ' + (shop_price_period == 'year' ? "SELECTED" : '') + ' value="year">Yearly</option>';

            if (has_lifetime_period == 1) {

                html += '<option ' + (shop_price_period == 'lifetime' ? "SELECTED" : '') + ' value="lifetime">Lifetime</option>';
            }

            html += '</select>';
            html += '</td>';

            html += '<td>';
            html += '<label>Period Count</label>';
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
            html += '<div id="business_fixed_price_period_text" class="fw-bold">' + (shop_price_period == 'lifetime' ? "Lifetime" : '') + '</div>';
            html += '</td>';

            html += '<td>';
            html += '<input type="hidden" name="business_subtotal" id="business_subtotal" value="' + parseFloat(initialBusinessPrice).toFixed(0) + '">';
            html += '<span class="price-txt">' + "{{ $planPriceCurrency }}" + ' <span id="span_business_subtotal">' + bdFormat(parseFloat(initialBusinessPrice).toFixed(0)) + '</span></span>';
            html += '</td>';
            html += '</tr>';

            $('#plan_price_table').append(html);
            calculateCartAmount();
        } else {

            $('#add_business_tr').remove();
            calculateCartAmount();
        }
    });
</script>

<script>
    function planPriceIfLocationIsBd(amount = 0) {
        var gioInfo = @json(\Modules\SAAS\Utils\GioInfo::getInfo());

        country = gioInfo['country'];
        currencyRateInUsd = parseFloat(gioInfo['currency_rate']);
        if (gioInfo['country'] == 'bangladesh') {

            return parseFloat(amount * currencyRateInUsd).toFixed(0);
        } else {

            return parseFloat(amount).toFixed(0);
        }
    }
</script>

<script>
    // $(document).on('click', '#remove_applied_coupon', function(e) {
    //     e.preventDefault();

    //     $('#coupon_code').val('');
    //     $('#coupon_id').val('');
    //     $('#coupon_success_msg').hide();
    //     $('#coupon_code_applying_area').show();

    //     $('#discount').val(0);
    //     $('#discount_percent').val(0);
    //     $('.span_discount').html(parseFloat(0).toFixed(2));
    //     calculateCartAmount();
    // });

    // $(document).on('click', '#applyCouponBtn', function(e) {
    //     e.preventDefault();

    //     var coupon_code = $('#coupon_code').val();
    //     var total_payable = $('#total_payable').val();
    //     if (coupon_code == '') {

    //         toastr.error("{{ __('Please enter a valid coupon code.') }}");
    //         return;
    //     }

    //     $('#applyCouponBtn').hide();
    //     $('#applyCouponLodingBtn').show();
    //     var url = "{{ route('saas.coupons.code.check') }}";

    //     $.ajax({
    //         url: url,
    //         type: 'get',
    //         data: {
    //             coupon_code,
    //             total_payable
    //         },
    //         success: function(data) {

    //             $('#applyCouponBtn').show();
    //             $('#applyCouponLodingBtn').hide();
    //             if (!$.isEmptyObject(data.errorMsg)) {

    //                 toastr.error(data.errorMsg);
    //                 return;
    //             }

    //             $('#applied_coupon_code').html(data.code);
    //             $('#coupon_id').val(data.id);
    //             $('#discount_percent').val(data.percent);
    //             $('#coupon_success_msg').show();
    //             $('#coupon_code_applying_area').hide();
    //             calculateCartAmount();

    //             toastr.success("{{ __('Coupon is applied successfully.') }}");
    //         },
    //         error: function(err) {

    //             $('#applyCouponBtn').show();
    //             $('#applyCouponLodingBtn').hide();
    //             if (err.status == 0) {

    //                 toastr.error("{{ __('Net Connection Error.') }}");
    //                 return;
    //             } else if (err.status == 500) {

    //                 toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
    //                 return;
    //             }
    //         }
    //     });
    // });
</script>

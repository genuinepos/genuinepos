(function ($) {
    'use strict';
    $(document).ready(function () {

        /*---------------------------
        Tab Change Function
        ---------------------------*/
        $('.single-nav').on('click', function () {
            var tabId = $(this).attr('data-tab');
            var planId = $('#plan_id').val();
            if (tabId == 'checkOutTab' && planId == '') {

                toastr.error('Please select a plan first.');
                return;
            }

            $('#' + tabId).addClass('active').siblings().removeClass('active');
        });

        $('#proceedToCheckout').on('click', function () {
            // $(this).prop('disabled', true);
            var planId = $('#plan_id').val();
            if (planId == '') {

                toastr.error('Please select a plan first.');
                return;
            }

            $('#checkOutTab').addClass('active').siblings().removeClass('active');
            $('.single-nav.active').next('.single-nav').addClass('active').removeAttr('disabled');
        });

        $('#palceOrder').on('click', function () {
            // $(this).prop('disabled', true);
            $('#orderCompletedTab').addClass('active').siblings().removeClass('active');
            $('.single-nav.active').next('.single-nav').addClass('active').removeAttr('disabled');
        });

        /*---------------------------
       Product Quantity
       ---------------------------*/
        $('.quantity').each(function () {

            var isTrialPlan = $('#is_trial_plan').val();

            var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');

            btnUp.on('click', function () {

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

            btnDown.on('click', function () {

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

        $(document).on('change', '#price_period', function () {

            var isTrialPlan = $('#is_trial_plan').val();
            if (isTrialPlan == 0 || isTrialPlan == undefined) {

                var price_period = $(this).val();
                var price_per_month = $('#price_per_month').val() ? $('#price_per_month').val() : 0;
                var price_per_year = $('#price_per_year').val() ? $('#price_per_year').val() : 0;
                var lifetime_price = $('#lifetime_price').val() ? $('#lifetime_price').val() : 0;

                if (price_period == 'month') {

                    $('#period_count_header').html('Months');
                    $('#plan_price').val(parseFloat(price_per_month));
                    $('#span_plan_price').html(parseFloat(price_per_month).toFixed(2));
                    $('.period_count').removeClass('d-none');
                    $('#fixed_period_text').html('');
                } else if (price_period == 'year') {

                    $('#period_count_header').html('Years');
                    $('#plan_price').val(parseFloat(price_per_year));
                    $('#span_plan_price').html(parseFloat(price_per_year).toFixed(2));
                    $('.period_count').removeClass('d-none');
                    $('#fixed_period_text').html('');
                } else if (price_period == 'lifetime') {

                    $('#period_count_header').html('Years');
                    $('#plan_price').val(parseFloat(lifetime_price));
                    $('#span_plan_price').html(parseFloat(lifetime_price).toFixed(2));
                    $('.period_count').addClass('d-none');
                    $('#fixed_period_text').removeClass('d-none');
                    $('#fixed_period_text').html('Lifetime');
                    $('#fixed_period_text').html('Lifetime');
                }

                calculateCartAmount();
            }
        })

        function calculateCartAmount() {

            var isTrialPlan = $('#is_trial_plan').val();

            if (isTrialPlan == 0 || isTrialPlan == undefined) {

                var price_period = $('#price_period:checked').val();
                var plan_price = $('#plan_price').val() ? $('#plan_price').val() : 0;
                var shop_count = $('#shop_count').val() ? $('#shop_count').val() : 0;
                var discount = $('#discount').val() ? $('#discount').val() : 0;
                var period_count = $('#period_count').val() ? $('#period_count').val() : 0;
                var __period_count = price_period == 'month' || price_period == 'year' ? parseFloat(period_count) : 1;

                var subtotal = (parseFloat(plan_price) * parseFloat(shop_count)) * parseFloat(__period_count);
                var totalPayableAmount = parseFloat(subtotal) - parseFloat(discount)
                $('#subtotal').val(parseFloat(subtotal).toFixed(2));
                $('#span_subtotal').html(parseFloat(subtotal).toFixed(2));

                $('.span_total_shop_count').html(parseInt(shop_count));
                $('.span_subtotal_after_discount').html(parseFloat(subtotal).toFixed(2));
                $('#total_payable').val(parseFloat(totalPayableAmount));
                $('.span_total_payable').html(parseFloat(totalPayableAmount).toFixed(2));
            }
        }

        /*---------------------------
        Payment Method Dropdown
        ---------------------------*/
        $('.single-payment-card .panel-body').hide();
        $('.single-payment-card .panel-header').on('click', function () {
            $(this).siblings().slideDown(300);
            $(this).parent().siblings().find('.panel-body').slideUp(300);
            $(this).find('input[type=checkbox]').prop('checked', true);
            $(this).parent().siblings().find('.panel-header').find('input[type=checkbox]').prop('checked', false);
        });

        /*---------------------------
        Add Space After Every Four Number
        ---------------------------*/
        // document.getElementById('creditCardNumber').addEventListener('input', function (e) {
        //     e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{4})/g, '$1 ').trim();
        // });

        /*---------------------------
        Card Icon Show On Entering Card Number
        ---------------------------*/
        // $('#creditCardNumber').on('change paste keyup', function () {
        //     var val = $(this).val();
        //     if (val.length >= 19) {
        //         $(this).siblings('.symbol').css('opacity', '1');
        //     } else {
        //         $(this).siblings('.symbol').css('opacity', '0');
        //     }
        // });
    });
})(jQuery);

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

        $(document).on('click', '.business_period_down_btn', function () {

            var isTrialPlan = $('#is_trial_plan').val();
            if (isTrialPlan == 0 || isTrialPlan == undefined) {

                var business_period_count = $('#business_period_count');

                var newVal = 0;
                var oldValue = parseInt(business_period_count.val());
                var min = $('#business_period_count').attr('min');
                if (oldValue <= min) {
                    newVal = oldValue;
                } else {
                    newVal = oldValue - 1;
                }

                business_period_count.val(parseInt(newVal))
                calculateCartAmount();
            }
        });

        $(document).on('click', '.business_period_up_btn', function () {

            var isTrialPlan = $('#is_trial_plan').val();
            if (isTrialPlan == 0 || isTrialPlan == undefined) {

                var business_period_count = $('#business_period_count');

                var newVal = 0;
                var oldValue = parseInt(business_period_count.val());
                var max = $('#business_period_count').attr('max');
                if (oldValue >= max) {
                    newVal = oldValue;
                } else {
                    newVal = oldValue + 1;
                }

                business_period_count.val(parseInt(newVal))
                calculateCartAmount();
                calculateCartAmount();
            }
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
                }

                calculateCartAmount();
            }
        });

        $(document).on('change', '#business_price_period', function () {

            var isTrialPlan = $('#is_trial_plan').val();

            if (isTrialPlan == 0 || isTrialPlan == undefined) {

                var business_price_period = $(this).val();
                var business_price_per_month = $('#business_price_per_month').val() ? $('#business_price_per_month').val() : 0;
                var business_price_per_year = $('#business_price_per_year').val() ? $('#business_price_per_year').val() : 0;
                var business_lifetime_price = $('#business_lifetime_price').val() ? $('#business_lifetime_price').val() : 0;

                if (business_price_period == 'month') {

                    $('#business_price').val(parseFloat(business_price_per_month));
                    $('#span_business_price').html(parseFloat(business_price_per_month).toFixed(2));
                    $('.business_period_count').removeClass('d-none');
                    $('#business_fixed_period_text').html('');
                } else if (business_price_period == 'year') {

                    $('#business_price').val(parseFloat(business_price_per_year));
                    $('#span_business_price').html(parseFloat(business_price_per_year).toFixed(2));
                    $('.business_period_count').removeClass('d-none');
                    $('#business_fixed_period_text').html('');
                } else if (business_price_period == 'lifetime') {

                    $('#business_price').val(parseFloat(business_lifetime_price));
                    $('#span_business_price').html(parseFloat(business_lifetime_price).toFixed(2));
                    $('.business_period_count').addClass('d-none');
                    $('#business_fixed_period_text').removeClass('d-none');
                    $('#business_fixed_period_text').html('Lifetime');
                }

                calculateCartAmount();
            }
        });

        function calculateCartAmount() {

            var isTrialPlan = $('#is_trial_plan').val();

            if (isTrialPlan == 0 || isTrialPlan == undefined) {

                var price_period = $('#price_period:checked').val() ? $('#price_period:checked').val() : 0;
                var business_price_period = $('#business_price_period').val() ? $('#business_price_period').val() : 0;
                var plan_price = $('#plan_price').val() ? $('#plan_price').val() : 0;
                var business_price = $('#business_price').val() ? $('#business_price').val() : 0;
                var shop_count = $('#shop_count').val() ? $('#shop_count').val() : 0;
                var discount = $('#discount').val() ? $('#discount').val() : 0;
                var period_count = $('#period_count').val() ? $('#period_count').val() : 0;
                var business_period_count = $('#business_period_count').val() ? $('#business_period_count').val() : 0;
                var __period_count = price_period == 'month' || price_period == 'year' ? parseFloat(period_count) : 1;
                var __business_period_count = business_price_period == 'month' || business_price_period == 'year' ? parseFloat(business_period_count) : 1;

                var subtotal = (parseFloat(plan_price) * parseFloat(shop_count)) * parseFloat(__period_count);
                $('#subtotal').val(parseFloat(subtotal).toFixed(2));
                $('#span_subtotal').html(parseFloat(subtotal).toFixed(2));
                var businessSubtotal = (parseFloat(business_price) * parseFloat(__business_period_count));
                $('#business_subtotal').val(parseFloat(businessSubtotal).toFixed(2));
                $('#span_business_subtotal').html(parseFloat(businessSubtotal).toFixed(2));
                var totalPayableAmount = parseFloat(subtotal) + parseFloat(businessSubtotal) - parseFloat(discount)

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

        $(document).on('change', '#has_business', function () {

            var planId = $('#plan_id').val();
            var price_period = $('#price_period:checked').val() ? $('#price_period:checked').val() : 0;
            var has_lifetime_period = $('#has_lifetime_period').val() ? $('#has_lifetime_period').val() : 0;
            var businessPricePerMonth = $('#business_price_per_month').val() ? $('#business_price_per_month').val() : 0;
            var businessPricePerYear = $('#business_price_per_year').val() ? $('#business_price_per_year').val() : 0;
            var businessLifetimePrice = $('#business_lifetime_price').val() ? $('#business_lifetime_price').val() : 0;

            var initialBusinessPrice = 0;
            if (price_period == 'month') {

                initialBusinessPrice = parseFloat(businessPricePerMonth);
            } else if (price_period == 'year') {

                initialBusinessPrice = parseFloat(businessPricePerYear);
            } else if (price_period == 'lifetime') {

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
                html += '<td>Multi Store Management System</td>';
                html += '<td>';
                html += '<input type="hidden" name="business_price" id="business_price" value="' + parseFloat(initialBusinessPrice).toFixed(2) + '">';
                html += '<span class="price-txt"><span id="span_business_price">' + parseFloat(initialBusinessPrice).toFixed(2) + '</span></span>';
                html += '</td>';

                html += '<td class="text-start">';
                html += '<label>Period</label>';
                html += '<select name="business_price_period" class="form-control" id="business_price_period">';
                html += '<option ' + (price_period == 'month' ? "SELECTED" : '') + ' value="month">Monthly</option>';
                html += '<option ' + (price_period == 'year' ? "SELECTED" : '') + ' value="year">Yearly</option>';

                if (has_lifetime_period == 1) {

                    html += '<option ' + (price_period == 'lifetime' ? "SELECTED" : '') + ' value="lifetime">Lifetime</option>';
                }

                html += '</select>';
                html += '</td>';

                html += '<td>';
                html += '<label>Period Count</label>';
                html += '<div class="product-count cart-product-count business_period_count ' + (price_period == 'lifetime' ? "d-none" : '') + '">';
                html += '<div class="quantity rapper-quantity">';
                html += '<input readonly name="business_period_count" id="business_period_count" type="number" min="1" step="1" value="1">';
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
                html += '<div id="business_fixed_period_text">' + (price_period == 'lifetime' ? "Lifetime" : '') + '</div>';
                html += '</td>';

                html += '<td>';
                html += '<input type="hidden" name="business_subtotal" id="business_subtotal" value="' + parseFloat(initialBusinessPrice).toFixed(2) + '">';
                html += '<span class="price-txt"><span id="span_business_subtotal">' + parseFloat(initialBusinessPrice).toFixed(2) + '</span></span>';
                html += '</td>';
                html += '</tr>';

                $('#plan_price_table').append(html);
                calculateCartAmount();
            } else {

                $('#add_business_tr').remove();
                calculateCartAmount();
            }
        });
    });
})(jQuery);

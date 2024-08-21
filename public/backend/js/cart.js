(function ($) {
    'use strict';
    $(document).ready(function () {

        /*---------------------------
        Tab Change Function
        ---------------------------*/
        $('.single-nav').on('click', function () {
            var tabId = $(this).attr('data-tab');
            $('#' + tabId).addClass('active').siblings().removeClass('active');
        });
        $('#proceedToCheckout').on('click', function () {
            $(this).prop('disabled', true);
            $('#checkOutTab').addClass('active').siblings().removeClass('active');
            $('.single-nav.active').next('.single-nav').addClass('active').removeAttr('disabled');
        });
        $('#palceOrder').on('click', function () {
            if ($('#cash-on-delivery').is(':checked')) {
                console.log('cod checked');
                submitPlan();
                // $(this).prop('disabled', true);
                $('#orderCompletedTab').addClass('active').siblings().removeClass('active');
                $('.single-nav.active').next('.single-nav').addClass('active').removeAttr('disabled');
            }
        });

        function submitPlan() {
            console.log('submit Plan');
            let planId = $('#plan-id').val();

            $.ajax({
                url: '/setups/billing/cart/for/upgrade/plan/' + planId,
                type: 'POST',
                data: {
                    payment_method_provide_name: 'COD',
                    payment_method_name: 'COD',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response.success);
                }
            });
        }


        /*---------------------------
       Product Quantity
       ---------------------------*/
        $('.quantity').each(function () {
            var spinner = jQuery(this),
                input = spinner.find('input[type="number"]'),
                btnUp = spinner.find('.quantity-up'),
                btnDown = spinner.find('.quantity-down'),
                min = input.attr('min'),
                max = input.attr('max');

            btnUp.on('click', function () {
                var oldValue = parseFloat(input.val());
                if (oldValue >= max) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue + 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

            btnDown.on('click', function () {
                var oldValue = parseFloat(input.val());
                if (oldValue <= min) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue - 1;
                }
                spinner.find("input").val(newVal);
                spinner.find("input").trigger("change");
            });

        });

        /*---------------------------
        Cart Price Update In Single Product
        ---------------------------*/
        $('.quantity-button').each(function () {
            $(this).on('click', function () {
                var mainPrice = parseFloat($(this).parents('tr').find('.main-price').text());
                var qty = $(this).parents('.quantity').find('input').val();
                var updatePrice = mainPrice * qty;
                $(this).parents('tr').find('.total-price').text(updatePrice);
                $('#cartUpdate').removeAttr('disabled');
                $('.single-nav.active').next().removeClass('active').prop('disabled', true);
                $('.sub-total').text(updatePrice);
            });
        });


        /*---------------------------
        Delete Product From Cart
        ---------------------------*/
        $('.cart-delete').on('click', function () {
            $(this).parents('tr').remove();
            $('#cartUpdate').removeAttr('disabled');
            if ($('tbody tr').length <= 0) {
                $('tbody').append('<tr><td colspan="6"><span class="cart-msg">Your cart is empty :(</span></td></tr>');
                $('.shipping-check').prop('disabled', true);
                $('.sub-total, #totalPrice').text('0')
                $('#proceedToCheckout').prop('disabled', true);
                $('.single-nav.active').next().removeClass('active').prop('disabled', true);
            }
        });


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
        document.getElementById('creditCardNumber').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{4})/g, '$1 ').trim();
        });


        /*---------------------------
        Card Icon Show On Entering Card Number
        ---------------------------*/
        $('#creditCardNumber').on('change paste keyup', function () {
            var val = $(this).val();
            if (val.length >= 19) {
                $(this).siblings('.symbol').css('opacity', '1');
            } else {
                $(this).siblings('.symbol').css('opacity', '0');
            }
        });


        /*---------------------------
        Month Picker Intialize
        ---------------------------*/
        $("#datepicker").MonthPicker({
            Button: false
        });
    });
})(jQuery);

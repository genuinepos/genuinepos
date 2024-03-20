<script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
<script>
    // Domain Check
    var sendVerificationCode = false;
    var isAvailable = false;
    var typingTimer; //timer identifier
    var doneTypingInterval = 800; //time in ms, 5 seconds for example
    var $input = $('#domain');

    //on keyup, start the countdown
    $input.on('keyup', function() {

        if ($input.val() == '') {

            $('#domainPreview').html('');
            return;
        }

        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown
    $input.on('keydown', function() {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping() {
        $('#domainPreview').html(`<span class="">🔍Checking availability...<span>`);
        var domain = $('#domain').val();

        if ($input.val() == '') {

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

                if ($input.val() == '') {

                    $('#domainPreview').html('');
                    return;
                }

                if (res.isAvailable) {

                    isAvailable = true;
                    $('#domainPreview').html(`<span class="text-success">✔ Doamin is available<span>`);
                } else {

                    $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
                }
            },
            error: function(err) {
                isAvailable = false;
                console.log(err);
            }
        });
    }
    /*---------------------------
        Tab Change Function
        ---------------------------*/
    $('.single-nav').on('click', function() {

        var tabId = $(this).attr('data-tab');
        var planId = $('#plan_id').val();

        // var pricePeriod = $('#price_period').val();
        // console.log(pricePeriod);

        var shopPricePeriod = document.getElementsByName('shop_price_period');

        if (tabId == 'stepTwoTab') {

            if (shopPricePeriod[0].checked == false && shopPricePeriod[1].checked == false && shopPricePeriod[2].checked == false) {

                toastr.error("{{ __('Please select a price period(Month, Year, Or Lifetime)') }}");
                return;
            }
        }

        if (tabId == 'stepThreeTab') {

            if (isAvailable == false) {

                toastr.error("{{ __('Doamin is not available') }}");
                return;
            }

            if ($('#plan_id').val() == '') {
                toastr.error("{{ __('Please select a plan first.') }}");
                return;
            }

            if ($('#name').val() == '') {

                toastr.error("{{ __('Business name is required.') }}");
                return;
            }

            if ($('#domain').val() == '') {

                toastr.error("{{ __('Store url is required.') }}");
                return;
            }

            if ($('#fullname').val() == '') {

                toastr.error("{{ __('Fullname is required.') }}");
                return;
            }

            if ($('#email').val() == '') {

                toastr.error("{{ __('Email is required.') }}");
                return;
            }

            var validEmail = $('#email').val().match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );

            if (validEmail == null) {

                toastr.error("{{ __('Email format is invalid.') }}");
                return;
            }

            if ($('#currency_id').val() == '') {

                toastr.error("{{ __('Country is required.') }}");
                return;
            }

            if ($('#phone').val() == '') {

                toastr.error("{{ __('Phone number is required.') }}");
                return;
            }

            if ($('#password').val() == '') {

                toastr.error("{{ __('Password is required.') }}");
                return;
            }

            if ($('#password_confirmation').val() == '') {

                toastr.error("{{ __('Confirm password is required.') }}");
                return;
            }

            if ($('#password_confirmation').val() != $('#password').val()) {

                toastr.error("{{ __('Password and comfirm password is mismatch.') }}");
                return;
            }

            var pass = false;
            var request = $('#planConfirmForm').serialize();
            $.ajax({
                url: "{{ route('saas.guest.plan.confirm.validation') }}",
                type: 'POST',
                data: request,
                async: false,
                success: function(res) {

                    pass = true;
                }, error: function(err) {

                    pass = false;
                    toastr.error(Object.values(err.responseJSON.errors)[0]);
                }
            });

            if (pass == false) {

                return;
            }

            if (sendVerificationCode == false || $('#sendVerificationEmailAddress').val() != $('#email').val()) {

                sendVerificationEmail();
            }
        }

        $('.single-tab').removeClass('active');
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
                $('#plan_price').val(parseFloat(shop_price_per_month));
                $('#span_plan_price').html(parseFloat(shop_price_per_month).toFixed(2));
                $('.period_count').removeClass('d-none');
                $('#fixed_period_text').html('');
            } else if (shop_price_period == 'year') {

                $('#period_count_header').html('Years');
                $('#plan_price').val(parseFloat(shop_price_per_year));
                $('#span_plan_price').html(parseFloat(shop_price_per_year).toFixed(2));
                $('.period_count').removeClass('d-none');
                $('#fixed_period_text').html('');
            } else if (shop_price_period == 'lifetime') {

                $('#period_count_header').html('Years');
                $('#plan_price').val(parseFloat(shop_lifetime_price));
                $('#span_plan_price').html(parseFloat(shop_lifetime_price).toFixed(2));
                $('.period_count').addClass('d-none');
                $('#fixed_period_text').removeClass('d-none');
                $('#fixed_period_text').html('Lifetime');
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
                $('#span_business_price').html(parseFloat(business_price_per_month).toFixed(2));
                $('.business_price_period_count').removeClass('d-none');
                $('#business_fixed_price_period_text').html('');
            } else if (business_price_period == 'year') {

                $('#business_price').val(parseFloat(business_price_per_year));
                $('#span_business_price').html(parseFloat(business_price_per_year).toFixed(2));
                $('.business_price_period_count').removeClass('d-none');
                $('#business_fixed_price_period_text').html('');
            } else if (business_price_period == 'lifetime') {

                $('#business_price').val(parseFloat(business_lifetime_price));
                $('#span_business_price').html(parseFloat(business_lifetime_price).toFixed(2));
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

            var shopSubtotal = (parseFloat(plan_price) * parseFloat(shop_count)) * parseFloat(__shop_price_period_count);
            $('#shop_subtotal').val(parseFloat(shopSubtotal).toFixed(2));
            $('#span_shop_subtotal').html(parseFloat(shopSubtotal).toFixed(2));
            var businessSubtotal = (parseFloat(business_price) * parseFloat(__business_price_period_count));
            $('#business_subtotal').val(parseFloat(businessSubtotal).toFixed(2));
            $('#span_business_subtotal').html(parseFloat(businessSubtotal).toFixed(2));
            var netTotal = parseFloat(shopSubtotal) + parseFloat(businessSubtotal);
            $('#net_total').val(parseFloat(netTotal));
            $('.span_net_total').html(parseFloat(netTotal).toFixed(2));

            var totalPayableAmount = parseFloat(shopSubtotal) + parseFloat(businessSubtotal) - parseFloat(discount)
            $('.span_total_shop_count').html(parseInt(shop_count));
            $('#total_payable').val(parseFloat(totalPayableAmount));
            $('.span_total_payable').html(parseFloat(totalPayableAmount).toFixed(2));
        }
    }

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
            html += '<td>Multi Store Management System</td>';
            html += '<td>';
            html += '<input type="hidden" name="business_price" id="business_price" value="' + parseFloat(initialBusinessPrice).toFixed(2) + '">';
            html += '<span class="price-txt"><span id="span_business_price">' + parseFloat(initialBusinessPrice).toFixed(2) + '</span></span>';
            html += '</td>';

            html += '<td class="text-start">';
            html += '<label>Period</label>';
            html += '<select name="business_price_period" class="form-control" id="business_price_period">';
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
            html += '<div id="business_fixed_price_period_text">' + (shop_price_period == 'lifetime' ? "Lifetime" : '') + '</div>';
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
</script>

<script>
    function sendVerificationEmail(showMessage = 0) {

        var email = $('#email').val();
        var url = "{{ route('saas.guest.email.send.verification.code') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                email
            },
            success: function(data) {

                sendVerificationCode = true;
                $('#sendVerificationEmailAddress').val(email);
                if (showMessage == 1) {

                    toastr.success("{{ __('Email verification code has been resend successfully.') }}");
                }
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    console.log('Server error.');;
                    return;
                }
            }
        });
    }

    $(document).on('click', '#resendVerificationEmail', function(e) {
        var showMessage = 1;
        sendVerificationEmail(showMessage);
    });

    $(document).on('click', '#checkEmailVerificationCode', function(e) {

        var email = $('#email').val();
        var code = $('#verification_code').val();

        if (code == '') {

            toastr.error("{{ __('Please enter the verification code.') }}");
            return;
        }

        $('#checkEmailVerificationCode').addClass('d-none');
        var url = "{{ route('saas.guest.email.verification.code.match') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                email,
                code
            },
            success: function(data) {

                if (data == 0) {

                    toastr.error('Email Verification code does not match.');
                    $('#checkEmailVerificationCode').removeClass('d-none');
                    return;
                }

                $('#email-verification-section').addClass('d-none');
                $('#email-verification-success').removeClass('d-none');
                $('#planConfirmForm').submit();
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    console.log('Server error.');;
                    return;
                }
            }
        });
    });

    $(document).on('input', '#email', function(e) {

        var value = $(this).val();
        $('#showEmail').html(value);
    });
</script>

<script>
    $(document).on('submit', '#planConfirmForm', function(e) {
        e.preventDefault();

        let url = $('#planConfirmForm').attr('action');
        $('#response-message').removeClass('d-none');
        var request = $(this).serialize();

        if (isAvailable == false) {

            $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
            $('#response-message').addClass('d-none');
            return;
        }

        $('.single-nav').addClass('d-none');
        $('.cart-header').addClass('d-none');

        $('.single-nav').removeClass('active');
        $('.single-tab').removeClass('active');
        $('#stepFourTab').addClass('active');

        $('#timespan').text(0);
        setInterval(function() {
            let currentValue = parseInt($('#timespan').text() || 0);
            $('#timespan').text(currentValue + 1);
        }, 1000);

        $.ajax({
            url: url,
            type: 'POST',
            data: request,
            success: function(res) {

                $('#response-message').html('<span class="text-white"> Redirecting to <span class="fw-bold">'+res+'</span></span>');
                // $('#successSection').removeClass('d-none');

                window.location = res;
            },
            error: function(err) {

                $('#response-message').addClass('d-none');
                toastr.error('Something went wrong');
                toastr.error(err.responseJSON.message);
                location.reload(true);
            }
        });
    });

    var res = setInterval(function() {
        $('#preloader-animitation-section').addClass('d-none');
        setTimeout(() => {
            $('#preloader-animitation-section').removeClass('d-none');
        }, 100);

    }, 13000);
</script>

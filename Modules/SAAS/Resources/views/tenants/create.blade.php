<x-saas::admin-layout title="Add Customer">
    @push('css')
        <style>
            .tab-section .tab-nav .single-nav {
                height: 35px;
                font-size: 15px;
            }

            .quantity .quantity-nav .quantity-button {
                width: 35px;
                height: 28px;
                line-height: 29px;
            }

            .quantity {
                width: 140px;
                height: 28px;
            }

            .revel-table .table-responsive th {
                padding: 8px 30px;
            }

            .revel-table .table-responsive tr:last-child td {
                padding-bottom: 20px;
            }

            .revel-table .table-responsive tr:first-child td {
                padding-top: 20px;
            }

            .tab-section .tab-contents .cart-total-panel .title {
                font-size: 16px;
                height: 40px;
                line-height: 40px;
                padding: 0 20px;
            }

            .tab-section .tab-contents .cart-total-panel .panel-body .calculate-area ul li:nth-child(2) {
                margin-bottom: 16px;
            }

            .tab-section .tab-contents .cart-total-panel .panel-body .calculate-area ul li {
                font-size: 14px;
                margin-bottom: 16px;
            }

            .tab-section .tab-contents .cart-total-panel .panel-body {
                padding: 20px;
            }

            .cart-coupon-form input {
                height: 40px;
            }

            .def-btn {
                height: 40px;
                line-height: 40px;
                padding: 0 30px;
                font-size: 13px;
            }

            .tab-section .tab-contents .tab-next-btn {
                font-size: 13px;
                text-align: center;
            }

            .tab-section .tab-contents .billing-details .form-row {
                gap: 10px 20px;
            }

            .tab-section .tab-contents .billing-details .form-row .form-col-5 label,
            .tab-section .tab-contents .billing-details .form-row .form-col-10 label {
                font-size: 13px;
            }

            .tab-section .tab-contents .billing-details .form-row .form-control {
                font-size: 14px;
                height: 35px;
                line-height: 33px;
                padding: 0 15px;
            }

            .domain-field span.txt {
                font-size: 17px;
            }

            .tab-section .tab-contents .billing-details .title {
                font-size: 16px;
            }

            .plan-select {
                max-width: 172px;
            }
        </style>
    @endpush
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Add Customer') }}</h5>
                </div>
                <div class="panel-body">
                    <div class="tab-section py-120">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="tab-nav">
                                        <button class="single-nav active" data-tab="cartTab">
                                            <span class="txt">{{ __('Step One') }}</span>
                                            <span class="sl-no">{{ __('01') }}</span>
                                        </button>

                                        <button class="single-nav" data-tab="checkOutTab" disabled>
                                            <span class="txt">{{ __('Step Two') }}</span>
                                            <span class="sl-no">{{ __('02') }}</span>
                                        </button>

                                        <button class="single-nav" data-tab="orderCompletedTab" disabled>
                                            <span class="txt">{{ __('Step Three') }}</span>
                                            <span class="sl-no">{{ __('03') }}</span>
                                        </button>
                                    </div>

                                    <div class="tab-contents">
                                        <form id="tenantStoreForm" method="POST" action="{{ route('saas.tenants.store') }}">
                                            @csrf
                                            @include('saas::tenants.partials.step_one')

                                            @include('saas::tenants.partials.step_two')
                                        </form>

                                        <div class="single-tab" id="orderCompletedTab">
                                            <div class="check-icon">
                                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                                                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                                                </svg>
                                            </div>
                                            <div class="order-complete-msg">
                                                <h2>{{ __('Your Order Has Been Completed') }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
                $.ajax({
                    url: "{{ route('saas.domain.checkAvailability') }}",
                    type: 'GET',
                    data: {
                        domain: domain
                    },
                    success: function(res) {

                        if (res.isAvailable) {

                            isAvailable = true;
                            $('#domainPreview').html(`<span class="text-success">✔ Doamin is available<span>`);
                        }
                    },
                    error: function(err) {

                        isAvailable = false;
                        $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
                    }
                });
            }

            $(document).on('submit', '#tenantStoreForm', function(e) {
                e.preventDefault();

                let url = $('#tenantStoreForm').attr('action');
                $('#response-message').removeClass('d-none');
                var request = $(this).serialize();

                if (isAvailable == false) {

                    $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
                    $('#response-message').addClass('d-none');
                    return;
                }

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

                        $('#response-message-text').addClass('text-success');
                        $('#response-message-text').text("{{ __('Successfully created! Redirecting you to the list') }}");
                        window.location = "{{ route('saas.tenants.index') }}";
                    },
                    error: function(err) {

                        $('#response-message').addClass('d-none');
                        toastr.error(err.responseJSON.message);
                    }
                });
            });

            $(document).on('change', '#plan_id', function() {

                var planId = $(this).val();
                var url = "{{ route('saas.plans.single.by.id', ':planId') }}";
                var route = url.replace(':planId', planId);

                if (planId == '') {
                    return;
                }

                $.ajax({
                    url: route,
                    type: 'get',
                    success: function(plan) {

                        $('#add_business_tr').remove();
                        $('#has_business').prop('checked', false);
                        $('#shop_price_period').prop('checked', true);
                        $('#is_trial_plan').val(plan.is_trial_plan);
                        $('#shop_price_per_month').val(plan.price_per_month);
                        $('#shop_price_per_year').val(plan.price_per_year);
                        $('#shop_lifetime_price').val(plan.lifetime_price);
                        $('#plan_price').val(plan.price_per_month);
                        $('#business_price_per_month').val(plan.business_price_per_month);
                        $('#business_price_per_year').val(plan.business_price_per_year);
                        $('#business_lifetime_price').val(plan.business_lifetime_price);
                        $('#span_plan_price').html(plan.price_per_month);
                        $('#shop_count').val(plan.is_trial_plan == 1 ? plan.trial_shop_count : 1);
                        $('#shop_price_period_count').val(1);
                        $('#shop_subtotal').val(plan.price_per_month);
                        $('#span_shop_subtotal').html(plan.price_per_month);
                        $('.span_total_shop_count').html(1);
                        $('#net_total').val(plan.price_per_month);
                        $('.span_net_total').html(plan.price_per_month);
                        $('.span_total_shop_count').html(plan.is_trial_plan == 1 ? plan.trial_shop_count : 1);
                        $('#total_payable').val(plan.price_per_month);
                        $('.span_total_payable').html(plan.price_per_month);

                        if (plan.is_trial_plan == 1) {

                            $('.shop_price_period_count').addClass('d-none');
                            $('#shop_fixed_price_period_text').removeClass('d-none');
                            $('#shop_fixed_price_period_text').html(plan.trial_days + ' days');
                            $('#payment_status').prop('required', false);
                            $('.payment-section').addClass('d-none');
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

                            toastr.error("{{ __('Net Connetion Error.') }}");
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
            $('.single-nav').on('click', function() {

                var tabId = $(this).attr('data-tab');
                var planId = $('#plan_id').val();

                if (tabId == 'checkOutTab' && planId == '') {

                    toastr.error('Please select a plan first.');
                    return;
                }

                $('#' + tabId).addClass('active').siblings().removeClass('active');
            });

            $('#proceedToCheckout').on('click', function() {
                // $(this).prop('disabled', true);
                var planId = $('#plan_id').val();
                if (planId == '') {

                    toastr.error('Please select a plan first.');
                    return;
                }

                $('#checkOutTab').addClass('active').siblings().removeClass('active');
                $('.single-nav.active').next('.single-nav').addClass('active').removeAttr('disabled');
            });

            $('#palceOrder').on('click', function() {
                // $(this).prop('disabled', true);
                $('#orderCompletedTab').addClass('active').siblings().removeClass('active');
                $('.single-nav.active').next('.single-nav').addClass('active').removeAttr('disabled');
            });

            $('#verifyTab').on('click', function() {
                // $(this).prop('disabled', true);
                $('#orderCompletedTab').addClass('active').siblings().removeClass('active');
                $('.single-nav.active').next('.single-nav').addClass('active').removeAttr('disabled');
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
                        $('#plan_price').val(parseFloat(shop_price_per_month));
                        $('#span_plan_price').html(parseFloat(shop_price_per_month).toFixed(2));
                        $('.shop_price_period_count').removeClass('d-none');
                        $('#shop_fixed_price_period_text').html('');
                    } else if (shop_price_period == 'year') {

                        $('#period_count_header').html('Years');
                        $('#plan_price').val(parseFloat(shop_price_per_year));
                        $('#span_plan_price').html(parseFloat(shop_price_per_year).toFixed(2));
                        $('.shop_price_period_count').removeClass('d-none');
                        $('#shop_fixed_price_period_text').html('');
                    } else if (shop_price_period == 'lifetime') {

                        $('#period_count_header').html('Years');
                        $('#plan_price').val(parseFloat(shop_lifetime_price));
                        $('#span_plan_price').html(parseFloat(shop_lifetime_price).toFixed(2));
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

                    var shop_subtotal = (parseFloat(plan_price) * parseFloat(shop_count)) * parseFloat(__shop_price_period_count);
                    $('#shop_subtotal').val(parseFloat(shop_subtotal).toFixed(2));
                    $('#span_shop_subtotal').html(parseFloat(shop_subtotal).toFixed(2));
                    var businessSubtotal = (parseFloat(business_price) * parseFloat(__business_price_period_count));
                    $('#business_subtotal').val(parseFloat(businessSubtotal).toFixed(2));
                    $('#span_business_subtotal').html(parseFloat(businessSubtotal).toFixed(2));

                    var netTotal = parseFloat(shop_subtotal) + parseFloat(businessSubtotal);
                    $('#net_total').val(parseFloat(netTotal));
                    $('.span_net_total').html(parseFloat(netTotal).toFixed(2));

                    var totalPayableAmount = parseFloat(shop_subtotal) + parseFloat(businessSubtotal) - parseFloat(discount)
                    $('.span_total_shop_count').html(parseInt(shop_count));
                    $('#total_payable').val(parseFloat(totalPayableAmount));
                    $('.span_total_payable').html(parseFloat(totalPayableAmount).toFixed(2));
                }
            }

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
    @endpush
</x-saas::admin-layout>

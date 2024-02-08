<x-saas::admin-layout title="Add Customer">
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
                                            <span class="txt">Step One</span>
                                            <span class="sl-no">01</span>
                                        </button>

                                        <button class="single-nav" data-tab="checkOutTab" disabled>
                                            <span class="txt">Step Two</span>
                                            <span class="sl-no">02</span>
                                        </button>

                                        <button class="single-nav" data-tab="orderCompletedTab" disabled>
                                            <span class="txt">Step Three</span>
                                            <span class="sl-no">03</span>
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
                                                <h2>Your Order Has Been Completed</h2>
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
        <script>
            $('table').addClass('table table-striped');
            $('.ck-container ul').addClass('list-group');
            $('.ck-container ul li').addClass('list-group-item');

            // $('#btn').click(function() {
            //     $('#exampleModalToggle').modal('show');
            // });

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
                    }, error: function(err) {
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
                    },
                    error: function(err) {

                        $('#response-message').addClass('d-none');
                        toastr.error(err.responseJSON.message);
                    }
                });
            });

            $(document).on('change', '#plan_id', function () {

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

                        $('#price_period').prop('checked', true);
                        $('#is_trial_plan').val(plan.is_trial_plan);
                        $('#price_per_month').val(plan.price_per_month);
                        $('#price_per_year').val(plan.price_per_year);
                        $('#lifetime_price').val(plan.lifetime_price);
                        $('#plan_price').val(plan.price_per_month);
                        $('#span_plan_price').html(plan.price_per_month);
                        $('#shop_count').val(plan.is_trial_plan == 1 ? plan.trial_shop_count : 1);
                        $('#period_count').val(1);
                        $('#subtotal').val(plan.price_per_month);
                        $('#span_subtotal').html(plan.price_per_month);
                        $('.span_total_shop_count').html(1);
                        $('.span_subtotal_after_discount').html(plan.price_per_month);
                        $('.span_total_shop_count').html(plan.is_trial_plan == 1 ? plan.trial_shop_count : 1);
                        $('#total_payable').val(plan.price_per_month);
                        $('.span_total_payable').html(plan.price_per_month);

                        if (plan.is_trial_plan == 1) {

                            $('.period_count').addClass('d-none');
                            $('#fixed_period_text').removeClass('d-none');
                            $('#fixed_period_text').html(plan.trial_days + ' days');
                        }else {

                            $('.period_count').removeClass('d-none');
                            $('#fixed_period_text').html('');
                            $('#fixed_period_text').addClass('d-none');
                        }
                    }, error: function(err) {

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

            $(document).on('change', '#payment_status', function () {

                $('.repayment_field').addClass('d-none');
                $('#repayment_date').prop('required', false);
                $('.payment_details_field').addClass('d-none');

                var paymentStatus = $(this).val();
                if (paymentStatus == 1) {

                    $('.repayment_field').addClass('d-none');
                    $('#repayment_date').prop('required', false);
                    $('.payment_details_field').removeClass('d-none');
                }else if (paymentStatus == 0) {

                    $('.repayment_field').removeClass('d-none');
                    $('#repayment_date').prop('required', true);
                    $('.payment_details_field').addClass('d-none');
                }
            });

        </script>
    @endpush
</x-saas::admin-layout>

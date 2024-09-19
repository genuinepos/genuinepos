<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/number-bdt-formater.js') }}"></script>

<script>
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

        var discount = $('#discount').val() ? $('#discount').val() : 0;
        var discountPercent = (parseFloat(discount) / parseFloat(netTotal)) * 100;
        var __discountPercent = discountPercent ? discountPercent : 0;
        $('#discount_percent').val(parseFloat(__discountPercent).toFixed(2));
        $('.span_discount').html(parseFloat(__discountPercent).toFixed(2) + '%=' + bdFormat(parseFloat(discount).toFixed(0)));

        var totalPayableAmount = parseFloat(netTotal) - parseFloat(discount)

        $('#total_payable').val(parseFloat(totalPayableAmount).toFixed(0));
        $('.span_total_payable').html(bdFormat(parseFloat(totalPayableAmount).toFixed(0)));
    }

    calculateCartAmount();

    $(document).on('input', '#discount', function() {
        calculateCartAmount();
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

                toastr.success(res);

                window.location = "{{ url()->previous() }}";
            },
            error: function(err) {

                $('#submit_button').removeClass('d-none');
                $('#loading_button').addClass('d-none');

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
            }
        });
    });
</script>

<script>
    new Litepicker({
        singleMode: true,
        element: document.getElementById('payment_date'),
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

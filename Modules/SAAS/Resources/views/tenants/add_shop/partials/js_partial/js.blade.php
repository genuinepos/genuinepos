<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/number-bdt-formater.js') }}"></script>

<script>
    $('.quantity').each(function() {

        var spinner = jQuery(this),
            input = spinner.find('input[type="number"]'),
            btnUp = spinner.find('.quantity-up'),
            btnDown = spinner.find('.quantity-down'),
            min = input.attr('min'),
            max = input.attr('max');

        btnUp.on('click', function() {

            var shopPricePeriod = $('#shop_price_period:checked').val();

            if ((shopPricePeriod == '' || shopPricePeriod == undefined)) {

                toastr.error('Please select a price period first');
                spinner.find("input").val(0);
                return;
            }

            var oldValue = parseFloat(input.val());
            if (oldValue >= max) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue + 1;
            }

            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
            calculateCartAmount();
        });

        btnDown.on('click', function() {

            var oldValue = parseFloat(input.val());
            if (oldValue <= min) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue - 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
            calculateCartAmount();

        });

        calculateCartAmount();
    });

    $(document).on('change', '#shop_price_period', function() {

        $('.shop_price_period_label').removeClass('bg-danger');
        $(this).closest('label').addClass('bg-danger');

        var increaseShopCount = $('#increase_shop_count').val();

        if (increaseShopCount == '' || parseFloat(increaseShopCount) == 0) {

            $('#increase_shop_count').val(1);
        }

        var shop_price_period = $(this).val();
        var shop_price_per_month = $('#shop_price_per_month').val() ? $('#shop_price_per_month').val() : 0;
        var shop_price_per_year = $('#shop_price_per_year').val() ? $('#shop_price_per_year').val() : 0;
        var shop_lifetime_price = $('#shop_lifetime_price').val() ? $('#shop_lifetime_price').val() : 0;

        if (shop_price_period == 'month') {

            $('#period_count_header').html('Months');
            $('#shop_price').val(parseFloat(shop_price_per_month));
            $('#span_shop_price').html(bdFormat(shop_price_per_month));
            $('.shop_price_period_count').removeClass('d-none');
            $('#fixed_shop_price_period_text').html('');
        } else if (shop_price_period == 'year') {

            $('#period_count_header').html('Years');
            $('#shop_price').val(parseFloat(shop_price_per_year));
            $('#span_shop_price').html(bdFormat(shop_price_per_year));
            $('.shop_price_period_count').removeClass('d-none');
            $('#fixed_shop_price_period_text').html('');
        } else if (shop_price_period == 'lifetime') {

            $('#period_count_header').html('Years');
            $('#shop_price').val(parseFloat(shop_lifetime_price));
            $('#span_shop_price').html(bdFormat(shop_lifetime_price));
            $('.shop_price_period_count').addClass('d-none');
            $('#fixed_shop_price_period_text').removeClass('d-none');
            $('#fixed_shop_price_period_text').html('Lifetime');
        }

        calculateCartAmount();
    });

    function calculateCartAmount() {

        var shop_price_period = $('#shop_price_period:checked').val() ? $('#shop_price_period:checked').val() : 0;
        var shop_price = $('#shop_price').val() ? $('#shop_price').val() : 0;
        var increase_shop_count = $('#increase_shop_count').val() ? $('#increase_shop_count').val() : 0;
        var discount = $('#discount').val() ? $('#discount').val() : 0;
        var shop_price_period_count = $('#shop_price_period_count').val() ? $('#shop_price_period_count').val() : 0;
        var __shop_price_period_count = shop_price_period == 'month' || shop_price_period == 'year' ? parseFloat(shop_price_period_count) : 1;

        var shop_subtotal = (parseFloat(shop_price) * parseFloat(increase_shop_count)) * parseFloat(__shop_price_period_count);
        $('#shop_subtotal').val(parseFloat(shop_subtotal).toFixed(2));
        $('#span_shop_subtotal').html(bdFormat(shop_subtotal));

        var netTotal = parseFloat(shop_subtotal);
        $('#net_total').val(parseFloat(netTotal));
        $('.span_net_total').html(bdFormat(netTotal));

        var discount = $('#discount').val() ? $('#discount').val() : 0;
        var discountPercent = (parseFloat(discount) / parseFloat(netTotal)) * 100;
        var __discountPercent = discountPercent ? discountPercent : 0;
        $('#discount_percent').val(parseFloat(__discountPercent).toFixed(2));
        $('.span_discount').html(parseFloat(__discountPercent).toFixed(2) + '%=' + bdFormat(parseFloat(discount).toFixed(0)));

        var totalPayableAmount = parseFloat(netTotal) - parseFloat(discount)

        $('#total_payable').val(parseFloat(totalPayableAmount).toFixed(0));
        $('.span_total_payable').html(bdFormat(parseFloat(totalPayableAmount).toFixed(0)));

        $('.span_shop_increase_shop_count').html(parseInt(increase_shop_count));
    }

    $(document).on('input', '#discount', function() {
        calculateCartAmount();
    });

    $(document).on('blur', '#discount', function(e) {

        if ($(this).val() == '') {

            $(this).val(0)
        }
    });
</script>

<script>
    $(document).on('submit', '#add_shop_form', function(e) {
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

                if (!$.isEmptyObject(res.errorMsg)) {

                    toastr.error(res.errorMsg);
                    return;
                }

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

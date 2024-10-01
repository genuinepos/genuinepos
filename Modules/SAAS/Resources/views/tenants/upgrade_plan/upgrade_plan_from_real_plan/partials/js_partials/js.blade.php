<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/plugins/custom/toastrjs/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/number-bdt-formater.js') }}"></script>

<script>
    $(document).on('click', '#togglePriceAdjustmentDetails', function(e) {
        e.preventDefault();
        $('#priceAdjustmentDetailsTable').toggle();
    });

    $(document).on('input', '#discount', function(e) {

        var netTotal = $('#net_total').val() ? $('#net_total').val() : 0;
        var totalAdjustedAmount = $('#total_adjusted_amount').val() ? $('#total_adjusted_amount').val() : 0;

        var discount = $('#discount').val() ? $('#discount').val() : 0;
        var discountPercent = (parseFloat(discount) / parseFloat(netTotal)) * 100;
        var __discountPercent = discountPercent ? discountPercent : 0;
        $('#discount_percent').val(parseFloat(__discountPercent).toFixed(2));
        $('.span_discount').html(parseFloat(__discountPercent).toFixed(2) + '%=' + bdFormat(parseFloat(discount).toFixed(0)));

        var totalPayableAmount = parseFloat(netTotal) - parseFloat(totalAdjustedAmount) - parseFloat(discount)

        $('#total_payable').val(parseFloat(totalPayableAmount).toFixed(0));
        $('.span_total_payable').html(bdFormat(parseFloat(totalPayableAmount).toFixed(0)));
    });

    $(document).on('blur', '#discount', function(e) {

        if ($(this).val() == '') {

            $(this).val(0)
        }
    });
</script>

<script>
    $(document).on('submit', '#upgrade_plan_form', function(e) {
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

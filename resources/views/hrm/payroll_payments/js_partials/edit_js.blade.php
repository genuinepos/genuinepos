<script>
    function nullSave(value) {

        return value == null ? '' : value;
    }

    $(document).on('input', '#payment_paying_amount', function(e) {

        var payingAmount = $(this).val();

        if (parseFloat(payingAmount) && parseFloat(payingAmount) > 0) {

            document.getElementById('inword').innerHTML = inWords(parseInt(payingAmount)) + 'ONLY';
        } else {

            document.getElementById('inword').innerHTML = '';
        }
    });

    function calcTotalAmount() {

        calculateCurrentBalance();
    }

    function calculateCurrentBalance() {

        var payingAmount = $('#payment_paying_amount').val() ? $('#payment_paying_amount').val() : 0;
        var closingBalanceFlatAmount = $('#closing_balance_flat_amount').val() ? $('#closing_balance_flat_amount').val() : 0;
        var currentPaidAmount = $('#current_paid_amount').val() ? $('#current_paid_amount').val() : 0;
        var currentBalance = parseFloat(closingBalanceFlatAmount) - parseFloat(payingAmount) + parseFloat(currentPaidAmount);
        $('#closing_balance_string').val(bdFormat(currentBalance));
    }
</script>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.payment_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            if ($(this).attr('id') == 'payment_remarks' && $('#payment_debit_account_id').val() == undefined) {

                $('#payment_paying_amount').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.payment_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_payment_form').on('submit', function(e) {
        e.preventDefault();

        $('.payment_loading_btn').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.error').html('');
                $('.payment_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('.modal').modal('hide');

                var commonReloaderClass = $('.common-reloader').html();
                var forPayments = $('#for_payments').html();
                if (commonReloaderClass != undefined) {

                    $('.common-reloader').DataTable().ajax.reload();
                }else {

                    payrollsTable.ajax.reload();
                }
            }, error: function(err) {

                $('.payment_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_payment_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>

<script>
    var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
    var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

    function inWords(num) {
        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return;
        var str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
        return str;
    }

    var paidTotalAmount = "{{ $payment->total_amount }}";
    document.getElementById('inword').innerHTML = inWords(parseInt(paidTotalAmount)) + 'ONLY';
</script>

<script>
    var picker = new Litepicker({
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
        format: 'DD-MM-YYYY'
    });

    $(document).on('change keypress', '#payment_date', function(e) {

        if (e.which == 13) {
            picker.hide()
        }
    });
</script>


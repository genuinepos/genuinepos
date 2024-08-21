<script>
    function editMoneyReceipt(event) {

        var url = $(event).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#moneyReciptAddOrEditModal').html(data);
                $('#moneyReciptAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#mr_amount').focus().select();
                }, 500);
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    };

    function addMoneryReceiptVoucher(event) {

        var url = $(event).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#moneyReciptAddOrEditModal').html(data);
                $('#moneyReciptAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#mr_amount').focus();
                }, 500);
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }

    var deleteAbleMoneryReceiptVoucherTr = '';

    function deleteMoneyReceipt(event) {

        var url = $(event).attr('href');
        deleteAbleMoneryReceiptVoucherTr = $(event).closest('tr');

        $('#delete_money_receipt_form').attr('action', url);

        $.confirm({
            'title': 'Confirmation',
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-danger',
                    'action': function() {
                        $('#delete_money_receipt_form').submit();
                    }
                },
                'No': {
                    'class': 'no btn-modal-primary',
                    'action': function() {
                        console.log('Deleted canceled.');
                    }
                }
            }
        });
    }

    function printMoneyReceipt(event) {

        var url = $(event).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            success: function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 500,
                    header: null,
                });
                return;
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    };
</script>

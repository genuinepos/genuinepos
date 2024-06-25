<script>
    var paymentTable = $('#payments-table').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            },
        ],
        "processing": true,
        "serverSide": true,
        //aaSorting: [[0, 'asc']],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('payments.index', ['debitAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#payments_branch_id').val();
                d.from_date = $('#payments_from_date').val();
                d.to_date = $('#payments_to_date').val();
            }
        },
        columns: [{
                data: 'action'
            },
            {
                data: 'date',
                name: 'accountingVoucher.date'
            },
            {
                data: 'voucher_no',
                name: 'accountingVoucher.voucher_no',
                className: 'fw-bold'
            },
            {
                data: 'branch',
                name: 'accountingVoucher.branch.name'
            },
            {
                data: 'reference',
                name: 'accountingVoucher.purchaseRef.invoice_id'
            },
            {
                data: 'remarks',
                name: 'accountingVoucher.remarks'
            },
            {
                data: 'paid_from',
                name: 'accountingVoucher.voucherCreditDescription.account.name'
            },
            {
                data: 'payment_method',
                name: 'accountingVoucher.voucherCreditDescription.paymentMethod.name'
            },
            {
                data: 'transaction_no',
                name: 'accountingVoucher.voucherCreditDescription.transaction_no'
            },
            {
                data: 'cheque_no',
                name: 'accountingVoucher.voucherCreditDescription.cheque_no'
            },
            // {data: 'cheque_serial_no',name: 'accountingVoucher.voucherDebitDescription.cheque_serial_no'},
            {
                data: 'total_amount',
                name: 'accountingVoucher.voucherCreditDescription.cheque_serial_no',
                className: 'text-end fw-bold'
            },
            // {data: 'created_by',name: 'accountingVoucher.createdBy.name'},
        ],
        fnDrawCallback: function() {

            var total_amount = sum_table_col($('#payments-table'), 'total_amount');
            $('#payments_total_amount').text(bdFormat(total_amount));

            $('.data_preloader').hide();
        }
    });

    $(document).on('submit', '#filter_payments', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        paymentTable.ajax.reload();

        var filterObj = {
            branch_id: $('#payments_branch_id').val(),
            from_date: $('#payments_from_date').val(),
            to_date: $('#payments_to_date').val(),
        };

        getAccountClosingBalance(filterObj, 'for_payments', false);
    });

    $(document).on('click', '#addPayment', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            cache: false,
            async: false,
            dataType: 'html',
            success: function(data) {

                // window.history.forward(1);
                // location.reload(true);
                $('#addOrEditPaymentModal').empty();
                $('#addOrEditPaymentModal').html(data);
                $('#addOrEditPaymentModal').modal('show');

                setTimeout(function() {

                    $('#payment_date').focus().select();
                }, 500);
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

    $(document).on('click', '#editPayment', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            cache: false,
            async: false,
            dataType: 'html',
            success: function(data) {

                $('#addOrEditPaymentModal').empty();
                $('#addOrEditPaymentModal').html(data);
                $('#addOrEditPaymentModal').modal('show');

                setTimeout(function() {

                    $('#payment_date').focus().select();
                }, 500);
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
</script>

<script>
    var receiptTable = $('#receipts-table').DataTable({
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
            "url": "{{ route('receipts.index', ['creditAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#receipts_branch_id').val();
                d.from_date = $('#receipts_from_date').val();
                d.to_date = $('#receipts_to_date').val();
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
                name: 'accountingVoucher.saleRef.invoice_id'
            },
            {
                data: 'remarks',
                name: 'accountingVoucher.remarks'
            },
            // {data: 'received_from',name: 'account.name'},
            {
                data: 'received_to',
                name: 'accountingVoucher.voucherDebitDescription.account.name'
            },
            {
                data: 'payment_method',
                name: 'accountingVoucher.voucherDebitDescription.paymentMethod.name'
            },
            {
                data: 'transaction_no',
                name: 'accountingVoucher.voucherDebitDescription.transaction_no'
            },
            {
                data: 'cheque_no',
                name: 'accountingVoucher.voucherDebitDescription.cheque_no'
            },
            // {data: 'cheque_serial_no',name: 'accountingVoucher.voucherDebitDescription.cheque_serial_no'},
            {
                data: 'total_amount',
                name: 'accountingVoucher.voucherDebitDescription.cheque_serial_no',
                className: 'text-end fw-bold'
            },
            // {data: 'created_by',name: 'accountingVoucher.createdBy.name'},
        ],
        fnDrawCallback: function() {

            var total_amount = sum_table_col($('#receipts-table'), 'total_amount');
            $('#receipts_total_amount').text(bdFormat(total_amount));
            $('.data_preloader').hide();
        }
    });

    $(document).on('submit', '#filter_receipts', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        receiptTable.ajax.reload();

        var filterObj = {
            branch_id: $('#receipts_branch_id').val(),
            from_date: $('#receipts_from_date').val(),
            to_date: $('#receipts_to_date').val(),
        };

        getAccountClosingBalance(filterObj, 'for_receipts', false);
    });

    $(document).on('click', '#addReceipt', function(e) {
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
                $('#addOrEditReceiptModal').empty();
                $('#addOrEditReceiptModal').html(data);
                $('#addOrEditReceiptModal').modal('show');

                setTimeout(function() {

                    $('#receipt_date').focus().select();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('click', '#editReceipt', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            cache: false,
            async: false,
            dataType: 'html',
            success: function(data) {

                $('#addOrEditReceiptModal').empty();
                $('#addOrEditReceiptModal').html(data);
                $('#addOrEditReceiptModal').modal('show');

                setTimeout(function() {

                    $('#receipt_date').focus().select();
                }, 500);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });
</script>

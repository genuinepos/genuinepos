<script>
    //Get customer Ledgers by yajra data table
    var accountLedgerTable = $('#ledger-table').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary'
            },
        ],
        "lengthMenu": [
            [50, 100, 500, 1000, -1],
            [50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('accounts.ledger.index', [$contact?->account?->id]) }}",
            "data": function(d) {
                d.branch_id = $('#ledger_branch_id').val();
                d.branch_name = $('#ledger_branch_id').find('option:selected').data('branch_name');
                d.from_date = $('#ledger_from_date').val();
                d.to_date = $('#ledger_to_date').val();
                d.note = $('#ledger_note').val();
                d.transaction_details = $('#ledger_transaction_details').val();
                d.voucher_details = $('#ledger_voucher_details').val();
                d.inventory_list = $('#ledger_inventory_list').val();
            }
        },
        columns: [{
                data: 'date',
                name: 'account_ledgers.date'
            },
            {
                data: 'particulars',
                name: 'particulars'
            },
            {
                data: 'voucher_type',
                name: 'voucher_no'
            },
            {
                data: 'voucher_no',
                name: 'voucher_no'
            },
            {
                data: 'debit',
                name: 'account_ledgers.debit',
                className: 'text-end'
            },
            {
                data: 'credit',
                name: 'account_ledgers.credit',
                className: 'text-end'
            },
            {
                data: 'running_balance',
                name: 'account_ledgers.running_balance',
                className: 'text-end'
            },
        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_customer_ledgers', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        accountLedgerTable.ajax.reload();

        var filterObj = {
            branch_id: $('#ledger_branch_id').val() ? $('#ledger_branch_id').val() : null,
            from_date: $('#ledger_from_date').val() ? $('#ledger_from_date').val() : null,
            to_date: $('#ledger_to_date').val() ? $('#ledger_to_date').val() : null,
        };

        getAccountClosingBalance(filterObj, 'for_ledger', true);
    });

    $(document).on('click', '#printLedger', function(e) {
        e.preventDefault();

        var url = "{{ route('accounts.ledger.print', [$contact?->account?->id]) }}";

        var branch_id = $('#ledger_branch_id').val();
        var branch_name = $('#ledger_branch_id').find('option:selected').data('branch_name');
        var from_date = $('#ledger_from_date').val();
        var to_date = $('#ledger_to_date').val();
        var note = $('#ledger_note').val();
        var transaction_details = $('#ledger_transaction_details').val();
        var voucher_details = $('#ledger_voucher_details').val();
        var inventory_list = $('#ledger_inventory_list').val();

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                from_date,
                to_date,
                note,
                transaction_details,
                voucher_details,
                inventory_list
            },
            success: function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 1000,
                    header: "",
                    pageTitle: "",
                    // footer: 'Footer Text',
                });
            }
        });
    });
</script>

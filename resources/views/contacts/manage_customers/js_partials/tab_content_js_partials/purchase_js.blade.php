<script>
    var purchasesTable = $('#purchases-table').DataTable({
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
            "url": "{{ route('purchases.index', ['supplierAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#purchases_branch_id').val();
                d.payment_status = $('#purchases_payment_status').val();
                d.from_date = $('#purchases_from_date').val();
                d.to_date = $('#purchases_to_date').val();
            }
        },
        columns: [{
                data: 'action'
            },
            {
                data: 'date',
                name: 'purchases.date'
            },
            {
                data: 'invoice_id',
                name: 'purchases.invoice_id'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'supplier_name',
                name: 'suppliers.name'
            },
            {
                data: 'payment_status',
                name: 'payment_status',
                className: 'fw-bold'
            },
            {
                data: 'total_purchase_amount',
                name: 'total_purchase_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'paid',
                name: 'paid',
                className: 'text-end fw-bold'
            },
            {
                data: 'purchase_return_amount',
                name: 'purchase_return_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'due',
                name: 'due',
                className: 'text-end fw-bold'
            },
            {
                data: 'created_by',
                name: 'created_by.name'
            },
        ],
        fnDrawCallback: function() {

            var total_purchase_amount = sum_table_col($('#purchases-table'), 'total_purchase_amount');
            $('#purchase_total_purchase_amount').text(bdFormat(total_purchase_amount));

            var paid = sum_table_col($('#purchases-table'), 'paid');
            $('#purchase_paid').text(bdFormat(paid));

            var due = sum_table_col($('#purchases-table'), 'due');
            $('#purchase_due').text(bdFormat(due));

            var purchase_return_amount = sum_table_col($('#purchases-table'), 'purchase_return_amount');
            $('#purchase_purchase_return_amount').text(bdFormat(purchase_return_amount));

            $('.data_preloader').hide();
        }
    });

    $(document).on('submit', '#filter_purchases', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        purchasesTable.ajax.reload();

        var filterObj = {
            branch_id: $('#purchases_branch_id').val(),
            from_date: $('#purchases_from_date').val(),
            to_date: $('#purchases_to_date').val(),
        };

        getAccountClosingBalance(filterObj, 'for_purchases', false);
    });

    $(document).on('click', '#printPurchasesReport', function(e) {
        e.preventDefault();

        var url = "{{ route('reports.purchases.print') }}";

        var branch_id = $('#purchases_branch_id').val();
        var branch_name = $('#purchases_branch_id').find('option:selected').data('branch_name');
        var supplier_account_id = "{{ $contact?->account?->id ? $contact?->account?->id : null }}";
        var supplier_name = "{{ $contact->name . '/' . $contact->phone }}";
        var payment_status = $('#purchases_payment_status').val();
        var from_date = $('#purchases_from_date').val();
        var to_date = $('#purchases_to_date').val();

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                supplier_account_id,
                supplier_name,
                payment_status,
                from_date,
                to_date
            },
            success: function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                    removeInline: false,
                    printDelay: 500,
                    header: "",
                    pageTitle: "",
                });
            }
        });
    });
</script>

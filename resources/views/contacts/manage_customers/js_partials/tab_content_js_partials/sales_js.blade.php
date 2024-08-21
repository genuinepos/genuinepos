<script>
    var salesTable = $('#sales-table').DataTable({
        "processing": true,
        "serverSide": true,
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
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            // "url": "{{ route('sales.index', ['customerAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "url": "{{ route('sales.helper.sales.list.table', ['customerAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#sales_branch_id').val();
                d.payment_status = $('#sales_payment_status').val();
                d.user_id = $('#user_id').val();
                d.from_date = $('#sales_from_date').val();
                d.to_date = $('#sales_to_date').val();
            }
        },
        columns: [{
                data: 'action'
            },
            {
                data: 'date',
                name: 'date'
            },
            {
                data: 'invoice_id',
                name: 'sales.invoice_id',
                className: 'fw-bold'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'customer_name',
                name: 'customers.name'
            },
            {
                data: 'payment_status',
                name: 'created_by.name',
                className: 'text-start'
            },
            {
                data: 'total_item',
                name: 'total_item',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_qty',
                name: 'total_qty',
                className: 'text-end fw-bold'
            },
            {
                data: 'total_invoice_amount',
                name: 'total_invoice_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'received_amount',
                name: 'paid',
                className: 'text-end fw-bold'
            },
            {
                data: 'sale_return_amount',
                name: 'sale_return_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'due',
                name: 'due',
                className: 'text-end fw-bold'
            },
            {
                data: 'created_by',
                name: 'created_by.name',
                className: 'text-end fw-bold'
            },
        ],
        fnDrawCallback: function() {

            var total_qty = sum_table_col($('#sales-table'), 'total_qty');
            $('#sales_total_qty').text(bdFormat(total_qty));

            var total_invoice_amount = sum_table_col($('#sales-table'), 'total_invoice_amount');
            $('#sales_total_invoice_amount').text(bdFormat(total_invoice_amount));

            var received_amount = sum_table_col($('#sales-table'), 'received_amount');
            $('#sales_received_amount').text(bdFormat(received_amount));

            var sale_return_amount = sum_table_col($('#sales-table'), 'sale_return_amount');
            $('#sales_sale_return_amount').text(bdFormat(sale_return_amount));

            var due = sum_table_col($('#sales-table'), 'due');
            $('#sales_due').text(bdFormat(due));

            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_sales', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        salesTable.ajax.reload();

        var filterObj = {
            branch_id: $('#sales_branch_id').val(),
            from_date: $('#sale_from_date').val(),
            to_date: $('#sales_to_date').val(),
        };

        getAccountClosingBalance(filterObj, 'for_sales', false);
    });

    $(document).on('click', '#printSalesReport', function(e) {
        e.preventDefault();

        var url = "{{ route('reports.sales.report.print') }}";

        var branch_id = $('#sales_branch_id').val();
        var branch_name = $('#sales_branch_id').find('option:selected').data('branch_name');
        var payment_status = $('#sales_payment_status').val();
        var customer_account_id = "{{ $contact?->account?->id ? $contact?->account?->id : null }}";
        var customer_name = "{{ $contact->name . '/' . $contact->phone }}";
        var from_date = $('#sales_from_date').val();
        var to_date = $('#sales_to_date').val();

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                payment_status,
                customer_account_id,
                customer_name,
                from_date,
                to_date
            },
            success: function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 500,
                    header: "",
                    pageTitle: "",
                    // footer: 'Footer Text',
                });
            }
        });
    });
</script>

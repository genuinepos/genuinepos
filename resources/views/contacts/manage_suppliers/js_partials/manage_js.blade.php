<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                name: 'suppliers.phone',
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
            $('#purchases_total_purchase_amount').text(bdFormat(total_purchase_amount));
            var paid = sum_table_col($('#purchases-table'), 'paid');
            $('#purchases_paid').text(bdFormat(paid));
            var due = sum_table_col($('#purchases-table'), 'due');
            $('#purchases_due').text(bdFormat(due));
            var purchase_return_amount = sum_table_col($('#purchases-table'), 'purchase_return_amount');
            $('#purchases_purchase_return_amount').text(bdFormat(purchase_return_amount));

            $('.data_preloader').hide();
        }
    });

    var purchaseOrdersTable = $('#purchase-orders-table').DataTable({
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
            "url": "{{ route('purchase.orders.index', ['supplierAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#purchase_orders_branch_id').val();
                d.receiving_status = $('#receiving_status').val();
                d.from_date = $('#purchase_orders_from_date').val();
                d.to_date = $('#purchase_orders_to_date').val();
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
                data: 'created_by',
                name: 'created_by.name'
            },
            {
                data: 'receiving_status',
                name: 'purchases.po_receiving_status',
                className: 'fw-bold'
            },
            {
                data: 'payment_status',
                name: 'created_by.last_name',
                className: 'fw-bold'
            },
            {
                data: 'total_purchase_amount',
                name: 'total_purchase_amount',
                className: 'text-end fw-bold'
            },
            {
                data: 'paid',
                name: 'purchases.paid',
                className: 'text-end fw-bold'
            },
            {
                data: 'due',
                name: 'purchases.due',
                className: 'text-end fw-bold'
            },
        ],
        fnDrawCallback: function() {

            var total_purchase_amount = sum_table_col($('#purchase-orders-table'), 'total_purchase_amount');
            $('#purchase_orders_total_purchase_amount').text(bdFormat(total_purchase_amount));
            var paid = sum_table_col($('#purchase-orders-table'), 'paid');
            $('#purchase_orders_paid').text(bdFormat(paid));
            var due = sum_table_col($('#purchase-orders-table'), 'due');
            $('#purchase_orders_due').text(bdFormat(due));
            $('.data_preloader').hide();
        }
    });

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
            var total_item = sum_table_col($('#sales-table'), 'total_item');
            $('#sales_total_item').text(bdFormat(total_item));

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

    var salesOrderTable = $('#sales-order-table').DataTable({
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
            "url": "{{ route('sale.orders.index', ['customerAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#sales_order_branch_id').val();
                d.payment_status = $('#sales_order_payment_status').val();
                d.from_date = $('#sale_order_from_date').val();
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
                data: 'order_id',
                name: 'sales.order_id',
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
                data: 'total_ordered_qty',
                name: 'total_ordered_qty',
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
            var total_item = sum_table_col($('#sales-order-table'), 'total_item');
            $('#sales_order_total_item').text(bdFormat(total_item));

            var total_ordered_qty = sum_table_col($('#sales-order-table'), 'total_ordered_qty');
            $('#sales_order_total_qty').text(bdFormat(total_ordered_qty));

            var total_invoice_amount = sum_table_col($('#sales-order-table'), 'total_invoice_amount');
            $('#sales_order_total_invoice_amount').text(bdFormat(total_invoice_amount));

            var received_amount = sum_table_col($('#sales-order-table'), 'received_amount');
            $('#sales_order_received_amount').text(bdFormat(received_amount));

            var due = sum_table_col($('#sales-order-table'), 'due');
            $('#sales_order_due').text(bdFormat(due));

            $('.data_preloader').hide();
        }
    });

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

    function sum_table_col(table, class_name) {
        var sum = 0;
        table.find('tbody').find('tr').each(function() {

            if (parseFloat($(this).find('.' + class_name).data('value'))) {

                sum += parseFloat(
                    $(this).find('.' + class_name).data('value')
                );
            }
        });
        return sum;
    }

    var filterObj = {
        branch_id: null,
        from_date: null,
        to_date: null,
    };

    // Submit filter form by select input changing
    $(document).on('submit', '#filter_supplier_ledgers', function(e) {
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

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_sales', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        var filterObj = {
            branch_id: $('#sales_branch_id').val(),
            from_date: $('#sale_from_date').val(),
            to_date: $('#sales_to_date').val(),
        };

        getAccountClosingBalance(filterObj, 'for_sales', false);
    });

    $(document).on('submit', '#filter_sales_order', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        var filterObj = {
            branch_id: $('#sales_order_branch_id').val(),
            from_date: $('#sales_order_from_date').val(),
            to_date: $('#sales_order_to_date').val(),
        };

        getAccountClosingBalance(filterObj, 'for_sales_order', false);
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

    $(document).on('submit', '#filter_purchase_orders', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        purchaseOrderstable.ajax.reload();

        var filterObj = {
            branch_id: $('#purchase_orders_branch_id').val(),
            from_date: $('#purchase_orders_from_date').val(),
            to_date: $('#purchase_orders_to_date').val(),
        };

        getAccountClosingBalance(filterObj, 'for_purchase_orders', false);
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

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();

        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });
</script>

<script>
    function getAccountClosingBalance(filterObj, parentDiv, changeLedgerTableCurrentTotal = false) {

        var url = "{{ route('accounts.balance', $contact?->account?->id) }}";

        $.ajax({
            url: url,
            type: 'get',
            data: filterObj,
            success: function(data) {

                if (parentDiv) {

                    $('#' + parentDiv + ' .opening_balance').html(data.opening_balance_in_flat_amount_string);
                    $('#' + parentDiv + ' .total_sale').html(data.total_sale_string);
                    $('#' + parentDiv + ' .total_purchase').html(data.total_purchase_string);

                    if (data.total_return < 0) {

                        $('#' + parentDiv + ' .total_return').html('<span class="text-dark">(</span>' + bdFormat(Math.abs(data.total_return)) + '<span class="text-dark">)</span>');
                    } else {

                        $('#' + parentDiv + ' .total_return').html(data.total_return_string);
                    }

                    $('#' + parentDiv + ' .total_received').html(data.total_received_string);
                    $('#' + parentDiv + ' .total_paid').html(data.total_paid_string);

                    if (data.closing_balance_in_flat_amount < 0) {

                        $('#' + parentDiv + ' .closing_balance').html('<span class="text-dark">(</span>' + bdFormat(Math.abs(data.closing_balance_in_flat_amount)) + '<span class="text-dark">)</span>');
                    } else {

                        $('#' + parentDiv + ' .closing_balance').html(data.closing_balance_in_flat_amount_string);
                    }
                } else {

                    $('.opening_balance').html(data.opening_balance_in_flat_amount_string);
                    $('.total_sale').html(data.total_sale_string);
                    $('.total_purchase').html(data.total_purchase_string);

                    if (data.total_return < 0) {

                        $('.total_return').html('<span class="text-dark">(</span>' + bdFormat(Math.abs(data.total_return)) + '<span class="text-dark">)</span>');
                    } else {

                        $('.total_return').html(data.total_return_string);
                    }

                    $('.total_received').html(data.total_received_string);
                    $('.total_paid').html(data.total_paid_string);

                    if (data.closing_balance_in_flat_amount < 0) {

                        $('.closing_balance').html('<span class="text-dark">(</span>' + bdFormat(Math.abs(data.closing_balance_in_flat_amount)) + '<span class="text-dark">)</span>');
                    } else {

                        $('.closing_balance').html(data.closing_balance_in_flat_amount_string);
                    }
                }

                if (changeLedgerTableCurrentTotal == true) {

                    $('#ledger_table_total_debit').html(data.all_total_debit_string);
                    $('#ledger_table_total_credit').html(data.all_total_debit_string);
                    $('#ledger_table_current_balance').html(data.closing_balance_string);
                }

            }
        });
    }

    var filterObj = {
        branch_id: null,
        from_date: null,
        to_date: null,
    };
    getAccountClosingBalance(filterObj, '', true);
</script>

<script type="text/javascript">
    function litepicker(idName) {

        new Litepicker({
            singleMode: true,
            element: document.getElementById(idName),
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
    }

    litepicker('ledger_from_date');
    litepicker('ledger_to_date');
    litepicker('sales_from_date');
    litepicker('sales_to_date');
    litepicker('sales_order_from_date');
    litepicker('sales_order_to_date');
    litepicker('purchases_from_date');
    litepicker('purchases_to_date');
    litepicker('purchase_orders_from_date');
    litepicker('purchase_orders_to_date');
    litepicker('payments_from_date');
    litepicker('payments_to_date');
    litepicker('receipts_from_date');
    litepicker('receipts_to_date');
</script>

<script>
    $(document).on('click', '#editShipmentDetails', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#editShipmentDetailsModal').html(data);
                $('#editShipmentDetailsModal').modal('show');
                $('.data_preloader').hide();

                setTimeout(function() {

                    $('#shipment_shipment_address').focus().select();
                }, 500);
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });

    $(document).on('click', '#details_btn', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#details').html(data);
                $('#detailsModal').modal('show');
                $('.data_preloader').hide();
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
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

    $(document).on('click', '#printSalesOrderReport', function(e) {
        e.preventDefault();

        var url = "{{ route('reports.sales.order.report.print') }}";

        var branch_id = $('#sales_order_branch_id').val();
        var branch_name = $('#sales_order_branch_id').find('option:selected').data('branch_name');
        var payment_status = $('#sales_order_payment_status').val();
        var customer_account_id = "{{ $contact?->account?->id ? $contact?->account?->id : null }}";
        var customer_name = "{{ $contact->name . '/' . $contact->phone }}";
        var from_date = $('#sales_order_from_date').val();
        var to_date = $('#sales_order_to_date').val();

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                customer_account_id,
                payment_status,
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
                });
            }
        });
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

    //Print purchase Payment report
    $(document).on('click', '#printPurchaseOrdersReport', function(e) {
        e.preventDefault();

        var url = "{{ route('reports.purchase.orders.print') }}";

        var branch_id = $('#purchase_orders_branch_id').val();
        var branch_name = $('#purchase_orders_branch_id').find('option:selected').data('branch_name');
        var supplier_account_id = "{{ $contact?->account?->id ? $contact?->account?->id : null }}";
        var supplier_name = "{{ $contact->name . '/' . $contact->phone }}";
        var payment_status = $('#purchase_orders_payment_status').val();
        var from_date = $('#purchase_orders_from_date').val();
        var to_date = $('#purchase_orders_to_date').val();

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
                    // footer: 'Footer Text',
                });
            }
        });
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
                    printDelay: 500,
                    header: "",
                    pageTitle: "",
                    // footer: 'Footer Text',
                });
            }
        });
    });
</script>

<script>
    $.ajaxSetup({
        // Disable caching of AJAX responses
        cache: false
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

                    toastr.error("{{ __('Net Connection Error.') }}");
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

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    function reloadAllAccountSummaryArea() {

        var summeryReloaderDatas = [{
                filterDatePrefix: 'ledger_',
                filterSummerParentDiv: 'for_ledger',
                changeLedgerTableCurrentTotal: true,
            },
            {
                filterDatePrefix: 'sales_',
                filterSummerParentDiv: 'for_sales',
                changeLedgerTableCurrentTotal: false,
            },
            {
                filterDatePrefix: 'sales_order_',
                filterSummerParentDiv: 'for_sales_order',
                changeLedgerTableCurrentTotal: false,
            },
            {
                filterDatePrefix: 'purchases_',
                filterSummerParentDiv: 'for_purchases',
                changeLedgerTableCurrentTotal: false,
            },
            {
                filterDatePrefix: 'purchases_orders_',
                filterSummerParentDiv: 'for_purchase_orders',
                changeLedgerTableCurrentTotal: false,
            },
            {
                filterDatePrefix: 'receipts_',
                filterSummerParentDiv: 'for_receipts',
                changeLedgerTableCurrentTotal: false,
            },
            {
                filterDatePrefix: 'payments_',
                filterSummerParentDiv: 'for_payments',
                changeLedgerTableCurrentTotal: false,
            },
        ];

        summeryReloaderDatas.forEach(function(element) {
            var filterObj = {
                branch_id: $('#' + element.filterDatePrefix + 'branch_id').val(),
                from_date: $('#' + element.filterDatePrefix + 'from_date').val(),
                to_date: $('#' + element.filterDatePrefix + 'to_date').val(),
            };

            getAccountClosingBalance(filterObj, element.filterSummerParentDiv, element.changeLedgerTableCurrentTotal);
        });
    }

    var tableId = '';
    $(document).on('click', '#delete', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        tableId = $(this).closest('tr').closest('table').attr('id');
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#deleted_form').submit();
                    }
                },
                'No': {
                    'class': 'no btn-danger',
                    'action': function() {
                        console.log('Deleted canceled.');
                    }
                }
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.error(data);
                $('.common-reloader').DataTable().ajax.reload();

                reloadAllAccountSummaryArea();
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });
</script>

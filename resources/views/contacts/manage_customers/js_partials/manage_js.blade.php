<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    //Get customer Ledgers by yajra data table
    // var ledger_table = $('.ledger_table').DataTable({
    //     "processing": true,
    //     "serverSide": true,
    //     "searching" : false,
    //     dom: "lBfrtip",
    //     buttons: [
    //         {extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary'},
    //         {extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> Pdf', className: 'btn btn-primary'},
    //     ],

    //     "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
    //     "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],

    //contacts.customer.ledger.list', $contact->id
    //     "ajax": {
    //         "url": "#",
    //         "data": function(d) {
    //             d.branch_id = $('#ledger_branch_id').val();
    //             d.voucher_type = $('#ledger_voucher_type').val();
    //             d.from_date = $('.from_date').val();
    //             d.to_date = $('.to_date').val();
    //         }
    //     },

    //     columns: [
    //         {data: 'date', name: 'customer_ledgers.report_date'},
    //         {data: 'particulars', name: 'particulars'},
    //         {data: 'b_name', name: 'branches.name'},
    //         {data: 'voucher_no', name: 'voucher_no'},
    //         {data: 'debit', name: 'debit', className: 'text-end'},
    //         {data: 'credit', name: 'credit', className: 'text-end'},
    //         {data: 'running_balance', name: 'running_balance', className: 'text-end'},
    //     ],fnDrawCallback: function() {

    //         var debit = sum_table_col($('.data_tbl'), 'debit');
    //         $('#debit').text(bdFormat(debit));

    //         var credit = sum_table_col($('.data_tbl'), 'credit');
    //         $('#credit').text(bdFormat(credit));
    //         $('.data_preloader').hide();
    //     }
    // });

    var salesTable = $('#sales-table').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('sales.index', ['customerAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#sales_branch_id').val();
                d.payment_status = $('#sales_payment_status').val();
                d.user_id = $('#user_id').val();
                d.from_date = $('#sales_from_date').val();
                d.to_date = $('#sales_to_date').val();
            }
        },
        columns: [
            {data: 'action'},
            {data: 'date', name: 'date'},
            {data: 'invoice_id', name: 'sales.invoice_id', className: 'fw-bold'},
            {data: 'branch', name: 'branches.name'},
            {data: 'customer_name', name: 'customers.name'},
            {data: 'payment_status', name: 'created_by.name', className: 'text-start'},
            {data: 'total_item', name: 'total_item', className: 'text-end fw-bold'},
            {data: 'total_qty', name: 'total_qty', className: 'text-end fw-bold'},
            {data: 'total_invoice_amount', name: 'total_invoice_amount', className: 'text-end fw-bold'},
            {data: 'received_amount', name: 'paid', className: 'text-end fw-bold'},
            {data: 'sale_return_amount', name: 'sale_return_amount', className: 'text-end fw-bold'},
            {data: 'due', name: 'due', className: 'text-end fw-bold'},
            {data: 'created_by', name: 'created_by.name', className: 'text-end fw-bold'},

        ],fnDrawCallback: function() {
            var total_item = sum_table_col($('.data_tbl'), 'total_item');
            $('#total_item').text(bdFormat(total_item));

            var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
            $('#total_qty').text(bdFormat(total_qty));

            var total_invoice_amount = sum_table_col($('.data_tbl'), 'total_invoice_amount');
            $('#total_invoice_amount').text(bdFormat(total_invoice_amount));

            var received_amount = sum_table_col($('.data_tbl'), 'received_amount');
            $('#received_amount').text(bdFormat(received_amount));

            var sale_return_amount = sum_table_col($('.data_tbl'), 'sale_return_amount');
            $('#sale_return_amount').text(bdFormat(sale_return_amount));

            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(bdFormat(due));

            $('.data_preloader').hide();
        }
    });

    var salesOrderTable = $('#sales-order-table').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('sale.orders.index', ['customerAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#sales_order_branch_id').val();
                d.payment_status = $('#sales_order_payment_status').val();
                d.from_date = $('#sale_order_from_date').val();
                d.to_date = $('#sales_to_date').val();
            }
        },
        columns: [
            {data: 'action'},
            {data: 'date', name: 'date'},
            {data: 'order_id', name: 'sales.order_id', className: 'fw-bold'},
            {data: 'branch', name: 'branches.name'},
            {data: 'customer_name', name: 'customers.name'},
            {data: 'payment_status', name: 'created_by.name', className: 'text-start'},
            {data: 'total_item', name: 'total_item', className: 'text-end fw-bold'},
            {data: 'total_qty', name: 'total_qty', className: 'text-end fw-bold'},
            {data: 'total_invoice_amount', name: 'total_invoice_amount', className: 'text-end fw-bold'},
            {data: 'received_amount', name: 'paid', className: 'text-end fw-bold'},
            {data: 'due', name: 'due', className: 'text-end fw-bold'},
            {data: 'created_by', name: 'created_by.name', className: 'text-end fw-bold'},

        ],fnDrawCallback: function() {
            var total_item = sum_table_col($('.data_tbl'), 'total_item');
            $('#total_item').text(bdFormat(total_item));

            var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
            $('#total_qty').text(bdFormat(total_qty));

            var total_invoice_amount = sum_table_col($('.data_tbl'), 'total_invoice_amount');
            $('#total_invoice_amount').text(bdFormat(total_invoice_amount));

            var received_amount = sum_table_col($('.data_tbl'), 'received_amount');
            $('#received_amount').text(bdFormat(received_amount));

            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(bdFormat(due));

            $('.data_preloader').hide();
        }
    });

    var purchasesTable = $('#purchases-table').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        "processing": true,
        "serverSide": true,
        //aaSorting: [[0, 'asc']],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('purchases.index', ['supplierAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#purchases_branch_id').val();
                d.payment_status = $('#purchases_payment_status').val();
                d.from_date = $('#purchases_from_date').val();
                d.to_date = $('#purchases_to_date').val();
            }
        },
        columns: [
            {data: 'action'},
            {data: 'date', name: 'purchases.date'},
            {data: 'invoice_id',name: 'purchases.invoice_id'},
            {data: 'branch',name: 'branches.name'},
            {data: 'supplier_name', name: 'suppliers.name'},
            {data: 'payment_status',name: 'payment_status', className: 'fw-bold'},
            {data: 'total_purchase_amount',name: 'total_purchase_amount', className: 'text-end fw-bold'},
            {data: 'paid',name: 'paid', className: 'text-end fw-bold'},
            {data: 'purchase_return_amount',name: 'purchase_return_amount', className: 'text-end fw-bold'},
            {data: 'due',name: 'due', className: 'text-end fw-bold'},
            {data: 'created_by',name: 'created_by.name'},
        ],fnDrawCallback: function() {

            var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
            $('#total_purchase_amount').text(bdFormat(total_purchase_amount));
            var paid = sum_table_col($('.data_tbl'), 'paid');
            $('#paid').text(bdFormat(paid));
            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(bdFormat(due));
            var purchase_return_amount = sum_table_col($('.data_tbl'), 'purchase_return_amount');
            $('#purchase_return_amount').text(bdFormat(purchase_return_amount));

            $('.data_preloader').hide();
        }
    });

    var purchaseOrderstable = $('#purchase-orders-table').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
        ],
        "processing": true,
        "serverSide": true,
        //aaSorting: [[0, 'asc']],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        "ajax": {
            "url": "{{ route('purchase.orders.index', ['supplierAccountId' => $contact?->account?->id ? $contact?->account?->id : 0]) }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.supplier_account_id = $('#supplier_account_id').val();
                d.receiving_status = $('#receiving_status').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [
            {data: 'action'},
            {data: 'date', name: 'purchases.date'},
            {data: 'invoice_id',name: 'purchases.invoice_id'},
            {data: 'branch',name: 'branches.name'},
            {data: 'supplier_name', name: 'suppliers.name'},
            {data: 'created_by', name: 'created_by.name'},
            {data: 'receiving_status', name: 'purchases.po_receiving_status', className: 'fw-bold'},
            {data: 'payment_status', name: 'created_by.last_name', className: 'fw-bold'},
            {data: 'total_purchase_amount', name: 'total_purchase_amount', className: 'text-end fw-bold'},
            {data: 'paid', name: 'purchases.paid', className: 'text-end fw-bold'},
            {data: 'due', name: 'purchases.due', className: 'text-end fw-bold'},
        ],fnDrawCallback: function() {

            var total_purchase_amount = sum_table_col($('.data_tbl'), 'total_purchase_amount');
            $('#total_purchase_amount').text(bdFormat(total_purchase_amount));
            var paid = sum_table_col($('.data_tbl'), 'paid');
            $('#paid').text(bdFormat(paid));
            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(bdFormat(due));
            $('.data_preloader').hide();
        }
    });

    // @if(auth()->user()->can('sale_payment'))

    //     var payments_table = $('.payments_table').DataTable({
    //         "processing": true,
    //         "serverSide": true,
    //         "searching" : true,
    //         dom: "lBfrtip",
    //         buttons: [
    //             {extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary'},
    //             {extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> Pdf', className: 'btn btn-primary'},
    //         ],

    //         "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
    //         "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],

    //customers.all.payment.list', $contact->id
    //         "ajax": {
    //             "url": "#",
    //             "data": function(d) {
    //                 d.branch_id = $('#payment_branch_id').val();
    //                 d.p_from_date = $('#payment_from_date').val();
    //                 d.p_to_date = $('#payment_to_date').val();
    //             }
    //         },

    //         columnDefs: [{
    //             "targets": [3, 4, 5, 6],
    //             "orderable": false,
    //             "searchable": false
    //         }],

    //         columns: [
    //             {data: 'date', name: 'customer_ledgers.date'},
    //             {data: 'voucher_no', name: 'customer_payments.voucher_no'},
    //             {data: 'reference', name: 'customer_payments.reference'},
    //             {data: 'against_invoice', name: 'sales.invoice_id'},
    //             {data: 'type', name: 'type'},
    //             {data: 'method', name: 'method'},
    //             {data: 'account', name: 'account'},
    //             {data: 'less_amount', name: 'customer_payments.less_amount', className: 'text-end'},
    //             {data: 'amount', name: 'customer_ledgers.amount', className: 'text-end'},
    //             {data: 'action'},
    //         ],fnDrawCallback: function() {

    //             var amount = sum_table_col($('.data_tbl'), 'amount');
    //             $('#amount').text(bdFormat(amount));

    //             var less_amount = sum_table_col($('.data_tbl'), 'less_amount');
    //             $('#less_amount').text(bdFormat(less_amount));
    //             $('.data_preloader').hide();
    //         }
    //     });
    // @endif

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
        branch_id : null,
        from_date : null,
        to_date : null,
    };

    //Submit filter form by select input changing
    // $(document).on('submit', '#filter_customer_ledgers', function (e) {
    //     e.preventDefault();
    //     $('.data_preloader').show();
    //     ledger_table.ajax.reload();

    //     filterObj = {
    //         branch_id : $('#ledger_branch_id').val(),
    //         from_date : $('.from_date').val(),
    //         to_date : $('.to_date').val(),
    //     };

    //     var data = getCustomerAmountsBranchWise(filterObj, 'ledger_', false);
    // });

     //Submit filter form by select input changing
    $(document).on('submit', '#filter_sales', function (e) {
        e.preventDefault();

        $('.data_preloader').show();
        salesTable.ajax.reload();

        filterObj = {
            branch_id : $('#sales_branch_id').val(),
            from_date : $('#sale_from_date').val(),
            to_date : $('#sales_to_date').val(),
        };

        // var data = getCustomerAmountsBranchWise(filterObj, 'sales_', false);
    });

    $(document).on('submit', '#filter_sales_order', function (e) {
        e.preventDefault();

        $('.data_preloader').show();
        salesOrderTable.ajax.reload();

        filterObj = {
            branch_id : $('#sales_order_branch_id').val(),
            from_date : $('#sales_order_from_date').val(),
            to_date : $('#sales_order_to_date').val(),
        };

        // var data = getCustomerAmountsBranchWise(filterObj, 'sales_', false);
    });

    $(document).on('submit', '#filter_purchases', function (e) {
        e.preventDefault();

        $('.data_preloader').show();
        purchasesTable.ajax.reload();

        filterObj = {
            branch_id : $('#purchases_branch_id').val(),
            from_date : $('#purchases_from_date').val(),
            to_date : $('#purchases_to_date').val(),
        };

        // var data = getCustomerAmountsBranchWise(filterObj, 'sales_', false);
    });

    //Submit filter form by select input changing
    // $(document).on('submit', '#filter_customer_payments', function (e) {
    //     e.preventDefault();

    //     $('.data_preloader').show();
    //     payments_table.ajax.reload();

    //     filterObj = {
    //         branch_id : $('#payment_branch_id').val(),
    //         from_date : $('#payment_from_date').val(),
    //         to_date : $('#payment_to_date').val(),
    //     };

    //     var data = getCustomerAmountsBranchWise(filterObj, 'cus_payments_', false);
    // });

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();

        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });

    // $(document).on('click', '#delete',function(e){
    //     e.preventDefault();

    //     var url = $(this).attr('href');
    //     $('#deleted_form').attr('action', url);

    //     $.confirm({
    //         'title': 'Confirmation',
    //         'message': 'Are you sure?',
    //         'buttons': {
    //             'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}},
    //             'No': {'class': 'no btn-modal-primary','action': function() { console.log('Deleted canceled.');}}
    //         }
    //     });
    // });

    //data delete by ajax
    // $(document).on('submit', '#deleted_form',function(e){
    //     e.preventDefault();

    //     var url = $(this).attr('action');
    //     var request = $(this).serialize();
    //     $.ajax({
    //         url:url,
    //         type:'post',
    //         data:request,
    //         success:function(data){

    //              $('.data_tbl').DataTable().ajax.reload();
    //             toastr.error(data);

    //             var filterObj = {
    //                 branch_id : $('#sale_branch_id').val(),
    //                 from_date : $('#from_sale_date').val(),
    //                 to_date : $('#to_sale_date').val(),
    //             };

    //             getCustomerAmountsBranchWise(filterObj, 'sales_', false);

    //             filterObj = {
    //                 branch_id : $('#payment_branch_id').val(),
    //                 from_date : $('#payment_from_date').val(),
    //                 to_date : $('#payment_to_date').val(),
    //             };

    //             getCustomerAmountsBranchWise(filterObj, 'cus_payments_', false);

    //             filterObj = {
    //                 branch_id : $('#ledger_branch_id').val(),
    //                 from_date : $('.from_date').val(),
    //                 to_date : $('.to_date').val(),
    //             };

    //             getCustomerAmountsBranchWise(filterObj, 'ledger_', false);
    //         }
    //     });
    // });
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

    litepicker('sales_from_date');
    litepicker('sales_to_date');
    litepicker('sales_order_from_date');
    litepicker('sales_order_to_date');
    litepicker('purchases_from_date');
    litepicker('purchases_to_date');
    litepicker('purchase_orders_from_date');
    litepicker('purchase_orders_to_date');
</script>

<script>

//    function getCustomerAmountsBranchWise(filterObj, showPrefix = 'ledger', is_show_all = true) {

//contacts.customer.amounts.branch.wise', $contact->id
//         $.ajax({
//            url :"#",
//             type :'get',
//             data : filterObj,
//             success:function(data){
//                 var keys = Object.keys(data);

//                 keys.forEach(function (val) {

//                     if (is_show_all) {

//                         $('.'+val).html(bdFormat(data[val]));
//                     }else {

//                         $('#'+showPrefix+val).html(bdFormat(data[val]));
//                     }
//                 });

//                 $('#card_total_due').val(data['total_sale_due']);
//             }
//         });
//     }

//     getCustomerAmountsBranchWise(filterObj);
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
            },error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                }else if (err.status == 500) {

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
            }, error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                }else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });

    // Make print
    $(document).on('click', '#modalDetailsPrintBtn', function(e) {
        e.preventDefault();

        var body = $('.print_modal_details').html();

        $(body).printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
            removeInline: false,
            printDelay: 500,
            header: null,
        });
    });

    // Print Packing slip
    $(document).on('click', '#PrintChallanBtn', function (e) {
        e.preventDefault();
        $('.data_preloader').show();

        var url = $(this).attr('href');

        $.ajax({
            url:url,
            type:'get',
            success:function(data){

                $('.data_preloader').hide();
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });
            },error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                }else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });

    // Print Packing slip
    $(document).on('click', '#printPackingSlipBtn', function (e) {
        e.preventDefault();
        $('.data_preloader').show();

        var url = $(this).attr('href');

        $.ajax({
            url:url,
            type:'get',
            success:function(data){

                $('.data_preloader').hide();
                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });
            },error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                }else if (err.status == 500) {

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
        var customer_name = "{{ $contact->name.'/'.$contact->phone }}";
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
            }, success: function(data) {

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
            }, success: function(data) {

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
        var supplier_name = "{{ $contact->name.'/'.$contact->phone }}";
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
            }, success: function(data) {

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
        var supplier_name = "{{ $contact->name.'/'.$contact->phone }}";
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
            }, success: function(data) {

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
</script>
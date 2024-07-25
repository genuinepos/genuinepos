<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var daybookTable = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        // "searching": false,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i>' + "{{ __('Excel') }}",
                className: 'btn btn-primary'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i>' + "{{ __('Pdf') }}",
                className: 'btn btn-primary'
            },
        ],
        "lengthMenu": [
            [50, 100, 500, 1000, -1],
            [50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('reports.day.book.index') }}",
            "data": function(d) {
                d.voucher_type = $('#voucher_type').val();
                d.branch_id = $('#branch_id').val();
                d.branch_name = $('#branch_id').find('option:selected').data('branch_name');
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.note = $('#note').val();
                d.transaction_details = $('#transaction_details').val();
                d.voucher_details = $('#voucher_details').val();
                d.inventory_list = $('#inventory_list').val();
            }
        },
        columns: [{
                data: 'date',
                name: 'day_books.date_ts'
            },
            {
                data: 'particulars',
                name: 'sales.invoice_id'
            },
            {
                data: 'voucher_type',
                name: 'sale_returns.voucher_no'
            },
            {
                data: 'voucher_no',
                name: 'purchases.invoice_id'
            },
            {
                data: 'debit',
                name: 'accounting_vouchers.voucher_no',
                className: 'text-end fw-bold'
            },
            {
                data: 'credit',
                name: 'stock_issues.voucher_no',
                className: 'text-end fw-bold'
            },
            {
                data: 'sales_order_voucher',
                name: 'sales.order_id',
                searchable: true,
                visible: false
            },
            {
                data: 'payroll',
                name: 'hrm_payrolls.voucher_no',
                searchable: true,
                visible: false
            },
            {
                data: 'transfer_stock',
                name: 'transfer_stocks.voucher_no',
                searchable: true,
                visible: false
            },
            {
                data: 'stock_adjustment',
                name: 'stock_adjustments.voucher_no',
                searchable: true,
                visible: false
            },
        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    daybookTable.buttons().container().appendTo('#exportButtonsContainer');

    // Submit filter form by select input changing
    $(document).on('submit', '#filter_day_book', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        daybookTable.ajax.reload(null, false);
    });

    //Print account ledger
    $(document).on('click', '#print_report', function(e) {
        e.preventDefault();

        var url = "{{ route('reports.day.book.print') }}";

        var voucher_type = $('#voucher_type').val();
        var voucher_name = $('#voucher_type').find('option:selected').data('voucher_name');
        var branch_id = $('#branch_id').val();
        var branch_name = $('#branch_id').find('option:selected').data('branch_name');
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var note = $('#note').val();
        var transaction_details = $('#transaction_details').val();
        var voucher_details = $('#voucher_details').val();
        var inventory_list = $('#inventory_list').val();

        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: {
                voucher_type,
                voucher_name,
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
                    printDelay: 700,
                    header: "",
                    pageTitle: "",
                    // footer: 'Footer Text',
                });

                var tempElement = document.createElement('div');
                tempElement.innerHTML = data;
                var filename = tempElement.querySelector('#title');

                document.title = filename.innerHTML;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            }
        });
    });

    // Show details modal with data
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
</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('from_date'),
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('to_date'),
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

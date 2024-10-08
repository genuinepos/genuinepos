<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var accountLedgerTable = $('.data_tbl').DataTable({
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
            "url": "{{ route('accounts.ledger.index', [$account->id]) }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.branch_name = $('#branch_id').find('option:selected').data('branch_name');
                d.currency_rate = $('#branch_id').find('option:selected').data('currency_rate');
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

    // accountLedgerTable.buttons().container().appendTo('#exportButtonsContainer');

    // Submit filter form by select input changing
    $(document).on('submit', '#filter_account_ledgers', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        accountLedgerTable.ajax.reload(null, false);

        getAccountClosingBalance();
    });

    //Print account ledger
    $(document).on('click', '#print_report', function(e) {
        e.preventDefault();

        var url = "{{ route('accounts.ledger.print', [$account->id]) }}";

        var branch_id = $('#branch_id').val();
        var branch_name = $('#branch_id').find('option:selected').data('branch_name');
        var currency_rate = $('#branch_id').find('option:selected').data('currency_rate');
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var note = $('#note').val();
        var transaction_details = $('#transaction_details').val();
        var voucher_details = $('#voucher_details').val();
        var inventory_list = $('#inventory_list').val();

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                currency_rate,
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

    function getAccountClosingBalance() {

        var branch_id = $('#branch_id').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        var filterObj = {
            branch_id: branch_id ? branch_id : null,
            from_date: from_date ? from_date : null,
            to_date: to_date ? to_date : null,
        };

        var url = "{{ route('accounts.balance', $account->id) }}";

        $.ajax({
            url: url,
            type: 'get',
            data: filterObj,
            success: function(data) {

                $('#debit_opening_balance').html('');
                $('#credit_opening_balance').html('');
                $('#debit_closing_balance').html('');
                $('#credit_closing_balance').html('');

                $('#table_total_debit').html(data.all_total_debit > 0 ? bdFormat(data.all_total_debit) : '');
                $('#table_total_credit').html(data.all_total_credit ? bdFormat(data.all_total_credit) : '');
                $('#table_current_balance').html(data.closing_balance > 0 ? data.closing_balance_string : '');

                if (data.opening_balance_side == 'dr') {

                    $('#debit_opening_balance').html(data.opening_balance > 0 ? bdFormat(data.opening_balance) : '');
                } else {

                    $('#credit_opening_balance').html(data.opening_balance > 0 ? bdFormat(data.opening_balance) : '');
                }

                $('#total_debit').html(data.curr_total_debit > 0 ? bdFormat(data.curr_total_debit) : '');
                $('#total_credit').html(data.curr_total_credit > 0 ? bdFormat(data.curr_total_credit) : '');

                if (data.closing_balance_side == 'dr') {

                    $('#debit_closing_balance').html(data.closing_balance > 0 ? bdFormat(data.closing_balance) : '');
                } else {

                    $('#credit_closing_balance').html(data.closing_balance > 0 ? bdFormat(data.closing_balance) : '');
                }
            }
        });
    }

    getAccountClosingBalance();

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Show session message by toster alert.
    var expenseTable = $('.data_tbl').DataTable({
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
            "url": "{{ route('expenses.index') }}",
            "data": function(d) {
                d.branch_id = $('#f_branch_id').val();
                // d.debit_account_id = $('#f_debit_account_id').val();
                d.from_date = $('#f_from_date').val();
                d.to_date = $('#f_to_date').val();
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
                data: 'voucher_no',
                name: 'voucher_no',
                className: 'fw-bold'
            },
            {
                data: 'branch',
                name: 'branch.name'
            },
            {
                data: 'reference',
                name: 'reference'
            },
            {
                data: 'remarks',
                name: 'remarks'
            },
            {
                data: 'paid_from',
                name: 'voucherCreditDescription.account.name'
            },
            {
                data: 'payment_method',
                name: 'voucherCreditDescription.paymentMethod.name'
            },
            {
                data: 'transaction_no',
                name: 'voucherCreditDescription.transaction_no'
            },
            {
                data: 'cheque_no',
                name: 'voucherCreditDescription.cheque_no'
            },
            {
                data: 'expense_descriptions',
                name: 'voucherDebitDescriptions.account.name'
            },
            {
                data: 'total_amount',
                name: 'branch.parentBranch.name',
                className: 'text-end fw-bold'
            },
            // {data: 'created_by',name: 'accountingVoucher.createdBy.name'},
        ],
        fnDrawCallback: function() {

            var total_amount = sum_table_col($('.data_tbl'), 'total_amount');
            $('#total_amount').text(bdFormat(total_amount));

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

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        expenseTable.ajax.reload();
    });

    $.ajaxSetup({
        cache: false
    });

    $(document).on('click', '#addExpense', function(e) {
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
                $('#addOrEditExpenseModal').empty();
                $('#addOrEditExpenseModal').html(data);
                $('#addOrEditExpenseModal').modal('show');

                setTimeout(function() {

                    $('#expense_date').focus().select();
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

    $(document).on('click', '#editExpense', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            cache: false,
            async: false,
            dataType: 'html',
            success: function(data) {

                $('#addOrEditExpenseModal').empty();
                $('#addOrEditExpenseModal').html(data);
                $('#addOrEditExpenseModal').modal('show');

                setTimeout(function() {

                    $('#expense_date').focus().select();
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

    $(document).on('click', '#delete', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        $('#delete_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure, you want to delete?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#delete_form').submit();
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
    $(document).on('submit', '#delete_form', function(e) {
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

                expenseTable.ajax.reload();
                toastr.error(data);
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

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('f_from_date'),
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
        element: document.getElementById('f_to_date'),
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

    function getSupplier() {}
</script>

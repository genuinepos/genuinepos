<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    var businessName = "{{ $generalSettings['business_or_shop__business_name'] }}";

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
            },
        ],
        "processing": true,
        "serverSide": true,
        //aaSorting: [[0, 'asc']],
        "language": {
            "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
        },
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('reports.expenses.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.child_branch_id = $('#child_branch_id').val();
                d.expense_group_id = $('#expense_group_id').val();
                d.expense_account_id = $('#expense_account_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [{
                data: 'date',
                name: 'account_ledgers.date',
                className: 'fw-bold'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'account_name',
                name: 'accounts.name'
            },
            {
                data: 'voucher_type',
                name: 'parentBranch.name',
            },
            {
                data: 'voucher_no',
                name: 'accounting_vouchers.voucher_no'
            },
            {
                data: 'amount',
                name: 'stock_adjustments.voucher_no',
                className: 'fw-bold'
            },
        ],
        fnDrawCallback: function() {

            var amount = sum_table_col($('.data_tbl'), 'amount');
            $('#amount').text(bdFormat(amount));

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
    $(document).on('submit', '#filter_expense_report', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        table.ajax.reload();
    });

    $(document).on('change', '#branch_id', function(e) {
        e.preventDefault();

        var branchId = $(this).val();

        $('#child_branch_id').empty();
        $('#child_branch_id').append('<option data-child_branch_name="" value="">' + "{{ __('All') }}" + '</option>');

        if (branchId == '') {
            return;
        }

        var route = '';
        var url = "{{ route('branches.parent.with.child.branches', ':branchId') }}";
        route = url.replace(':branchId', branchId);

        $.ajax({
            url: route,
            type: 'get',
            success: function(branch) {

                if (branch.child_branches.length > 0) {

                    $('#child_branch_id').empty();
                    $('#child_branch_id').append('<option data-child_branch_name="' + "{{ __('All') }}" + '" value="">' + "{{ __('All') }}" + '</option>');
                    $('#child_branch_id').append('<option data-child_branch_name="' + branch.name + '(' + branch.area_name + ')' + '" value="' + branch.id + '">' + branch.name + '(' + branch.area_name + ')' + '</option>');

                    $.each(branch.child_branches, function(key, childBranch) {

                        $('#child_branch_id').append('<option data-child_branch_name="' + branch.name + '(' + childBranch.area_name + ')' + '" value="' + childBranch.id + '">' + branch.name + '(' + childBranch.area_name + ')' + '</option>');
                    });
                }
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

    function expenseAccounts() {

        console.log('expenseAccounts');
        var branch_id = $('#branch_id').val();
        var child_branch_id = $('#child_branch_id').val();
        var expense_group_id = $('#expense_group_id').val();

        $('#expense_account_id').empty();
        $('#expense_account_id').append('<option data-expense_account_name="' + "{{ __('All') }}" + '" value="">' + "{{ __('All') }}" + '</option>');

        var url = "{{ route('accounts.expense.accounts') }}";

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                child_branch_id,
                expense_group_id
            },
            success: function(expenseAccounts) {

                if (expenseAccounts.length > 0) {

                    $('#expense_account_id').empty();
                    $('#expense_account_id').append('<option data-child_branch_name="' + "{{ __('All') }}" + '" value="">' + "{{ __('All') }}" + '</option>');

                    $.each(expenseAccounts, function(key, expenseAccount) {

                        var name = expenseAccount.name;

                        var branchName = businessName;
                        if (expenseAccount.branch_id != null) {

                            if (expenseAccount.parent_branch_name != null) {

                                // branchName = expenseAccount.parent_branch_name + '(' + expenseAccount.area_name + ')-' + expenseAccount.branch_code;
                                branchName = expenseAccount.parent_branch_name + '(' + expenseAccount.area_name + ')';
                            } else {

                                // branchName = expenseAccount.branch_name + '(' + expenseAccount.area_name + ')-' + expenseAccount.branch_code;
                                branchName = expenseAccount.branch_name + '(' + expenseAccount.area_name + ')';
                            }
                        }

                        var expenseAccountName = name + ' | ' + branchName;

                        $('#expense_account_id').append('<option data-expense_account_name="' + expenseAccountName + '" value="' + expenseAccount.id + '">' + expenseAccountName + '</option>');
                    });
                }
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
    }
    expenseAccounts();

    $(document).on('change', '.filter_expense_accounts', function(e) {
        e.preventDefault();

        expenseAccounts();
    });

    //Print financial report
    $(document).on('click', '#printReport', function(e) {
        e.preventDefault();

        var url = "{{ route('reports.expenses.print') }}";

        var branch_id = $('#branch_id').val();
        var branch_name = $('#branch_id').find('option:selected').data('branch_name');
        var child_branch_id = $('#child_branch_id').val();
        var child_branch_name = $('#child_branch_id').find('option:selected').data('child_branch_name');
        var expense_group_id = $('#expense_group_id').val();
        var expense_group_name = $('#expense_group_id').find('option:selected').data('expense_group_name');
        var expense_account_id = $('#expense_account_id').val();
        var expense_account_name = $('#expense_account_id').find('option:selected').data('expense_account_name');
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                child_branch_id,
                child_branch_name,
                expense_group_id,
                expense_group_name,
                expense_account_id,
                expense_account_name,
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
                    printDelay: 700,
                    header: null,
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

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });

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

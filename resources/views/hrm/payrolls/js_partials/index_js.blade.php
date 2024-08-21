<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/plugins/custom/print_this/printThis.js') }}"></script>

<script>
    // Show session message by toster alert.
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    var payrollsTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: 'Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
        ],
        "processing": true,
        "serverSide": true,
        "searching": true,
        "language": {
            "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
        },
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('hrm.payrolls.index') }}",
            "data": function(d) {
                d.branch_id = $('#f_branch_id').val();
                d.user_id = $('#f_user_id').val();
                d.month_year = $('#f_month_year').val();
            }
        },
        columns: [{
                data: 'month_year',
                name: 'hrm_payrolls.month'
            },
            {
                data: 'user',
                name: 'users.name'
            },
            {
                data: 'voucher_no',
                name: 'hrm_payrolls.voucher_no',
                className: 'fw-bold'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'department_name',
                name: 'hrm_departments.name'
            },
            {
                data: 'payment_status',
                name: 'users.last_name'
            },
            {
                data: 'gross_amount',
                name: 'parentBranch.name',
                className: 'fw-bold'
            },
            {
                data: 'paid',
                name: 'hrm_payrolls.paid',
                className: 'fw-bold'
            },
            {
                data: 'due',
                name: 'hrm_payrolls.due',
                className: 'fw-bold'
            },
            {
                data: 'action'
            },
        ],
        fnDrawCallback: function() {

            var gross_amount = sum_table_col($('.data_tbl'), 'gross_amount');
            $('#gross_amount').text(bdFormat(gross_amount));

            var paid = sum_table_col($('.data_tbl'), 'paid');
            $('#paid').text(bdFormat(paid));

            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(bdFormat(due));
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
        payrollsTable.ajax.reload();
    });

    $('#department_id').on('change', function(e) {
        e.preventDefault();
        var department_id = $(this).val();

        var url = "{{ route('hrm.departments.users', ':department_id') }}";
        var route = url.replace(':department_id', department_id);

        $.ajax({
            url: route,
            type: 'get',
            success: function(users) {

                $('#user_id').empty();
                $('#user_id').append('<option value="">' + "{{ __('Select Employee') }}" + '</option>');

                $.each(users, function(key, user) {

                    var prefix = user.prefix != null ? user.prefix : '';
                    var name = user.name != null ? ' ' + user.name : '';
                    var last_name = user.last_name != null ? ' ' + user.last_name : '';
                    var emp_id = user.emp_id != null ? '(' + user.emp_id + ')' : '';

                    var __name = prefix + name + last_name + emp_id;

                    $('#user_id').append('<option value="' + user.id + '">' + __name + '</option>');
                });
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error') }}");
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

    $(document).on('click', '#extraDetailsBtn', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#extra_details').html(data);
                $('#extra_details #detailsModal').modal('show');
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
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes bg-primary',
                    'action': function() {
                        $('#deleted_form').submit();
                    }
                },
                'No': {
                    'class': 'no bg-danger',
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

                payrollsTable.ajax.reload(null, false);
                toastr.error(data);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);
            }
        });
    });
</script>

<script>
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

    $(document).on('click', '#deletePayment', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#delete_payroll_payment_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes bg-primary',
                    'action': function() {
                        $('#delete_payroll_payment_form').submit();
                    }
                },
                'No': {
                    'class': 'no bg-danger',
                    'action': function() {
                        console.log('Deleted canceled.');
                    }
                }
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#delete_payroll_payment_form', function(e) {
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

                $('.modal').modal('hide');
                payrollsTable.ajax.reload(null, false);
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

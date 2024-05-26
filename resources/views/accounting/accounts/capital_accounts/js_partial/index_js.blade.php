<script>
    $('.select2').select2();

    var accounts_table = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
        ],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('accounts.capitals.index') }}",
            "data": function(d) {
                d.account_group_id = $('#f_account_group_id').val();
            }
        },
        columns: [{
                data: 'group',
                name: 'account_groups.name'
            },
            {
                data: 'name',
                name: 'accounts.name'
            },
            {
                data: 'opening_balance',
                name: 'accounts.opening_balance',
                className: 'text-end fw-bold'
            },
            {
                data: 'debit',
                name: 'accounts.account_number',
                className: 'text-end fw-bold'
            },
            {
                data: 'credit',
                name: 'accounts.account_number',
                className: 'text-end fw-bold'
            },
            {
                data: 'closing_balance',
                name: 'accounts.account_number',
                className: 'text-end fw-bold'
            },
            {
                data: 'action',
                name: 'accounts.account_number'
            },
        ],
        fnDrawCallback: function() {

            var dr_opening_balance = sum_table_col($('.data_tbl'), 'dr_opening_balance');
            var cr_opening_balance = sum_table_col($('.data_tbl'), 'cr_opening_balance');

            var totalOpeningBalance = 0;
            var totalOpeningBalanceSide = 'Dr.';
            if (dr_opening_balance > cr_opening_balance) {

                totalOpeningBalance = dr_opening_balance - cr_opening_balance;
                totalOpeningBalanceSide = 'Dr.';
            } else if (cr_opening_balance > dr_opening_balance) {

                totalOpeningBalance = cr_opening_balance - dr_opening_balance;
                totalOpeningBalanceSide = 'Cr.';
            }

            $('#total_opening_balance').html(bdFormat(totalOpeningBalance) + ' ' + totalOpeningBalanceSide);

            var total_debit = sum_table_col($('.data_tbl'), 'debit');
            $('#total_debit').html(bdFormat(total_debit));
            var total_credit = sum_table_col($('.data_tbl'), 'credit');
            $('#total_credit').html(bdFormat(total_credit));

            var dr_closing_balance = sum_table_col($('.data_tbl'), 'dr_closing_balance');
            var cr_closing_balance = sum_table_col($('.data_tbl'), 'cr_closing_balance');

            var totalClosingBalance = 0;
            var totalClosingBalanceSide = 'Dr.';
            if (dr_closing_balance > cr_closing_balance) {

                totalClosingBalance = dr_closing_balance - cr_closing_balance;
                totalClosingBalanceSide = 'Dr.';
            } else if (cr_closing_balance > dr_closing_balance) {

                totalClosingBalance = cr_closing_balance - dr_closing_balance;
                totalClosingBalanceSide = 'Cr.';
            }

            $('#total_closing_balance').html(bdFormat(totalClosingBalance) + ' ' + totalClosingBalanceSide);

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
        accounts_table.ajax.reload();
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {
        // Add account by ajax
        $(document).on('click', '#addAccountBtn', function(e) {
            e.preventDefault();
            var group_id = $(this).data('group_id');
            $('#parent_group_id').val(group_id).trigger('change');
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                async: false,
                success: function(data) {

                    $('#accountAddOrEditModal .modal-dialog').remove();
                    $('#accountAddOrEditModal').html(data);
                    $('#accountAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#account_name').focus();
                    }, 500);

                    $('.data_preloader').hide();

                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#editAccount', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#accountAddOrEditModal').empty();
                    $('#accountAddOrEditModal').html(data);
                    $('#accountAddOrEditModal').modal('show');

                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('#account_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else {

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
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
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
                    accounts_table.ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });

    // document.onkeyup = function () {
    //     var e = e || window.event; // for IE to cover IEs window event-object
    //     //console.log(e);
    //     if(e.ctrlKey && e.which == 13) {
    //         $('#addModal').modal('show');
    //         setTimeout(function () {
    //             $('#name').focus();
    //         }, 500);
    //         //return false;
    //     }
    // }
</script>

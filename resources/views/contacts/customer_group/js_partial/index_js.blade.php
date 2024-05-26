<script>
    // Show session message by toster alert.
    @if (Session::has('successMsg'))

        toastr.success('{{ session('successMsg')[0] }}');
    @endif

    var customerGroupsTable = $('.data_tbl').DataTable({
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
        "processing": true,
        "serverSide": true,
        //aaSorting: [[0, 'asc']],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('contacts.customers.groups.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'name',
                name: 'customer_groups.name'
            },
            {
                data: 'price_calculation_type',
                name: 'customer_groups.price_calculation_type',
            },
            {
                data: 'calculation_percentage',
                name: 'customer_groups.calculation_percentage',
                className: 'fw-bold'
            },
            {
                data: 'price_group_name',
                name: 'price_groups.name'
            },
            {
                data: 'action'
            },
        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        customerGroupsTable.ajax.reload();
    });

    // call jquery method
    $(document).on('click', '#addCustomerGroup', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#customerGroupAddOrEditModal').html(data);
                $('#customerGroupAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#cus_group_name').focus();
                }, 500);
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

    // pass editable data to edit modal fields
    $(document).on('click', '#edit', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $('.data_preloader').show();
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#customerGroupAddOrEditModal').empty();
                $('#customerGroupAddOrEditModal').html(data);
                $('#customerGroupAddOrEditModal').modal('show');
                $('.data_preloader').hide();
                setTimeout(function() {

                    $('#cus_group_name').focus().select();
                }, 500);
            },
            error: function(err) {

                $('.data_preloader').hide();
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

                customerGroupsTable.ajax.reload();
                toastr.error(data);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });
</script>

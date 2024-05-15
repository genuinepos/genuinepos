<script>
    var leavesTable = $('#leaves_table').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'pdf',
                text: 'Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                className: 'btn btn-primary',
                autoPrint: true,
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        "language": {
            "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
        },
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('hrm.leaves.index') }}",
        columns: [{
                data: 'leave_no',
                name: 'hrm_leaves.leave_no'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'leave_type',
                name: 'hrm_leave_types.name'
            },
            {
                data: 'user',
                name: 'users.name'
            },
            {
                data: 'start_date',
                name: 'hrm_leaves.start_date'
            },
            {
                data: 'end_date',
                name: 'hrm_leaves.end_date'
            },
            {
                data: 'reason',
                name: 'hrm_leaves.reason'
            },
            {
                data: 'status',
                name: 'users.last_name'
            },
            {
                data: 'action'
            },
        ],
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {

        $(document).on('click', '#addLeave', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#leaveAddOrEditModal').html(data);
                    $('#leaveAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#leave_user_id').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#editLeave', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#leaveAddOrEditModal').empty();
                    $('#leaveAddOrEditModal').html(data);
                    $('#leaveAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#leave_user_id').focus();
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

        $(document).on('click', '#deleteLeave', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_leave_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#delete_leave_form').submit();
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
        $(document).on('submit', '#delete_leave_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    if ($.isEmptyObject(data.errorMsg)) {

                        toastr.error(data);
                        leavesTable.ajax.reload();
                        $('#delete_leave_form')[0].reset();
                    } else {

                        toastr.error(data.errorMsg);
                    }
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
    });
</script>

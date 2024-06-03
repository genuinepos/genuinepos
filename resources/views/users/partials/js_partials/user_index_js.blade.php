<script>
    // Show session message by toster alert.
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'pdf',
                className: '',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'excel',
                className: '',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                className: '',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
        ],
        "processing": true,
        "serverSide": true,
        // aaSorting: [[8, 'asc']],
        "language": {
            "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
        },
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('users.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.user_type = $('#user_type').val();
            }
        },
        columns: [{
                data: 'username',
                name: 'username'
            },
            {
                data: 'allow_login',
                name: 'username'
            }, {
                data: 'type',
                name: 'type',
                className: 'fw-bold'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'phone',
                name: 'phone'
            }

            , {
                data: 'branch',
                name: 'branches.name'
            }, {
                data: 'role_name',
                name: 'role_name'
            }, {
                data: 'email',
                name: 'email'
            }, {
                data: 'action'
            },
        ],
    });
    // table.buttons().container().appendTo('#exportButtonsContainer');
    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function() {
        table.ajax.reload();
    });

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation',
            'content': 'Are you sure?',
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

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }
                
                table.ajax.reload(null, false);
                toastr.error(data);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
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

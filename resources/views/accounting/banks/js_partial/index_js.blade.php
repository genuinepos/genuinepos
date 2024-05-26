<script>
    var bankTable = $('.bank_table').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: 'Excel',
                messageTop: 'Asset types',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: 'Pdf',
                messageTop: 'Asset types',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: 'Print',
                messageTop: '<b>Asset types</b>',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        "lengthMenu": [25, 100, 500, 1000, 2000],
        ajax: "{{ route('banks.index') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'action',
                name: 'action'
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
        // Add bank by ajax
        $(document).on('click', '#addBankBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#bankAddOrEditModal').html(data);
                    $('#bankAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#bank_name').focus();
                    }, 500);
                }
            })
        });

        $(document).on('click', '#editBank', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#bankAddOrEditModal').html(data);
                    $('#bankAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#bank_name').focus().select();
                    }, 500);
                }
            })
        });

        $(document).on('click', '#deleteBank', function(e) {
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
                async: false,
                data: request,
                success: function(data) {

                    bankTable.ajax.reload(false, null);
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });
    });
</script>

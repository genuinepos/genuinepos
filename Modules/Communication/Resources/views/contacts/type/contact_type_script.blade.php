<script>
    var table = $('.contactGroupTable').DataTable({
        // dom: "lBfrtip",
        processing: true,
        serverSide: true,
        ajax: "{{ route('communication.contacts.group.index') }}",
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
        // Add locations by ajax
        $(document).on('submit', '#add_group_form', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.submit_button').prop('type', 'button');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    $('.error').html('');
                    toastr.success(data);
                    $('#add_group_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.contactGroupTable').DataTable().ajax.reload();
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');
                    $('.submit_button').prop('type', 'submit');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        // pass editable data to edit model fields
        $(document).on('click', '#edit_group', function(e) {

            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).closest('tr').data('href');

            console.log(url);
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit_group_form_body').html(data);
                    $('#add_form_group_div').hide();
                    $('#edit_group_form').show();
                    $('.data_preloader').hide();
                    document.getElementById('name').focus();
                },
                error: function(err) {
                    $('.data_preloader').hide();
                    if (err.status == 0) {
                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else {
                        toastr.error("{{ __('Server Error, Please contact to the support team.') }}");
                    }
                }
            });
        });

        $(document).on('click', '#update_group_btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_group_form').attr('action', url);
            $.confirm({
                'title': 'Edit Confirmation',
                'content': 'Are you sure to edit?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#update_group_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            console.log('Edit canceled.');
                        }
                    }
                }
            });
        });

        // edit units by ajax
        $(document).on('submit', '#update_group_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            console.log(url);
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data);
                    $('.contactGroupTable').DataTable().ajax.reload();
                    $('.loading_button').hide();
                    $('#add_form_group_div').show();
                    $('#edit_group_form').hide();
                    $('.error').html('');
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {
                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_e_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('click', '#delete_group', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_group_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#deleted_group_form').submit();
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
        $(document).on('submit', '#deleted_group_form', function(e) {
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
                        $('.contactGroupTable').DataTable().ajax.reload();
                        $('#deleted_group_form')[0].reset();
                    } else {

                        toastr.error(data.errorMsg);
                    }
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Please check the connection.');
                    } else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        $(document).on('click', '#close_group_form', function() {
            $('#add_form_group_div').show();
            $('#edit_group_form').hide();
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });
    });
</script>

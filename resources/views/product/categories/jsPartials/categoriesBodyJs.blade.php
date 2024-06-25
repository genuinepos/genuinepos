<script>
    var categoriesTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'pdf',
                'title': 'List of categories',
                text: 'Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                'title': 'List of categories',
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
        ajax: "{{ route('categories.index') }}",
        columns: [
            // {
            //     data: 'DT_RowIndex',
            //     name: 'DT_RowIndex'
            // },
            {
                data: 'code',
                name: 'categories.code'
            },
            {
                data: 'photo',
                name: 'categories.photo'
            },
            {
                data: 'name',
                name: 'categories.name'
            },
            {
                data: 'description',
                name: 'categories.description'
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

        $(document).on('click', '#addCategory', function(e) {
            e.preventDefault();

            var url = "{{ route('categories.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#categoryAddOrEditModal').html(data);
                    $('#categoryAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#category_name').focus();
                    }, 500);
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

        $(document).on('click', '#editCategory', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#categoryAddOrEditModal').empty();
                    $('#categoryAddOrEditModal').html(data);
                    $('#categoryAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#category_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
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

        $(document).on('click', '#deleteCategory', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_category_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#deleted_category_form').submit();
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
        $(document).on('submit', '#deleted_category_form', function(e) {
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
                        categoriesTable.ajax.reload();
                        $('#deleted_category_form')[0].reset();
                    } else {

                        toastr.error(data.errorMsg);
                    }
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error.');
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }

                    toastr.error(err.responseJSON.message);
                }
            });
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

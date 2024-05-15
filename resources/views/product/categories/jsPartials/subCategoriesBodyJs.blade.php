<script>
    var subcategoriesTable = $('.data_tbl2').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'pdf',
                'title': 'List of Subcategories',
                text: 'Pdf',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                'title': 'List of Subcategories',
                text: 'Print',
                className: 'btn btn-primary',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
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
        ajax: "{{ route('subcategories.index') }}",
        columns: [
            // {
            //     data: 'DT_RowIndex'
            // },
            {
                data: 'code',
                name: 'categories.code'
            },
            {
                data: 'photo',
                name: 'categories.name'
            },
            {
                data: 'name',
                name: 'categories.name'
            },
            {
                data: 'parent_category_name',
                name: 'parentCategory.name'
            },
            {
                data: 'description',
                name: 'categories.description'
            },
            {
                data: 'action'
            },
        ]
    });

    $(document).ready(function() {

        $(document).on('click', '#addSubcategory', function(e) {
            e.preventDefault();

            var url = "{{ route('subcategories.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#subcategoryAddOrEditModal').html(data);
                    $('#subcategoryAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#subcategory_name').focus();
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
                }
            });
        });

        $(document).on('click', '#editSubcategory', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#subcategoryAddOrEditModal').empty();
                    $('#subcategoryAddOrEditModal').html(data);
                    $('#subcategoryAddOrEditModal').modal('show');
                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('#subcategory_name').focus().select();
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
                }
            });
        });

        $(document).on('click', '#deleteSubcategory', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            $('#deleted_sub_category_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_sub_category_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary ',
                        'action': function() {
                            console.log('Deleted canceled.');
                        }
                    }
                }
            });
        });

        $(document).on('submit', '#deleted_sub_category_form', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                async: false,
                data: request,
                success: function(data) {

                    subcategoriesTable.ajax.reload();
                    toastr.error(data);
                    $('#deleted_sub_category_form')[0].reset();
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    });
</script>

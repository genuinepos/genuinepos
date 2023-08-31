<script>
        var subcategoriesTable = $('.data_tbl2').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf', 'title' : 'List of Subcategories', text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print', 'title' : 'List of Subcategories', text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        ajax: "{{ route('subcategories.index') }}",
        columnDefs: [{"targets": [0, 1, 3, 4], "orderable": false, "searchable": false}],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'photo',name: 'photo'},
            {data: 'name',name: 'name'},
            {data: 'parentname',name: 'parentname'},
            {data: 'description',name: 'description'},
            {data: 'action',name: 'action'},
        ]
    });

    $(document).ready(function () {

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

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
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

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#deleteSubcategory',function(e){
            e.preventDefault();
            var url = $(this).attr('href');

            $('#deleted_sub_category_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_sub_category_form').submit();}},
                    'No': {'class': 'no btn-modal-primary ','action': function() {console.log('Deleted canceled.');}}
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
                },error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else{

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    });
</script>

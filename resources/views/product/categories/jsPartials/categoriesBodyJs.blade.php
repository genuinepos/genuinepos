<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',autoPrint: true,exportOptions: {columns: ':visible'}}
        ],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('product.categories.index') }}",
        columnDefs: [{"targets": [0, 1, 3], "orderable": false, "searchable": false}],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'photo',name: 'photo'},
            {data: 'name',name: 'name'},
            {data: 'action',name: 'action'},
        ],
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method 
    $(document).ready(function() {
        // Add category by ajax
        $(document).on('submit', '#add_category_form', function(e) {
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
                    $('#add_category_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    table.ajax.reload();
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                    });
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).closest('tr').data('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit_cate_form_body').html(data);
                    $('#add_cate_form').hide();
                    $('#edit_cate_form').show();
                    $('.data_preloader').hide();
                    document.getElementById('e_name').focus();
                }
            });
        });

        $(document).on('click', '#update_btn',function(e){
            e.preventDefault(); 
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);       
            $.confirm({
                'title': 'Edit Confirmation',
                'content': 'Are you sure to edit?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#edit_category_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Edit canceled.');}}
                }
            });
        });

        // edit category by ajax
        $(document).on('submit', '#edit_category_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data);
                    table.ajax.reload();
                    $('.loading_button').hide();
                    $('#add_cate_form').show();
                    $('#edit_cate_form').hide();
                    $('.error').html('');
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_e_' + key + '').html(error[0]);
                    });
                }
            }); 
        });
        
        $(document).on('click', '#delete',function(e){
            e.preventDefault(); 
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);       
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
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
                    toastr.error(data);
                    table.ajax.reload();
                    $('#deleted_form')[0].reset();
                }
            });
        });

        $(document).on('click', '#close_cate_form', function() {
            $('#add_cate_form').show();
            $('#edit_cate_form').hide();
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
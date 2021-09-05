<script>
    var loans_table = $('.data_tbl2').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',autoPrint: true,exportOptions: {columns: ':visible'}}
        ],
        "lengthMenu" : [25, 100, 500, 1000, 2000],
        processing: true,
        serverSide: true,
        searchable: true,
        "ajax": {
            "url": "{{ route('accounting.loan.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.company_id = $('#f_company_id').val();
                d.date_range = $('#date_range').val();
            }
        },
        columns: [
            {data: 'action', name: 'action'},
            {data: 'report_date',name: 'report_date'},
            {data: 'branch',name: 'branch'},
            {data: 'reference_no', name: 'reference_no'},
            {data: 'c_name', name: 'c_name'},
            {data: 'type', name: 'type'},
            {data: 'loan_amount', name: 'loan_amount'},
            {data: 'due', name: 'due'},
            {data: 'total_paid', name: 'total_paid'},
        ],
    });

    // Add loan by ajax
    $(document).on('submit', '#adding_loan_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.error').html('');
                toastr.success(data);
                $('#adding_loan_form')[0].reset();
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                loans_table.ajax.reload();
                companies_table.ajax.reload();
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
    $(document).on('click', '#edit_loan', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function (data) {
            if (!$.isEmptyObject(data.errorMsg)) {
                toastr.error(data.errorMsg);
            }else{
                $('#edit_loan_form_body').html(data);
                $('#add_loan_form').hide();
                $('#edit_loan_form').show();
                document.getElementById('e_company_id').focus();
            }
            $('.data_preloader').hide();
        });
    });

     // Edit company by ajax
    $(document).on('submit', '#editting_loan_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                companies_table.ajax.reload();
                loans_table.ajax.reload();
                $('.loading_button').hide();
                $('#add_loan_form').show();
                $('#edit_loan_form').hide();
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

    $(document).on('click', '#delete_loan',function(e){
        e.preventDefault(); 
        var url = $(this).attr('href');
        $('#delete_loan_form').attr('action', url);       
        $.confirm({
            'title': 'Delete Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#delete_loan_form').submit();}},
                'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#delete_loan_form', function(e) {
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
                }else{
                    toastr.error(data);
                    companies_table.ajax.reload();
                    loans_table.ajax.reload();
                    $('#delete_loan_form')[0].reset();
                }
            }
        });
    });

    function allCompanies() {
        var url = "{{ route('accounting.loan.all.companies.for.form') }}";
        $.ajax({
            url: url,
            type: 'get',
            success: function(companies) {
                console.log(companies);
                $('#company_id').empty();
                $('#f_company_id').empty();
                $('#company_id').append('<option value="">Select Company</option>');
                $('#f_company_id').append('<option value="">All</option>');
                $.each(companies, function (key, com) {
                    $('#company_id').append('<option value="'+com.id +'">'+com.name+'</option>');
                    $('#f_company_id').append('<option value="'+com.id +'">'+com.name+'</option>');
                })
            }
        });
    }
    allCompanies();

    $(document).on('click', '#close_loan_edit_form', function() {
        $('#add_loan_form').show();
        $('#edit_loan_form').hide();
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        loans_table.ajax.reload();
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            loans_table.ajax.reload();
        }, 500);
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.applyBtn', function () {
        setTimeout(function() {
            $('.submit_able_input').addClass('.form-control:focus');
            $('.submit_able_input').blur();
        }, 500);
    });
</script>
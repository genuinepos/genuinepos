<script>
    $('.loans').hide();
    var companies_table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',autoPrint: true,exportOptions: {columns: ':visible'}}
        ],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('accounting.loan.companies.index') }}",
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'name',name: 'name'},
            {data: 'pay_loan_amount',name: 'pay_loan_amount'},
            {data: 'total_receive',name: 'total_receive'},
            {data: 'get_loan_amount',name: 'get_loan_amount'},
            {data: 'total_pay',name: 'total_pay'},
            {data: 'action',name: 'action'},
        ],
    });

    // Add company by ajax
    $(document).on('submit', '#add_company_form', function(e) {
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
                $('#add_company_form')[0].reset();
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                companies_table.ajax.reload();
                allCompanies();
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
    $(document).on('click', '#edit_company', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function (data) {
            $('#edit_com_form_body').html(data);
            $('#add_com_form').hide();
            $('#edit_com_form').show();
            $('.data_preloader').hide();
            document.getElementById('e_name').focus();
        });
    });

    // Edit company by ajax
    $(document).on('submit', '#edit_company_form', function(e) {
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
                allCompanies();
                $('.loading_button').hide();
                $('#add_com_form').show();
                $('#edit_com_form').hide();
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

    $(document).on('click', '#delete_company',function(e){
        e.preventDefault(); 
        var url = $(this).attr('href');
        $('#delete_companies_form').attr('action', url);       
        $.confirm({
            'title': 'Delete Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#delete_companies_form').submit();}},
                'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#delete_companies_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.error(data);
                companies_table.ajax.reload();
                loans_table.ajax.reload();
                allCompanies();
                $('#delete_companies_form')[0].reset();
            }
        });
    });

    $(document).on('click', '#close_com_edit_form', function() {
        $('#add_com_form').show();
        $('#edit_com_form').hide();
    });

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });

    $(document).on('click', '#loan_payment', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $('#loanPymentModal').html(data); 
            $('#loanPymentModal').modal('show'); 
            $('.data_preloader').hide();
        });
    });

            //Add sale payment request by ajax
    $(document).on('submit', '#loan_payment_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var available_amount = $('#p_available_amount').val();
        var paying_amount = $('#p_amount').val();
        if (parseFloat(paying_amount) > parseFloat(available_amount)) {
            $('.error_p_amount').html('Paying amount must not be greater then due amount.');
            $('.loading_button').hide();
            return;
        }

        if (parseFloat(paying_amount) <= 0) {
            $('.error_p_amount').html('Amount must be greater then 0.');
            $('.loading_button').hide();
            return;
        }

        var url = $(this).attr('action');
        var inputs = $('.p_input');
            $('.error').html('');  
            var countErrorField = 0;  
        $.each(inputs, function(key, val){
            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();
            if(idValue == ''){
                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){
            $('.loading_button').hide();
            toastr.error('Please check again all form fields.','Some thing want wrong.'); 
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success:function(data){
                $('.loading_button').hide();
                $('#loanPaymentModal').modal('hide');
                $('#paymentViewModal').modal('hide');
                toastr.error(data);
                companies_table.ajax.reload();
                loans_table.ajax.reload();
            },error:function(err){
                toastr.error('Please Reload this page again.','Net Connetion is Error'); 
            }
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script>
    var lastChartListClass = '';

    $(document).on('click', '#addAccountGroupBtn', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var group_id = $(this).data('group_id');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#accountGroupAddOrEditModal').html(data);
                $('#accountGroupAddOrEditModal').modal('show');

                $('#parent_group_id').val(group_id).trigger('change');

                var is_allowed_bank_details = $('#parent_group_id').find('option:selected').data('is_allowed_bank_details');
                $('#is_allowed_bank_details').val(is_allowed_bank_details);
                var is_default_tax_calculator = $('#parent_group_id').find('option:selected').data('is_default_tax_calculator');
                $('#is_default_tax_calculator').val(is_default_tax_calculator);

                setTimeout(function() {

                    $('#account_group_name').focus();
                }, 500);
            }
        })
    });

    // pass editable data to edit modal fields
    $(document).on('click', '#editAccountGroupBtn', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        var url = $(this).attr('href');
        lastChartListClass = $(this).data('class_name');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#accountGroupAddOrEditModal').empty();
                $('#accountGroupAddOrEditModal').html(data);
                $('#accountGroupAddOrEditModal').modal('show');
                $('.data_preloader').hide();

                setTimeout(function() {

                    $('#account_group_name').focus().select();
                }, 500);
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });

    function getAjaxList() {

        $('.data_preloader').show();
        var branch_id = $('#f_branch_id').val();
        $.ajax({
            url: "{{ route('account.groups.list') }}",
            async: true,
            type: 'get',
            data: {
                branch_id
            },
            success: function(data) {

                var div = $('#list_of_groups').html(data);

                if (lastChartListClass) {

                    var scrollTo = $('.' + lastChartListClass);
                    scrollTo.addClass('jstree-clicked');

                    $('html, body').animate({

                        scrollTop: scrollTo.offset().top - 500
                    }, 0);
                }

                $('.data_preloader').hide();
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                } else {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    }
    getAjaxList();

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        getAjaxList();
    });


    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation',
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-danger',
                    'action': function() {
                        $('#deleted_form').submit();
                    }
                },
                'No': {
                    'class': 'no btn-primary',
                    'action': function() {}
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

                lastChartListClass = '';
                getAjaxList();
                $("#parent_group_id").load(location.href + " #parent_group_id>*", "");
                toastr.error(data);
                $('#deleted_form')[0].reset();

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

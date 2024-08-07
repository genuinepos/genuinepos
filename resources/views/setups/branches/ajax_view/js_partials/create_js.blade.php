<script>
    $('#logo').dropify({
        messages: {
            'default': "{{ __('Drag and drop a file here or click') }}",
            'replace': "{{ __('Drag and drop or click to replace') }}",
            'remove': "{{ __('Remove') }}",
            'error': "{{ __('Ooops, something wrong happended.') }}"
        }
    });

    $('#branch_timezone').select2();
    $('#branch_currency_id').select2();
    $('#branch_financial_year_start_month').select2();

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.branch_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.branch_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#add_branch_form').on('submit', function(e) {
        e.preventDefault();
        $('.branch_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        isAjaxIn = false;
        isAllowSubmit = false;

        $.ajax({
            beforeSend: function() {
                isAjaxIn = true;
            },
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.branch_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#branchAddOrEditModal').modal('hide');
                toastr.success(data);
                branchTable.ajax.reload();
            },
            error: function(err) {

                isAjaxIn = true;
                isAllowSubmit = true;

                $('.branch_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connection Error.');
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        var branchType = $('#branch_type').val();

        if (nextId == 'branch_stock_accounting_method' && branchType == 2) {
            console.log(branchType);
            setTimeout(function() {

                $('#branch_currency_id').focus();
            }, 100);

            return;
        }

        console.log({
            nextId
        });

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'branch_type' && $('#branch_type').val() == 2) {

                $('#branch_parent_branch_id').focus();
                return;
            }

            if ($(this).attr('id') == 'branch_parent_branch_id') {

                $('#branch_area_name').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $('#add_initial_user_btn').on('click', function() {
        $('.add_initial_user_section').toggle(500);

        if ($('#add_initial_user').val() == 0) {

            $('#add_initial_user').val(1);
            $('#user_first_name').focus();
            $('.initial_user_required_field').prop('required', true);
        } else if ($('#add_initial_user').val() == 1) {

            $('#add_initial_user').val(0);
            $('#branch_save').focus();
            $('.initial_user_required_field').prop('required', false);
        }
    });

    function changeBranchType(e) {

        $('.parent_branches_field').hide();
        $('#branch_parent_branch_id').val('');
        console.log($('#branch_parent_branch_id'));
        getBranchCode($('#branch_parent_branch_id'));

        if ($(e).val() == 2) {

            $('.parent_branches_field').show();
            $('#branch_parent_branch_id').prop('required', true);
            $('.branch_name_field').hide();
            $('.branch_log_field').hide();
            $('#branch_name').prop('required', false);

            $('#stock_accounting_method_field').hide();
            $('#account_start_date_field').hide();
            $('#branch_account_start_date').prop('required', false);
            $('#financial_year_start_month_field').hide();
        } else {

            $('.parent_branches_field').hide();
            $('#branch_parent_branch_id').prop('required', false);
            $('.branch_name_field').show();
            $('.branch_log_field').show();
            $('#branch_name').prop('required', true);

            $('#stock_accounting_method_field').show();
            $('#account_start_date_field').show();
            $('#branch_account_start_date').prop('required', true);
            $('#financial_year_start_month_field').show();
        }
    };

    function getBranchCode(e) {

        var parentBranchId = $(e).val();
        var url = "{{ route('branches.code', ':parentBranchId') }}";
        var route = url.replace(':parentBranchId', parentBranchId);

        $.ajax({
            url: route,
            type: 'get',
            success: function(data) {

                $('#branch_code').val(data);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connection Error.');
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    };

    $(document).on('change', '#branch_currency_id', function(e) {
        var currencySymbol = $(this).find('option:selected').data('currency_symbol');
        $('#branch_currency_symbol').val(currencySymbol);
    });

    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');

    new Litepicker({
        singleMode: true,
        element: document.getElementById('branch_account_start_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: _expectedDateFormat,
    });
</script>

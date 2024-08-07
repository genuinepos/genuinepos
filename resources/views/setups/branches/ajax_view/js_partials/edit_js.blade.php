<script>
    @if ($branch->branch_type != \App\Enums\BranchType::ChainShop->value)
        $('#logo').dropify({
            messages: {
                'default': "{{ __('Drag and drop a file here or click') }}",
                'replace': "{{ __('Drag and drop or click to replace') }}",
                'remove': "{{ __('Remove') }}",
                'error': "{{ __('Ooops, something wrong happended.') }}"
            }
        });
    @endif

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

    $('#edit_branch_form').on('submit', function(e) {
        e.preventDefault();
        $('.branch_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.branch_loading_btn').hide();
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $('#branchAddOrEditModal').modal('hide');
                toastr.success(data);
                branchTable.ajax.reload(false, null);
            },
            error: function(err) {

                $('.branch_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_branch_' + key + '').html(error[0]);
                });
            }
        });
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        var branchType = $('#branch_type').val();
        if (nextId == 'branch_stock_accounting_method' && branchType == 2) {

            setTimeout(function() {

                $('#branch_currency_id').focus();
            }, 100);

            return;
        }

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

                $('#branch_code').focus();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    // $('#branch_type').on('click', function() {

    //     $('.parent_branches_field').hide();
    //     $('#parent_branch_id').val('');

    //     if ($(this).val() == 2) {

    //         $('.parent_branches_field').show();
    //         $('#branch_parent_branch_id').prop('required', true);
    //         $('.branch_name_field').hide();
    //         $('#branch_name').prop('required', false);
    //     } else {

    //         $('.parent_branches_field').hide();
    //         $('#branch_parent_branch_id').prop('required', false);
    //         $('.branch_name_field').show();
    //         $('#branch_name').prop('required', true);
    //     }
    // });

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

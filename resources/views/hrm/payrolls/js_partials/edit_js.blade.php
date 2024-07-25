<script>
    function calculateAmount() {

        var durationTime = $('#duration_time').val() ? $('#duration_time').val() : 0;
        var amountPerUnit = $('#amount_per_unit').val() ? $('#amount_per_unit').val() : 0;
        var totalAmount = parseFloat(durationTime) * parseFloat(amountPerUnit);
        $('#total_amount').val(parseFloat(totalAmount).toFixed(2));

        var allowances = document.querySelectorAll('#allowances');
        allowances.forEach(function(allowances) {

            var className = allowances.getAttribute('class');
            var closestTr = $('.' + className).closest('tr');
            var allowanceAmountType = closestTr.find('#allowance_amount_type').val();

            if (allowanceAmountType == 2) {
                var allowancePercent = closestTr.find('#allowance_percent').val() ? closestTr.find('#allowance_percent').val() : 0;
                var calAllowanceAmount = (parseFloat(totalAmount) / 100) * parseFloat(allowancePercent);
                closestTr.find('#allowance_amount').val(parseFloat(calAllowanceAmount).toFixed(2));
            }
        });

        var deductions = document.querySelectorAll('#deductions');
        deductions.forEach(function(deduction) {
            var className = deduction.getAttribute('class');
            var closestTr = $('.' + className).closest('tr');
            var deductionAmountType = closestTr.find('#deduction_amount_type').val();

            if (deductionAmountType == 2) {

                var deductionPercent = closestTr.find('#deduction_percent').val() ? closestTr.find('#deduction_percent').val() : 0;
                var calDeductionAmount = (parseFloat(totalAmount) / 100) * parseFloat(deductionPercent);
                closestTr.find('#deduction_amount').val(parseFloat(calDeductionAmount).toFixed(2));
            }
        });

        var allowanceAmounts = document.querySelectorAll('#allowance_amount');
        var totalAllowanceAmount = 0;
        allowanceAmounts.forEach(function(allowanceAmount) {
            totalAllowanceAmount += parseFloat(allowanceAmount.value ? allowanceAmount.value : 0);
        });

        $('#span_total_allowance').html(parseFloat(totalAllowanceAmount).toFixed(2));
        $('#total_allowance').val(parseFloat(totalAllowanceAmount).toFixed(2));

        var deductionAmounts = document.querySelectorAll('#deduction_amount');
        var totalDeductionAmount = 0;
        deductionAmounts.forEach(function(deductionAmount) {
            totalDeductionAmount += parseFloat(deductionAmount.value ? deductionAmount.value : 0);
        });

        $('#span_total_deduction').html(parseFloat(totalDeductionAmount).toFixed(2));
        $('#total_deduction').val(parseFloat(totalDeductionAmount).toFixed(2));

        // Calc Gross amount
        var grossAmount = parseFloat(totalAmount) + parseFloat(totalAllowanceAmount) - parseFloat(totalDeductionAmount);
        $('#span_gross_amount').html(parseFloat(grossAmount).toFixed(2));
        $('#gross_amount').val(parseFloat(grossAmount).toFixed(2));
    }
    calculateAmount();

    $(document).on('input', '#duration_time', function() {
        calculateAmount();
    });

    $(document).on('input', '#amount_per_unit', function() {
        calculateAmount();
    });

    $(document).on('input', '#deduction_percent', function() {
        calculateAmount();
    });

    $(document).on('input', '#allowance_percent', function() {
        calculateAmount();
    });

    $(document).on('input', '#allowance_amount', function() {
        calculateAmount();
    });

    $(document).on('input', '#deduction_amount', function() {
        calculateAmount();
    });

    // Add More Allowance
    var index = {{ $index }};
    $(document).on('click', '#add_more_allowance', function(e) {
        e.preventDefault();
        var html = '';
        html += '<tr>';
        html += '<td>';
        html += '<input type="hidden" name="payroll_allowance_ids[]">';
        html += '<input type="hidden" class="allowance-' + (index + 1) + '" id="allowances">';
        html += '<input type="hidden" name="allowance_ids[]" id="allowance_id">';
        html += '<input type="text" name="allowance_names[]" class="form-control" id="allowance_name" placeholder="' + "{{ __('Allowance Name') }}" + '" autocomplete="off">';
        html += '</td>';

        html += '<td>';
        html += '<select class="form-control" name="allowance_amount_types[]" id="allowance_amount_type">';
        html += '<option value="1">' + "{{ __('Fixed') }}" + '</option>';
        html += '<option value="2">' + "{{ __('Percentage') }}" + '</option>';
        html += '</select>';

        html += '<div class="input-group allowance_percent_field d-hide">';
        html += '<input type="number" step="any" name="allowance_percents[]" class="form-control fw-bold" id="allowance_percent" value="0.00" autocomplete="off">';
        html += '<div class="input-group-prepend">';
        html += ' <span class="input-group-text" id="basic-addon1">';
        html += '<i class="fas fa-percentage input_i"></i>';
        html += '</span>';
        html += '</div>';
        html += '</div>';
        html += '</td>';

        html += '<td>';
        html += '<input type="number" step="any" name="allowance_amounts[]" class="form-control fw-bold" id="allowance_amount" value="0.00" placeholder="' + "{{ __('Amount') }}" + '" autocomplete="off">';
        html += '</td>';

        html += '<td class="text-right">';
        html += '<a href="#" id="remove_allowane" class="btn btn-sm btn-danger mt-1">X</a>';
        html += '</td>';
        html += '</tr>';
        $('#allowance_body').append(html);
        index++;
    });

    // Add More Deduction
    var index2 = {{ $index2 }};
    $(document).on('click', '#add_more_deduction', function(e) {
        e.preventDefault();
        var html = '';
        html += '<tr>';
        html += '<td>';
        html += '<input type="hidden" name="payroll_deduction_ids[]">';
        html += '<input type="hidden" class="deduction-' + (index2 + 1) + '" id="deductions">';
        html += '<input type="hidden" name="deduction_ids[]" id="deduction_id">';
        html += '<input type="text"  name="deduction_names[]" id="deduction_name" class="form-control" placeholder="' + "{{ __('Deduction Name') }}" + '" autocomplete="off">';
        html += '</td>';

        html += '<td>';
        html += '<select class="form-control" name="deduction_amount_types[]" id="deduction_amount_type">';
        html += '<option value="1">' + "{{ __('Fixed') }}" + '</option>';
        html += '<option value="2">' + "{{ __('Percentage') }}" + '</option>';
        html += '</select>';

        html += '<div class="input-group deduction_percent_field d-hide">';

        html += '<input type="number" step="any" name="deduction_percents[]" class="form-control fw-bold" id="deduction_percent" value="0.00" autocomplete="off">';

        html += '<div class="input-group-prepend">';
        html += '<span class="input-group-text" id="basic-addon1">';
        html += '<i class="fas fa-percentage input_i"></i>';
        html += '</span>';
        html += '</div>';
        html += '</div>';
        html += '</td>';

        html += '<td>';
        html += '<input type="number" step="any" name="deduction_amounts[]" class="form-control fw-bold" id="deduction_amount" value="0.00" placeholder="' + "{{ __('Amount') }}" + '">';
        html += '</td>';

        html += '<td class="text-right">';
        html += '<a href="#" id="remove_deduction" class="btn btn-sm btn-danger mt-1">X</a>';
        html += '</td>';
        html += '</tr>';
        $('#deduction_body').append(html);
        index2++;
    });

    // Remove Allowance
    $(document).on('click', '#remove_allowane', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateAmount();
    });

    // Remove Deduction
    $(document).on('click', '#remove_deduction', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();
        calculateAmount();
    });

    $(document).on('click', '#allowance_amount_type', function() {
        calculateAmount();
        if ($(this).val() == 2) {
            $(this).closest('tr').find('.allowance_percent_field').removeClass('d-hide');
            $(this).closest('tr').find('#allowance_amount').prop('readonly', true);
        } else {
            $(this).closest('tr').find('.allowance_percent_field').addClass('d-hide');
            $(this).closest('tr').find('#allowance_amount').prop('readonly', false);
        }
    });

    $(document).on('click', '#deduction_amount_type', function() {
        calculateAmount();
        if ($(this).val() == 2) {
            $(this).closest('tr').find('.deduction_percent_field').removeClass('d-hide');
            $(this).closest('tr').find('#deduction_amount').prop('readonly', true);
        } else {
            $(this).closest('tr').find('.deduction_percent_field').addClass('d-hide');
            $(this).closest('tr').find('#deduction_amount').prop('readonly', false);
        }
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.payroll_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_payroll_form').on('submit', function(e) {
        e.preventDefault();

        $('.payroll_loading_btn').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                $('.payroll_loading_btn').hide();
                $('.error').html('');
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                window.location = "{{ route('hrm.payrolls.index') }}";
            }, error: function(err) {

                $('.payroll_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if (err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>

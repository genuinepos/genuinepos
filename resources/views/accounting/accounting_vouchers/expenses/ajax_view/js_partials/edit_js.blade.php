<script>
    var expenseAccounts = @json($expenseAccounts);
    $('#expense_debit_account_id').select2();

    function calcTotalAmount() {

        var allTr = $('#expense_ledger_list').find('tr');

        var totalAmount = 0;
        allTr.each(function(index, value) {

            var amount = $(value).find('#expense_amount').val() ? $(value).find('#expense_amount').val() : 0;
            totalAmount += parseFloat(amount);
        });

        $('#span_expense_total_amount').html(bdFormat(totalAmount));
        $('#expense_total_amount').val(parseFloat(totalAmount).toFixed(2));
    }

    var count = 0;
    function nextStep(event) {

        var tr = $(event).closest('tr');
        var nxt = tr.next();

        if (event.value == 'add_more') {

            tr.find('#expense_next_step').val('');

            var expenseDebitAccountIds = document.querySelectorAll('.expense_debit_account_id ');
            var lastExpenseDebitAccountId = expenseDebitAccountIds[expenseDebitAccountIds.length - 1];

            var expenseAmounts = document.querySelectorAll('#expense_amount');
            var lastExpenseAmount = expenseAmounts[expenseAmounts.length - 1];

            if (tr.find('#expense_amount').val() == '') {

                tr.find('#expense_amount').focus();
                return;
            }else if (tr.find('.expense_debit_account_id').val() == '') {

                tr.find('.expense_debit_account_id').focus();
                return;
            }

            if (lastExpenseDebitAccountId.value == '') {

                // nxt.find('#variant_child').focus().select();
                lastExpenseDebitAccountId.focus();
                return;
            }else if (lastExpenseAmount.value == '') {

                lastExpenseAmount.focus();
                return;
            }

            var generate_unique_id = parseInt(Date.now() + Math.random());
            var html = '';
            html += '<tr>';
            html += '<td width="50">';
            html += '<select required name="debit_account_ids[]" class="form-control expense_debit_account_id" id="expense_debit_account_id' + count + '">';
            html += '<option value="">'+"{{ __('Select Expense Ledger') }}"+'</option>';

            expenseAccounts.forEach(function(expenseAccount) {

                html += '<option value="' + expenseAccount.id + '">' + expenseAccount.name + '</option>';
            });

            html += '</select>';
            html += '<input type="hidden" name="accounting_voucher_description_ids[]">';
            html += '<input type="hidden" class="unique_id-' + generate_unique_id + '" id="unique_id" value="' + generate_unique_id + '">';
            html += '</td>';

            html += '<td>';
            html += '<input required type="number" step="any" name="amounts[]" class="form-control fw-bold" id="expense_amount" value="0.00">';
            html += '</td>';

            html += '<td>';
            html += '<select onchange="nextStep(this); return false;" class="form-control" id="expense_next_step">';
            html += '<option value="">'+"{{ __('Next Step') }}"+'</option>';
            html += '<option value="add_more">'+"{{ __('Add More') }}"+'</option>';
            html += '<option value="next_field">'+"{{ __('Next Field') }}"+'</option>';
            html += '<option value="list_end">'+"{{ __('List End') }}"+'</option>';
            html += '<option value="remove">'+"{{ __('Remove') }}"+'</option>';
            html += '</select>';
            html += '</td>';
            html += '</tr>';

            $('#expense_ledger_list').append(html);
            $('#expense_debit_account_id' + count, '#expense_ledger_list').select2();
            count++;

            var tr = $('.unique_id-' + generate_unique_id).closest('tr');
            var previousTr = tr.prev();
            tr.find('.expense_debit_account_id').focus().select();
        }else if(event.value == 'remove') {

            previousTr = tr.prev();
            nxtTr = tr.next();

            tr.remove();

            if (nxtTr.length == 1) {

                nxtTr.find('#expense_amount').focus().select();
            } else if (previousTr.length == 1) {

                previousTr.find('#expense_amount').focus().select();
            }

            calcTotalAmount();
        }else if(event.value == 'next_field') {

            tr.find('#expense_next_step').val('');
            nxt.find('.expense_debit_account_id').focus();
        }else if(event.value == 'list_end') {

            tr.find('#expense_next_step').val('');
            $('#save_changes').focus();
        }
    }

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

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('select2:close', '.expense_debit_account_id', function(e) {

        var tr = $(this).closest('tr');

        setTimeout(function() {

            tr.find('#expense_amount').focus().select();
        }, 100);
    });

    $(document).on('keyup', '#expense_amount', function(e) {

        if (e.which == 13) {

            if ($(this).val() == '') {
                return;
            }

            $(this).closest('tr').find('#expense_next_step').focus();
        }
    });

    $(document).on('input', '#expense_amount', function(e) {

        calcTotalAmount();
    });
</script>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.expense_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.expense_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    //Add payment request by ajax
    $('#edit_expense_form').on('submit', function(e) {
        e.preventDefault();

        $('.expense_loading_btn').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.error').html('');
                $('.expense_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                toastr.success(data);
                $('#addOrEditExpenseModal').modal('hide');
                $('#addOrEditExpenseModal').empty();
                expenseTable.ajax.reload();
            }, error: function(err) {

                $('.expense_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_expense_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>

<script>
    new Litepicker({
        singleMode: true,
        element: document.getElementById('expense_date'),
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
        format: 'DD-MM-YYYY'
    });

    var expenseDebitAccountIds = document.querySelectorAll('.expense_debit_account_id');

    $.each(expenseDebitAccountIds, function(index, value) {

        if (index > 0) {

            var id = $(value).attr('id');
            $('#' + id, '#expense_ledger_list').select2();
        }
    });
</script>

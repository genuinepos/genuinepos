<script>
    function nullSave(value) {

        return value == null ? '' : value;
    }

    function selectVoucher(event) {

        if ($(event).is(':checked') == false) {

            var value = $(event).val();
            $('#selected_voucher_list #' + value).closest('tr').remove();
            calcTotalAmount();
            $('#receipt_received_amount').focus().select();
            return;
        }

        var tr = $(event).closest('tr');
        var db_voucher_no = tr.find('#db_voucher_no').val();
        var db_voucher_id = tr.find('#db_voucher_id').val();
        var db_voucher_type = tr.find('#db_voucher_type').val();
        var db_voucher_type_str = tr.find('#db_voucher_type_str').val();
        var db_ref_id = tr.find('#db_ref_id').val();
        var db_amount = tr.find('#db_amount').val();

        var newUniqueId = db_voucher_id + db_ref_id;

        var html = '';
        html += '<tr id="voucher_tr">';
        html += '<td class="text-start">';
        html += '<input readonly type="text" class="form-control fw-bold" id="voucher_no" value="' + db_voucher_no + '">';
        html += '<input type="hidden" class="' + db_voucher_id + '" id="voucher_id" value="' + db_voucher_id + '">';
        html += '<input type="hidden" name="voucher_types[]" id="voucher_type" value="' + db_voucher_type + '">';
        html += '<input type="hidden" id="voucher_type_str" value="' + db_voucher_type_str + '">';
        html += '<input type="hidden" name="ref_ids[]" id="ref_id" value="' + db_ref_id + '">';
        html += '<input type="hidden" class="unique_id" id="' + newUniqueId + '" value="' + newUniqueId + '">';
        html += '</td>';

        html += '<td class="text-start"><span id="span_voucher_type">' + db_voucher_type_str + '</span></td>';

        html += '<td class="text-start">';
        html += '<input readonly type="number" name="amounts[]" step="any" class="form-control fw-bold" id="amount" value="' + db_amount + '">';
        html += '</td>';

        html += '<td class="text-start fw-bold text-danger">' + db_amount + '</td>';

        html += '<td class="text-start">';
        html += '<a href="#" id="remove_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>';
        html += '</td>';
        html += '</tr>';

        $('#selected_voucher_list').append(html);
        var voucherTr = document.querySelectorAll('#voucher_tr');
        var lastVoucherIndex = voucherTr[voucherTr.length - 1];

        calcTotalAmount();
        $('#receipt_received_amount').focus().select();
    }

    $(document).on('click', '#remove_btn', function(e) {

        e.preventDefault();
        var tr = $(this).closest('tr');
        var uniqueId = tr.find('.unique_id').val();
        $('#vouchers_list #' + uniqueId).prop('checked', false);
        tr.remove();
        calcTotalAmount();
    });

    function calcTotalAmount() {

        var allTr = $('#selected_voucher_list').find('tr');

        var totalAmount = 0;
        allTr.each(function(index, value) {

            var amount = $(value).find('#amount').val() ? $(value).find('#amount').val() : 0;
            totalAmount += parseFloat(amount);
        });

        $('#voucher_total_amount').html(parseFloat(totalAmount).toFixed(2));
        $('#receipt_received_amount').val(parseFloat(totalAmount).toFixed(2));

        if (parseFloat(totalAmount) && parseFloat(totalAmount) > 0) {

            document.getElementById('inword').innerHTML = inWords(parseInt(totalAmount)) + 'ONLY';
        } else {

            document.getElementById('inword').innerHTML = '';
        }

        calculateCurrentBalance();
    }

    function calculateCurrentBalance() {

        var receivedAmount = $('#receipt_received_amount').val() ? $('#receipt_received_amount').val() : 0;
        var closingBalanceFlatAmount = $('#closing_balance_flat_amount').val() ? $('#closing_balance_flat_amount').val() : 0;
        var defaultBalanceType = $('#default_balance_type').val() ? $('#default_balance_type').val() : 0;

        var currentBalance = 0;
        if (defaultBalanceType == 'dr') {

            currentBalance = parseFloat(closingBalanceFlatAmount) - parseFloat(receivedAmount);
        }else {

            currentBalance = parseFloat(closingBalanceFlatAmount) + parseFloat(receivedAmount);
        }

        $('#closing_balance_string').val(bdFormat(currentBalance));
    }
</script>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.receipt_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();
            if ($(this).attr('id') == 'receipt_remarks' && $('#receipt_credit_account_id').val() == undefined) {

                $('#receipt_received_amount').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.receipt_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    //Add Receipt request by ajax
    $('#edit_receipt_form').on('submit', function(e) {
        e.preventDefault();

        $('.receipt_loading_btn').show();
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
                $('.receipt_loading_btn').hide();

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#addOrEditReceiptModal').modal('hide');
                $('#addOrEditReceiptModal').empty();

                var commonReloaderClass = $('.common-reloader').html();
                var forReceipts = $('#for_receipts').html();
                if (commonReloaderClass != undefined) {

                    $('.common-reloader').DataTable().ajax.reload();

                    if (forReceipts != undefined) {

                        reloadAllAccountSummaryArea();
                    }
                }else {

                    receiptTable.ajax.reload(null, false);
                }
            },
            error: function(err) {

                $('.receipt_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_receipt_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>

<script>
    var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
    var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

    function inWords(num) {
        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return;
        var str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
        return str;
    }


    var receivedTotalAmount = "{{ $receipt->total_amount }}";
    document.getElementById('inword').innerHTML = inWords(parseInt(receivedTotalAmount)) + 'ONLY';
    // $(document).on('click', '.invoice_and_order_table_area table tbody tr', function () {

    //     $('.invoice_and_order_table_area table tbody tr').removeClass('active_tr');
    //     $(this).addClass('active_tr');
    // });
</script>

@if (!$account)
    <script>
        $('#receipt_credit_account_id').select2();

        $(document).on('change', '#receipt_credit_account_id', function() {

            var accountId = $(this).val();
            var type = "{{ \App\Enums\AccountingVoucherType::Receipt->value }}";

            var url = "{{ route('daybooks.vouchers.for.receipt.or.payment', ['accountId' => ':accountId', 'type' => ':type']) }}"
            var route = url.replace(':accountId', accountId);
             route = route.replace(':type', type);

            $.ajax({
                url: route,
                type: 'get',
                success: function(vouchers) {

                    console.log(vouchers);
                    $('#vouchers_list').empty();

                    var html = '';
                    $.each(vouchers, function(key, voucher) {

                        html += '<tr id="selectable_tr">';
                        html += '<td class="text-start">';
                        html += '<input type="checkbox" onchange="selectVoucher(this)" class="select_voucher" id="'+ (voucher.voucherNo+''+voucher.refId) +'"  value="'+ (voucher.voucherNo+''+voucher.refId) +'">';
                        html += '<input type="hidden" id="db_voucher_no" value="'+ voucher.voucherNo +'">';
                        html += '<input type="hidden" id="db_voucher_type" value="'+ voucher.voucherType +'">';
                        html += '<input type="hidden" id="db_voucher_type_str" value="'+ voucher.voucherTypeStr +'">';
                        html += '<input type="hidden" id="db_voucher_id" value="'+ voucher.voucherId +'">';
                        html += '<input type="hidden" id="db_ref_id" value="'+ voucher.refId +'">';
                        html += '<input type="hidden" id="db_amount" value="'+ voucher.due +'">';
                        html += '</td>';
                        html += '<td class="text-start"><a href="#">'+ voucher.voucherNo +'</a></td>';
                        html += '<td class="text-start">'+ voucher.voucherTypeStr +'</td>';
                        html += '<td class="text-start ' + (voucher.paymentStatus == 'Partial' ? 'text-primary' : 'text-danger') +'">'+ voucher.paymentStatus +'</td>';
                        html += '<td class="text-start text-danger fw-bold">'+ bdFormat(voucher.due) +'</td>';
                        html += '</tr>';
                    });

                    $('#vouchers_list').append(html);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        $('select').on('select2:close', function(e) {

            var nextId = $(this).data('next');

            $('#' + nextId).focus();

            setTimeout(function() {

                $('#' + nextId).focus();
            }, 100);
        });

        function changeAccount (event) {

            $('#closing_balance_string').val(parseFloat(0).toFixed(2));
            $('#closing_balance_flat_amount').val(parseFloat(0).toFixed(2));
            $('#default_balance_type').val('dr');

            var accountId = event.value;
            if (accountId == '') {

                return;
            }

            var branchId = "{{ $receipt->branch_id == null ? 'NULL' : $receipt->branch_id }}";
            var subSubGroupNumber = $(event).find('option:selected').data('sub_sub_group_number');
            var __branchId = subSubGroupNumber != 6 ? branchId : null;
            var filterObj = {
                branch_id : __branchId,
                from_date : null,
                to_date : null,
            };

            getAccountClosingBalance(accountId, filterObj);
        };

        function getAccountClosingBalance(accountId, filterObj) {

            var url = "{{ route('accounts.balance', ':accountId') }}";
            var route = url.replace(':accountId', accountId);

            $.ajax({
                url: route,
                type: 'get',
                data: filterObj,
                success: function(data) {

                    $('#closing_balance_string').val(data.closing_balance_in_flat_amount_string);
                    $('#closing_balance_flat_amount').val(parseFloat(data.closing_balance_in_flat_amount));
                    $('#default_balance_type').val(data.default_balance_type);
                    calculateCurrentBalance();
                }
            });
        }
    </script>
@endif

<script>
    new Litepicker({
        singleMode: true,
        element: document.getElementById('receipt_date'),
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
</script>

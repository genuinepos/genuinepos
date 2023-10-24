<script>
    var vouchers = @json($vouchers);
    var unique_id = '';

    function nullSave(value) {

        return value == null ? '' : value;
    }

    var tbody = document.getElementById('vouchers_list');
    var trSelectObjClassName = 'selected_tr';
    $(document).on('focus', '#voucher_no',function(e){

        var tr = $(this).closest('tr');
        var voucher_id = tr.find('#voucher_id').val();
        var ref_id = tr.find('#ref_id').val();
        unique_id = voucher_id + ref_id;

        tbody = document.getElementById('vouchers_list');
        trSelectObjClassName = 'selected_tr';
    });

    var selectedArray = [];
    var uniqueIds = [];
    function selectVoucher(event){

        var voucherTr = document.querySelectorAll('#voucher_tr');
        var lastVoucherIndex = voucherTr[voucherTr.length - 1];
        var lastVoucherTr = $(lastVoucherIndex);
        var tr = $(event).closest('tr');
        var db_voucher_no = tr.find('#db_voucher_no').val();
        var db_voucher_id = tr.find('#db_voucher_id').val();
        var db_voucher_type = tr.find('#db_voucher_type').val();
        var db_voucher_type_str = tr.find('#db_voucher_type_str').val();
        var db_ref_id = tr.find('#db_ref_id').val();
        var db_amount = tr.find('#db_amount').val();

        var newUniqueId = db_voucher_id + db_ref_id;
        var index = uniqueIds.indexOf(newUniqueId);

        if (index > -1) {

            var oldTr = $('#'+newUniqueId).closest('tr');
            oldTr.find('#voucher_no').focus().select();
            // toastr.error("{{ __('Dublicate voucher is not allowed.') }}");
            voucherList(vouchers)
            return;
        }

        if (unique_id) {

            // var existsTr = $('.voucher_id-'+voucherId);
            var idName = $('#'+unique_id);
            var existsTr = $(idName).closest('tr');
            existsTr.find('#voucher_id').val(db_voucher_id);
            existsTr.find('#voucher_no').val(db_voucher_no);
            existsTr.find('#voucher_type').val(db_voucher_type);
            existsTr.find('#voucher_type_str').val(db_voucher_type_str);
            existsTr.find('#span_voucher_type').html(db_voucher_type_str);
            existsTr.find('#ref_id').val(db_ref_id);
            existsTr.find('#amount').val(db_amount);
            existsTr.find('.unique_id').attr('id', newUniqueId);
            existsTr.find('#next_step').val('').focus();
            removeValueFromArray(unique_id);
            uniqueIds.push(newUniqueId);
            unique_id = '';
            setSelectIcon();
            calcTotalAmount();
        }else if (lastVoucherTr.find('#voucher_id').val() == '') {

            lastVoucherTr.find('#voucher_id').val(db_voucher_id);
            lastVoucherTr.find('#voucher_no').val(db_voucher_no);
            lastVoucherTr.find('#voucher_type').val(db_voucher_type);
            lastVoucherTr.find('#voucher_type_str').val(db_voucher_type_str);
            lastVoucherTr.find('#span_voucher_type').html(db_voucher_type_str);
            lastVoucherTr.find('#ref_id').val(db_ref_id);
            lastVoucherTr.find('#amount').val(db_amount);
            lastVoucherTr.find('.unique_id').attr('id', newUniqueId);
            uniqueIds.push(newUniqueId);
            lastVoucherTr.find('#next_step').val('').focus();
            uniqueId = '';
            setSelectIcon();
            calcTotalAmount();
        }else {

            var html = '';
            html += '<tr id="voucher_tr" class="voucher_id-'+db_voucher_id+'">';
            html += '<td class="text-start">';
            html += '<input required type="text" class="form-control fw-bold" id="voucher_no" value="'+ db_voucher_no +'">';
            html += '<input type="hidden" class="'+db_voucher_id+'" id="voucher_id" value="'+ db_voucher_id +'">';
            html += '<input type="hidden" name="voucher_types[]" id="voucher_type" value="'+ db_voucher_type +'">';
            html += '<input type="hidden" id="voucher_type_str" value="'+ db_voucher_type_str +'">';
            html += '<input type="hidden" name="ref_ids[]" id="ref_id" value="'+ db_ref_id +'">';
            html += '<input type="hidden" class="unique_id" id="'+newUniqueId+'" value="'+ newUniqueId+'">';
            html += '</td>';

            html += '<td class="text-start"><span id="span_voucher_type">'+ db_voucher_type_str +'</span></td>';

            html += '<td class="text-start">';
            html += '<input readonly type="number" name="amounts[]" step="any" class="form-control fw-bold" id="amount" value="'+ db_amount +'">';
            html += '</td>';

            html += '<td class="text-start">';
            html += '<select id="next_step" class="form-control">';
            html += '<option value="">{{ __("Next Step") }}</option>';
            html += '<option value="add_more">{{ __("Add More") }}</option>';
            html += '<option value="next_field">{{ __("Next Field") }}</option>';
            html += '<option value="list_end">{{ __("List End") }}</option>';
            html += '<option value="remove">{{ __("Remove") }}</option>';
            html += '</select>';
            html += '</td>';
            html += '</tr>';

            $('#selected_voucher_list').append(html);
            var voucherTr = document.querySelectorAll('#voucher_tr');
            var lastVoucherIndex = voucherTr[voucherTr.length - 1];

            $(voucherTr).find('#next_step').focus();
            uniqueIds.push(newUniqueId);
            uniqueId = '';

            setSelectIcon();
            calcTotalAmount();
        }

        voucherList(vouchers);
    }

    function removeValueFromArray(value) {

        var index = uniqueIds.indexOf(value);
        if (index > -1) {

            uniqueIds.splice(index, 1);
        }
    }

    function setSelectIcon() {

        var allTr = $('#vouchers_list').find('tr');

        allTr.each(function (index, value) {

            $(value).find('#is_selected').html('');
            var voucherId =  $(value).find('#db_voucher_id').val();
            var refId = $(value).find('#db_ref_id').val();
            var uniqueId = voucherId+''+refId;

            var newUniqueId = db_voucher_id + db_ref_id;
            var index = uniqueIds.indexOf(uniqueId);

            if (index > -1) {

                $(value).find('#is_selected').html('✔');
            }
        });
    }

    $(document).on('change', '#next_step',function(e){

        var tr = $(this).closest('tr');

        var previousTr = tr.prev();
        var nxtTr = tr.next();
        if ($(this).val() == 'remove') {

            var voucherId = tr.find('#voucher_id').val();
            var refId = tr.find('#ref_id').val();
            var uniqueId = voucherId+''+refId;

            removeValueFromArray(uniqueId);
            tr.remove();
            setSelectIcon();
            calcTotalAmount();

            if (nxtTr.length == 1) {

                nxtTr.find('#voucher_no').focus().select();
            } else if (previousTr.length == 1) {

                previousTr.find('#voucher_no').focus().select();
            }
        }else if ($(this).val() == 'add_more') {

            tr.find('#next_step').val('');

            var voucherNo = document.querySelectorAll('#voucher_no');
            var lastvoucherNo = voucherNo[voucherNo.length - 1];

            if (tr.find('#voucher_no').val() == '') {

                tr.find('#voucher_no').focus();
                return;
            }


            if (lastvoucherNo.value == '') {

                lastvoucherNo.focus().select();
                return;
            }

            var html = '';
            html += '<tr id="voucher_tr">';
            html += '<td class="text-start">';
            html += '<input required type="text" class="form-control fw-bold" id="voucher_no" value="">';
            html += '<input type="hidden" id="voucher_id" value="">';
            html += '<input type="hidden" name="voucher_types[]" id="voucher_type" value="">';
            html += '<input type="hidden" id="voucher_type_str" value="">';
            html += '<input type="hidden" name="ref_ids[]" id="ref_id" value="">';
            html += '<input type="hidden" class="unique_id" id="" value="">';
            html += '</td>';

            html += '<td class="text-start"><span id="span_voucher_type"></span></td>';

            html += '<td class="text-start">';
            html += '<input readonly type="number" name="amounts[]" step="any" class="form-control fw-bold" id="amount" value="">';
            html += '</td>';

            html += '<td class="text-start">';
            html += '<select id="next_step" class="form-control">';
            html += '<option value="">{{ __("Next Step") }}</option>';
            html += '<option value="add_more">{{ __("Add More") }}</option>';
            html += '<option value="next_field">{{ __("Next Field") }}</option>';
            html += '<option value="list_end">{{ __("List End") }}</option>';
            html += '<option value="remove">{{ __("Remove") }}</option>';
            html += '</select>';
            html += '</td>';
            html += '</tr>';

            $('#selected_voucher_list').append(html);

            var voucherNo = document.querySelectorAll('#voucher_no');
            var lastvoucherNo = voucherNo[voucherNo.length - 1];
            lastvoucherNo.focus();

        }else if ($(this).val() == 'next_field') {

            tr.find('#next_step').val('');
            nxt.find('#voucher_no').focus().select();
        }else if ($(this).val() == 'list_end') {

            tr.find('#next_step').val('');
            $('#receipt_received_amount').focus().select();
        }
    });

    function calcTotalAmount() {

        var allTr = $('#selected_voucher_list').find('tr');

        var totalAmount = 0;
        allTr.each(function (index, value) {

            var amount = $(value).find('#amount').val() ? $(value).find('#amount').val() : 0;
            totalAmount += parseFloat(amount);
        });

        $('#total_amount').html(totalAmount);
        $('#receipt_received_amount').val(totalAmount);
    }

    $(document).on('input focus', '#voucher_no',function(e) {

        var value = $(this).val();
        var __vouchers = null;
        if ($(this).val() == '') {

            var tr = $(this).closest('tr');
            tr.find('#voucher_no').val('');
            tr.find('#voucher_type').val('');
            tr.find('#voucher_type_str').val('');
            tr.find('#span_voucher_type').html('');
            tr.find('#amount').val('');
            calcTotalAmount();
            __vouchers = vouchers;
        }

        var res = vouchers.filter(function (currentValue, currentIndex) {

            var voucherNo = currentValue.voucherNo;
            var __voucherNo = voucherNo.toLowerCase();
            var keyword = value.toLowerCase();

            if (__voucherNo.indexOf(keyword) >= 0) {

                return currentValue;
            }
        });

        if ($(this).val() != '') {

            __vouchers = res;
        }

        voucherList(__vouchers);
    });

    function voucherList(__vouchers) {

        var tr = '';
        __vouchers.forEach(function (row, index) {

            tr += '<tr id="selectable_tr">';
            tr += '<td id="default_select" class="text-start" onclick="selectVoucher(this); return false;">';
            tr += '<span id="is_selected"></span>';
            tr += '<input type="hidden" id="db_voucher_no" value="' + row.voucherNo + '">';
            tr += '<input type="hidden" id="db_voucher_type" value="' + row.voucherType + '">';
            tr += '<input type="hidden" id="db_voucher_type_str" value="' + row.voucherTypeStr + '">';
            tr += '<input type="hidden" id="db_voucher_id" value="' + row.voucherId + '">';
            tr += '<input type="hidden" id="db_ref_id" value="' + row.refId + '">';
            tr += '<input type="hidden" id="db_amount" value="' + row.due + '">';
            tr += '</td>';
            tr += '<td class="text-start"><a href="#">' + row.voucherNo + '</a></td>';
            tr += '<td class="text-start" onclick="selectVoucher(this); return false;">' + row.voucherTypeStr + '</td>';
            tr += '<td class="text-start '+ (row.voucherTypeStr == 'Partial' ? "text-primary" : "text-danger") +'" onclick="selectVoucher(this); return false;">' + row.paymentStatus + '</td>';
            tr += '<td class="text-start text-danger fw-bold" onclick="selectVoucher(this); return false;">'+bdFormat(row.due)+'</td>';
            tr += '</tr>';
        });

        $('#vouchers_list').empty();
        $('#vouchers_list').html(tr);

        setSelectIcon();
    }

    $(document).on('keyup', '#voucher_no',function(e) {

        if (e.keyCode == 13){

            $(".selected_tr").click();

            var tr = $(this).closest('tr');
            tr.find('#next_step').focus();
        }
    });

    $(document).on('blur', '#voucher_no',function(e) {

        voucherList(vouchers);
    });

    $(document).on('click', '.selected_tr',function(e) {

        var tr = $(this).closest('tr');
        selectVoucher(tr);
    });

    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.submit_button').prop('type', 'button');
    });

</script>
<script src="{{ asset('assets/plugins/custom/select_li/selectTr.custom.js') }}"></script>

<form id="add_contra_form" action="{{ route('accounting.contras.store') }}">
    <div class="form-group row">
        <div class="col-md-6">
            <label><strong>@lang('menu.date') </strong> <span class="text-danger">*</span></label>
            <input type="text" name="date" class="form-control add_input" data-name="Date" id="date"
                placeholder="DD-MM-YYYY" autocomplete="off" value="{{ date($generalSettings['business__date_format']) }}"/>
            <span class="error error_date"></span>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('menu.voucher_no') </strong></label>
            <input type="text" name="voucher_no" class="form-control add_input" data-name="Date" id="voucher_no" placeholder="Voucher Number" autocomplete="off"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><strong>@lang('menu.sender_ac') </strong><span class="text-danger">*</span></label>
            <select name="sender_account_id" class="form-control add_input" data-name="Sender Account"
                id="sender_account_id">
                <option value="">@lang('menu.select_receiver_ac')</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}">
                        @php
                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                            $bank = $account->bank ? ', BK : '.$account->bank : '';
                            $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                            $balance = ', BL : '.$account->balance;
                        @endphp
                        {{ $account->name.$accountType.$bank.$ac_no.$balance}}
                    </option>
                @endforeach
            </select>
            <span class="error error_sender_account_id"></span>
        </div>

        <div class="col-md-6">
            <label><strong>@lang('menu.receiver_ac') </strong><span class="text-danger">*</span></label>
            <select name="receiver_account_id" class="form-control add_input" data-name="Receiver Account"
                id="receiver_account_id">
                <option value="">@lang('menu.select_receiver_ac')</option>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}">
                        @php
                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                            $bank = $account->bank ? ', BK : '.$account->bank : '';
                            $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                            $balance = ', BL : '.$account->balance;
                        @endphp
                        {{ $account->name.$accountType.$bank.$ac_no.$balance }}
                    </option>
                @endforeach
            </select>
            <span class="error error_receiver_account_id"></span>
        </div>
    </div>

    <div class="form-group mt-1">
        <label><strong>@lang('menu.amount') </strong> <span class="text-danger">*</span></label>
        <input type="number" step="any" name="amount" class="form-control add_input" data-name="Amount" id="date"
            placeholder="@lang('menu.amount')" autocomplete="off"/>
        <span class="error error_amount"></span>
    </div>

    <div class="form-group mt-1">
        <label><strong>@lang('menu.remarks') </strong></label>
        <input type="text" name="remarks" class="form-control" id="remarks" placeholder="@lang('menu.remarks')"/>
    </div>

    <div class="form-group d-flex justify-content-end pb-2 pt-4">
        <div class="btn-loading">
            <button type="button" class="btn loading_button d-hide">
                <i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
            </button>
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
        </div>
    </div>
</form>

<script>

    // Add account by ajax
    $('#add_contra_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        $('.submit_button').prop('type', 'button');
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.submit_button').prop('type', 'submit');
                toastr.success(data);
                $('#add_contra_form')[0].reset();
                $('.loading_button').hide();
                contra_table.ajax.reload();
                $('#addModal').modal('hide');
                $('#name').focus();
            },
            error: function(err) {
                $('.submit_button').prop('type', 'submit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500) {
                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('date'),
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
        format: 'DD-MM-YYYY',
    });
</script>

<style>
    .payment_top_card {
        background: #d7dfe8;
    }

    .payment_top_card span {
        font-size: 12px;
        font-weight: 400;
    }

    .payment_top_card li {
        font-size: 12px;
    }

    .payment_top_card ul {
        padding: 6px;
        border: 1px solid #dcd1d1;
    }

    .payment_list_table {
        position: relative;
    }

    .payment_details_contant {
        background: azure !important;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.loan_advance_receive')</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="payment_modal_body">
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('menu.company') </strong><span class="card_text">{{ $company->name }}</span>
                                </li>
                                <li><strong>@lang('menu.phone') </strong><span class="card_text"></span></li>
                                <li><strong>@lang('menu.address') </strong><span class="card_text"></span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('menu.total_loan_advance') </strong>
                                    <span class="card_text invoice_no">
                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        <b>{{ App\Utils\Converter::format_in_bdt($company->pay_loan_amount) }}</b>
                                    </span>
                                </li>

                                <li><strong>@lang('menu.total_received') </strong>
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                    <span class="card_text text-success">
                                        <b>{{ App\Utils\Converter::format_in_bdt($company->total_receive) }}</b>
                                    </span>
                                </li>

                                <li><strong>@lang('menu.total_due') </strong>
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                    <span class="card_text text-danger">
                                        <b>{{ App\Utils\Converter::format_in_bdt($company->pay_loan_due) }}</b>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Form-->
            <form id="loan_payment_form" action="{{ route('accounting.loan.advance.receive.store', $company->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.amount') </strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="far fa-money-bill-alt text-dark input_i"></i></span>
                            </div>
                            <input type="hidden" id="p_available_amount" value="{{ $company->pay_loan_due }}">
                            <input type="number" name="paying_amount" class="form-control p_input" step="any" data-name="Amount" id="p_paying_amount" value="" autocomplete="off" autofocus />
                        </div>
                        <span class="error error_p_paying_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="p_date"><strong>@lang('menu.date') </strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark input_i"></i></span>
                            </div>
                            <input type="text" name="date" class="form-control p_input" autocomplete="off" id="p_date" data-name="Date" value="{{ date($generalSettings['business_or_shop__date_format']) }}">
                        </div>
                        <span class="error error_p_date"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>@lang('menu.payment_method') </strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark input_i"></i></span>
                            </div>
                            <select name="payment_method_id" class="form-control" id="p_payment_method_id">
                                @foreach ($methods as $method)
                                    <option value="{{ $method->id }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_p_payment_method_id"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.debit_account') </strong> </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check-alt text-dark input_i"></i></span>
                            </div>
                            <select required name="account_id" class="form-control add_input" id="p_account_id">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        @php
                                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                            $balance = ' BL : ' . $account->balance;
                                        @endphp
                                        {{ $account->name . $accountType . $balance }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_p_account_id"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label><strong> @lang('menu.payment_note') </strong></label>
                    <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3" placeholder="Note"></textarea>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button_p loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            <button name="action" value="save" type="submit" class="btn btn-sm btn-success submit_button" id="add_payment">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Add loan payment request by ajax
    $('#loan_payment_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button_p').show();
        var available_amount = $('#p_available_amount').val();
        var paying_amount = $('#p_paying_amount').val();
        if (parseFloat(paying_amount) > parseFloat(available_amount)) {
            $('.error_p_paying_amount').html('Paying amount must not be greater then due amount.');
            $('.loading_button_p').hide();
            return;
        }

        if (parseFloat(paying_amount) <= 0) {
            $('.error_p_amount').html('Amount must be greater then 0.');
            $('.loading_button_p').hide();
            return;
        }

        var url = $(this).attr('action');
        $('#submit_button').prop('type', 'button');
        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $('#submit_button').prop('type', 'submit');
                $('.loading_button_p').hide();
                $('#loanPymentModal').modal('hide');
                toastr.success(data);
                companies_table.ajax.reload();
                loans_table.ajax.reload();
            },
            error: function(err) {
                $('#submit_button').prop('type', 'submit');
                $('.loading_button_p').hide();
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {
                    toastr.error('Server error. Please contact the support team.');
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_p_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>

<script>
    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '';
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('p_date'),
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

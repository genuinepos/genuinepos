<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
    h6.checkbox_input_wrap {border: 1px solid #495677;padding: 0px 7px;}
</style>
<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Purchase Return Payment') }} <span class="type_name"></span></h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('menu.supplier') : </strong><span>{{ $payment->purchase->supplier->name }}</span></li>
                                <li><strong>@lang('menu.business') : </strong>
                                    <span>{{ $payment->purchase->supplier->business_name }}</span>
                                </li>
                                <li><strong>@lang('menu.phone') : </strong>
                                    <span>{{ $payment->purchase->supplier->phone }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong> @lang('menu.reference_id') : </strong><span class="invoice_no">{{ $payment->purchase->invoice_id }}</span>
                                </li>
                                <li><strong>@lang('menu.b_location') : </strong>
                                    <span class="warehouse">
                                        {{ $payment->purchase->branch ? $payment->purchase->branch->name . '/' . $payment->purchase->branch->branch_code : $generalSettings['business__shop_name'].' (HO)' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>@lang('menu.total_due') : {{ $generalSettings['business__currency'] }} </strong>
                                    <span class="total_due">{{ $payment->purchase->purchase_return_due }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form id="payment_form" action="{{ route('purchases.return.payment.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4">
                        <label><strong>@lang('menu.amount') : </strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="far fa-money-bill-alt text-dark input_i"></i>
                                </span>
                            </div>
                            <input type="hidden" id="p_available_amount" value="{{ $payment->purchase->purchase_return_due + $payment->paid_amount }}">
                            <input type="number" name="paying_amount" class="form-control p_input" step="any" data-name="Amount" id="p_paying_amount" value="{{ $payment->paid_amount }}"/>
                        </div>
                        <span class="error error_p_paying_amount"></span>
                    </div>

                    <div class="col-md-4">
                        <label for="p_date"><strong>@lang('menu.date') : </strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-calendar-week text-dark input_i"></i>
                                </span>
                            </div>
                            <input type="text" name="date" class="form-control p_input" autocomplete="off" id="p_date" data-name="Date" value="{{ date($generalSettings['business__date_format'], strtotime($payment->date)) }}">
                        </div>
                        <span class="error error_p_date"></span>
                    </div>

                    <div class="col-md-4">
                        <label><strong>@lang('menu.payment_method') : </strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-money-check text-dark input_i"></i>
                                </span>
                            </div>
                            <select name="payment_method_id" class="form-control" id="p_payment_method_id">
                                @foreach ($methods as $method)
                                    <option
                                        {{ $method->id == $payment->payment_method_id ? 'SELECTED' : '' }}
                                        data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                        value="{{ $method->id }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_p_payment_method_id"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-7">
                        <label><strong>@lang('menu.debit_account') : </strong> </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-money-check-alt text-dark input_i"></i>
                                </span>
                            </div>
                            <select name="account_id" class="form-control" id="p_account_id">
                                @foreach ($accounts as $account)
                                    <option {{ $payment->account_id == $account->id ? 'SELECTED' : '' }} value="{{ $account->id }}">
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
                            <span class="error error_p_account_id"></span>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <label><strong>@lang('menu.attach_document') : </strong> <small class="text-danger">@lang('menu.note_max_size_2mb'). </small> </label>
                        <input type="file" name="attachment" class="form-control" id="attachment" data-name="Date" >
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label><strong> @lang('menu.payment_note') : </strong></label>
                    <textarea name="note" class="form-control" id="note" cols="30" rows="3" placeholder="Note">{{ $payment->note }}</textarea>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12">
                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')...</b></button>
                        <button type="submit" class="c-btn button-success me-0 float-end">@lang('menu.save_changes')</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('menu.close')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Add purchase payment request by ajax
    $('#payment_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();

        var available = $('#p_available_amount').val();
        var paying_amount = $('#p_paying_amount').val();

        if (parseFloat(paying_amount) > parseFloat(available)) {

            $('.error_p_paying_amount').html('Paying amount must not be greater then due amount.');
            $('.loading_button').hide();
            return;
        }

        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg, 'ERROR');
                    $('.loading_button').hide();
                } else {

                    $('.loading_button').hide();
                    $('#paymentModal').modal('hide');
                    $('#paymentViewModal').modal('hide');
                    toastr.success(data);
                    $('.data_tbl').DataTable().ajax.reload();
                    getSupplier();
                }
            },error: function(err) {

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
                    $('.error_p_' + key + '').html(error[0]);
                });
            }
        });
    });

    var dateFormat = "{{ $generalSettings['business__date_format'] }}";
    var _expectedDateFormat = '' ;
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

    $('#p_payment_method_id').on('change', function () {

        var account_id = $(this).find('option:selected').data('account_id');
        setMethodAccount(account_id);
    });

    function setMethodAccount(account_id) {

        if (account_id) {

            $('#p_account_id').val(account_id);
        }else if(account_id === ''){

            $('#p_account_id option:first-child').prop("selected", true);
        }
    }
</script>
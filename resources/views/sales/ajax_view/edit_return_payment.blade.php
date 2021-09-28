<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Customer : </strong><span>{{ $payment->sale->customer ? $payment->sale->customer->name : 'Walk-In-Customer' }}</span> </li>
                    <li><strong>Business : </strong><span>{{ $payment->sale->customer ? $payment->sale->customerbusiness_name : '' }}</span> </li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong> Invoice ID : </strong><span>{{ $payment->sale->invoice_id }}</span> </li>
                    <li><strong>Branch/Business : </strong>
                        <span>
                            @if ($payment->sale->branch)
                                {{ $payment->sale->branch->name.'/'.$payment->sale->branch->branch_code }}
                            @else
                                {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head Office</b>)
                            @endif
                        </span>  
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li class="sale_due">
                        <strong>Total Return Due : {{ json_decode($generalSettings->business, true)['currency'] }} </strong>
                        <span>{{ $payment->sale->sale_return_due }}</span> </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<form id="sale_payment_form" action="{{ route('sales.return.payment.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-4">
            <label><strong>Amount :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-money-bill-alt text-dark"></i></span>
                </div>
                <input type="hidden" id="available_amount" value="{{ $payment->sale->sale_return_due+$payment->paid_amount }}">
                <input type="number" name="amount" class="form-control form-control-sm p_input" step="any" data-name="Amount" id="p_amount" value="{{ $payment->paid_amount }}"/>
            </div>
            <span class="error error_p_amount"></span>
        </div>

        <div class="col-md-4">
            <label for="p_date"><strong>Date :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week text-dark"></i></span>
                </div>
                <input type="text" name="date" class="form-control form-control-sm datepicker p_input" autocomplete="off" id="p_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($payment->date)) }}">
            </div>
            <span class="error error_p_date"></span>
        </div>

        <div class="col-md-4">
            <label><strong>Payment Method :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark"></i></span>
                </div>
                <select name="payment_method" class="form-control form-control-sm"  id="payment_method">
                    <option {{ $payment->pay_mode == 'Cash' ? 'SELECTED' : '' }} value="Cash">Cash</option>  
                    <option {{ $payment->pay_mode == 'Advanced' ? 'SELECTED' : '' }} value="Advanced">Advanced</option> 
                    <option {{ $payment->pay_mode == 'Card' ? 'SELECTED' : '' }} value="Card">Card</option> 
                    <option {{ $payment->pay_mode == 'Cheque' ? 'SELECTED' : '' }} value="Cheque">Cheque</option> 
                    <option {{ $payment->pay_mode == 'Bank-Transfer' ? 'SELECTED' : '' }} value="Bank-Transfer">Bank-Transfer</option> 
                    <option {{ $payment->pay_mode == 'Other' ? 'SELECTED' : '' }} value="Other">Other</option> 
                    <option {{ $payment->pay_mode == 'Custom' ? 'SELECTED' : '' }} value="Custom">Custom Field</option> 
                </select>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-7">
            <label><strong>Payment Account :</strong> </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check-alt text-dark"></i></span>
                </div>
                <select name="account_id" class="form-control form-control-sm"  id="p_account_id">
                <option value="">None</option>
                @foreach ($accounts as $account)
                    <option {{ $payment->account_id == $account->id ? 'SELECTED' : '' }} value="{{ $account->id }}">{{ $account->name }} (A/C:
                        {{ $account->account_number }}) (Balance: {{ $account->balance }})</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-5">
            <label><strong>Attach document :</strong> <small class="text-danger">Note: Max Size 2MB. </small> </label>
            <input readonly type="file" name="attachment" class="form-control form-control-sm" id="attachment" data-name="Date" >
        </div>
    </div>

    <div class="form-group mt-2">
        <div class="payment_method {{ $payment->pay_mode == 'Card' ? '' : 'd-none' }}" id="Card">
            <div class="row">
                <div class="col-md-3">
                    <label><strong>Card Number :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="card_no" id="p_card_no" placeholder="Card number" value="{{ $payment->card_no }}">
                </div>

                <div class="col-md-3">
                    <label><strong>Card Holder Name :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="card_holder_name" id="p_card_holder_name" placeholder="Card holder name" value="{{ $payment->card_holder }}">
                </div>

                <div class="col-md-3">
                    <label><strong>Card Transaction No :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="card_transaction_no" id="p_card_transaction_no" placeholder="Card transaction no" value="{{ $payment->card_transaction_no }}">
                </div>

                <div class="col-md-3">
                    <label><strong>Card Type :</strong> </label>
                    <select name="card_type" class="form-control form-control-sm"  id="p_card_type">
                        <option {{ $payment->card_type == 'Credit-Card' ? 'SELECTED' : '' }} value="Credit-Card">Credit Card</option>  
                        <option {{ $payment->card_type == 'Debit-Card' ? 'SELECTED' : '' }} value="Debit-Card">Debit Card</option> 
                        <option {{ $payment->card_type == 'Visa' ? 'SELECTED' : '' }} value="Visa">Visa Card</option> 
                        <option {{ $payment->card_type == 'Master-Card' ? 'SELECTED' : '' }} value="Master-Card">Master Card</option> 
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-3">
                    <label><strong>Month :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="month" id="p_month" placeholder="Month" value="{{ $payment->card_month }}">
                </div>

                <div class="col-md-3">
                    <label><strong>Year :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="year" id="p_year" placeholder="Year" value="{{ $payment->card_year }}">
                </div>

                <div class="col-md-3">
                    <label><strong>Secure Code :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="secure_code" id="p_secure_code" placeholder="Secure code" value="{{ $payment->card_secure_code }}">
                </div>
            </div>
        </div>

        <div class="payment_method {{ $payment->pay_mode == 'Cheque' ? '' : 'd-none' }}" id="">
            <div class="row">
                <div class="col-md-12">
                    <label><strong>Cheque Number :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="cheque_no" id="p_cheque_no" placeholder="Cheque number" value="{{ $payment->cheque_no }}">
                </div>
            </div>
        </div>

        <div class="payment_method {{ $payment->pay_mode == 'Bank-Transfer' ? '' : 'd-none' }}" id="Bank-Transfer">
            <div class="row">
                <div class="col-md-12">
                    <label><strong>Account Number :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="account_no" id="p_account_no" placeholder="Account number" value="{{ $payment->account_no }}">
                </div>
            </div>
        </div>

        <div class="payment_method {{ $payment->pay_mode == 'Custom' ? '' : 'd-none' }}" id="Custom">
            <div class="row">
                <div class="col-md-12">
                    <label><strong>Transaction No :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="transaction_no" id="p_transaction_no" placeholder="Transaction number" value="{{ $payment->transaction_no }}">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-2">
        <label><strong> Payment Note :</strong></label>
        <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3" placeholder="Note">{{ $payment->note }}</textarea>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end">Save</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>


<script>
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
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
</script>
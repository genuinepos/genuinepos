<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<div class="info_area mb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Customer : </strong><span class="card_text customer_name">{{ $customer->name }}</span>
                    </li>
                    <li><strong>Business : </strong><span
                            class="card_text customer_business">{{ $customer->business_name }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Total Sale : </strong>
                        <span class="card_text invoice_no">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_sale }}
                        </span>
                    </li>
                    <li><strong>Total Paid : </strong>
                        <span class="card_text branch">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_paid }}
                        </span>
                    </li>
                    <li><strong>Total Due : </strong>
                        <span class="card_text branch">
                            {{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $customer->total_sale_due }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!--begin::Form-->
<form id="customer_payment_form" action="{{ route('customers.payment.add', $customer->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group row">
        <div class="col-md-4">
            <label><strong>Amount :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i
                            class="far fa-money-bill-alt text-dark"></i></span>
                </div>
                <input type="hidden" id="p_available_amount" value="{{ $customer->total_sale_due }}">
                <input type="number" name="amount" class="form-control form-control-sm p_input" step="any"
                    data-name="Amount" id="p_amount" value="{{ $customer->total_sale_due }}" />
            </div>
            <span class="error error_p_amount"></span>
        </div>

        <div class="col-md-4">
            <label for="p_date"><strong>Date :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i
                            class="fas fa-calendar-week text-dark"></i></span>
                </div>
                <input type="text" name="date" class="form-control form-control-sm datepicker p_input"
                    autocomplete="off" id="p_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
            </div>
            <span class="error error_p_date"></span>
        </div>

        <div class="col-md-4">
            <label><strong>Payment Method :</strong> <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-money-check text-dark"></i></span>
                </div>
                <select name="payment_method" class="form-control form-control-sm" id="payment_method">
                    <option value="Cash">Cash</option>
                    <option value="Advanced">Advanced</option>
                    <option value="Card">Card</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Bank-Transfer">Bank-Transfer</option>
                    <option value="Other">Other</option>
                    <option value="Custom">Custom Field</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-7">
            <label><strong>Payment Account :</strong> </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i
                            class="fas fa-money-check-alt text-dark"></i></span>
                </div>
                <select name="account_id" class="form-control form-control-sm" id="p_account_id">
                    <option value="">None</option>
                    @foreach ($accounts as $account)
                        <option {{ auth()->user()->branch ? auth()->user()->branch->default_account_id == $account->id ? 'SELECTED' : '' : '' }} value="{{ $account->id }}">{{ $account->name }} (A/C:
                            {{ $account->account_number }}) (Balance: {{ $account->balance }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-5">
            <label><strong>Attach document :</strong> <small class="text-danger">Note: Max Size 2MB. </small> </label>
            <input type="file" name="attachment" class="form-control form-control-sm" id="attachment">
        </div>
    </div>

    <div class="form-group mt-2">
        <div class="payment_method d-none" id="Card">
            <div class="row">
                <div class="col-md-3">
                    <label><strong>Card Number :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="card_no" id="p_card_no"
                        placeholder="Card number">
                </div>

                <div class="col-md-3">
                    <label><strong>Card Holder Name :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="card_holder_name"
                        id="p_card_holder_name" placeholder="Card holder name">
                </div>

                <div class="col-md-3">
                    <label><strong>Card Transaction No :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="card_transaction_no"
                        id="p_card_transaction_no" placeholder="Card transaction no">
                </div>

                <div class="col-md-3">
                    <label><strong>Card Type :</strong> </label>
                    <select name="card_type" class="form-control form-control-sm" id="p_card_type">
                        <option value="Credit-Card">Credit Card</option>
                        <option value="Debit-Card">Debit Card</option>
                        <option value="Visa">Visa Card</option>
                        <option value="Master-Card">Master Card</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <label><strong>Month :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="month" id="p_month"
                        placeholder="Month">
                </div>

                <div class="col-md-3">
                    <label><strong>Year :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="year" id="p_year" placeholder="Year">
                </div>

                <div class="col-md-3">
                    <label><strong>Secure Code :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="secure_code" id="p_secure_code"
                        placeholder="Secure code">
                </div>
            </div>
        </div>

        <div class="payment_method d-none" id="Cheque">
            <div class="row">
                <div class="col-md-12">
                    <label><strong>Cheque Number :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="cheque_no" id="p_cheque_no"
                        placeholder="Cheque number">
                </div>
            </div>
        </div>

        <div class="payment_method d-none" id="Bank-Transfer">
            <div class="row">
                <div class="col-md-12">
                    <label><strong>Account Number :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="account_no" id="p_account_no"
                        placeholder="Account number">
                </div>
            </div>
        </div>

        <div class="payment_method d-none" id="Custom">
            <div class="row">
                <div class="col-md-12">
                    <label><strong>Transaction No :</strong> </label>
                    <input type="text" class="form-control form-control-sm" name="transaction_no" id="p_transaction_no"
                        placeholder="Transaction number">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-2">
        <label><strong> Payment Note :</strong></label>
        <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3"
            placeholder="Note"></textarea>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue float-end">Save</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>

<script>
    var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'dd');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
    $('.datepicker').datepicker({format: _expectedDateFormat});
</script>


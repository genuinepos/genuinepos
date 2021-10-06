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
                    <li><strong>Supplier : </strong><span class="card_text customer_name">{{ $supplier->name }}</span>
                    </li>
                    <li><strong>Business : </strong><span
                            class="card_text customer_business">{{ $supplier->business_name }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="payment_top_card">
                <ul class="list-unstyled">
                    <li><strong>Total Purchase : </strong>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        <span class="card_text">
                            <b>{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}</b>
                        </span>
                    </li>
                    <li><strong>Total Paid : </strong>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        <span class="card_text text-success">
                            <b>{{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}</b>
                        </span>
                    </li>
                    <li><strong>Total Due : </strong>
                        {{ json_decode($generalSettings->business, true)['currency'] }}
                        <span class="card_text text-danger">
                            <b>{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}</b> 
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!--begin::Form-->
<form id="supplier_payment_form" action="{{ route('suppliers.payment.add', $supplier->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group row mt-2">
        <div class="col-md-4">
           <strong>Amount :</strong> <span class="text-danger">*</span>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i
                            class="far fa-money-bill-alt text-dark"></i></span>
                </div>
                <input type="hidden" id="p_available_amount" value="{{ $supplier->total_purchase_due }}">
                <input type="number" name="amount" class="form-control form-control-sm p_input" step="any"
                    data-name="Amount" id="p_amount" value="" autocomplete="off" autofocus/>
            </div>
            <span class="error error_p_amount"></span>
        </div>

        <div class="col-md-4">
            <strong for="p_date">Date :</strong> <span class="text-danger">*</span>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i
                            class="fas fa-calendar-week text-dark"></i></span>
                </div>
                <input type="text" name="date" class="form-control form-control-sm p_input"
                    autocomplete="off" id="p_date" data-name="Date" value="{{ date(json_decode($generalSettings->business, true)['date_format']) }}">
            </div>
            <span class="error error_p_date"></span>
        </div>

        <div class="col-md-4">
           <strong>Payment Method :</strong> <span class="text-danger">*</span>
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
            <strong>Payment Account :</strong> 
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i
                            class="fas fa-money-check-alt text-dark"></i></span>
                </div>
                <select name="account_id" class="form-control form-control-sm" id="p_account_id">
                    <option value="">None</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} (A/C:
                            {{ $account->account_number }}) (Balance: {{ $account->balance }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-5">
           <strong>Attach document :</strong> <small class="text-danger">Note: Max Size 2MB. </small> 
            <input type="file" name="attachment" class="form-control form-control-sm">
        </div>
    </div>

    <div class="form-group mt-2">
        <div class="payment_method d-none" id="Card">
            <div class="row">
                <div class="col-md-3">
                    <strong>Card Number :</strong> 
                    <input type="text" class="form-control form-control-sm" name="card_no" id="p_card_no"
                        placeholder="Card number">
                </div>

                <div class="col-md-3">
                    <strong>Card Holder Name :</strong> 
                    <input type="text" class="form-control form-control-sm" name="card_holder_name"
                        id="p_card_holder_name" placeholder="Card holder name">
                </div>

                <div class="col-md-3">
                   <strong>Card Transaction No :</strong>
                    <input type="text" class="form-control form-control-sm" name="card_transaction_no"
                        id="p_card_transaction_no" placeholder="Card transaction no">
                </div>

                <div class="col-md-3">
                    <strong>Card Type :</strong> 
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
                   <strong>Month :</strong> 
                    <input type="text" class="form-control form-control-sm" name="month" id="p_month"
                        placeholder="Month">
                </div>

                <div class="col-md-3">
                    <strong>Year :</strong>
                    <input type="text" class="form-control form-control-sm" name="year" id="p_year" placeholder="Year">
                </div>

                <div class="col-md-3">
                    <strong>Secure Code :</strong>
                    <input type="text" class="form-control form-control-sm" name="secure_code" id="p_secure_code"
                        placeholder="Secure code">
                </div>
            </div>
        </div>

        <div class="payment_method d-none" id="Cheque">
            <div class="row">
                <div class="col-md-12">
                    <strong>Cheque Number :</strong> 
                    <input type="text" class="form-control form-control-sm" name="cheque_no" id="p_cheque_no"
                        placeholder="Cheque number">
                </div>
            </div>
        </div>

        <div class="payment_method d-none" id="Bank-Transfer">
            <div class="row">
                <div class="col-md-12">
                    <strong>Account Number :</strong> 
                    <input type="text" class="form-control form-control-sm" name="account_no" id="p_account_no"
                        placeholder="Account number">
                </div>
            </div>
        </div>

        <div class="payment_method d-none" id="Custom">
            <div class="row">
                <div class="col-md-12">
                    <strong>Transaction No :</strong> 
                    <input type="text" class="form-control form-control-sm" name="transaction_no" id="p_transaction_no"
                        placeholder="Transaction number">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-2">
       <strong> Payment Note :</strong>
        <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3"
            placeholder="Note"></textarea>
    </div>

    <div class="form-group row mt-4">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button name="action" type="button" value="save" class="c-btn btn_blue float-end" id="add_payment">Save</button>
            <button name="action" value="save_and_print" type="button" class="c-btn btn_blue float-end" id="add_payment">Save & Print</button>
            
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
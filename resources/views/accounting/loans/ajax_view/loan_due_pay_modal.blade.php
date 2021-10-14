<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
</style>
<div class="modal-dialog four-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Receive Loan Due Payment</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="payment_modal_body">
            <div class="info_area mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>Company/People : </strong><span class="card_text">{{ $company->name }}</span>
                                </li>
                                <li><strong>Phone : </strong><span class="card_text"></span></li>
                                <li><strong>Address : </strong><span class="card_text"></span></li>
                            </ul>
                        </div>
                    </div>
            
                    <div class="col-md-6">
                        <div class="payment_top_card">
                            <ul class="list-unstyled">
                                <li><strong>Total Loan Get : </strong>
                                    <span class="card_text invoice_no">
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                       <b>{{ App\Utils\Converter::format_in_bdt($company->get_loan_amount) }}</b> 
                                    </span>
                                </li>

                                <li><strong>Total Due Paid : </strong>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    <span class="card_text text-success">
                                        <b>{{ App\Utils\Converter::format_in_bdt($company->total_pay) }}</b> 
                                    </span>
                                </li>
                                <li><strong>Total Payment Due : </strong>
                                    {{ json_decode($generalSettings->business, true)['currency'] }}
                                    <span class="card_text text-danger">
                                        <b>{{ App\Utils\Converter::format_in_bdt($company->get_loan_due) }}</b> 
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--begin::Form-->
            <form id="loan_payment_form" action="{{ route('accounting.loan.payment.due.pay.store', $company->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4">
                        <label><strong>Amount :</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i
                                        class="far fa-money-bill-alt text-dark"></i></span>
                            </div>
                            <input type="hidden" id="p_available_amount" value="{{ $company->get_loan_due }}">
                            <input type="number" name="amount" class="form-control form-control-sm p_input" step="any"
                                data-name="Amount" id="p_amount" value="" autocomplete="off" autofocus/>
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
                            <input type="text" name="date" class="form-control form-control-sm p_input"
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
                </div>
            
                <div class="form-group mt-2">
                    <label><strong> Payment Note :</strong></label>
                    <textarea name="note" class="form-control form-control-sm" id="note" cols="30" rows="3"
                        placeholder="Note"></textarea>
                </div>
            
                <div class="form-group row mt-3">
                    <div class="col-md-12">
                        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                        <button name="action" value="save" type="submit" class="c-btn btn_blue float-end" id="add_payment">Save</button>
                        <button name="action" value="save_and_print" type="submit" class="c-btn btn_blue float-end" id="add_payment">Save & Print</button>
                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
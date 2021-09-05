<form id="editting_loan_form" action="{{ route('accounting.loan.update', $loan->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label><strong>Company : <span class="text-danger">*</span></strong></label>
            <select name="company_id" class="form-control" id="e_company_id">
                <option value="">Select Company</option>
                @foreach ($companies as $company)
                    <option {{ $loan->loan_company_id == $company->id ? 'SELECTED' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_company_id"></span>
        </div>

        <div class="col-md-6">
            <label><b>Type :</b> <span class="text-danger">*</span></label>
            <select name="type" class="form-control" id="e_type">
                <option value="">Select Type</option>
                <option {{ $loan->type == 1 ? 'SELECTED' : '' }} value="1">Pay Loan</option>
                <option {{ $loan->type == 2 ? 'SELECTED' : '' }} value="2">Get Loan</option>
            </select>
            <span class="error error_e_type"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>Loan Amount :</b> <span class="text-danger">*</span> </label>
            <input type="number" step="any" name="loan_amount" class="form-control" id="e_loan_amount" placeholder="Loan Amount" value="{{ $loan->loan_amount }}"/>
            <span class="error error_e_loan_amount"></span>
        </div>

        <div class="col-md-6">
            <label><b>Account :</b> <span class="text-danger">*</span></label>
            <select name="account_id" class="form-control" id="e_account_id">
                <option value="">Select Account</option>
                @foreach ($accounts as $account)
                    <option {{ $loan->account_id == $account->id ? 'SELECTED' : '' }} value="{{ $account->id }}">{{ $account->name.' (A/C: '.$account->account_number.')' }}</option>
                @endforeach
            </select>
            <span class="error error_e_account_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>Loan Reason :</b> </label>
            <textarea name="loan_reason" class="form-control" id="loan_reason" cols="10" rows="3" placeholder="Loan Reason">{{ $loan->loan_reason }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_loan_edit_form">Close</button>
        </div>
    </div>
</form>
<form id="editting_loan_form" action="{{ route('accounting.loan.update', $loan->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-6">
            <label><strong>@lang('menu.date') : <span class="text-danger">*</span></strong></label>
            <input type="text" name="date" class="form-control" id="e_date" value="{{ date($generalSettings['business_or_shop__date_format'], strtotime($loan->report_date)) }}">
            <span class="error error_e_date"></span>
        </div>

        <div class="col-md-6">
            <label><b>@lang('menu.loan_ac') </b> <span class="text-danger">*</span></label>
            <select required name="loan_account_id" class="form-control" id="loan_account_id">
                <option value="">@lang('menu.select_loan_account')</option>
                @foreach ($loanAccounts as $loanAc)
                    <option {{ $loanAc->id == $loan->loan_account_id ? 'SELECTED' : '' }} value="{{ $loanAc->id }}">
                        {{ $loanAc->name.' ('.App\Utils\Util::accountType($loanAc->account_type).')' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label><strong>@lang('menu.company')/@lang('menu.people'): <span class="text-danger">*</span></strong></label>
            <select name="company_id" class="form-control" id="e_company_id">
                <option value="">@lang('menu.select_company')</option>
                @foreach ($companies as $company)
                    <option {{ $loan->loan_company_id == $company->id ? 'SELECTED' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            <span class="error error_e_company_id"></span>
        </div>

        <div class="col-md-6">
            <label><b>@lang('menu.type') </b> <span class="text-danger">*</span></label>
            <select name="type" class="form-control" id="e_type">
                <option value="">@lang('menu.select_type')</option>
                <option {{ $loan->type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.loan_and_advance')</option>
                <option {{ $loan->type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.loan_and_liabilities')</option>
            </select>
            <span class="error error_e_type"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>@lang('menu.loan_amount') </b> <span class="text-danger">*</span> </label>
            <input type="number" step="any" name="loan_amount" class="form-control" id="e_loan_amount" placeholder="@lang('menu.loan_amount')" value="{{ $loan->loan_amount }}"/>
            <span class="error error_e_loan_amount"></span>
        </div>

        <div class="col-md-6">
            <label><b>@lang('menu.debit')/@lang('menu.credit_account') </b> <span class="text-danger">*</span></label>
            <select name="account_id" class="form-control" id="e_account_id">
                <option value="">@lang('menu.select_account')</option>
                @foreach ($accounts as $account)
                    <option {{ $loan->account_id == $account->id ? 'SELECTED' : '' }} value="{{ $account->id }}">
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
            <span class="error error_e_account_id"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>@lang('menu.loan_reason') </b> </label>
            <textarea name="loan_reason" class="form-control" id="loan_reason" cols="10" rows="3" placeholder="@lang('menu.loan_reason')">{{ $loan->loan_reason }}</textarea>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="button" class="btn btn-sm btn-danger" id="close_loan_edit_form">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
            </div>
        </div>
    </div>
</form>

<script>
    // var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    // var _expectedDateFormat = '';
    // _expectedDateFormat = dateFormat.replace('d', 'dd');
    // _expectedDateFormat = _expectedDateFormat.replace('m', 'mm');
    // _expectedDateFormat = _expectedDateFormat.replace('Y', 'yyyy');
    // $('.datepicker').datepicker({ format: _expectedDateFormat })

    var dateFormat = "{{ $generalSettings['business_or_shop__date_format'] }}";
    var _expectedDateFormat = '' ;
    _expectedDateFormat = dateFormat.replace('d', 'DD');
    _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
    _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_date'),
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

<div class="row g-3 tab_contant loans">
    <div class="col-md-4">
        <div class="card" id="add_loan_form">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>@lang('menu.add_loan') </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="adding_loan_form" action="{{ route('accounting.loan.store') }}" method="POST">
                    @csrf
                    <div class="form-group row gx-3">
                        <div class="col-md-6">
                            <label><strong>@lang('menu.date') : <span class="text-danger">*</span></strong></label>
                            <input type="text" name="date" class="form-control" id="date" value="{{ str_replace('/', '-', date($generalSettings['business_or_shop__date_format'])) }}">
                            <span class="error error_date"></span>
                        </div>

                        <div class="col-md-6">
                            <label><b>@lang('menu.loan_ac') </b> <span class="text-danger">*</span></label>
                            <select required name="loan_account_id" class="form-control" id="loan_account_id">
                                <option value="">@lang('menu.select_loan_account')</option>
                                @foreach ($loanAccounts as $loanAc)
                                    <option value="{{ $loanAc->id }}">
                                        {{ $loanAc->name . ' (' . App\Utils\Util::accountType($loanAc->account_type) . ')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row gx-3">
                        <div class="col-md-6">
                            <label><strong>@lang('menu.company')/@lang('menu.people') : <span class="text-danger">*</span></strong></label>
                            <select name="company_id" class="form-control" id="company_id">
                                <option value="">@lang('menu.select_company')</option>
                            </select>
                            <span class="error error_company_id"></span>
                        </div>

                        <div class="col-md-6">
                            <label><b>@lang('menu.type') </b> <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" id="type">
                                <option value="">@lang('menu.select_type')</option>
                                <option value="1">@lang('menu.loan_and_advance')</option>
                                <option value="2">@lang('menu.loan_and_liabilities')</option>
                            </select>
                            <span class="error error_type"></span>
                        </div>
                    </div>

                    <div class="form-group row gx-3 mt-1">
                        <div class="col-md-6">
                            <label><b>@lang('menu.loan_amount') </b> <span class="text-danger">*</span> </label>
                            <input type="number" step="any" name="loan_amount" class="form-control" id="loan_amount" placeholder="@lang('menu.loan_amount')" />
                            <span class="error error_loan_amount"></span>
                        </div>

                        <div class="col-md-6">
                            <label><b>@lang('menu.debit')/@lang('menu.credit_account') </b> <span class="text-danger">*</span></label>
                            <select name="account_id" class="form-control" id="account_id">
                                <option value="">@lang('menu.select_account')</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        @php
                                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                            $bank = $account->bank ? ', BK : ' . $account->bank : '';
                                            $ac_no = $account->account_number ? ', A/c No : ' . $account->account_number : '';
                                            $balance = ', BL : ' . $account->balance;
                                        @endphp
                                        {{ $account->name . $accountType . $bank . $ac_no . $balance }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_account_id"></span>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-12">
                            <label><b>@lang('menu.loan_reason') </b> </label>
                            <textarea name="loan_reason" class="form-control" id="loan_reason" cols="10" rows="3" placeholder="@lang('menu.loan_reason')"></textarea>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card d-hide" id="edit_loan_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('menu.edit_loan')</h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2" id="edit_loan_form_body">

            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>@lang('menu.loans')</h6>
                </div>

                <div class="col-6">
                    <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i>@lang('menu.print')</a>
                </div>
            </div>

            <div class="widget_content">
                <form id="filter_form" class="px-2 mb-2">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label><strong>@lang('menu.company')/@lang('menu.people') </strong></label>
                            <select name="company_id" class="form-control submit_able select2" id="f_company_id" autofocus>
                                <option value="">@lang('menu.all')</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong>@lang('menu.loan_type') </strong></label>
                            <select name="type_id" class="form-control submit_able select2" id="type_id">
                                <option value="">@lang('menu.all')</option>
                                <option value="1">@lang('menu.loan_and_advance')</option>
                                <option value="2">@lang('menu.loan_and_liabilities')</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label><strong>@lang('menu.from_date') </strong></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-calendar-week input_i"></i>
                                    </span>
                                </div>
                                <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label><strong>@lang('menu.to_date') </strong></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-calendar-week input_i"></i>
                                    </span>
                                </div>
                                <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label><strong></strong></label>
                            <div class="input-group">
                                <button type="submit" id="filter_button" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-search"></i> @lang('menu.filter')</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                </div>

                <div class="table-responsive">
                    <table class="display data_tbl2 data__table asset_table w-100">
                        <thead>
                            <tr>
                                <th>@lang('menu.action')</th>
                                <th>@lang('menu.date')</th>
                                <th>@lang('menu.b_location')</th>
                                <th>@lang('menu.ref_no')</th>
                                <th>@lang('menu.company')/@lang('menu.people')</th>
                                <th>@lang('menu.type')</th>
                                <th>@lang('menu.loan_by')</th>
                                <th>@lang('menu.loan_amount')({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                <th>@lang('menu.due')({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                <th>@lang('menu.total_paid')({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <form id="delete_loan_form" action="" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Payment list modal-->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog four-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('menu.loan_details')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div id="loan_details">

                </div>

                <div class="row">
                    <div class="col-md-12 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            <button type="submit" id="print_loan_details" class="btn btn-sm btn-success">@lang('menu.print')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

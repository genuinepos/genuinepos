<div class="row tab_contant loans mt-1">
    <div class="col-md-4">
        <div class="card" id="add_loan_form">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>Add Loan </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="adding_loan_form" action="{{ route('accounting.loan.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label><strong>Date : <span class="text-danger">*</span></strong></label>
                            <input type="text" name="date" class="form-control" id="date" value="{{ str_replace('/', '-', date(json_decode($generalSettings->business, true)['date_format'])) }}">
                            <span class="error error_date"></span>
                        </div>

                        <div class="col-md-6">
                            <label><b>Loan A/C :</b> <span class="text-danger">*</span></label>
                            <select required name="loan_account_id" class="form-control" id="loan_account_id">
                                <option value="">Select Loan Account</option>
                                @foreach ($loanAccounts as $loanAc)
                                    <option value="{{ $loanAc->id }}">
                                        {{ $loanAc->name.' ('.App\Utils\Util::accountType($loanAc->account_type).')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label><strong>Company/People : <span class="text-danger">*</span></strong></label>
                            <select name="company_id" class="form-control" id="company_id">
                                <option value="">Select Company</option>
                            </select>
                            <span class="error error_company_id"></span>
                        </div>

                        <div class="col-md-6">
                            <label><b>Type :</b> <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" id="type">
                                <option value="">Select Type</option>
                                <option value="1">Loan & Advance</option>
                                <option value="2">Loan & Liabilities</option>
                            </select>
                            <span class="error error_type"></span>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-6">
                            <label><b>Loan Amount :</b> <span class="text-danger">*</span> </label>
                            <input type="number" step="any" name="loan_amount" class="form-control" id="loan_amount" placeholder="Loan Amount"/>
                            <span class="error error_loan_amount"></span>
                        </div>

                        <div class="col-md-6">
                            <label><b>Debit/Credit Account :</b> <span class="text-danger">*</span></label>
                            <select name="account_id" class="form-control" id="account_id">
                                <option value="">Select Account</option>
                                @foreach ($accounts as $account)
                                    @php
                                        $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                        $balance = ' BL : '.$account->balance;
                                    @endphp
                                    <option value="{{ $account->id }}">{{ $account->name.$accountType.$balance}}</option>
                                @endforeach
                            </select>
                            <span class="error error_account_id"></span>
                        </div>
                    </div>

                    <div class="form-group row mt-1">
                        <div class="col-md-12">
                            <label><b>Loan Reason :</b> </label>
                            <textarea name="loan_reason" class="form-control" id="loan_reason" cols="10" rows="3" placeholder="Loan Reason"></textarea>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card d-none" id="edit_loan_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Edit Loan </h6>
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
                    <h6>Loans</h6>
                </div>
                
                <div class="col-md-6">
                    <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> Print</a>
                </div>
            </div>

            <div class="widget_content">
                <form id="filter_form" class="px-1">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label><strong>Company/People :</strong></label>
                            <select name="company_id" class="form-control submit_able" id="f_company_id" autofocus>
                                <option value="">All</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><strong>Loan Type :</strong></label>
                            <select name="type_id" class="form-control submit_able" id="type_id">
                                <option value="">All</option>
                                <option value="1">Loan & Advance</option>
                                <option value="2">Loan & Liabilities</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label><strong>From Date :</strong></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-calendar-week input_i"></i></span>
                                </div>
                                <input type="text" name="from_date" id="datepicker"
                                    class="form-control from_date date"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label><strong>To Date :</strong></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-calendar-week input_i"></i></span>
                                </div>
                                <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                            </div>
                        </div>
            
                        <div class="col-md-2">
                            <label><strong></strong></label>
                            <div class="input-group">
                                <button type="submit" id="filter_button" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-search"></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                </div>
                
                <div class="table-responsive" >
                    <table class="display data_tbl2 data__table asset_table w-100">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Date</th>
                                <th>B.Location</th>
                                <th>Ref. No.</th>
                                <th>Company/People</th>
                                <th>Type</th>
                                <th>Loan By</th>
                                <th>Loan Amount({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                <th>Due({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                <th>Total Paid({{ json_decode($generalSettings->business, true)['currency'] }})</th>
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
                <h6 class="modal-title" id="exampleModalLabel">Loan Details</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                    class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div id="loan_details">

                </div>

                <div class="row">
                    <div class="col-md-12 text-end">
                        <ul class="list-unstyled">
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                            <button type="submit" id="print_loan_details" class="c-btn btn_blue">Print</button>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row tab_contant loans mt-1">
    <div class="col-md-12">
        <div class="sec-name">
            <div class="col-md-12">
                <form id="filter_tax_report_form" action="" method="get">
                    @csrf
                    <div class="form-group row">
                        @if ($addons->branches == 1)
                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                <div class="col-md-3">
                                    <label><strong>Business Location :</strong></label>
                                    <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                        <option value="">All</option>
                                        <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                        @foreach ($branches as $br)
                                            <option value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        @endif
                        
                        <div class="col-md-3">
                            <label><strong>Company :</strong></label>
                            <select name="company_id" class="form-control submit_able" id="f_company_id" autofocus>
                                <option value="">All</option>
                            </select>
                        </div>
            
                        <div class="col-md-3">
                            <label><strong>Date range :</strong></label>
                            <input type="text" class="form-control daterange submit_able_input" id="date_range">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
   
    <div class="col-md-4">
        <div class="card" id="add_loan_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Add Loan </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="adding_loan_form" action="{{ route('accounting.loan.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label><strong>Company : <span class="text-danger">*</span></strong></label>
                            <select name="company_id" class="form-control" id="company_id">
                                <option value="">Select Company</option>
                            </select>
                            <span class="error error_company_id"></span>
                        </div>

                        <div class="col-md-6">
                            <label><b>Type :</b> <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" id="type">
                                <option value="">Select Type</option>
                                <option value="1">Pay Loan</option>
                                <option value="2">Get Loan</option>
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
                            <label><b>Account :</b> <span class="text-danger">*</span></label>
                            <select name="account_id" class="form-control" id="account_id">
                                <option value="">Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name.' (A/C: '.$account->account_number.')' }}</option>
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
            </div>
            <div class="widget_content">
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
                                <th>Company</th>
                                <th>Type</th>
                                <th>Loan Amount</th>
                                <th>Due</th>
                                <th>Total Paid</th>
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
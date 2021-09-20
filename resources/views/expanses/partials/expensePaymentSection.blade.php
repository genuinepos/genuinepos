<section class="">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="form_element m-0 mt-3">
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="checkbox_input_wrap">
                                    <input type="checkbox" name="is_loan" id="is_loan">
                                    <strong>Is Loan Exists In This Expense</strong>
                                </p>
                            </div>

                            <div class="col-md-4 loan_amount_field d-none">
                                <div class="input-group">
                                    <label for="inputEmail3" class=" col-4"><b>Loan Amt: ({{ json_decode($generalSettings->business, true)['currency'] }})</b> </label>
                                    <div class="col-8">
                                        <input name="loan_amount" class="form-control" id="loan_amount" value="0.00">
                                        <span class="error error_loan_amount"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 loan_amount_field d-none">
                                <div class="input-group">
                                    <label for="inputEmail3" class=" col-4"><b>Company: </b> </label>
                                    <div class="col-8">
                                        <select name="company_id" class="form-control">
                                            <option value="">Loan Paying Company</option>
                                            @foreach ($companies as $com)
                                                <option value="{{ $com->id }}">{{ $com->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error error_company_id"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class=" col-4"><b>Paying : ({{ json_decode($generalSettings->business, true)['currency'] }})</b> </label>
                                    <div class="col-8">
                                        <input name="paying_amount" class="form-control" id="paying_amount" value="0.00">
                                        <span class="error error_paying_amount"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group mt-1">
                                    <label for="inputEmail3" class="col-4"><b>Pay Method :</b></label>
                                    <div class="col-8">
                                        <select name="payment_method" class="form-control" id="payment_method">
                                            <option value="Cash">Cash</option>
                                            <option value="Advanced">Advanced</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Card">Card</option>
                                            <option value="Bank-Transfer">Bank-Transter</option>
                                            <option value="Other">Other</option>
                                            <option value="Custom">Custom Field</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-4"><b>Account :</b></label>
                                    <div class="col-8">
                                        <select required name="account_id" class="form-control" id="account_id">
                                            <option value="">None</option>
                                        </select>
                                        <span class="error error_account_id"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-4"><b>Total Due :</b> </label>
                                    <div class="col-8">
                                        <input readonly name="total_due" type="number" step="any" id="total_due" class="form-control text-danger" value="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="payment_method d-none" id="Card">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mt-1">
                                        <label for="inputEmail3" class=" col-4"><b>Card No :</b> </label>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="card_no" id="card_no" placeholder="Card number">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mt-1">
                                        <label for="inputEmail3" class="col-4"><b>Account Holder :</b> </label>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="card_holder_name" id="card_holder_name" placeholder="Card holder name">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3 " class="col-4"><b>Transection No</b> :</label>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="card_transaction_no" id="card_transaction_no" placeholder="Card transaction no">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group ">
                                        <label for="inputEmail3" class=" col-4"><b>Card Type :</b> </label>
                                        <div class="col-8">
                                            <select name="card_type" class="form-control"  id="p_card_type">
                                                <option value="Credit-Card">Credit Card</option>  
                                                <option value="Debit-Card">Debit Card</option> 
                                                <option value="Visa">Visa Card</option> 
                                                <option value="Master-Card">Master Card</option> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3 mt-1" class=" col-4"><b> Month :</b> </label>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="month" id="month" placeholder="Month">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Year :</b></label>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="year" id="year" placeholder="Year">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>Secure ID :</b></label>
                                        <div class="col-8">
                                            <input type="text" class="form-control" name="secure_code" id="secure_code" placeholder="Secure code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="payment_method d-none" id="Cheque">
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label for="inputEmail3" class=" col-2"><b>Cheque Number :</b>  </label>
                                        <div class="col-10">
                                            <input type="text" class="form-control" name="cheque_no" id="cheque_no" placeholder="Cheque number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="payment_method d-none" id="Bank-Transfer">
                            <div class="row  mt-1">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label for="inputEmail3" class=" col-2"><b>Account No :</b></label>
                                        <div class="col-10">
                                            <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="payment_method d-none" id="Custom">
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <div class="input-group ">
                                        <label for="inputEmail3" class=" col-2"><b>Transaction No :</b></label>
                                        <div class="col-10">
                                            <input type="text" class="form-control " name="transaction_no" id="transaction_no" placeholder="Transaction number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label for="inputEmail3" class=" col-2"><b>Payment Note :</b></label>
                                    <div class="col-10">
                                        <input type="text" name="payment_note" class="form-control form-control-sm" id="payment_note" placeholder="Payment note">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="submit-area py-3 mb-4">
                    <button type="button" class="btn loading_button d-none"><i
                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                    <button data-action="save" class="btn btn-sm btn-primary submit_button float-end">Save</button>
                    <button data-action="sale_and_print" class="btn btn-sm btn-primary submit_button float-end me-1">Save & Print</button>
                </div>
            </div>
        </div>
    </div>
</section>
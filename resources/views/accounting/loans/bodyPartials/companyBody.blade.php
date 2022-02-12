<div class="row tab_contant companies mt-1">
    <div class="col-md-4">
        <div class="card" id="add_com_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Add Company/People </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="add_company_form" action="{{ route('accounting.loan.companies.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label><b>Name :</b> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name" autocomplete="off"
                                placeholder="Company/People Name"/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="col-md-12">
                            <label><b>Phone :</b> <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" id="phone" autocomplete="off"
                                placeholder="Phone Number Name"/>
                            <span class="error error_phone"></span>
                        </div>

                        <div class="col-md-12">
                            <label><b>Address :</b> </label>
                            <textarea name="address" class="form-control" id="address" cols="10" rows="3" placeholder="Address"></textarea>
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

        <div class="card d-none" id="edit_com_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Edit Company </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2" id="edit_com_form_body"></div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>Companies/Peoples</h6>
                </div>
            </div>
            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                </div>
                
                <div class="table-responsive" >
                    <table class="display data_tbl data__table asset_type_table">
                        <thead>
                            <tr>
                                <th>S/L</th>
                                <th>Name</th>
                                <th>Total Loan&Advance</th>
                                <th>Total Receive</th>
                                <th>Total Loan&Liabilities</th>
                                <th>Total Pay</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <form id="delete_companies_form" action="" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>  
    </div>
</div>

 <!-- Customer payment Modal-->
 <div class="modal fade" id="loanPymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
 aria-hidden="true">

</div>
<!-- Customer payment Modal End-->

  <!-- Customer payment view Modal--> 
  <div class="modal fade" id="viewPaymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog col-60-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">View Payment</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body" id="payment_list"></div>
        </div>
    </div>
</div>
<!-- Customer payment view Modal End-->
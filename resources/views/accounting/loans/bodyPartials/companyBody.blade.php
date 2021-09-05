<div class="row tab_contant companies mt-1">
    <div class="col-md-4">
        <div class="card" id="add_com_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Add Company </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="add_company_form" action="{{ route('accounting.loan.companies.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label><b>Name :</b> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name" autocomplete="off"
                                placeholder="Company Name"/>
                            <span class="error error_name"></span>
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

            <div class="form-area px-3 pb-2" id="edit_com_form_body">

            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>Companies</h6>
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
                                <th>Pay loan amount</th>
                                <th>Total Pay</th>
                                <th>Get Loan Amount</th>
                                <th>Total Receive</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
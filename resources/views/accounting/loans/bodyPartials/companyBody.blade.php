<div class="row g-3 tab_contant companies">
    <div class="col-md-4">
        <div class="card" id="add_com_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('menu.add_company')/@lang('menu.people') </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="add_company_form" action="{{ route('accounting.loan.companies.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label><b>@lang('menu.name') :</b> <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="name" autocomplete="off"
                                placeholder="@lang('menu.company')/@lang('menu.people')"/>
                            <span class="error error_name"></span>
                        </div>

                        <div class="col-md-12">
                            <label><b>@lang('menu.phone') :</b> <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" id="phone" autocomplete="off"
                                placeholder="Phone Number Name"/>
                            <span class="error error_phone"></span>
                        </div>

                        <div class="col-md-12">
                            <label><b>@lang('menu.address') :</b> </label>
                            <textarea name="address" class="form-control" id="address" cols="10" rows="3" placeholder="Address"></textarea>
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

        <div class="card d-hide" id="edit_com_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>@lang('menu.edit_company')</h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2" id="edit_com_form_body"></div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>@lang('menu.companies')/@lang('menu.peoples')</h6>
                </div>
            </div>
            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                </div>

                <div class="table-responsive" >
                    <table class="display data_tbl data__table asset_type_table">
                        <thead>
                            <tr>
                                <th>@lang('menu.sl')</th>
                                <th>@lang('menu.name')</th>
                                <th>@lang('menu.total_loan_advance')</th>
                                <th>@lang('menu.total_receive')</th>
                                <th>@lang('menu.total_loan_and_liabilities')</th>
                                <th>@lang('menu.total_pay')</th>
                                <th>@lang('menu.action')</th>
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
                <h6 class="modal-title" id="exampleModalLabel">@lang('menu.view_payment')</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body" id="payment_list"></div>
        </div>
    </div>
</div>
<!-- Customer payment view Modal End-->

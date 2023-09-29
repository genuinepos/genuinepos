<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Recent Transactions') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <div class="tab_list_area">
                <div class="btn-group">
                    <a id="tab_btn" class="btn btn-sm btn-primary tab_btn tab_active" href="{{ url('common/ajax/call/recent/sales/1') }}"><i class="fas fa-info-circle"></i> @lang('menu.final')</a>

                    <a id="tab_btn" class="btn btn-sm btn-primary tab_btn" href="{{ url('common/ajax/call/recent/quotations/1') }}"><i class="fas fa-scroll"></i>@lang('menu.quotation')</a>

                    <a id="tab_btn" class="btn btn-sm btn-primary tab_btn" href="{{ url('common/ajax/call/recent/drafts/1') }}"><i class="fas fa-shopping-bag"></i> @lang('menu.draft')</a>
                </div>
            </div>

            <div class="tab_contant">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table_area">
                            <div class="data_preloader" id="recent_trans_preloader">
                                <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table modal-table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.sl')</th>
                                            <th class="text-start">@lang('menu.invoice_id')</th>
                                            <th class="text-start">@lang('menu.customer')</th>
                                            <th class="text-start">@lang('menu.total')</th>
                                            <th class="text-start">@lang('menu.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="data-list" id="transection_list"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end">@lang('menu.close')</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-1 conatct_group tab_contant">
        <div class="col-md-4">
            <div class="card mb-0" id="add_form_group_div">
                <div class="section-header">
                    <div class="col-md-12">
                        <h6>Add Group Name </h6>
                    </div>
                </div>

                <div class="form-area px-3 pb-2">
                    <form id="add_group_form" action="{{ route('communication.contacts.group.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label><b>Group Name </b> <span class="text-danger">*</span></label>
                            <input required type="text" name="name" class="form-control" id="name" placeholder="Group name" />
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>
                                <button type="submit"
                                    class="c-btn button-success me-0 float-end submit_button">@lang('menu.save')</button>
                                <button type="reset" class="c-btn btn_orange float-end">@lang('menu.reset')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-0 d-none" id="edit_group_form">
                <div class="section-header">
                    <div class="col-md-12">
                        <h6>Edit Group </h6>
                    </div>
                </div>
                <div class="form-area px-3 pb-2" id="edit_group_form_body"></div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-0">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>Contact Group Name</h6>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table contactGroupTable">
                            <thead>
                                <tr class="bg-navey-blue">
                                    <th class="text-black">@lang('menu.sl')</th>
                                    <th class="text-black">@lang('menu.name')</th>
                                    <th class="text-black">@lang('menu.actions')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
<form id="deleted_group_form" action="" method="post">
    @method('DELETE')
    @csrf
</form>

<form id="edit_company_form" action="{{ route('accounting.loan.companies.update', $company->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>Name :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name" autocomplete="off"
                placeholder="Company Name" value="{{ $company->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_com_edit_form">Close</button>
        </div>
    </div>
</form>
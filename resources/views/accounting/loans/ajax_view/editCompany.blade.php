<form id="edit_company_form" action="{{ route('accounting.loan.companies.update', $company->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>Name :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" id="e_name" autocomplete="off"
                placeholder="Company/People Name" value="{{ $company->name }}"/>
            <span class="error error_e_name"></span>
        </div>
    </div>

    <div class="col-md-12">
        <label><b>Phone :</b> <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control" id="e_phone" autocomplete="off"
            placeholder="Phone Number Name" value="{{ $company->phone }}"/>
        <span class="error error_e_phone"></span>
    </div>

    <div class="col-md-12">
        <label><b>Address :</b> </label>
        <textarea name="address" class="form-control" id="e_address" cols="10" rows="3" placeholder="Address">{{ $company->address }}</textarea>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                <button type="button" class="btn btn-sm btn-danger" id="close_com_edit_form">Close</button>
                <button type="submit" class="btn btn-sm btn-success submit_button">Save</button>
            </div>
        </div>
    </div>
</form>